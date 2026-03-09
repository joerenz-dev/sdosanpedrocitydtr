<?php
include 'db.php';
session_start();
date_default_timezone_set('Asia/Manila');

// Helper: format undertime as HH:MM
function formatUndertime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    return sprintf("%02d:%02d", $hours, $minutes);
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_number = $conn->real_escape_string($_POST['employee_number']);
    $action = $_POST['action'];

    $employee = $conn->query("SELECT employee_name, Flexi FROM employees WHERE employee_number = '$employee_number'")->fetch_assoc();

    if (!$employee) {
        $_SESSION['message'] = "Employee number not found.";
        header('Location: DTR.php'); exit();
    }

    $employee_name = $employee['employee_name'];
    $flexi = $employee['Flexi'];
    $current_date = date('Y-m-d');
    $now = time();

if ($flexi === 'Full10pm' && in_array($action, ['lunch_out', 'lunch_in', 'time_out'])) {
    // Look for time_in from previous day (since work spans overnight)
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $row = $conn->query("SELECT * FROM time_logs WHERE employee_number = '$employee_number' AND date_record = '$yesterday'")->fetch_assoc();
} else {
    // Default logic for all other schedules
    $row = $conn->query("SELECT * FROM time_logs WHERE employee_number = '$employee_number' AND date_record = '$current_date'")->fetch_assoc();
}


// TIME IN
if ($action == 'time_in') {
    if ($row && $row['time_in']) {
        $_SESSION['message'] = "Duplicate Time-In!";
    } else {
        $now_time = date('H:i:s');

        // Set schedule start based on flexi schedule
        switch ($flexi) {
            case 'Full6am':
                $schedule_start = '06:00:00';
                break;
            case 'Full8am':
                $schedule_start = '08:00:00';
                break;
            case 'Full2pm':
                $schedule_start = '14:00:00';
                break;
            case 'Full10pm':
                $schedule_start = '22:00:00';
                break;
            case 'Full':
                $schedule_start = '11:00:00';
                break;
            case 'Fixed7am':
                $schedule_start = '07:00:00';
                break;
            case 'Fixed8am':
                $schedule_start = '08:00:00';
                break;
            case 'Fixed9am':
                $schedule_start = '09:00:00';
                break;
            default:
                $_SESSION['message'] = "Unknown schedule: $flexi";
                header('Location: DTR.php'); exit();
        }

        // Determine status
        if ($flexi == 'Full') {
            // No grace period for Full flexi
            $status = ($now_time <= $schedule_start) ? 'On Time' : 'First in recorded';
        } else {
            // With grace period for all other flexi types
            if ($now_time <= $schedule_start) {
                $status = 'On Time';
            } elseif ($now_time <= date('H:i:s', strtotime($schedule_start) + 15 * 60)) {
                $status = 'Grace Period';
            } else {
                $status = 'Late';
            }
        }

        // Save time_in
        $sql = "INSERT INTO time_logs (employee_number, employee_name, date_record, time_in, status)
                VALUES ('$employee_number', '$employee_name', '$current_date', NOW(), '$status')";

        if ($conn->query($sql)) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = "Time-In recorded successfully ($status).";
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
        }
    }
}
// LUNCH OUT
elseif ($action == 'lunch_out') {
    if (!$row || !$row['time_in']) {
        $_SESSION['message'] = "Time In required before Lunch Out.";
    } elseif ($row['lunch_out']) {
        $prev = strtotime($row['lunch_out']);
        $_SESSION['message'] = (time() - $prev < 600)
            ? "Duplicate Lunch Out within 10 minutes."
            : "Lunch Out already recorded.";
    } else {

        // Get schedule start time
        switch ($flexi) {
            case 'Full6am':  $schedule_start = '06:00:00'; break;
            case 'Full8am':  $schedule_start = '08:00:00'; break;
            case 'Full2pm':  $schedule_start = '14:00:00'; break;
            case 'Full10pm': $schedule_start = '22:00:00'; break;
            case 'Full':     $schedule_start = '09:00:00'; break; // Default schedule start for Full
            case 'Fixed7am': $schedule_start = '07:00:00'; break;
            case 'Fixed8am': $schedule_start = '08:00:00'; break;
            case 'Fixed9am': $schedule_start = '09:00:00'; break;
            default:
                $_SESSION['message'] = "Unknown schedule: $flexi";
                header('Location: DTR.php'); exit();
        }

        $schedule_date = $row['date_record'];
        $time_in_ts = strtotime($row['date_record'] . ' ' . $row['time_in']);
        $now = time();

        // Special handling for "Full" flexi
        if ($flexi == 'Full') {
            $earliest_time = $time_in_ts;

            // If time_in is before 7:00 AM, treat as 7:00 AM
            $min_start = strtotime("$schedule_date 07:00:00");
            if ($earliest_time < $min_start) {
                $earliest_time = $min_start;
            }

            // If time_in is after 9:00 AM, lunch must be 4 hours after 9:00 AM
            $max_start = strtotime("$schedule_date 09:00:00");
            if ($earliest_time > $max_start) {
                $earliest_time = $max_start;
            }

            // Add 4 hours for lunch eligibility
            $earliest_lunch_out = $earliest_time + (4 * 3600);

        } else {
            // All other schedules: based on schedule start time
            $earliest_lunch_out = strtotime("$schedule_date $schedule_start +4 hours");
        }

        // Skip timing restriction for Full10pm
        if ($flexi != 'Full10pm' && $now < $earliest_lunch_out) {
            $_SESSION['message'] = "Lunch Out allowed only after 4 hours from allowed start time.";
        } else {

            $date_record = $row['date_record'];
            $sql = "UPDATE time_logs 
                    SET lunch_out = NOW(), date_record = '$date_record' 
                    WHERE id = '{$row['id']}'";

            if ($conn->query($sql)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = "Lunch Out recorded successfully.";
            } else {
                $_SESSION['message'] = "Error: " . $conn->error;
            }
        }
    }
}

// LUNCH IN
elseif ($action == 'lunch_in') {
    if ($row && $row['lunch_in']) {
        $prev = strtotime($row['lunch_in']);
        $_SESSION['message'] = (time() - $prev < 600)
            ? "Duplicate Lunch In within 10 minutes."
            : "Lunch In already recorded.";
    } else {
        $now = time();
        $break_start = ($row && $row['lunch_out']) ? strtotime($row['date_record'] . ' ' . $row['lunch_out']) : null;
        $break_duration = $break_start ? $now - $break_start : 0;

        if ($break_duration && $break_duration < 900) {
            $_SESSION['message'] = "Not Accepted lunch too short";
        } else {
            //$status = ($break_duration <= 2700) ? 'On Time' :
            //          (($break_duration <= 3600) ? 'On Time' : 'Late');

            $date_record = $row ? $row['date_record'] : date('Y-m-d');

            if ($row) {
                $sql = "UPDATE time_logs 
                        SET lunch_in = NOW(), date_record = '$date_record'
                        WHERE id = '{$row['id']}'";
            } else {
                $sql = "INSERT INTO time_logs 
                        (employee_number, employee_name, lunch_in, date_record)
                        VALUES ('$employee_number', '$employee_name', NOW(), '$date_record')";
            }

            if ($conn->query($sql)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = "Lunch In recorded successfully.";
            } else {
                $_SESSION['message'] = "Error: " . $conn->error;
            }
        }
    }
}


// TIME OUT
elseif ($action == 'time_out') {
    $now = time();
    $cutoff_time = strtotime(date('Y-m-d') . ' 14:45:00');

    if ($flexi != 'Full10pm' && $now < $cutoff_time	) {
        $_SESSION['message'] = "Time Out can only be recorded after 2:45 PM.";
    } elseif (!$row || !$row['time_in']) {
        $_SESSION['message'] = "Time-In is required before Time Out.";
    } elseif ($row['time_out']) {
        $prev = strtotime($row['time_out']);
        $_SESSION['message'] = (time() - $prev < 900)
            ? "Duplicate Time Out within 15 minutes."
            : "Time Out already recorded.";
    } else {
        $status = 'Present';
        $undertime_seconds = 0;
        $undertime_str = '';
        $time_in = strtotime($row['date_record'] . ' ' . $row['time_in']);
        $date_record = $row['date_record'];

        if (empty($row['lunch_out']) || empty($row['lunch_in'])) {
            $sql = "UPDATE time_logs 
                    SET time_out = NOW(), status = 'Present', date_record = '$date_record'
                    WHERE id = '{$row['id']}'";
            if ($conn->query($sql)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = "Time Out recorded (PM only).";
            } else {
                $_SESSION['message'] = "Error: " . $conn->error;
            }
        } else {
if ($flexi == 'Full') {
    $actual_in = $time_in;
    $actual_out = $now;

    $scheduled_start = strtotime($row['date_record'] . ' 09:00:00');

    // 1. Late if time-in > 9:00 AM
    $late = ($actual_in > $scheduled_start) ? ($actual_in - $scheduled_start) : 0;

    // 2. Count punches
    $has_lunch_out = !empty($row['lunch_out']);
    $has_lunch_in = !empty($row['lunch_in']);

    // 3. Determine required work duration
    $required_seconds = 32400; // 9 hrs

    // 4. If no lunch punches but it's after 3:00 PM, allow time out
    $override_cutoff = strtotime(date('Y-m-d', $now) . ' 15:00:00'); // 3:00 PM

    if ((!$has_lunch_out || !$has_lunch_in) && $actual_out < $override_cutoff) {
        $_SESSION['message'] = "Time Out not allowed before 3:00 PM if lunch punches are missing.";
        return;
    }

    // 5. Compute early out based on required work duration
    $scheduled_end = $actual_in + $required_seconds;
    $early_out = ($actual_out < $scheduled_end) ? ($scheduled_end - $actual_out) : 0;

    // 6. Total undertime
    $undertime_seconds = $late + $early_out;

    if ($undertime_seconds > 0) {
        $status = 'Undertime';
        $undertime_str = formatUndertime($undertime_seconds);
    }
} elseif ($flexi == 'Full10pm') {
                // Compute from actual intervals, no undertime
                $am_work = strtotime($row['date_record'] . ' ' . $row['lunch_out']) - $time_in;
                $pm_work = $now - strtotime($row['date_record'] . ' ' . $row['lunch_in']);
                $total_work = $am_work + $pm_work;

                if ($total_work < 28800) {
                    $status = 'Undertime';
                    $missing = 28800 - $total_work;
                    $undertime_str = formatUndertime($missing);
                }
            } else {
                $am_work = strtotime($row['date_record'] . ' ' . $row['lunch_out']) - $time_in;
                $pm_work = $now - strtotime($row['date_record'] . ' ' . $row['lunch_in']);
                $total_work = $am_work + $pm_work;

                if ($total_work < 28800) {
                    $status = 'Undertime';
                    $missing = 28800 - $total_work;
                    $undertime_str = formatUndertime($missing);
                }
            }

            $sql = "UPDATE time_logs 
                    SET time_out = NOW(), status = '$status', undertime = '$undertime_str', date_record = '$date_record'
                    WHERE id = '{$row['id']}'";
            if ($conn->query($sql)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = "Time Out recorded successfully" . ($undertime_str ? " ($status: $undertime_str)" : ".");
            } else {
                $_SESSION['message'] = "Error: " . $conn->error;
            }
        }
    }
}
// ==========================================
// AUTO HALF-DAY CHECK (FLEXI-BASED – FIXED)
// ==========================================

$current_date = date('Y-m-d');
$end_of_day = strtotime($current_date . ' 17:00:00');

if (time() >= $end_of_day) {

    $logs = $conn->query("
        SELECT tl.*, e.Flexi
        FROM time_logs tl
        JOIN employees e ON tl.employee_number = e.employee_number
        WHERE tl.date_record = '$current_date'
        AND tl.status NOT IN ('Present', 'Half Day')
    ");

    while ($log = $logs->fetch_assoc()) {

        if (empty($log['time_in'])) continue;

        // Skip if complete
        if (!empty($log['lunch_out']) &&
            !empty($log['lunch_in']) &&
            !empty($log['time_out'])) {
            continue;
        }

        $time_in_ts = strtotime($log['date_record'] . ' ' . $log['time_in']);
        $cutoff = min(time(), $end_of_day);
        $worked_seconds = 0;

        // CASE 1: Only time_in
        if (empty($log['lunch_out'])) {
            $worked_seconds = $cutoff - $time_in_ts;
        }

        // CASE 2: Has lunch_out only
        elseif (!empty($log['lunch_out']) && empty($log['lunch_in'])) {
            $worked_seconds = strtotime($log['date_record'] . ' ' . $log['lunch_out']) - $time_in_ts;
        }

        // CASE 3: Has lunch punches but no time_out
        elseif (!empty($log['lunch_out']) && !empty($log['lunch_in'])) {
            $am = strtotime($log['date_record'] . ' ' . $log['lunch_out']) - $time_in_ts;
            $pm = $cutoff - strtotime($log['date_record'] . ' ' . $log['lunch_in']);
            $worked_seconds = $am + $pm;
        }

        // =========================
        // REQUIRED HOURS BY FLEXI
        // =========================
        switch ($log['Flexi']) {
            case 'Full':
                $required_seconds = 32400; // 9 hours
                break;

            case 'Full10pm':
                $required_seconds = 28800; // 8 hours overnight
                break;

            default:
                $required_seconds = 28800; // 8 hours
        }

        if ($worked_seconds < $required_seconds) {

            $undertime_seconds = $required_seconds - $worked_seconds;
            $undertime_str = formatUndertime($undertime_seconds);

            $conn->query("
                UPDATE time_logs
                SET status = 'Half Day',
                    undertime = '$undertime_str',
                    total_undertime = $undertime_seconds
                WHERE id = '{$log['id']}'
            ");
        }
    }
}
    $conn->close();
    header('Location: DTR.php');
    exit();
}
?>
