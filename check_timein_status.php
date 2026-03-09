<?php
    include 'db.php';
    date_default_timezone_set('Asia/Manila');

    $employee_number = $_GET['employee_number']; // 🔄 move this to the top

//    $flexi = 'Full';
//
//    $empQuery = "SELECT Flexi FROM employees WHERE employee_number = ?";
//    $empStmt = $conn->prepare($empQuery);
//    $empStmt->bind_param("s", $employee_number);
//    $empStmt->execute();
//    $empResult = $empStmt->get_result();
	
//    if ($empRow = $empResult->fetch_assoc()) {
//        $flexi = $empRow['Flexi'];
//    }

//switch ($flexi) {
//    case 'Flexi7am': $lunch_end_hour = 12; break;
//    case 'Flexi8am': $lunch_end_hour = 13; break;
//    case 'Flexi9am': $lunch_end_hour = 14; break;
//    case 'Full6am': $lunch_end_hour = 11; break;
//    case 'Full8am': $lunch_end_hour = 13; break;
//    case 'Full2pm': $lunch_end_hour = 19; break;
//    case 'Full10pm': $lunch_end_hour = 3; break;
//    case 'Full': default: $lunch_end_hour = 14; break;
//}

	$date = date('Y-m-d');
	$currentHour = date('G');

	$response = [
		'buttons' => [
			['action' => 'time_in', 'enabled' => false],
			['action' => 'time_out', 'enabled' => false],
			['action' => 'lunch_out', 'enabled' => false],
			['action' => 'lunch_in', 'enabled' => false],
		]
	];

	$query = "SELECT * FROM time_logs WHERE employee_number = ? AND date_record = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ss", $employee_number, $date);
	$stmt->execute();
	$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // HALF-DAY PM-In already recorded
    if (empty($row['time_in']) && !empty($row['lunch_in']) && empty($row['time_out'])) {
        $response['buttons'][1]['enabled'] = true; // PM-Out
    }
    // First-time AM-In
    elseif (empty($row['time_in']) && empty($row['lunch_in']) && empty($row['lunch_out']) && empty($row['time_out'])) {
        $response['buttons'][0]['enabled'] = true; // time_in (AM-In)
    }
    // Lunch Out allowed
    elseif (!empty($row['time_in']) && empty($row['lunch_out'])) {
        $response['buttons'][2]['enabled'] = true;
    }
    // Lunch In allowed regardless of hour (status will be Late Lunch In if beyond schedule)
    elseif (!empty($row['lunch_out']) && empty($row['lunch_in'])) {
        $response['buttons'][3]['enabled'] = true;
    }
    // Time Out allowed if time_in or lunch_in exists
    elseif ((!empty($row['time_in']) || !empty($row['lunch_in'])) && empty($row['time_out'])) {
        $response['buttons'][1]['enabled'] = true; // time_out
    }

// ✅ Extra Rule: Only AM-In exists, no lunch punches, check if >= 7 hours worked
if (!empty($row['lunch_out']) && empty($row['lunch_in']) && empty($row['time_out'])) {
    $timeInDT = new DateTime($row['date_record'] . ' ' . $row['time_in']);
    $now = new DateTime();
    $interval = $timeInDT->diff($now);
    $hoursWorked = ($interval->days * 24) + $interval->h + ($interval->i / 60);

    if ($hoursWorked >= 7) {
        // Disable AM Out, enable only PM Out
        $response['buttons'][2]['enabled'] = false; // lunch_out
        $response['buttons'][1]['enabled'] = true;  // time_out
    }
}

} else {
    $response['buttons'][0]['enabled'] = true; // time_in (AM-In)
}
	echo json_encode($response);
?>