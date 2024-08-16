<?php
include 'db.php';
include 'functions.php';
session_start();
$_SESSION['error'] = [];

if (isset($_GET['register']) && isset($_POST['register'])) {
    $response = validateSignUpForm($_POST);
    if ($response['status']) {
        if (createUser($_POST)) {
            header("Location: ../../auth/login.php?uc=success&code=INCODED");
            exit();
        } else {
            header("Location: ../../auth/register.php?uc=faild&error=some thing went wrong");
            exit();
        }
    } else {
        $_SESSION['error'] = $response;
        $_SESSION['formdata'] = $_POST;

        header("Location: ../../auth/register.php");
        exit();
    }
}
if (isset($_GET['google']) && isset($_GET['login']) && isset($_GET['auth']) && isset($_GET['gname']) && isset($_GET['gemail']) && isset($_GET['gprofile'])) {
    $gname = $_GET['gname'];
    $gemail = $_GET['gemail'];
    $gprofile = $_GET['gprofile'];

    $google = [$gname, $gemail, $gprofile];

    if (isEmailRegistered($gemail)) {
        $user = getUserByEmail($gemail);
        createRememberMeToken($user['id']);
        $_SESSION['user'] = $user['id'];
        header("Location: ../../index.php");
        exit();
    } else {
        if (createGoogleUser($google)) {
            $user = getUserByEmail($gemail);
            createRememberMeToken($user['id']);
            $_SESSION['user'] = $user['id'];
            header("Location: ../../index.php");
            exit();
        }
    }
    $_SESSION['error']['msg'] = "Something went wrong! ";
    header("Location: ../../auth/login.php");
    exit();
}
if (isset($_GET['verified']) && isset($_GET['token'])) {
    $user = getUserByToken($_GET['token']);
    if ($user) {
        if (removeToken($_GET['token'])) {
            $_SESSION['user'] = $user['id'];
            header("Location: ../../index.php");
        } else {
            header("Location: ../../auth/login.php");
        }
    }
}
if (isset($_GET['login']) && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = validateLoginForm($_POST);

    if ($response['status']) {
        if ($user = getUserByEmail($email)) {
            if ($user['ac_status'] == "true") {
                if (password_verify($password, $user['password'])) {

                    $user_id = $user['id'];
                    $token = bin2hex(random_bytes(32));
                    $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));
                    $insertQuery = "INSERT INTO rememberme_tokens (user_id, token, expiration) VALUES ($user_id, '$token', '$expiration')";
                    $run = mysqli_query($con, $insertQuery);
                    if ($run) {
                        setcookie("rememberme", $token, strtotime('+30 days'), '/', '', false, true); // Setting cookie
                        $_SESSION['user'] = $user['id'];
                        if (empty($_POST['cb'])) {
                            header("Location: ../../index.php");
                            exit();
                        } else {
                            $callback = $_POST['cb'];
                            header("Location: ../../$callback.php");
                            exit();
                        }
                    } else {
                        echo "<script>alert('Error while login please try again')</script>";
                        header("Location: ../../auth/login.php");
                        exit();
                    }
                } else {
                    $_SESSION['error']['msg'] = "Invalid Password!";
                    header("Location: ../../auth/login.php");
                    exit();
                }
            } else {
                $_SESSION['error']['msg'] = "Email is Not Verified! Please check Email";
                header("Location: ../../auth/login.php");
                exit();
            }
        } else {
            $_SESSION['error']['msg'] = "Email Not Exist!";
            header("Location: ../../auth/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = $response;
        header("Location: ../../auth/login.php");
        exit();
    }
}
if (isset($_GET['addtocart']) && isset($_GET['loggeduser']) && isset($_GET['product_id']) && isset($_GET['token'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error']['msg'] = "You need to login to add to cart!";
        header("Location: ../../auth/login.php");
        exit();
    }
    if (addToCart($_SESSION['user'], $_GET['product_id'])) {
        if (isset($_GET['cb'])) {
            $callback = $_GET['cb'];
            header("Location: ../../$callback");
            exit();
        } else {
            header("Location: ../../index.php");
            exit();
        }
    }
}
if (isset($_GET['removefromcart']) && isset($_GET['loggeduser']) && isset($_GET['product_id']) && isset($_GET['token'])) {

    if (removeItemFromCart($_GET['token'])) {
        header("Location: ../../cart.php");
        exit();
    }
}
if (isset($_GET['copoun_code']) && $_GET['copoun_code'] == 'find' && isset($_POST['copoun']) && isset($_POST['subtotal'])) {
    $entered_copoun = mysqli_real_escape_string($con, $_POST['copoun']);
    $subtotal = (int)$_POST['subtotal'];

    if (isCoupounCodeActive($entered_copoun)) {
        $copoun = coupounCodeFetch($entered_copoun);
        if ($subtotal >= $copoun['min']) {
            if ($subtotal <= $copoun['max']) {
                $discounted_total = number_format($subtotal * (1 - $copoun['value'] / 100), 0);
                $dic_amount = $subtotal - $discounted_total;
                echo json_encode([
                    'status' => true,
                    'msg' => 'Coupon applied successfully!',
                    'discounted_total' => $discounted_total,
                    'discount' => $copoun['value'],
                    'less_amount' => $dic_amount,
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'msg' => 'Max cart value exceeded! Max value: ' . $copoun['max'] . '₹'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Min cart value required: ' . $copoun['min'] . '₹'
            ]);
        }
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Invalid coupon!'
        ]);
    }
}
if (isset($_POST['send_otp']) && isset($_POST['user_id']) && isset($_GET['chat_verify'])) {
    $user_data = getUserById($_POST['user_id']);
    $token = strtoupper(generateRandomToken(5));
    $email_sended = sendCode($user_data['email'], 'Verify Email for Chat', $token, $user_data['username'], "today", "chat_otp.html");

    if ($email_sended) {
        $_SESSION['code'] = $token;
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP']);
    }
    exit();
}
if (isset($_POST['num1']) && isset($_POST['num2']) && isset($_POST['num3']) && isset($_POST['num4']) && isset($_POST['num5']) && isset($_POST['submit_otp']) && isset($_SESSION['code'])) {
    $user_code = strtoupper(mysqli_real_escape_string($con, $_POST['num1']) . mysqli_real_escape_string($con, $_POST['num2']) . mysqli_real_escape_string($con, $_POST['num3']) . mysqli_real_escape_string($con, $_POST['num4']) . mysqli_real_escape_string($con, $_POST['num5']));

    if ($user_code === $_SESSION['code']) {
        setcookie("chat_admin", generateRandomToken(15), time() + (1 * 24 * 60 * 60), "/");
        header("Location: ../../chat.php");
        exit();
    } else {
        header("Location: ../../chat_verify.php?err=code not matched");
        exit();
    }
}
if (isset($_GET['attempts']) && isset($_GET['resend_mail'])) {
    unset($_SESSION['code']);
    header("Location: ../../chat_verify.php");
    exit();
}
if (isset($_POST['comment']) && isset($_POST['submit_review']) && isset($_POST['product_id'])) {
    if (isset($_SESSION['user'])) {
        $user_data = getUserById($_SESSION['user']);
        $username = $user_data['username'];
        $product_id = $_POST['product_id'];
        $comment = mysqli_real_escape_string($con, $_POST['comment']);

        $comm_q = mysqli_query($con, "INSERT INTO `reviews`(`product_id`, `user_id`, `comment`)
         VALUES ('$product_id','$username','$comment')");
        createNotification($username . " write new comment", "game_reviews.php?s=$username");
        header("Location: ../../game_profile.php?p=$product_id");
        exit();
    } else {
        $_SESSION['error']['msg'] = "You need to login for review!";
        header("Location: ../../auth/login.php");
        exit();
    }
}
if (isset($_GET['contact_form']) && isset($_POST['contact_submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $reason = mysqli_real_escape_string($con, $_POST['reason']);
    if (isset($_POST['other_reason'])) {
        $other_reason = mysqli_real_escape_string($con, $_POST['other_reason']);
    } else {
        $other_reason = "";
    }
    $message = mysqli_real_escape_string($con, $_POST['message']);

    $contact_query = mysqli_query($con, "INSERT INTO `contact`(`name`, `email`, `reason`, `other`, `message`)
     VALUES ('$name','$email','$reason','$other_reason','$message')");
    if ($contact_query) {
        createNotification($name . " Submitted Contact Form", "contact.php?s=$email");
        $message = "Your message was sent successfully!";
        header("Location: ../../contact.php?success=$message");
        exit();
    } else {
        $message = "Faild to send Please Try Again!";
        header("Location: ../../contact.php?err=$message");
        exit();
    }
}
if (isset($_GET['txt_start']) && isset($_POST['cart_id'])) {
    $user_id = $_SESSION['user'];
    $cart_id = $_POST['cart_id'];

    $txt_id = 'STMG' . strtoupper(generateRandomToken(8)) . 'IN';
    $_SESSION['txt_id'] = $txt_id;
    $update_txt = mysqli_query($con, "UPDATE `cart` SET `txt_id`='$txt_id' WHERE `user_id`='$user_id'");

    if ($update_txt) {
        $response['success'] = true;
        $response['txt_id'] = $txt_id;
    }
    echo json_encode($response);
    exit;
}
if (isset($_POST['send_logindata'])) {
    // Sanitize and escape user inputs

    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $gamename = mysqli_real_escape_string($con, $_POST['gamename']);
    $txt_id = mysqli_real_escape_string($con, $_POST['txt_id']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Define the admin URL
    $_adminurl = $domain . 'admin/pending_deliveries.php';

    $check_query = "
        SELECT COUNT(*) as count 
        FROM `delivery` 
        WHERE `user_id` = '$user_id' 
          AND `product_id` = '$product_id' 
          AND `txt_id` = '$txt_id'
    ";
    $check_result = mysqli_query($con, $check_query);
    $check_row = mysqli_fetch_assoc($check_result);

    if ($check_row['count'] > 0) {
        $update_data = mysqli_query($con, "UPDATE `delivery` SET `message`='$message'  WHERE `user_id` = '$user_id'  
          AND `product_id` = '$product_id' 
          AND `txt_id` = '$txt_id'");
        header("Location: $_adminurl");
        exit;
    }

    $add_data = mysqli_query($con, "
        INSERT INTO `delivery` (`user_id`, `product_id`, `txt_id`,`message`)
        VALUES ('$user_id', '$product_id', '$txt_id', '$message')");

    if ($add_data) {
        $email_sended = sendLogindata($email, "Your $gamename Order is ready", $message, $gamename, "user_credentials.html");

        // Redirect based on email sending status
        if ($email_sended) {
            header("Location: $_adminurl?success=sent successfully");
        } else {
            header("Location: $_adminurl?err=user IDs sent but failed to send mail!");
        }
    } else {
        header("Location: $_adminurl?err=failed to send user IDs");
    }
    exit;
}
if (isset($_GET['recover_email']) && isset($_POST['reset_email'])) {
    $response =  validateEmailForm($_POST);

    if ($response['status'] == true) {
        echo $_POST['email'];
        $user_data = getUserByEmail($_POST['email']);
        print_r($user_data);
        $token = generateRandomToken(52);
        $user_email = $user_data['email'];
        $token_set = mysqli_query($con, "UPDATE users SET `code`='$token' WHERE `id` = '$user_data[id]'");
        if ($token_set) {
            $url_token = $domain . "auth/verify.php?reset_password&account=active&recover=" . $token . "&p";
            if (sendCode($user_data['email'], "Recover your Account", $url_token, $user_data['username'], 'today', "password_reset.html")) {
                header("Location: ../../auth/forget-password.php?uc=$user_email");
                exit();
            }
        } else {
            $_SESSION['error']['msg'] = "something went wrong!";
            header("Location: ../../auth/forget-password.php");
            exit();
        }
    } else {
        $_SESSION['error']['msg'] = $response['msg'];
        header("Location: ../../auth/forget-password.php");
        exit();
    }
}
