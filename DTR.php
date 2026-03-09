<?php
include 'db.php';
session_start();
$stats = [];

$sql = "SELECT func_div_office, COUNT(*) as total 
        FROM idlar_uploaded 
        GROUP BY func_div_office";

$result_stats = $conn->query($sql);

while($row = $result_stats->fetch_assoc()){
    $stats[strtolower($row['func_div_office'])] = $row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DTR Work From Home</title>
    <link rel="stylesheet" href="DTRStyle.css">
	<link rel="icon" href="frontpage-image/SDO Sanpedro Logo.png" type="image/png">
	<meta http-equiv="refresh" content="300">
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
        <div style="flex: 1;height:65vh;display: flex; flex-direction: column; gap: 10px; border-radius: 30px; background-color: rgba(255, 255, 255, 0.5); box-shadow:0px 4px 10px rgba(0,0,0,0.15);">
            <!-- TIME-IN FORM -->
			<div class="dtrinput" id="outin" style="width:100%;">
				<form id="dtrForm" action="process.php" method="POST">
					<input type="text" id="employee_number" name="employee_number" required placeholder="Employee #" autofocus>
					<p style="font-size:30px;font-weight:bold;margin:0px;text-align:center;"><span id="employee_name"></span></p>
					<p style="font-size:11px;margin-bottom:5px;margin-top:0px;" id="time_logs"></p>
					<div id="actionButtons"><!-- Dynamic Buttons Here --></div>
				</form>
			</div>
<?php include 'logic/latest_punch.php'; ?>
	
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
				document.getElementById("dtrForm").addEventListener("keydown", function(e) {
					if (e.key === "Enter") {
						e.preventDefault();
					}
				});
			</script>
        </div>

        <!-- RIGHT COLUMN -->
    <div style="flex: 2;">
			<div class="timestamp" style="height:15vh; overflow-y: auto; border: 1px solid #ccc; width:100%;">
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
					<?php include 'logic/recent_logs.php'; 
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
						?>
					</tbody>
				</table>
			</div>
		<div>
			<!-- IDLAR UPLOADING Section -->
			<div class="idlar-container" style="height:24vh">

				<div class="idlar-title">
					IDLAR Document Upload
				</div>

				<form class="idlar-form" action="upload_idlar.php" method="POST" enctype="multipart/form-data">

					<div class="idlar-row">

						<div class="idlar-field">
							<label>Employee Number</label>
							<input type="text" id="idlar_emp_number" name="employee_number" 
							placeholder="Enter Employee #" required>
						</div>

						<div class="idlar-field">
							<label>Employee Name</label>
							<input type="text" id="idlar_emp_name" name="emp_name" readonly>
						</div>

						<div class="idlar-field">
							<label>Functional Division</label>
							<input type="text" id="idlar_division" name="func_div_office" readonly>
						</div>

					</div>

					<div class="idlar-upload">

						<input type="file" name="idlar_file" id="idlar_file" required>

						<button type="submit" class="upload-btn">
							Upload IDLAR
						</button>

					</div>

				</form>

			</div>
		</div>
		<div>
			<div class="idlar-container" style="height:23vh">
				<div class="idlar-title">
					IDLAR Upload Statistics
				</div>
				<div class="stat-cards">
					<div class="card osds">
						<h3>OSDS</h3>
						<p><?= $stats['osds'] ?? 0 ?></p>
					</div>
					<div class="card sgod">
						<h3>SGOD</h3>
						<p><?= $stats['sgod'] ?? 0 ?></p>
					</div>
					<div class="card cid">
						<h3>CID</h3>
						<p><?= $stats['cid'] ?? 0 ?></p>
					</div>
				</div>
			</div>
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
</footer>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="scripts/employee_lookup.js"></script>
	<script src="scripts/buttons.js"></script>
	<script src="scripts/security.js"></script>
	<script src="scripts/greetings.js"></script>
	<script src="scripts/clock.js"></script>
	<script src="scripts/idlar_lookup.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<?php if(isset($_SESSION['message'])): ?>
		<script>
		Swal.fire({
			icon: '<?php echo $_SESSION['status'] == "success" ? "success" : "error"; ?>',
			title: '<?php echo $_SESSION['status'] == "success" ? "Success" : "Upload Error"; ?>',
			text: '<?php echo $_SESSION['message']; ?>',
			confirmButtonText: 'OK'
		});
		</script>
		<?php 
			unset($_SESSION['message']); 
			unset($_SESSION['status']); 
			endif; 
		?>
</body>
</html>
