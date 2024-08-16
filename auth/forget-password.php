<?php
include '../php/configs/db.php';
session_start();

if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Email Recovery - Steam Games";
    $meta_desc = "Recover your password on Steam-Games.in. Enter your email to receive instructions for resetting your password and gaining access to your account.";
    $meta_keywords = "email recovery, forgot password, password reset, Steam Games password recovery, reset password instructions";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "auth/forget-password.php"; // URL of the email recovery page
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
                            <div class="uk-heading-line uk-text-center"><span>Recover Email</span></div>
                            <form action="../php/configs/actions.php?recover_email" method="post">
                                <?php
                                if (isset($_SESSION['error']['msg'])) {
                                    echo '<div class="uk-margin"><p class="error">' . $_SESSION['error']['msg'] . '</p></div>';
                                }
                                if (isset($_GET['uc'])) {
                                    echo '<div class="uk-margin"><p class="success">Please check your email. We Just Send Reset Passowrd Link on ' . $_GET['uc'] . '</p></div>';
                                }

                                ?>

                                <div class="uk-margin"><input class="uk-input" type="text" name="email" placeholder="Email"></div>
                                <div class="uk-margin"><button name="reset_email" type="submit" class="uk-button uk-button-danger uk-width-1-1">Send Reset Link</button></div>
                                <hr>
                                <div class="uk-text-center"><span>Ahh! i remember my password.</span><a class="uk-margin-small-left" href="login.php">Login</a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


</body>

</html>
<?php

unset($_SESSION['error']);
?>