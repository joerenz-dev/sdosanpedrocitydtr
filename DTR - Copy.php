<?php
include 'db.php';
session_start();

	// Handle logout
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['admin']); 
		header("Location: DTR.php");
		exit;
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DTR Work From Home</title>
    <link rel="stylesheet" href="DTRStyle.css">
	<link rel="icon" href="frontpage-image/SDO Sanpedro Logo.png" type="image/png">
	<meta http-equiv="refresh" content="60">

    <!-- ==========================
         SECURITY: DISABLE INSPECT
         ========================== -->
    <script>
        document.addEventListener("contextmenu", e => e.preventDefault());
        document.addEventListener("keydown", e => {
            if (
                e.ctrlKey && ["u", "U", "s", "S"].includes(e.key) ||
                e.key === "F12" ||
                (e.ctrlKey && e.shiftKey && e.key === "I")
            ) {
                e.preventDefault();
            }
        });
    </script>
</head>
<body style="background: linear-gradient(to right, #ffab7a, #fff87a); font-family: 'Century Gothic', sans-serif; padding-left:30px; padding-right:30px; padding-top:10px;">

    <!-- HEADER -->
    <header style="margin-bottom:3px;">
        <div class="headerDiv" style="width:100%; border-radius: 30px; background-color: rgba(255, 255, 255, 0.5);">
            <div class="newLogo" style=" margin:5px; padding-left:20px;">
                <img src="frontpage-image/DepEd-MATATAG.png" style="height:10vh;">
                <img src="frontpage-image/Bagong_Pilipinas_logo.png" style="height:10vh; margin-left:5px;">
                <img src="frontpage-image/SDO Sanpedro Logo.png" style="height:9.5vh; margin-left:5px;">
            </div>
			<div class="SDO"></div>
			<div class="time"id="current-time"></div>
        </div>

        <div class="Home" style="display: flex; justify-content: space-between; align-items: center; padding:1px;">
            <div style="font-size:2vh;">
                <!-- <a href="EmpReg.php" style="padding-left:25px;">Register Employee /</a>
                <a href="#" id="fullscreen-toggle" onclick="openFullscreen()">Go Fullscreen</a>
				-->
            </div>
            <div id="top-date" style="font-weight: bold; font-size: 1.2vw; padding-right:25px;"></div>
        </div>
    </header>

    <!-- MAIN SECTION WITH TWO COLUMNS -->
    <main style="display: flex; gap: 10px;">
        <!-- LEFT COLUMN -->
        <div style="flex: 1;height:70vh;display: flex; flex-direction: column; gap: 10px; border-radius: 30px; background-color: rgba(255, 255, 255, 0.5);">
            <!-- TIME-IN FORM -->
			<div class="dtrinput" id="outin" style="width:100%;">
				<form id="dtrForm" action="process.php" method="POST">
					<input type="text" id="employee_number" name="employee_number" required placeholder="Employee #" autofocus>
					<p style="font-size:30px;font-weight:bold;margin:0px;text-align:center;"><span id="employee_name"></span></p>
					<p style="font-size:11px;margin-bottom:5px;margin-top:0px;" id="time_logs"></p>
					<div id="actionButtons"><!-- Dynamic Buttons Here --></div>
				</form>
				
				<!-- jQuery for AJAX -->
				<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
				<script>
				$(document).ready(function() {
					$('#employee_number').on('input', function() {
						var emp_id = $(this).val();

						if (emp_id.length > 0) {
							$.ajax({
								url: 'fetch_employee.php',
								type: 'POST',
								dataType: 'json',
								data: { employee_number: emp_id },
						
								success: function(response) {
									if (response.status === 'ok') {
										$('#employee_name').text(response.name);
										$('#time_logs').html(
											`AM-IN <strong>${response.time_in}</strong> | ` +
											`AM-OUT <strong>${response.lunch_out}</strong> | ` +
											`PM-IN <strong>${response.lunch_in}</strong> | ` +
											`PM-OUT <strong>${response.time_out}</strong>`
										);
									} else {
										$('#employee_name').text('Not found');
										$('#time_logs').html('');
									}
								}
							});
						} else {
							$('#employee_name').text('');
							$('#time_logs').text('');
						}
					});
				});
				</script>
			</div>
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
	
		<div class="StatusPicture" style="margin-bottom:30px;display:flex;">
            <!-- STATUS INFO -->
			<div class="status" style="padding-left:10px;width:50%;justify-content:left;">
				<p class='afterTimeInfo' style="font-size:2.5vh;font-weight:bold;margin-top:0px;">Employee: <br><span id='empName'><?php echo $employeeName; ?></span></p>
				<p class='afterTimeInfo' style="font-size:2vh;margin-top:-12px;">Employee Number: <span id='empNumber'><?php echo $employeeNumber; ?></span></p>
				<p class='afterTimeInfo' style="font-size:2vh;margin-top:-12px;">Flexi Category: <span id='Flexi'><?php echo $flexi; ?></span></p>
				<p class='afterTimeInfo' style="font-size:2vh;margin-top:-12px;">Latest Punch: <span id='latestPunch'><?php echo $latestPunch ?? 'No recent punch'; ?></span></p>
				<p class='afterTimeInfo' style="font-size:2vh;margin-top:-12px;">Time: <span id='latestTime'><?php echo $latestTime ?? '-'; ?></span></p>
				<p class='afterTimeInfo' style="font-size:2.5vh;font-weight:bold;margin-top:-12px;">Status: <br><span id='statusText' style="color: <?php echo $statusColor; ?>; font-weight: bold;font-size:3vh;"><?php echo $status; ?></span></p>
			</div>

			<!-- PROFILE PICTURE -->
			<div class="profpic" style="padding-right:10px; display: flex; justify-content: right; width:50%;">
				<img id="profileImage" src="<?php echo $profilePic; ?>" alt="Profile Picture" 
					onerror="this.onerror=null; this.src='frontpage-image/default-image.jpg';" 
					style="height:25vh">
			</div>
		</div>
			<script>
				// AUTO-CLEAR STATUS INFO AFTER 10 SECONDS
				setTimeout(() => {
					["empName", "empNumber", "latestPunch", "latestTime", "statusText","Flexi"].forEach(id => {
						document.getElementById(id).innerText = "";
					});
				document.getElementById("profileImage").src = "frontpage-image/default-image.jpg";
				}, 30000);
				
document.addEventListener('keydown', function(event) {
	if (event.code === 'Enter' || event.code === 'NumpadEnter') {
		const empNumberInput = document.getElementById('employee_number');
		const employeeNumber = empNumberInput ? empNumberInput.value.trim() : '';

		if (!employeeNumber) {
			alert("Please enter employee number.");
			return;
		}

		console.log("Sending to fingerprint listener:", employeeNumber); // DEBUG

		fetch('http://localhost:5000/verify/', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ employee_number: employeeNumber })
		})
		.then(response => {
			if (!response.ok) {
				throw new Error(`Server error: ${response.status}`);
			}
			return response.json();
		})
.then(result => {
	if (result.status === 'verified') {
		console.log("[✓] Fingerprint verified. Waiting for action button...");

		const buttonIds = ['time_in', 'lunch_out', 'lunch_in', 'time_out'];
		let attempts = 0;

		const tryClick = () => {
			const submitButton = buttonIds
				.map(id => document.querySelector(`button[value="${id}"]`))
				.find(btn => btn && !btn.disabled && btn.offsetParent !== null);

			if (submitButton) {
				console.log("[✓] Auto-clicking:", submitButton.value);
				submitButton.click();
			} else if (++attempts < 10) {
				setTimeout(tryClick, 200); // Retry every 200ms up to 2 seconds
			} else {
				alert("No active time-in/out button found.");
			}
		};

		tryClick();
	} else {
		alert('Fingerprint verification failed.');
	}
})

.catch(err => {
    console.warn("PLEASE DO NOT SPAM THE ENTER BUTTON", err.message);
    // Silent fail — no popup
});

		event.preventDefault();
	}
});

			</script>
        </div>

        <!-- RIGHT COLUMN -->
    <div style="flex: 2;">
<?php
    $today = date("m-d");
    $today_full = date("Y-m-d");
    $previous_month = date("Y-m", strtotime("last month"));

    $birthdays = [];
    $anniversaries = [];
    $perfect_attendance = [];
    $activities = [];
    $announcements = [];

    // =============================
    // Fetch Activities
    // =============================
    $result = $conn->query("SELECT title, announcement FROM announcement_tb 
                            WHERE type = 'activities' AND '$today_full' BETWEEN start_date_post AND end_date_post");
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row['title'] . ": " . $row['announcement'];
    }

    // =============================
    // Fetch Announcements
    // =============================
    $result = $conn->query("SELECT title, announcement FROM announcement_tb 
                            WHERE type = 'announcements' AND '$today_full' BETWEEN start_date_post AND end_date_post");
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row['title'] . ": " . $row['announcement'];
    }

    // =============================
    // Birthdays
    // =============================
    $result = $conn->query("SELECT employee_name FROM employees WHERE DATE_FORMAT(birth_date, '%m-%d') = '$today'");
    while ($row = $result->fetch_assoc()) {
        $birthdays[] = $row['employee_name'];
    }

    // =============================
    // Anniversaries
    // =============================
    $result = $conn->query("SELECT employee_name FROM employees WHERE DATE_FORMAT(date_hired, '%m-%d') = '$today'");
    while ($row = $result->fetch_assoc()) {
        $anniversaries[] = $row['employee_name'];
    }

    // =============================
    // Perfect Attendance
    // =============================
    $previous_month_label = date("F Y", strtotime("last month"));

    $daysResult = $conn->query("SELECT COUNT(*) AS workdays 
        FROM (SELECT date_record FROM time_logs 
              WHERE DATE_FORMAT(date_record, '%Y-%m') = '$previous_month' 
              AND WEEKDAY(date_record) < 5 
              GROUP BY date_record) AS days");
    $total_working_days = $daysResult->fetch_assoc()['workdays'] ?? 0;

    $perfect_names = [];
    if ($total_working_days > 0) {
        $result = $conn->query("
            SELECT e.employee_name 
            FROM employees e
            WHERE NOT EXISTS (
                SELECT 1 FROM time_logs t
                WHERE e.employee_number = t.employee_number 
                AND DATE_FORMAT(t.date_record, '%Y-%m') = '$previous_month'
                AND WEEKDAY(t.date_record) < 5 
                AND (t.time_in IS NULL OR t.time_out IS NULL)
            )
            GROUP BY e.employee_number
            HAVING COUNT(*) = $total_working_days");
        while ($row = $result->fetch_assoc()) {
            $perfect_names[] = $row['employee_name'];
        }
        if (!empty($perfect_names)) {
            $perfect_attendance[] = "Perfect Attendance for the month of $previous_month_label (" . implode(", ", $perfect_names) . ")";
        }
    }

    // =============================
    // Final Display Content
    // =============================
    $bday = !empty($birthdays) ? implode(" • ", $birthdays) : "No birthday celebrants today";
    $act = !empty($activities) ? implode("<br><hr>", $activities) : "No Activities for today.";
    $anno = !empty($announcements) ? implode("<br><hr>", $announcements) : "No Announcement for today.";

?>
    <div style="height:52vh; overflow-y: auto; margin-top:0;">
        <!--
		<p style="margin-bottom:-15px;"><b>Happy Birthday:</b></p>
        <p><?= $bday; ?></p>
        <p style="margin-bottom:-15px;"><b>Activities:</b></p>
        <p style="margin-bottom:0px;"><?= $act; ?></p>
        <p style="margin-bottom:-15px;"><b>Announcements:</b></p>
        <p style="margin-bottom:0px;"><?= $anno; ?></p>
		--!>
    </div>
			<div class="timestamp" style="height:19vh; overflow-y: auto; border: 1px solid #ccc; width:100%;">
				<table style="width:100%;" id="employee" class="table-container">
					<thead>
						<tr>
							<th>Full Name</th>
							<th>Date</th>
							<th>AM-in</th>
							<th>AM-out</th>
							<th>PM-in</th>
							<th>PM-out</th>
							<th>Undertime</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$result = $conn->query("
								SELECT e.employee_name, a.date_record, 
								TIME(a.time_in) as time_in, 
								TIME(a.lunch_out) as lunch_out, 
								TIME(a.lunch_in) as lunch_in, 
								TIME(a.time_out) as time_out,
								TIME(a.undertime) as undertime,
								a.status,
								GREATEST(
									IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.time_in)), 0),
									IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.lunch_out)), 0),
									IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.lunch_in)), 0),
									IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.time_out)), 0),
									IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.undertime)), 0)
								) as latest_punch_time
							FROM time_logs a 
							JOIN employees e ON a.employee_number = e.employee_number 
							WHERE a.date_record >= CURDATE() - INTERVAL 1 DAY
							ORDER BY latest_punch_time DESC
							LIMIT 5
							");
while ($row = $result->fetch_assoc()) {
    $status_time_in = strtolower($row['status_time_in'] ?? '');
    $status_lunch_in = strtolower($row['status_lunch_in'] ?? '');

    // Apply red style individually if either is late
    $highlight_time_in = ($status_time_in === 'Late') ? "style='color: red; font-weight: bold;'" : "";
    $highlight_lunch_in = ($status_lunch_in === 'Late') ? "style='color: red; font-weight: bold;'" : "";

    echo "<tr>
        <td style='text-align:left;'>{$row['employee_name']}</td>
        <td>{$row['date_record']}</td>
        <td {$highlight_time_in}>{$row['time_in']}</td>
        <td>{$row['lunch_out']}</td>
        <td {$highlight_lunch_in}>{$row['lunch_in']}</td>
        <td>" . ($row['time_out'] ?: '') . "</td>
        <td style='color: red; font-weight: bold;'>" . ($row['undertime'] ? substr($row['undertime'], 0, 5) : '') . "</td>
    </tr>";
}
						$conn->close();
						?>
					</tbody>
				</table>
			</div>
		
	</div>
    </main>

<!-- FOOTER -->
<footer>
<style>
	#footer-alert {
    font-size: 15px;
    color: red;
	font-weight:bold;
    text-align: center;
    animation: blink 1s step-start 0s infinite;
    margin-top: 5px;
}

@keyframes blink {
    50% { opacity: 0; }
}
</style>
    <!-- //MARQUEE DISPLAY -->
    <div class="marquee">
        <span id="greeting" style="font-size:40px; color:blue; padding-top:5px;"></span>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div id="footer-alert"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</footer>

	<!-- //Greetings -->
	<script>
    function getGreeting() {
        const now = new Date();
        const hour = now.getHours();
        let greeting = "GOOD DAY, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";

        if (hour < 12) {
            greeting = "GOOD MORNING, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";
        } else if (hour < 18) {
            greeting = "GOOD AFTERNOON, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";
        } else {
            greeting = "GOOD EVENING, WELCOME TO SCHOOLS DIVISION OFFICE OF SAN PEDRO CITY!!!";
        }

        document.getElementById("greeting").textContent = greeting;
    }

    // Run when the page loads
    window.onload = getGreeting;
</script>
	<!-- JS FUNCTIONS FOR UI UPDATES -->
	<script>
	// FETCH AND DISPLAY ACTION BUTTONS BASED ON EMPLOYEE NUMBER
		document.getElementById("employee_number").addEventListener("input", function () {
		const empNum = this.value.trim();

		const actionDiv = document.getElementById("actionButtons");
			if (!actionDiv) return;

			if (empNum.length === 0) {
				actionDiv.innerHTML = '';
				return;
			}

		fetch("check_timein_status.php?employee_number=" + encodeURIComponent(empNum))
			.then(res => res.json())
.then(data => {
	console.log("Fetched button data:", data); // Debug line

	actionDiv.innerHTML = "";
	actionDiv.style.display = "flex";
	actionDiv.style.justifyContent = "center";
	actionDiv.style.flexWrap = "wrap";
	actionDiv.style.gap = "1px";

	const btnStyle = "font-size:1vw; padding:5px;width:75px;";

	const labelMap = {
		"time_in": "AM - in",
		"lunch_out": "AM - out",
		"lunch_in": "PM - in",
		"time_out": "PM - out"
	};

	const buttonOrder = ["time_in", "lunch_out", "lunch_in", "time_out"];
	const buttonMap = new Map((data.buttons || []).map(b => [b.action, b]));

	buttonOrder.forEach(action => {
		const buttonData = buttonMap.get(action);
		if (!buttonData) return;

		const button = document.createElement("button");
		button.type = "submit";
		button.name = "action";
		button.value = action;
		button.innerText = labelMap[action] || action;
		button.className = "buttons";
		button.style = btnStyle;

		if (!buttonData.enabled) {
			button.disabled = true;
			button.style.opacity = 0.5;
			button.style.cursor = "not-allowed";
		}

		actionDiv.appendChild(button); // Append directly
	});
})

			.catch(err => {
				console.error("Error fetching button status:", err);
				actionDiv.innerHTML = "<p style='color:red;'>Error loading actions.</p>";
			});
		});

    // DISPLAY TODAY'S DATE
    function updateTopDate() {
		const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById("top-date").textContent = now.toLocaleDateString('en-US', options);
    }
    updateTopDate();

    // DISPLAY LIVE CLOCK
    function updateCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById("current-time").textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateCurrentTime, 1000);
    updateCurrentTime();
	</script>
</body>
</html>
