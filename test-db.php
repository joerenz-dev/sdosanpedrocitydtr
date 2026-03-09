<?php
/**
 * Database Connection Test
 * Use this to verify your database connection is working
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test - SDO San Pedro City DTR</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 50px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }
        .test-result {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            overflow-x: auto;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Connection Test</h1>
        
        <?php
        require_once 'config/database.php';
        
        try {
            echo '<div class="test-result info"><strong>Testing connection...</strong></div>';
            
            $db = getConnection();
            
            if ($db) {
                echo '<div class="test-result success">';
                echo '<strong>✓ Database connection successful!</strong><br>';
                echo 'Connected to database: <strong>' . DB_NAME . '</strong>';
                echo '</div>';
                
                // Test query
                $stmt = $db->query("SELECT COUNT(*) as count FROM users");
                $result = $stmt->fetch();
                
                echo '<div class="test-result success">';
                echo '<strong>✓ Users table accessible!</strong><br>';
                echo 'Total users: <strong>' . $result['count'] . '</strong>';
                echo '</div>';
                
                // Check tables
                $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                
                echo '<div class="test-result info">';
                echo '<strong>Database Tables:</strong><br>';
                echo '<pre>' . implode("\n", $tables) . '</pre>';
                echo '</div>';
                
                echo '<div class="test-result success">';
                echo '<strong>All tests passed! Your database is configured correctly.</strong><br><br>';
                echo '<a href="index.php">← Go to Landing Page</a> | ';
                echo '<a href="login.php">Login →</a>';
                echo '</div>';
                
            } else {
                echo '<div class="test-result error">';
                echo '<strong>✗ Failed to connect to database</strong>';
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="test-result error">';
            echo '<strong>✗ Database Error:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
            
            echo '<div class="test-result info">';
            echo '<strong>Troubleshooting Tips:</strong><br>';
            echo '1. Make sure MySQL is running in XAMPP<br>';
            echo '2. Verify database credentials in config/database.php<br>';
            echo '3. Import database/schema.sql via phpMyAdmin<br>';
            echo '4. Check if database "' . DB_NAME . '" exists<br>';
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 0.9rem;">
            <strong>Note:</strong> For security reasons, delete or rename this file after confirming your database connection works.
        </div>
    </div>
</body>
</html>
