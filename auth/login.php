<?php
include '../php/configs/db.php';
require '../vendor/autoload.php';
include '../php/configs/google-config.php';
session_start();

if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
}

if (isset($_GET['code'])) {
    try {
        $token = $gclient->fetchAccessTokenWithAuthCode($_GET['code']);
        if (!isset($token['error'])) {
            $gclient->setAccessToken($token['access_token']);
            $_SESSION['access_token'] = $token['access_token'];
            $gservice = new Google_Service_Oauth2($gclient);
            $udata = $gservice->userinfo->get();
            $googlename = strtok($udata['name'], " ");
            $googleemail = $udata['email'];
            $googleprofile = $udata['picture'];
            header("location:../php/configs/actions.php?google=true&login=true&auth=set&gname=$googlename&gemail=$googleemail&gprofile=$googleprofile");
        }
    } catch (Exception $e) {
        echo 'Exception caught: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Login - Steam Games";
    $meta_desc = "Log in to Steam-Games.in. Access your account to manage purchases, track orders, and explore our vast collection of discounted Steam games.";
    $meta_keywords = "login, Steam Games login, user login, account access, manage purchases, track orders, discounted Steam games";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "auth/login.php"; // URL of the login page
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
                        <div class="form-login__social">
                            <ul class="social">
                                <li><a href="<?= $gclient->createAuthUrl() ?>"><img src="../assets/img/google.svg" alt="google"> </a></li>
                            </ul>
                        </div>
                        <div class="form-login__box">
                            <div class="uk-heading-line uk-text-center"><span>or with Email</span></div>
                            <form action="../php/configs/actions.php?login" method="post">
                                <?php
                                if (isset($_SESSION['error']['msg'])) {
                                    echo '<div class="uk-margin"><p class="error">' . $_SESSION['error']['msg'] . '</p></div>';
                                }
                                if (isset($_GET['uc']) && isset($_GET['code'])) {
                                    echo '<div class="uk-margin"><p class="success">Please check your email. We need you to verify your email address.</p></div>';
                                }
                                if (isset($_GET['psschanged'])) {
                                    echo '<div class="uk-margin"><p class="success">Password is successfully changed!</p></div>';
                                }

                                ?>
                                <input type="hidden" name="cb" value="<?php if (isset($_GET['cb'])) {
                                                                            echo $_GET['cb'];
                                                                        } else {
                                                                            echo "";
                                                                        } ?>">
                                <div class="uk-margin"><input class="uk-input" type="text" name="email" placeholder="Email"></div>
                                <div class="uk-margin custom-password-input">

                                    <input class="uk-input" type="password" name="password" id="password" placeholder="Password">
                                    <svg id="toggle-password" class="custom-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4.5C7.305 4.5 3.27 7.17 1.5 11.25c1.77 4.08 5.805 6.75 10.5 6.75s8.73-2.67 10.5-6.75C20.73 7.17 16.695 4.5 12 4.5zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0-5c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z" />
                                    </svg>

                                </div>
                                <div class="uk-margin"><button name="login" type="submit" class="uk-button uk-button-danger uk-width-1-1">Log In</button></div>
                                <div class="uk-margin uk-text-center"><a href="forget-password.php">Forgotten password?</a></div>

                                <hr>
                                <div class="uk-text-center"><span>Don’t have an account?</span><a class="uk-margin-small-left" href="register.php">Register</a></div>
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
?>