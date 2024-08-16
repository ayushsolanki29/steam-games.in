<?php
include '../php/configs/db.php';
require '../vendor/autoload.php';
include '../php/configs/google-config.php';

session_start();
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
}

if (isset($_GET['uc']) && isset($_GET['error'])) {
    $_SESSION['error']['msg'] = $_GET['error'];
}
function showFormData($field)
{
    if (isset($_SESSION['formdata'])) {
        $formdata = $_SESSION['formdata'];
        return $formdata[$field];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Register - Steam Games";
    $meta_desc = "Register on Steam-Games.in. Create your account to start buying discounted Steam games, manage orders, and explore our vast game collection.";
    $meta_keywords = "register, Steam Games register, user registration, create account, buy Steam games, manage orders";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "auth/register.php"; // URL of the register page
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
                                <li><a href="<?= $gclient->createAuthUrl() ?>"><img src="../assets/img/google.svg" alt="google"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="form-login__box">
                            <div class="uk-heading-line uk-text-center"><span>or with Email</span></div>
                            <form action="../php/configs/actions.php?register" method="post">
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="uk-margin"><p class="error">' . $_SESSION['error']['msg'] . '</p></div>';
                                }
                                ?>


                                <div class="uk-margin"><input class="uk-input" name="email" type="email" value="<?= showFormData('email') ?>" placeholder="Email"></div>
                                <div class="uk-margin"><input class="uk-input" name="username" type="text" value="<?= showFormData('username') ?>" placeholder="Username"></div>
                                <div class="uk-margin custom-password-input">

                                    <input class="uk-input" type="password" name="password" id="password" placeholder="Password">
                                    <svg id="toggle-password" class="custom-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4.5C7.305 4.5 3.27 7.17 1.5 11.25c1.77 4.08 5.805 6.75 10.5 6.75s8.73-2.67 10.5-6.75C20.73 7.17 16.695 4.5 12 4.5zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0-5c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z" />
                                    </svg>

                                </div>
                                <div class="uk-margin"><button class="uk-button uk-button-danger uk-width-1-1" name="register" type="submit">Register</button></div>
                                <div class="uk-margin">
                                    <p>By Creating an Account, You Are Accepting Our <a href="policy.php"> Privacy Policy </a> and <a href="terms.php"> Terms of service.</a></p>
                                </div>

                                <div class="uk-text-center"><span>Already have an account?</span><a class="uk-margin-small-left" href="login.php">Log In</a></div>
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
unset($_SESSION['formdata']);
?>