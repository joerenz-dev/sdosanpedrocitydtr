<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

$conn = new mysqli("localhost", "pedro", "Deped@1234", "hr_timekeeping");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Helper functions ---
function formatTime($time) {
    return empty($time) ? ' ' : date('h:i', strtotime($time));
}

function getLogs($conn, $employee, $startDate, $endDate) {
    $stmt = $conn->prepare("SELECT employee_name, date_record, time_in, lunch_out, lunch_in, time_out, undertime, total_undertime, status
        FROM time_logs 
        WHERE employee_name = ? AND date_record BETWEEN ? AND ? 
        ORDER BY date_record");
    $stmt->bind_param("sss", $employee, $startDate, $endDate);
    $stmt->execute();
    return $stmt->get_result();
}

function getEmployeeNumber($conn, $employee_name) {
    $stmt = $conn->prepare("SELECT employee_number FROM employees WHERE employee_name = ?");
    $stmt->bind_param("s", $employee_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['employee_number'] : null;
}

function getHolidays($conn, $startDate, $endDate) {
    $holidays = [];

    $stmt = $conn->prepare("
        SELECT hd_start, hd_end, hd_details, holiday_type 
        FROM holidays 
        WHERE hd_end >= ? AND hd_start <= ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $start = strtotime($row['hd_start']);
        $end = strtotime($row['hd_end']);

        if ($start && $end) {
            for ($d = $start; $d <= $end; $d = strtotime('+1 day', $d)) {
                $date = date('Y-m-d', $d);
                $holidays[$date] = [
                    'hdtype' => $row['holiday_type']
                ];
            }
        }
    }

    return $holidays;
}

function getLatestUndertime($conn, $employee, $startDate, $endDate) {
    $stmt = $conn->prepare("
        SELECT total_undertime 
        FROM time_logs 
        WHERE employee_name = ? 
          AND date_record BETWEEN ? AND ? 
          AND total_undertime > 0
        ORDER BY date_record DESC 
        LIMIT 1
    ");
    $stmt->bind_param("sss", $employee, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? (int)$row['total_undertime'] : 0;
}

function getTotalUndertime($conn, $employee, $startDate, $endDate) {
    $stmt = $conn->prepare("
        SELECT SUM(total_undertime) AS sum_undertime 
        FROM time_logs 
        WHERE employee_name = ? 
          AND date_record BETWEEN ? AND ?
    ");
    $stmt->bind_param("sss", $employee, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row && $row['sum_undertime'] !== null ? (int)$row['sum_undertime'] : 0;
}

function getLeaveTravelData($conn, $employee_number, $start_date, $end_date) {
    $query = "SELECT * FROM employee_leave_requests
              WHERE employee_number = ? 
              AND (
                  (vl_type = 'Approved Leave' 
                      AND DATE(vl_start) <= ? 
                      AND DATE(vl_end) >= ?) 
                  OR
                  (vl_type = 'Official Travel' 
                      AND DATE(otravel_start_date) <= ? 
                      AND DATE(otravel_end_date) >= ?)
                  OR
                  (vl_type = 'Work From Home' 
                      AND DATE(wfh_start) <= ? 
                      AND DATE(wfh_end) >= ?)
              )";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", 
        $employee_number, 
        $end_date, $start_date,    // Leave
        $end_date, $start_date,    // Travel
        $end_date, $start_date     // WFH
    );
    $stmt->execute();
    $result = $stmt->get_result();

    $leaves = [];
    while ($row = $result->fetch_assoc()) {
        $leaves[] = $row;
    }
    return $leaves;
}
function buildData($result, $cutoffTag, $holidays = [], $leaveTravelData = [], $startDate = null) {
    $data = [];
    for ($i = 1; $i <= 31; $i++) {
        $data[] = [
            "day$cutoffTag" => $i,
            "am_arrival$cutoffTag" => ' ',
            "am_depart$cutoffTag" => ' ',
            "pm_arrival$cutoffTag" => ' ',
            "pm_depart$cutoffTag" => ' ',
            "under_hr$cutoffTag" => ' ',
            "under_min$cutoffTag" => ' ',
        ];
    }

    $logData = [];
    while ($row = $result->fetch_assoc()) {
        if (!isset($row['date_record'])) continue;
        $logData[$row['date_record']] = $row;
    }

    $monthYear = $startDate ? date('Y-m', strtotime($startDate)) : date('Y-m');

for ($i = 1; $i <= 31; $i++) {
    $day = str_pad($i, 2, '0', STR_PAD_LEFT);
    $date = "$monthYear-$day";
    $dataIndex = $i - 1;
    $dayOfWeek = date('l', strtotime($date));

    $log = $logData[$date] ?? null;
    $undertime = $log ? str_pad($log['undertime'], 5, '0', STR_PAD_LEFT) : '00:00';

    // Set default time log values if present
    if ($log) {
        $data[$dataIndex]["am_arrival$cutoffTag"] = formatTime($log['time_in']);
        $data[$dataIndex]["am_depart$cutoffTag"] = formatTime($log['lunch_out']);
        $data[$dataIndex]["pm_arrival$cutoffTag"] = formatTime($log['lunch_in']);
        $data[$dataIndex]["pm_depart$cutoffTag"] = formatTime($log['time_out']);
        $data[$dataIndex]["under_hr$cutoffTag"] = substr($undertime, 0, 2);
        $data[$dataIndex]["under_min$cutoffTag"] = substr($undertime, 3, 2);
    }

    // Holidays
    if (isset($holidays[$date])) {
        $map = ['Special Non-Working' => 'SNW', 'Regular' => 'RH', 'Others' => 'OTHERS', 'Suspension of Work' => 'WS'];
        $data[$dataIndex]["am_arrival$cutoffTag"] = $map[$holidays[$date]['hdtype']] ?? $holidays[$date]['hdtype'];
        continue;
    }

    // Leave / Travel / WFH
    foreach ($leaveTravelData as $leave) {
        $vl_type = $leave['vl_type'];
        $leaveStart = substr($leave['vl_start'] ?? '', 0, 10);
        $leaveEnd = substr($leave['vl_end'] ?? '', 0, 10);
        $travelStart = substr($leave['otravel_start_date'] ?? '', 0, 10);
        $travelEnd = substr($leave['otravel_end_date'] ?? '', 0, 10);
        $wfhStart = substr($leave['wfh_start'] ?? '', 0, 10);
        $wfhEnd = substr($leave['wfh_end'] ?? '', 0, 10);

        // Approved Leave
        if ($vl_type === 'Approved Leave' && $leaveStart <= $date && $leaveEnd >= $date) {
            $data[$dataIndex]["am_arrival$cutoffTag"] = 'AL';
            break;
        }

        // Official Travel
        if ($vl_type === 'Official Travel' && $travelStart <= $date && $travelEnd >= $date) {
            $periods = isset($leave['travel_periods']) ? explode(',', $leave['travel_periods']) : [];

            // Mark periods only if log is missing per slot
            foreach ($periods as $period) {
                $period = trim($period);
                switch ($period) {
                    case 'AM Arrival':
                        if (empty($log['time_in'])) $data[$dataIndex]["am_arrival$cutoffTag"] = 'Travel';
                        break;
                    case 'AM Departure':
                        if (empty($log['lunch_out'])) $data[$dataIndex]["am_depart$cutoffTag"] = 'Travel';
                        break;
                    case 'PM Arrival':
                        if (empty($log['lunch_in'])) $data[$dataIndex]["pm_arrival$cutoffTag"] = 'Travel';
                        break;
                    case 'PM Departure':
                        if (empty($log['time_out'])) $data[$dataIndex]["pm_depart$cutoffTag"] = 'Travel';
                        break;
                }
            }

            // If no periods specified and no logs, assume full-day travel
            if (empty($periods) && !$log) {
                $data[$dataIndex]["am_arrival$cutoffTag"] = 'Travel';
                $data[$dataIndex]["am_depart$cutoffTag"] = 'Travel';
                $data[$dataIndex]["pm_arrival$cutoffTag"] = 'Travel';
                $data[$dataIndex]["pm_depart$cutoffTag"] = 'Travel';
            }

            break;
        }

        // Work From Home
        if ($vl_type === 'Work From Home' && $wfhStart <= $date && $wfhEnd >= $date) {
            if (empty($log['time_in'])) $data[$dataIndex]["am_arrival$cutoffTag"] = 'WFH';
            if (empty($log['lunch_out'])) $data[$dataIndex]["am_depart$cutoffTag"] = 'WFH';
            if (empty($log['lunch_in'])) $data[$dataIndex]["pm_arrival$cutoffTag"] = 'WFH';
            if (empty($log['time_out'])) $data[$dataIndex]["pm_depart$cutoffTag"] = 'WFH';
            break;
        }
    }

    // Weekends if no log
    if (in_array($dayOfWeek, ['Saturday', 'Sunday']) && !$log) {
        $data[$dataIndex]["under_hr$cutoffTag"] = strtoupper(substr($dayOfWeek, 0, 3));
    }
}


    return $data;
}


// === DOCX Export by cutoff ===
if (isset($_GET['export_docx'], $_GET['employee'], $_GET['cutoff'])) {
    $cutoff = $_GET['cutoff'];
    $month = $_GET['month'];

    if (!in_array($cutoff, ['1', '2', 'full']) || !preg_match('/^\d{4}-\d{2}$/', $month)) {
        die("Invalid input.");
    }

$employee_name = $_GET['employee']; // this is the name
$employee_number = getEmployeeNumber($conn, $employee_name);
if (!$employee_number) {
    die("Employee number not found.");
}


    if ($cutoff === '1') {
        $start = "$month-01";
        $end = "$month-15";
        $templatePath = '/var/www/dtr/template/DailyTimeRecordCuttoff1.docx';
        $tag = '1';
        $rangeLabel = date("F 1–15, Y", strtotime($start));
    } elseif ($cutoff === '2') {
        $start = "$month-16";
        $end = date("Y-m-t", strtotime("$month-01"));
        $templatePath = '/var/www/dtr/template/DailyTimeRecordCuttoff2.docx';
        $tag = '2';
        $rangeLabel = date("F 16–t, Y", strtotime($start));
    } else {
        $start = "$month-01";
        $end = date("Y-m-t", strtotime($start));
        $templatePath = '/var/www/dtr/template/DailyTimeRecordFullMonth.docx';
        $tag = '';
        $rangeLabel = date("F Y", strtotime($start));
    }

    $holidays = getHolidays($conn, $start, $end);
    $logs = getLogs($conn, $employee_name, $start, $end);
    $leaves = getLeaveTravelData($conn, $employee_number, $start, $end);
$data = buildData($logs, $tag, $holidays, $leaves, $start);
$logs->data_seek(0);
$datab = buildData($logs, $tag . 'b', $holidays, $leaves, $start);

    $undertime1 = getTotalUndertime($conn, $employee_number, "$month-01", "$month-15");
    $undertime2 = getTotalUndertime($conn, $employee_number, "$month-16", date("Y-m-t", strtotime("$month-01")));
    $totalUndertime = $undertime1 + $undertime2;
    $latestUndertime = getLatestUndertime($conn, $employee_number, $start, $end);

    $templateProcessor = new TemplateProcessor($templatePath);
    $templateProcessor->setValue('Name', $employee_name);
    $templateProcessor->setValue('MonthCutoff', $rangeLabel);
    $templateProcessor->setValue('LatestUndertime', $latestUndertime);
    $templateProcessor->setValue('totalUndertime', $totalUndertime);
    $templateProcessor->cloneRowAndSetValues("day{$tag}", $data);
    $templateProcessor->cloneRowAndSetValues("day{$tag}b", $datab);

    $filename = "DTR-{$employee_name}-{$month}-" . ($cutoff === 'full' ? 'FullMonth' : "Cutoff$cutoff") . ".docx";
    $temp_file = tempnam(sys_get_temp_dir(), 'DTR');
    $templateProcessor->saveAs($temp_file);

    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header("Content-Length: " . filesize($temp_file));
    readfile($temp_file);
    unlink($temp_file);
    exit;
}

// === CSV Export Logic ===
if (isset($_GET['all']) || isset($_GET['today']) || isset($_GET['employee'])) {
    header('Content-Type: text/csv; charset=utf-8');
    
    // Set filename based on context
    if (isset($_GET['today'])) {
        $filename = 'Time_Logs_Today_' . date('Y-m-d') . '.csv';
    } elseif (isset($_GET['employee'])) {
        $filename = 'Time_Logs_Employee_' . date('Y-m-d') . '.csv';
    } else {
        $filename = 'Time_Logs_All_' . date('Y-m-d') . '.csv';
    }
    
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Employee Name', 'Employee Number', 'Date', 'Time In', 'Lunch Out', 'Lunch In', 'Time Out', 'Status', 'Undertime']);

    $result = false;

    if (isset($_GET['all'])) {
        $query = "SELECT employee_name, employee_number, date_record, time_in, lunch_out, lunch_in, time_out, status, undertime 
                  FROM time_logs 
                  ORDER BY employee_name, date_record DESC";
        $result = $conn->query($query);

    } elseif (isset($_GET['today'])) {
        $query = "SELECT employee_name, employee_number, date_record, time_in, lunch_out, lunch_in, time_out, status, undertime 
                  FROM time_logs 
                  WHERE date_record = CURDATE() 
                  ORDER BY employee_name, time_in ASC";
        $result = $conn->query($query);

    } elseif (isset($_GET['employee'])) {
        $employee_name = urldecode($_GET['employee']);
        $stmt = $conn->prepare("SELECT employee_name, employee_number, date_record, time_in, lunch_out, lunch_in, time_out, status, undertime 
                                FROM time_logs 
                                WHERE employee_name = ? 
                                ORDER BY date_record DESC");
        $stmt->bind_param("s", $employee_name);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    // Output data rows
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } else {
        fputcsv($output, ['No records found.']);
    }

    fclose($output);
    exit;
}
$conn->close();
ob_end_clean();
?>
