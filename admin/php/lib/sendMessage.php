<?php
include '../../../php/configs/db.php';

$user_id = $_POST['user_id'];
$admin_id = $_POST['admin_id'];
$msg = $_POST['msg'];
$file_name = $_POST['file_name'];

if ($user_id && $admin_id && $msg) {
    $query = "INSERT INTO messages (user_id, admin_id, msg, file_name, read_status, time) VALUES ('$user_id', '$admin_id', '$msg', '$file_name', 0, NOW())";
    if ($con->query($query)) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $query . "<br>" . $con->error;
    }
} else {
    echo "User ID, admin ID, and message are required";
}

