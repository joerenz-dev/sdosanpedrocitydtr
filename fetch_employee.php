<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

if (isset($_POST['employee_number'])) {
    $emp_num = $conn->real_escape_string($_POST['employee_number']);

    // Get employee name
    $sql = "SELECT employee_name FROM employees WHERE employee_number = '$emp_num'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $employee_name = $result->fetch_assoc()['employee_name'];

        // Get today's logs
        $today = date('Y-m-d');
        $log_sql = "SELECT time_in, lunch_out, lunch_in, time_out 
                    FROM time_logs 
                    WHERE employee_number = '$emp_num' 
                    AND date_record = '$today' 
                    LIMIT 1";
        $log_result = $conn->query($log_sql);

        // Defaults
        $time_in = '--:-- --';
        $lunch_out = '--:-- --';
        $lunch_in = '--:-- --';
        $time_out = '--:-- --';

        if ($log_result && $log_result->num_rows > 0) {
            $log = $log_result->fetch_assoc();

            if (!empty($log['time_in']))     $time_in = date('h:i A', strtotime($log['time_in']));
            if (!empty($log['lunch_out']))   $lunch_out = date('h:i A', strtotime($log['lunch_out']));
            if (!empty($log['lunch_in']))    $lunch_in = date('h:i A', strtotime($log['lunch_in']));
            if (!empty($log['time_out']))    $time_out = date('h:i A', strtotime($log['time_out']));
        }

        echo json_encode([
            'status' => 'ok',
            'name' => $employee_name,
            'time_in' => $time_in,
            'lunch_out' => $lunch_out,
            'lunch_in' => $lunch_in,
            'time_out' => $time_out
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>
