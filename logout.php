<?php
/**
 * Logout
 * SDO San Pedro City DTR System
 */
require_once 'config/session.php';
require_once 'config/database.php';

if (isLoggedIn()) {
    try {
        $db = getConnection();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, 'logout', 'User logged out', ?)");
        $stmt->execute([getUserId(), getUserIP()]);
    } catch (PDOException $e) {
        // Silent fail
    }
}

destroySession();
header('Location: login.php');
exit();
?>
