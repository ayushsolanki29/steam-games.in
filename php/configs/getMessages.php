<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user'] ?? null;
$admin_id = 1;
$chat_user_id = $_GET['user_id'] ?? null;

// Check if the user is an admin
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;

if ($is_admin && $chat_user_id) {
    // If the user is an admin and a chat user ID is specified
    $current_user_id = $chat_user_id;
} elseif ($user_id) {
    // If a regular user is logged in
    $current_user_id = $user_id;
} else {
    echo json_encode(['error' => 'User not authenticated or chat user ID not specified']);
    exit;
}

$query = "SELECT * FROM messages WHERE user_id = '$current_user_id' ORDER BY time ASC";
$result = $con->query($query);

if ($result) {
    $messages = array();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
} else {
    echo json_encode(['error' => 'Query failed: ' . $con->error]);
}
