<?php
session_start();
include('db.php');
include('functions.php');

if (isset($_POST['upi_id'], $_POST['phone_number']) && isset($_GET['add_contact_info'])) {
    $upi_id = mysqli_real_escape_string($con, $_POST['upi_id']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);

    if (empty($upi_id) || empty($phone_number)) {
        $response['message'] = "Please enter UPI ID and Phone Number";
    } elseif (!preg_match('/^[\w.-]+@[\w.-]+$/', $upi_id)) {
        $response['message'] = "Invalid UPI ID";
    } elseif (!preg_match('/^[0-9]{1,4}[\s.-]?[0-9]{1,15}$/', $phone_number)) {
        $response['message'] = "Invalid Phone Number";
    } else {
        $response['message'] = "Verified";
        $response['success'] = true;
        $response['phone_number'] = $phone_number;
        $response['upi_id'] = $upi_id;
        setcookie('upi_id', $upi_id, time() + (3600 * 24 * 30), "/");
        setcookie('phone_number', $phone_number, time() + (3600 * 24 * 30), "/");
    }
    echo json_encode($response);
    exit;
}

if (isset($_POST['cart_id']) && isset($_SESSION['user']) && isset($_SESSION['txt_id']) && isset($_GET['add_payment_info'])) {
    $response = array('success' => false);
    $user_id = $_SESSION['user'];
    $cart_id = $_POST['cart_id'];
    $coupon_code = $_POST['coupon_code'];
    $txt_id = $_POST['txt_id'];
    $upi_id = $_POST['upi_id'];
    $phone_number = $_POST['phone_number'];
    $amount = $_POST['amount'];

    $result = mysqli_query($con, "SELECT `product_id` FROM `cart` WHERE `user_id`='$user_id'");
    $product_ids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $product_ids[] = $row['product_id'];
    }
    $product_ids_str = implode(",", $product_ids);

    $insert_transition = mysqli_query($con, "INSERT INTO `transition` (`user_id`, `txt_id`, `products`, `coupon_code`, `upi`, `contact`, `amount`, `status`) VALUES ('$user_id', '$txt_id', '$product_ids_str', '$coupon_code', '$upi_id', '$phone_number', '$amount', 'processing')");

    if ($insert_transition) {
        $response['success'] = true;
        $settings_result = mysqli_query($con, "SELECT `data1` FROM `settings` WHERE `id`='5'");
        $settings = mysqli_fetch_assoc($settings_result);
        $response['merchant_id'] = $settings['data1'];
        $response['company_name'] = "steam-games.in";
        $response['domain'] = $domain;
        createNotification("New Order Found!", "orders.php?s=$txt_id");
    }
    echo json_encode($response);
    exit;
}
