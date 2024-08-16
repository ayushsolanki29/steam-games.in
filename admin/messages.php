<?php
session_start();
include "../php/configs/db.php";
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['userid']) && isset($_GET['seen_message'])) {
    $uid = $_GET['userid'];
    $block_q = mysqli_query($con, "UPDATE `messages` SET `read` = 'read' WHERE `user_id` = '$uid'");
    if ($block_q) {
        $message = "All Message seen!";
        header("Location: messages.php?success=$message&user_id=$uid");
        exit;
    } else {
        $message = "seen Faild!";
        header("Location: messages.php?err=$message&user_id=$uid");
        exit;
    }
}

$chat_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$chat_user_id) {
    header("location:chat.php");
    exit;
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Messages - steam-games.in</title>
    <?php include 'php/pages/head.php' ?>
    <style>
        .msg {
            width: fit-content;
        }

        .user-message {
            background-color: #e2e2e2;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 5px;
            width: 200px;
        }

        .reply-message {
            background-color: #007bff;
            width: 200px;
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 5px;
            margin-left: auto;
        }

        #chat-messages-body {
            display: flex;
            flex-direction: column;

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
            background-color: #000;
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

<body id="page-top">
    <div id="wrapper">
        <?php include 'php/pages/sidebar.php' ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'php/pages/nav.php' ?>
                <?php $user_data = getUserById($chat_user_id); ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Chat with <?= $user_data['username'] ?> </h1>
                    <?php
                    if (isset($_GET['err'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_GET['err'] ?>
                        </div>
                    <?php }
                    if (isset($_GET['success'])) { ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_GET['success'] ?>
                        </div>
                    <?php }
                    ?>
                    <div class="card">
                        <div class="card-header py-3">
                            <a href="chat.php" class="btn btn-info btn-sm btn-circle">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <?php
                            if ($user_data['type'] == "google") { ?>
                                <img src="<?= $user_data['profile'] ?>" alt="Logo" class="team-logo rounded-circle" width="25">

                            <?php } else { ?>
                                <img src="../assets/img/profile/<?= $user_data['profile'] ?>" alt="Logo" class="team-logo rounded-circle" width="25">

                            <?php }
                            ?>
                            <a href="orders.php?s=<?= $user_data['username'] ?>" class="btn btn-info btn-sm btn-circle">
                                <i class="far fa-credit-card"></i>
                            </a>
                            <a href="users_list.php?s=<?= $user_data['email'] ?>" class="btn btn-info btn-sm btn-circle">
                                <i class="fas fa-user"></i>
                            </a>
                            <a href="messages.php?seen_message&userid=<?= $user_data['id'] ?>" class="btn btn-success btn-circle btn-sm">
                                <i class="fas fa-check-double"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="chat-messages-body" id="chat-messages-body">
                                <!-- Messages will be appended here -->
                                <span class="chat_loader"></span>
                            </div>
                        </div>

                        <div class="card-footer">
                            <form id="chat-form">
                                <div class="input-group">
                                    <input type="text" class="form-control chat-messages-input" id="chat-message" placeholder="Type a message">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'php/pages/footer.php' ?>
        </div>
    </div>

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
                url: '../php/configs/getMessages.php?user_id=<?= $chat_user_id ?>',
                method: 'GET',
                success: function(response) {
                    try {
                        var messages = JSON.parse(response);
                        var chatBody = $('#chat-messages-body');
                        chatBody.html('');

                        messages.forEach(function(message) {
                            var messageItemClass = message.type === 'user' ? 'user-message' : 'reply-message';
                            var messageItem = $('<div>').addClass('card mb-2 p-2').addClass(messageItemClass);
                            var messageText = $('<div>').addClass('card-text').text(message.msg);
                            messageItem.append(messageText);
                            chatBody.append(messageItem);
                        });

                        chatBody.scrollTop(chatBody[0].scrollHeight);
                    } catch (e) {
                        console.error("Invalid JSON response:", response);
                    }
                }
            });
        }

        function sendMessage() {
            var chatUserId = <?= $chat_user_id ?>;
            var message = $('#chat-message').val();
            var fileName = '';

            $.ajax({
                url: '../php/configs/sendMessage.php',
                method: 'POST',
                data: {
                    user_id: chatUserId,
                    type: 'admin',
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