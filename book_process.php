<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn() || !hasRole('customer')) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['service_id']);
    $customer_id = $_SESSION['user_id'];
    $date = clean($_POST['booking_date']);
    $time = clean($_POST['booking_time']);
    $notes = clean($_POST['notes']);

    $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, service_id, booking_date, booking_time, notes) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$customer_id, $service_id, $date, $time, $notes])) {
        // Create Notification for Provider
        $stmt = $pdo->prepare("SELECT provider_id FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        $provider_id = $stmt->fetchColumn();

        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, 'New Booking Request', 'You have a new booking request for your service.')");
        $stmt->execute([$provider_id]);

        setFlash('booking', 'Booking requested successfully! Waiting for provider approval.', 'success');
        redirect('customer/my-bookings.php');
    } else {
        setFlash('booking', 'Failed to book service. Please try again.', 'danger');
        redirect('service-details.php?id=' . $service_id);
    }
} else {
    redirect('index.php');
}
?>