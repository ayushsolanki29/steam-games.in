<?php
if (basename($_SERVER['PHP_SELF']) == 'actions.php' || basename($_SERVER['PHP_SELF']) == 'actions.php') include 'sendmail.php';;

function generateRandomToken($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[random_int(0, $max)];
    }

    return $token;
}
function createRememberMeToken($user_id)
{
    global $con;

    if (is_null($user_id)) {
        return false;
    }

    $user_id = (int)$user_id; // Ensure it's an integer
    $token = bin2hex(random_bytes(32));
    $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));

    $insertQuery = "INSERT INTO rememberme_tokens (user_id, token, expiration) VALUES (?, ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param('iss', $user_id, $token, $expiration);

    if ($stmt->execute()) {
        setcookie("rememberme", $token, strtotime('+30 days'), '/', '', false, true);
        return $user_id;
    } else {
        return false;
    }
}
function isEmailRegistered($email)
{
    global $con;
    $query = "SELECT COUNT(*) AS row FROM users WHERE email='$email'";
    $run = mysqli_query($con, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}
function isUserRegistered($user)
{
    global $con;
    $query = "SELECT COUNT(*) AS row FROM users WHERE username='$user'";
    $run = mysqli_query($con, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}
function getUserByEmail($email)
{
    global $con;
    $query = "SELECT `username`, `email`,`password`,`ac_status`,`id` FROM users WHERE `email`='$email'";
    $run = mysqli_query($con, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data;
}
function validateSignUpForm($form_data)
{
    $response = array();
    $response['status'] = true;

    // Check password
    if (empty($form_data['password']) || strlen($form_data['password']) < 6) {
        $response['msg'] = "Password should be at least 6 characters long!";
        $response['status'] = false;
        $response['field'] = "password";
        return $response;
    }

    // Check username length
    if (empty($form_data['username']) || strlen($form_data['username']) < 3 || strlen($form_data['username']) > 25) {
        $response['msg'] = "Username should be between 3 and 25 characters!";
        $response['status'] = false;
        $response['field'] = "username";
        return $response;
    }

    // Check email
    if (empty($form_data['email'])) {
        $response['msg'] = "Email is not given!";
        $response['status'] = false;
        $response['field'] = "email";
        return $response;
    }

    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $response['msg'] = "Invalid email format!";
        $response['status'] = false;
        $response['field'] = "email";
        return $response;
    }

    if (isEmailRegistered($form_data['email'])) {
        $response['msg'] = "Email ID is already in use";
        $response['status'] = false;
        $response['field'] = "email";
        return $response;
    }

    if (isUserRegistered($form_data['username'])) {
        $response['msg'] = "Username is already taken!";
        $response['status'] = false;
        $response['field'] = "username";
        return $response;
    }

    return $response;
}
function validateLoginForm($form_data)
{
    $response = ['status' => true];

    if (empty($form_data['password'])) {
        return [
            'status' => false,
            'msg' => 'Password is not given!',
        ];
    }

    if (empty($form_data['email'])) {
        return [
            'status' => false,
            'msg' => 'Email is not given!',
        ];
    }

    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        return [
            'status' => false,
            'msg' => 'Invalid email format!',

        ];
    }
    return $response;
}
function validateEmailForm($form_data)
{
    $response = ['status' => true];
    if (!isEmailRegistered($form_data['email'])) {
        return [
            'status' => false,
            'msg' => 'Email is not found',
        ];
    }
    if (empty($form_data['email'])) {
        return [
            'status' => false,
            'msg' => 'Email is not given!',
        ];
    }

    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        return [
            'status' => false,
            'msg' => 'Invalid email format!',

        ];
    }
    return $response;
}
function getUserById($userId)
{
    global $con;
    $query = "SELECT * FROM users WHERE `id` = '$userId'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function createUser($data)
{
    global $con;
    global $domain;

    $email = mysqli_real_escape_string($con, $data['email']);
    $username = mysqli_real_escape_string($con, $data['username']);
    $password = mysqli_real_escape_string($con, $data['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $code =  generateRandomToken(52);
    $URL = $domain . 'auth/verify.php?u=success&UNCODDED=true&token=' . $code;

    $query = "INSERT INTO `users`(`email`, `username`, `password`,`code`) VALUES ('$email','$username','$hashed_password','$code')";
    $added = mysqli_query($con, $query);
    if ($added) {
        $isSended = sendCode($email, "verify Email", $URL, $username, "Today", "register.html");
        if ($isSended) {
            return true;
        } else
            return true;
    } else {
        return false;
    }
}
function createGoogleUser($data)
{
    global $con;

    $username = mysqli_real_escape_string($con, $data[0]);
    $email = mysqli_real_escape_string($con, $data[1]);
    $profile = mysqli_real_escape_string($con, $data[2]);

    $query = "INSERT INTO `users`(`email`, `username`,`profile`,`ac_status`,`type`) VALUES ('$email','$username','$profile','true','google')";
    $added = mysqli_query($con, $query);
    if ($added) {
        return true;
    } else {
        return false;
    }
}
function getUserByToken($token)
{
    global $con;
    $query = "SELECT * FROM users WHERE `code` = '$token'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function getpromo()
{
    global $con;
    $query = "SELECT * FROM `settings` WHERE `id` = '1'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function getcoupon_code($id)
{
    global $con;
    $query = "SELECT * FROM `coupon_codes` WHERE `id` = '$id'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function removeToken($token)
{
    global  $con;
    $query = "UPDATE users SET `code`='' WHERE code = '$token'";
    return mysqli_query($con, $query);
}
function addToken($userID)
{
    global  $con;
    $token = generateRandomToken(52);
    $query = "UPDATE users SET `code`='$token' WHERE `id` = '$userID'";
    return mysqli_query($con, $query);
}
function addToCart($user_id, $product_id)
{
    global  $con;
    $query = "INSERT INTO `cart`(`user_id`, `product_id`) VALUES ('$user_id','$product_id')";
    $added = mysqli_query($con, $query);
    if ($added) {
        return true;
    } else {
        return false;
    }
}
function getProductData($id)
{
    global $con;
    $query = "SELECT `id`, `title`, `price`, `discount`, `thumbnail`,`category_id` FROM `products` WHERE `id` = '$id'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function getCategory($id)
{
    global $con;
    $query = "SELECT `category_name` FROM `category` WHERE `id` = '$id'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function removeItemFromCart($id)
{
    global $con;
    $query = "DELETE FROM `cart` WHERE `id` = '$id'";
    $result = mysqli_query($con, $query);

    return $result;
}

function coupounCodeFetch($coupon)
{
    global $con;
    $query = mysqli_query($con, "SELECT * FROM `coupon_codes` WHERE `name` = '$coupon'");
    return mysqli_fetch_assoc($query);
}
function isCoupounCodeActive($coupon)
{
    global $con;
    $query = "SELECT COUNT(*) AS `row` FROM `coupon_codes` WHERE `name`='$coupon'";
    $run = mysqli_query($con, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}
function totalAmount($cart_total, $taxes, $discount)
{
    $final =  number_format($cart_total + $taxes, 0);
    return $final - $discount;
}
function fetchProductIDs($con, $user_id, $status = "processing", $delivered = null)
{
    if ( $status == "paid") {
        $condition = "user_id = '$user_id' AND status = '$status' OR status = 'processing'";
    }else{
        $condition = "user_id = '$user_id' AND status = '$status'";
    }
    if ($delivered !== null) {
        $condition .= " AND delivered = '$delivered'";
    }

    $query = "SELECT products FROM transition WHERE $condition";
    $result = mysqli_query($con, $query);

    $product_ids = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_ids = array_merge($product_ids, explode(',', $row['products']));
        }
    }

    return $product_ids;
}
function fetchProductDetails($con, $product_ids)
{
    $product_details = [];

    if (!empty($product_ids)) {
        $product_ids = array_map('intval', $product_ids); // Ensure all IDs are integers
        $product_ids_str = implode(',', $product_ids); // Convert IDs to a comma-separated string

        // Query to fetch product details
        $query = "SELECT id, title, thumbnail,twofactor FROM products WHERE id IN ($product_ids_str)";
        $result = mysqli_query($con, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_details[] = $row;
            }
        }
    }

    return $product_details;
}
function fetchTrendingProducts($con, $product_ids)
{
    $ids = implode(',', array_map('intval', $product_ids));
    $query = "SELECT * FROM products WHERE id IN ($ids) ORDER BY FIELD(id, $ids)";
    $result = mysqli_query($con, $query);
    $product_details = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $product_details;
}
function fetchProductCount($con, $user_id)
{
    $query = "SELECT COUNT(DISTINCT product_id) AS product_count
              FROM (
                  SELECT TRIM(BOTH ',' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(transition.products, ',', numbers.n), ',', -1)) AS product_id
                  FROM transition
                  JOIN (
                      SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
                      UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
                  ) numbers
                  ON CHAR_LENGTH(transition.products) - CHAR_LENGTH(REPLACE(transition.products, ',', '')) >= numbers.n - 1
                  WHERE transition.user_id = ? 
              ) AS product_ids";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['product_count'] ?? 0;
}
function getTxtId($user_id)
{
    global $con;
    $query = "SELECT `txt_id` FROM transition WHERE `user_id` = '$user_id'";
    $result = mysqli_query($con, $query);

    return mysqli_fetch_assoc($result);
}
function createNotification($message, $url)
{
    global $con;
    $query = mysqli_query($con, "INSERT INTO `notification`(`message`, `url`) VALUES ('$message','$url')");
    if ($query) {
        return true;
    } else {
        return false;
    }
}
