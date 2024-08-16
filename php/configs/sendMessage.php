<?php

include 'db.php';
session_start();

$user_id = $_POST['user_id'];
$type = $_POST['type'];
$msg = $_POST['msg'];

if ($user_id && $type && $msg) {
    $query = "INSERT INTO messages (type, user_id, msg,status, time) VALUES ('$type', '$user_id', '$msg', 0, NOW())";
    if ($con->query($query)) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $query . "<br>" . $con->error;
    }
} else {
    echo "User ID, type, and message are required";
}
