<?php
/**
 * Dashboard Page
 * SDO San Pedro City DTR System
 */
require_once 'config/session.php';
require_once 'config/database.php';

// Require login
requireLogin();

$db = getConnection();
$userId = getUserId();
$userName = getUserName();
$userRole = getUserRole();

// Get today's DTR record
$today = date('Y-m-d');
$stmt = $db->prepare("SELECT * FROM dtr_records WHERE user_id = ? AND date = ?");
$stmt->execute([$userId, $today]);
$todayRecord = $stmt->fetch();

// Get stats
$statsStmt = $db->prepare("
    SELECT 
        COUNT(*) as total_days,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_days,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_days
    FROM dtr_records 
    WHERE user_id = ? AND MONTH(date) = MONTH(CURRENT_DATE())
");
$statsStmt->execute([$userId]);
$stats = $statsStmt->fetch();

// Get recent records
$recentStmt = $db->prepare("SELECT * FROM dtr_records WHERE user_id = ? ORDER BY date DESC LIMIT 10");
$recentStmt->execute([$userId]);
$recentRecords = $recentStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SDO San Pedro City DTR</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #0066cc;
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .logout {
            background: #dc3545;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .welcome h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0066cc;
        }

        .dtr-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .dtr-section h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .time-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .records-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .records-table th,
        .records-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .records-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-pending {
            background: #ffc107;
            color: #000;
        }

        .badge-approved {
            background: #28a745;
            color: white;
        }

        .badge-rejected {
            background: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .time-buttons {
                flex-direction: column;
            }

            .records-table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SDO San Pedro City DTR</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong> (<?php echo ucfirst($userRole); ?>)</span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>Dashboard</h2>
            <p>Welcome to your Daily Time Record management system. Track your attendance and view your records here.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Days This Month</h3>
                <div class="number"><?php echo $stats['total_days']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Approved Records</h3>
                <div class="number"><?php echo $stats['approved_days']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending Approval</h3>
                <div class="number"><?php echo $stats['pending_days']; ?></div>
            </div>
        </div>

        <div class="dtr-section">
            <h3>Today's Time Record - <?php echo date('F d, Y'); ?></h3>
            <?php if ($todayRecord): ?>
                <p>✓ You have already recorded your DTR for today.</p>
                <p><strong>Time In AM:</strong> <?php echo $todayRecord['time_in_am'] ?? 'N/A'; ?></p>
                <p><strong>Time Out AM:</strong> <?php echo $todayRecord['time_out_am'] ?? 'N/A'; ?></p>
                <p><strong>Time In PM:</strong> <?php echo $todayRecord['time_in_pm'] ?? 'N/A'; ?></p>
                <p><strong>Time Out PM:</strong> <?php echo $todayRecord['time_out_pm'] ?? 'N/A'; ?></p>
                <p><strong>Work Arrangement:</strong> <?php echo ucwords(str_replace('_', ' ', $todayRecord['work_arrangement'])); ?></p>
            <?php else: ?>
                <p>You haven't recorded your DTR for today yet.</p>
                <div class="time-buttons">
                    <a href="record-dtr.php" class="btn btn-primary">Record DTR</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="dtr-section">
            <h3>Recent DTR Records</h3>
            <table class="records-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In AM</th>
                        <th>Time Out AM</th>
                        <th>Time In PM</th>
                        <th>Time Out PM</th>
                        <th>Work Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentRecords)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No records found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($recentRecords as $record): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                            <td><?php echo $record['time_in_am'] ?? '-'; ?></td>
                            <td><?php echo $record['time_out_am'] ?? '-'; ?></td>
                            <td><?php echo $record['time_in_pm'] ?? '-'; ?></td>
                            <td><?php echo $record['time_out_pm'] ?? '-'; ?></td>
                            <td><?php echo ucwords(str_replace('_', ' ', $record['work_arrangement'])); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $record['status']; ?>">
                                    <?php echo ucfirst($record['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
