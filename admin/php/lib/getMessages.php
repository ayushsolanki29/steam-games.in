<?php
include '../../../php/configs/db.php';

$user_id = $_SESSION['user'];
$chat_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($user_id && $chat_user_id) {
    $query = "SELECT * FROM messages WHERE (user_id = '$user_id' AND admin_id = '$chat_user_id') OR (user_id = '$chat_user_id' AND admin_id = '$user_id') ORDER BY time ASC";
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
} else {
    echo json_encode(['error' => 'User not authenticated or chat user ID not specified']);
}
