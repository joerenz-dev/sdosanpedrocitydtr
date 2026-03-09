<?php
/**
 * Record DTR Page
 * SDO San Pedro City DTR System
 */
require_once 'config/session.php';
require_once 'config/database.php';

// Require login
requireLogin();

$db = getConnection();
$userId = getUserId();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $time_in_am = $_POST['time_in_am'] ?? null;
    $time_out_am = $_POST['time_out_am'] ?? null;
    $time_in_pm = $_POST['time_in_pm'] ?? null;
    $time_out_pm = $_POST['time_out_pm'] ?? null;
    $work_arrangement = $_POST['work_arrangement'] ?? 'office';
    $remarks = $_POST['remarks'] ?? '';
    
    try {
        // Check if record already exists
        $checkStmt = $db->prepare("SELECT id FROM dtr_records WHERE user_id = ? AND date = ?");
        $checkStmt->execute([$userId, $date]);
        
        if ($checkStmt->fetch()) {
            $error = 'DTR record for this date already exists.';
        } else {
            $stmt = $db->prepare("
                INSERT INTO dtr_records 
                (user_id, date, time_in_am, time_out_am, time_in_pm, time_out_pm, work_arrangement, remarks) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId, $date, $time_in_am, $time_out_am, 
                $time_in_pm, $time_out_pm, $work_arrangement, $remarks
            ]);
            
            // Log activity
            $logStmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, 'record_dtr', 'Recorded DTR for ' || ?, ?)");
            $logStmt->execute([$userId, $date, getUserIP()]);
            
            $success = 'DTR recorded successfully!';
        }
    } catch (PDOException $e) {
        $error = 'Failed to record DTR. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record DTR - SDO San Pedro City</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #0066cc;
            font-size: 1.5rem;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .form-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .form-card h2 {
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .time-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #0066cc;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-left: 10px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0066cc;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .time-grid {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SDO San Pedro City DTR</h1>
    </div>

    <div class="container">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <div class="form-card">
            <h2>Record Daily Time Record</h2>

            <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="date">Date *</label>
                    <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required max="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label>Morning Schedule</label>
                    <div class="time-grid">
                        <div>
                            <label for="time_in_am">Time In AM</label>
                            <input type="time" id="time_in_am" name="time_in_am">
                        </div>
                        <div>
                            <label for="time_out_am">Time Out AM</label>
                            <input type="time" id="time_out_am" name="time_out_am">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Afternoon Schedule</label>
                    <div class="time-grid">
                        <div>
                            <label for="time_in_pm">Time In PM</label>
                            <input type="time" id="time_in_pm" name="time_in_pm">
                        </div>
                        <div>
                            <label for="time_out_pm">Time Out PM</label>
                            <input type="time" id="time_out_pm" name="time_out_pm">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="work_arrangement">Work Arrangement *</label>
                    <select id="work_arrangement" name="work_arrangement" required>
                        <option value="office">Office-Based</option>
                        <option value="work_from_home">Work From Home</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="field_work">Field Work</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Optional: Add any notes or remarks for this DTR entry"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit DTR</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
