	<?php
		// =============================
		// FETCH LATEST PUNCH DETAILS
		// =============================
		$latestPunchQuery = "
		SELECT e.employee_name, e.employee_number, e.id_picture, e.Flexi,
			punch_type, punch_time, a.status
		FROM (
			SELECT employee_number, 'AM - in' AS punch_type, CONCAT(date_record, ' ', time_in) AS punch_time, id FROM time_logs
			UNION ALL
			SELECT employee_number, 'AM - out' AS punch_type, CONCAT(date_record, ' ', lunch_out) AS punch_time, id FROM time_logs
			UNION ALL
			SELECT employee_number, 'PM - in' AS punch_type, CONCAT(date_record, ' ', lunch_in) AS punch_time, id FROM time_logs
			UNION ALL
			SELECT employee_number, 'PM - out' AS punch_type, CONCAT(date_record, ' ', time_out) AS punch_time, id FROM time_logs
		) punches
		JOIN time_logs a ON punches.id = a.id
		JOIN employees e ON punches.employee_number = e.employee_number
		WHERE punch_time IS NOT NULL
		ORDER BY STR_TO_DATE(punch_time, '%Y-%m-%d %H:%i:%s') DESC
		LIMIT 1";

		$latestEntry = $conn->query($latestPunchQuery);

		if ($latestEntry->num_rows > 0) {
			$row = $latestEntry->fetch_assoc();

			$imagePath = "id_picture/" . $row['id_picture'];
			$profilePic = (!empty($row['id_picture']) && file_exists($imagePath)) ? $imagePath : "frontpage-image/default-image.jpg";

			$employeeName = $row['employee_name'];
			$employeeNumber = $row['employee_number'];
			$flexi = $row['Flexi'];
			$latestPunch = $row['punch_type'];
			$latestTime = date("H:i:s", strtotime($row['punch_time']));
			$status = $row['status'];
			$statusColor = ($status == "Late") ? "red" : "green";
		} else {
			$profilePic = "frontpage-image/default-image.jpg";
			$employeeName = $employeeNumber = "No records";
			$latestPunch = $latestTime = $status = "";
			$statusColor = "black";
			$flexi = "";
		}
	?>