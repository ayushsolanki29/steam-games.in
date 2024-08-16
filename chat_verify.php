<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();
$_SESSION['error'] = [];
if (!isset($_SESSION['user'])) {
    $_SESSION['error']['msg'] = "You need to login for chat with admin!";
    header("Location:auth/login.php?cb=chat");
    exit();
}
if (isset($_COOKIE['chat_admin'])) {
    header("Location:chat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Chat Verification - Steam Games";
    $meta_desc = "Verify your email for chat on Steam-Games.in. Complete the verification process to access real-time communication and support with our admin.";
    $meta_keywords = "chat verification, email verification, verify email for chat, Steam Games chat, admin support";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "chat_verify.php"; // URL of the chat verify page
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

    <?php include 'php/pages/header.php' ?>
    <style>
        .chat_verify_form {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: space-around;
            width: 50%;
            background-color: white;
            border-radius: 12px;
            padding: 20px;
        }

        .chat_verify_form .title {
            font-size: 20px;
            font-weight: bold;
            color: black
        }

        .chat_verify_form .message {
            color: #a3a3a3;
            font-size: 14px;
            margin-top: 4px;
            text-align: center
        }

        .chat_verify_form .inputs {
            margin-top: 10px
        }

        .chat_verify_form .inputs input {
            width: 32px;
            height: 32px;
            text-align: center;
            border: none;
            border-bottom: 1.5px solid #d2d2d2;
            margin: 0 10px;
        }

        .chat_verify_form .inputs input:focus {
            border-bottom: 1.5px solid royalblue;
            outline: none;
        }

        .chat_verify_form .action {
            margin-top: 24px;
            padding: 12px 16px;
            border-radius: 8px;
            width: 100%;
            border: none;
            background-color: royalblue;
            color: white;
            cursor: pointer;
            align-self: end;
        }

        .chat_verify_form .action.resend {
            background-color: orangered;
        }

        .notice-board {
            display: flex;
            width: 100%;
            height: 50%;
            margin-top: 50px;
            text-align: center;
            align-items: center;
            justify-content: center;
            z-index: 99999999999999999;
            padding-bottom: 20px;
        }

        @media only screen and (max-width: 575px) {
            .chat_verify_form {
                width: 350px;
            }


        }

        .chat_loader {
            z-index: 111;
            width: 48px;
            height: 48px;
            display: block;
            margin: 15px auto;
            position: relative;
            color: #FFF;
            box-sizing: border-box;
            animation: rotation_19 1s linear infinite;
        }

        .chat_loader::after,
        .chat_loader::before {
            content: '';
            box-sizing: border-box;
            position: absolute;
            width: 24px;
            height: 24px;
            top: 0;
            background-color: royalblue;
            border-radius: 50%;
            animation: scale50 1s infinite ease-in-out;
        }

        .chat_loader::before {
            top: auto;
            bottom: 0;
            background-color: #FF3D00;
            animation-delay: 0.5s;
        }

        @keyframes rotation_19 {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes scale50 {

            0%,
            100% {
                transform: scale(0);
            }

            50% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <h3 class="uk-text-lead">Verify Yourself</h3>
                <p>Verification is required to chat with the admin to prevent spammers. You need to verify each time you use the chat with admin function. Your session is stored only 24h. after 1 day, you will need to verify again for an extra layer of security.</p>

                <h4 class="uk-heading-line"><span>Why Verification is Needed?</span></h4>
                <p>Verification helps us maintain a secure environment and protect against spammers. It ensures that only verified users can access the chat with admin function.</p>

                <?php
                if (isset($_GET['err'])) {
                    echo '<div class="uk-margin"><p class="error">' . $_GET['err'] . '</p></div>';
                }
                ?>
                <br>
                <div class="notice-board">
                    <form class="chat_verify_form" id="email_form">
                        <div class="title">Email Verify</div>
                        <div class="title"><?= $user['email'] ?></div>
                        <p class="message">We will sent a verification code on your email address</p>
                        <button type="button" class="action" id="send_mail">send mail</button>
                    </form>
                    <form class="chat_verify_form" method="post" action="php/configs/actions.php" id="otp_form">
                        <span class="chat_loader"></span>
                        <div class="opt_details">

                            <div class="title">OTP</div>
                            <div class="title">Verification Code</div>
                            <p class="message">We have sent a verification code to <?= $user['email'] ?></p>
                            <div class="inputs">
                                <input type="text" name="num1" maxlength="1" tabindex="0" required>
                                <input type="text" name="num2" maxlength="1" tabindex="1" required>
                                <input type="text" name="num3" maxlength="1" tabindex="2" required>
                                <input type="text" name="num4" maxlength="1" tabindex="3" required>
                                <input type="text" name="num5" maxlength="1" tabindex="4" required>
                            </div>
                            <button name="submit_otp" class="action">verify me</button>
                            <?php if (isset($_GET['err'])) { ?>
                                <button type="button" onclick="window.location.href= 'php/configs/actions.php?resend_mail=true&attempts=over'" class="action resend">resend otp</button>

                            <?php  } ?>

                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('.inputs input').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase

                var index = $('.inputs input').index(this);
                if ($(this).val().length === 1 && index < $('.inputs input').length - 1) {
                    $('.inputs input').eq(index + 1).focus();
                }
            });

            $('.inputs input').on('keydown', function(e) {
                var index = $('.inputs input').index(this);
                if (e.key === 'Backspace' && $(this).val().length === 0 && index > 0) {
                    $('.inputs input').eq(index - 1).focus();
                }
            });

            $('.inputs input').on('focus', function() {
                var index = $('.inputs input').index(this);
                var filled = true;
                for (var i = 0; i < index; i++) {
                    if ($('.inputs input').eq(i).val().length === 0) {
                        $('.inputs input').eq(i).focus();
                        filled = false;
                        break;
                    }
                }
                if (!filled) {
                    $(this).blur();
                }
            });
            const optSession = <?= isset($_SESSION['code']) ? 'true' : 'false'; ?>;

            if (optSession) {
                $("#email_form").hide();
                $("#otp_form").show();
                $(".opt_details").show();
                $(".chat_loader").hide();
            } else {
                $("#otp_form").hide();

                $('#send_mail').on('click', function() {
                    $("#email_form").hide();
                    $("#otp_form").show();
                    $(".opt_details").hide();

                    $.ajax({
                        url: 'php/configs/actions.php?chat_verify=true',
                        method: 'POST',
                        data: {
                            user_id: <?= json_encode($_SESSION['user']); ?>,
                            send_otp: true,
                        },
                        success: function(response) {
                            try {
                                $(".opt_details").show();
                                $(".chat_loader").hide();
                            } catch (e) {
                                console.error("Invalid JSON response:", response);
                            }
                        }
                    });
                });
            }
        });
    </script>

</body>

</html>