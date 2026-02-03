<?php
/**
 * MYCITY - Utility Functions
 */

/**
 * Redirect to a given URL
 */
function redirect($path)
{
    header("Location: " . BASE_URL . $path);
    exit();
}

/**
 * Clean input data
 */
function clean($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check user role
 */
function hasRole($role)
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

/**
 * Format currency
 */
function formatCurrency($amount)
{
    return 'K' . number_format($amount, 2);
}

/**
 * Flash messages
 */
function setFlash($name, $message, $type = 'success')
{
    $_SESSION['flash'][$name] = [
        'message' => $message,
        'type' => $type
    ];
}

function getFlash($name)
{
    if (isset($_SESSION['flash'][$name])) {
        $flash = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $flash;
    }
    return null;
}
?>