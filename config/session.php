<?php
/**
 * Session Management
 * SDO San Pedro City DTR System
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Check if user has one of multiple roles
 */
function hasAnyRole($roles) {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], $roles);
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /sdosanpedrocitydtr/login.php');
        exit();
    }
}

/**
 * Require specific role - redirect if doesn't have role
 */
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header('Location: /sdosanpedrocitydtr/dashboard.php');
        exit();
    }
}

/**
 * Get current user ID
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 */
function getUserName() {
    return $_SESSION['user_name'] ?? 'User';
}

/**
 * Get current user role
 */
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Set user session data
 */
function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['employee_id'] = $user['employee_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['role'] = $user['role'];
}

/**
 * Destroy user session (logout)
 */
function destroySession() {
    session_unset();
    session_destroy();
}

/**
 * Get user IP address
 */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
?>
