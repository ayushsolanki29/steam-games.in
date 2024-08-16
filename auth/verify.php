<?php
include '../php/configs/db.php';
session_start();
function verifyEmail($code)
{
    global  $con;
    $query = "UPDATE users SET `ac_status`='true' WHERE code = '$code'";
    return mysqli_query($con, $query);
}

if (isset($_GET['token']) && isset($_GET['UNCODDED']) && isset($_GET['u'])) {
    $token = $_GET['token'];
    $status =  verifyEmail($token);
    if ($status) {
        echo "<script>window.location.href = '../php/configs/actions.php?verified=user&token=$token'</script>";
    } else {
        echo "<script>alert('Something went wrong !')</script>";
        echo "<script>window.location.href = 'login.php'</script>";
    }
}

if (isset($_GET['recover'])) {
    $code = $_GET['recover'];
    $code_check = mysqli_query($con, "SELECT `id` FROM `users` WHERE `code`='$code'");
    if (mysqli_num_rows($code_check) > 0) {
        while ($user = mysqli_fetch_assoc($code_check)) {
            $_SESSION['password_reset'] =  $user['id'];
            header("location:change_password.php");
               exit;
        }
    } else {
        echo "<script>alert('Invalid Token!')</script>";
        echo "<script>window.location.href = 'login.php'</script>";
    }
}
