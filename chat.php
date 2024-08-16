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
if (!isset($_COOKIE['chat_admin'])) {
    header("Location:chat_verify.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Chat with Admin - Steam Games";
    $meta_desc = "Chat in real-time with the admin of Steam-Games.in. Get instant assistance, ask questions, or provide feedback directly through our chat feature.";
    $meta_keywords = "chat with admin, real-time chat, admin support, live chat, Steam Games chat, instant assistance";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "chat.php"; // URL of the chat page
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
            background-color: #FFF;
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

        .notice-board {
            display: none;
            width: 100%;
            height: 100%;
            text-align: center;
            height: 100%;
            align-items: center;
            justify-content: center;
            z-index: 99999999999999999;
            padding-bottom: 20px;
        }

        .cookie-card {

            max-width: 320px;
            padding: 1rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 20px 20px 30px rgba(0, 0, 0, .05);
        }

        .cookie-card .title {
            font-weight: 600;
            color: red;
        }

        .cookie-card .description {
            margin-top: 1rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: rgb(75 85 99);
        }

        .cookie-card .description a {
            --tw-text-opacity: 1;
            color: rgb(59 130 246);
        }

        .cookie-card .description a:hover {
            -webkit-text-decoration-line: underline;
            text-decoration-line: underline;
        }

        .cookie-card .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            -moz-column-gap: 1rem;
            column-gap: 1rem;
            flex-shrink: 0;
        }

        .cookie-card .pref {
            font-size: 0.75rem;
            line-height: 1rem;
            color: rgb(31 41 55);
            -webkit-text-decoration-line: underline;
            text-decoration-line: underline;
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background-color: transparent;
        }

        .cookie-card .pref:hover {
            color: rgb(156 163 175);
        }

        .cookie-card .pref:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        .cookie-card .accept {
            font-size: 0.75rem;
            line-height: 1rem;
            background-color: rgb(17 24 39);
            font-weight: 500;
            border-radius: 0.5rem;
            color: #fff;
            padding-left: 1rem;
            padding-right: 1rem;
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
            border: none;
            transition: all .15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cookie-card .accept:hover {
            background-color: rgb(55 65 81);
        }

        .cookie-card .accept:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }
    </style>
</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <h3 class="uk-text-lead">Chat With Admin</h3>
                <div class="uk-grid uk-grid-small" data-uk-grid>

                    <div class="uk-width">
                        <div class="chat-messages-box">
                            <div class="chat-messages-head">
                                <div class="user-item">
                                    <?php $admin_stmt = $con->prepare("SELECT `data2`, `data3` FROM `settings` WHERE `id` = ?");
                                    $_id = 4;
                                    $admin_stmt->bind_param("i", $_id);
                                    $admin_stmt->execute();
                                    $result = $admin_stmt->get_result();

                                    if ($result->num_rows == 1) {
                                        $data = $result->fetch_assoc();
                                    }
                                    ?>
                                    <div class="user-item__avatar"><img src="assets/img/profile/<?= $data['data3'] ?>" alt="user"></div>
                                    <div class="user-item__desc">
                                        <div class="user-item__name">
                                            <?= $data['data2'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <!-- <a class="ico_call" href="contact.php"></a> -->
                                    <a class="ico_info-circle" href="contact.php#contact_more"></a>
                                </div>
                            </div>
                            <div class="chat-messages-body" id="chat-messages-body">
                                <!-- Messages will be appended here -->
                            </div>
                            <span class="chat_loader"></span>
                            <div class="notice-board">
                                <div class="cookie-card">
                                    <span class="title">⚠ Messaging Blocked by Admin</span>
                                    <p class="description">You have been blocked by the admin for inappropriate behavior or spamming, which may violate our policy. You cannot send messages but can still use our other services.</p>
                                    <div class="actions">
                                        <button class="pref" onclick="window.location.href = 'privacy_policy.php'">
                                            privacy policy
                                        </button>
                                        <button onclick="window.location.href = 'index.php'" class="accept">
                                            okay got it
                                        </button >
                                    </div>
                                </div>
                            </div>
                            <div class="chat-messages-footer">
                                <form id="chat-form" action="#!">
                                    <div class="chat-messages-form">
                                        <div class="chat-messages-form-controls">
                                            <input class="chat-messages-input" type="text" id="chat-message" placeholder="Type a message">
                                        </div>
                                        <div class="chat-messages-form-btn">
                                            <button class="ico fa fa-paper-plane" type="submit"></button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>


    <script>
        $(document).ready(function() {
            setInterval(fetchMessages, 2000);

            $('#chat-form').on('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });
        });

        function fetchMessages() {
            $.ajax({
                url: 'php/configs/getMessages.php',
                method: 'GET',
                success: function(response) {
                    $(".chat_loader").hide();
                    try {
                        var messages = JSON.parse(response);
                        var chatBody = $('#chat-messages-body');
                        chatBody.html('');

                        messages.forEach(function(message) {
                            $(".chat_loader").hide();
                            if (message.status == 1) {
                                $(".notice-board").show();
                                $(".notice-board").css("display", "flex");
                                $(".chat-messages-footer").hide();
                            } else {
                                $(".notice-board").hide();
                                $(".chat-messages-footer").show();
                                var messageItem = $('<div>').addClass('messages-item').addClass(message.type == 'admin' ? '--your-message' : '--friend-message');
                                var messageText = $('<div>').addClass('messages-item__text').text(message.msg);
                                messageItem.append(messageText);
                                chatBody.append(messageItem);
                            }

                        });

                        chatBody.scrollTop(chatBody[0].scrollHeight);
                    } catch (e) {
                        console.error("Invalid JSON response:", response);
                    }
                }
            });
        }

        function sendMessage() {
            var userId = <?= $user_id ?>;
            var message = $('#chat-message').val();
            var fileName = '';

            $.ajax({
                url: 'php/configs/sendMessage.php',
                method: 'POST',
                data: {
                    user_id: userId,
                    type: 'user',
                    msg: message,
                    file_name: fileName
                },
                success: function(response) {
                    $('#chat-message').val('');
                    fetchMessages();
                }
            });
        }
    </script>

</body>

</html>