<?php
include '../../../php/configs/db.php';

$query = "
    SELECT user_id, MAX(id) as last_message_id
    FROM messages
    GROUP BY user_id
    ORDER BY last_message_id DESC
";

$result = $con->query($query);

$users = array();
while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $last_message_id = $row['last_message_id'];
    
    // Get the last message
    $messageQuery = "SELECT msg FROM messages WHERE id = $last_message_id";
    $messageResult = $con->query($messageQuery);
    $messageRow = $messageResult->fetch_assoc();
    
    $users[] = array(
        'user_id' => $user_id,
        'last_message' => $messageRow['msg']
    );
}

echo json_encode($users);
?>
