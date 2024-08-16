<?php
include '../php/configs/db.php';
require '../vendor/autoload.php';
include '../php/configs/google-config.php';

session_start();
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
}
if (!isset($_SESSION['password_reset'])) {
    header("Location: forget-password.php");
}
if (isset($_POST['change_passowrd'])) {
    $response['status'] = true;
    $password = $_POST['password'];
    $user_id = $_SESSION['password_reset'];
    $confirm_password = $_POST['confirm_passowrd'];

    if (empty($password) || strlen($password) < 6) {
        $_SESSION['error']['msg'] = "Password should be at least 6 characters long!";
        header("Location: change_password.php");
        exit;
    }

    if ($password == $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $passowrd_change = mysqli_query($con, "UPDATE users SET `code`='',`password`='$hashed_password' WHERE id = '$user_id'");
        if ($passowrd_change) {
            header("Location: login.php?psschanged");
            exit;
        }
    } else {
        $_SESSION['error']['msg'] = "Password & Confirm password should be same!";
        header("Location: change_password.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Change Password - Steam Games";
    $meta_desc = "Change your password on Steam-Games.in. Securely update your account password to enhance account security and access.";
    $meta_keywords = "change password, update password, Steam Games change password, account security, password update";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "auth/change_password.php"; // URL of the change password page
    ?>

    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_desc ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $url ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_desc ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $url ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_desc ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $url ?>">

    <?php include '../php/pages/header.php' ?>
    <style>
        .custom-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            fill: currentColor;
        }

        .custom-password-input {
            position: relative;
        }

        .custom-password-input svg {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body class="page-login dark-theme">


    <div class="page-wrapper">
        <main class="page-first-screen">
            <div class="uk-grid uk-grid-small uk-child-width-1-2@s uk-flex-middle uk-width-1-1" data-uk-grid>
                <div class="logo-big" onclick="window.location.href = '../index.php'">
                    <img src="../assets/img/logo-white.png" alt="logo" class="animation-navspinv">

                </div>
                <div>
                    <br>
                    <div class="form-login">

                        <div class="form-login__box">
                            <div class="uk-heading-line uk-text-center"><span>Create New Passowrd</span></div>
                            <form action="" method="post">
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="uk-margin"><p class="error">' . $_SESSION['error']['msg'] . '</p></div>';
                                }
                                ?>


                                <div class="uk-margin custom-password-input">

                                    <input class="uk-input" type="password" name="password" id="password" placeholder="Password">
                                    <svg id="toggle-password" class="custom-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4.5C7.305 4.5 3.27 7.17 1.5 11.25c1.77 4.08 5.805 6.75 10.5 6.75s8.73-2.67 10.5-6.75C20.73 7.17 16.695 4.5 12 4.5zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0-5c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z" />
                                    </svg>

                                </div>
                                <div class="uk-margin">
                                    <input class="uk-input" id="con_pass" name="confirm_passowrd" type="password" placeholder="Confirm Password">
                                </div>
                                <div class="uk-margin">
                                    <button class="uk-button uk-button-danger uk-width-1-1" id="chang_pass" name="change_passowrd" type="submit" disabled>Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../assets/js/libs.js"></script>
    <script>
        $(document).ready(function() {
            $('#pass, #con_pass').on('keyup', function() {
                const password = $('#pass').val();
                const confirmPassword = $('#con_pass').val();

                if (password === confirmPassword && password !== '') {
                    $('#chang_pass').prop('disabled', false);
                } else {
                    $('#chang_pass').prop('disabled', true);
                }
            });
            $('#toggle-password').on('click', function(e) {
                e.preventDefault();
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).html('<path d="M12 4.5C7.305 4.5 3.27 7.17 1.5 11.25c1.77 4.08 5.805 6.75 10.5 6.75s8.73-2.67 10.5-6.75C20.73 7.17 16.695 4.5 12 4.5zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0-5c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"/>');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).html('<path d="M12 5C7.806 5 4.078 7.107 2.105 10.5c1.973 3.393 5.701 5.5 9.895 5.5s7.922-2.107 9.895-5.5C19.922 7.107 16.194 5 12 5zM12 14c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0-4c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"/>');
                }
            });
        });
    </script>


</body>

</html>
<?php
unset($_SESSION['error']);
unset($_SESSION['formdata']);
?>