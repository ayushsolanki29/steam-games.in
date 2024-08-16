<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Contact Us - Steam Games";
    $meta_desc = "Contact the admin of Steam-Games.in for assistance, inquiries, or feedback. Utilize our contact form, direct chat with admin, Instagram DM, email, or visit our physical address.";
    $meta_keywords = "contact us, Steam Games contact, admin contact, customer support, contact form, direct chat, Instagram DM, email contact, physical address";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "contact.php"; // URL of the contact page
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
        body.dark-theme .uk-alert-success {
            color: #fff;
            background: green;
        }

        body.dark-theme .nice-select {
            color: #fff;

        }

        body.dark-theme .uk-input,
        body.dark-theme .uk-textarea {
            background: #1a2634;
            color: white;
        }

        .uk-input {
            background: #fff;
        }

        body.dark-theme .uk-form-label {
            color: #fff;
        }

        .uk-alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .uk-alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .nice-select {
            width: 100%;
        }

        .insta_chat {
            max-width: 320px;
            display: flex;
            overflow: hidden;
            position: relative;
            padding: 0.875rem 72px 0.875rem 1.75rem;
            background: #f09433;
            background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f09433', endColorstr='#bc1888', GradientType=1);
            color: #ffffff;
            font-size: 15px;
            line-height: 1.25rem;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            vertical-align: middle;
            align-items: center;
            border-radius: 0.5rem;
            gap: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: none;
            transition: all .6s ease;
        }

        .insta_chat span {
            background: #f09433;
            background: -webkit-linear-gradient(45deg, #dc2743 0%, #dc2743 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: linear-gradient(45deg, #dc2743 0%, #dc2743 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f09433', endColorstr='#bc1888', GradientType=1);
            display: grid;
            position: absolute;
            right: 0;
            place-items: center;
            width: 3rem;
            height: 100%;
        }

        .insta_chat span svg {
            width: 1.5rem;
            font-size: 10px;
            height: 1.5rem;
        }

        .insta_chat:hover {
            box-shadow: 0 4px 30px rgba(4, 175, 255, .1), 0 2px 30px rgba(11, 158, 255, 0.06);
        }

        .admin_chat {
            max-width: 320px;
            font-family: inherit;
            font-size: 20px;
            background: royalblue;
            color: white;
            padding: 0.7em 1em;
            padding-left: 0.9em;
            display: flex;
            align-items: center;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            text-align: center;
        }

        .admin_chat span {
            display: block;
            margin-left: 0.3em;
            transition: all 0.3s ease-in-out;
        }

        .admin_chat svg {
            display: block;
            transform-origin: center center;
            transition: transform 0.3s ease-in-out;
        }

        .admin_chat:hover .svg-wrapper {
            animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        .admin_chat:hover svg {
            transform: translateX(3.2em) rotate(45deg) scale(1.1);
        }

        .admin_chat:hover span {
            transform: translateX(7em);
        }

        .admin_chat:active {
            transform: scale(0.95);
        }

        @keyframes fly-1 {
            from {
                transform: translateY(0.1em);
            }

            to {
                transform: translateY(-0.1em);
            }
        }

        .chat-options {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .instagram {
            background: #f09433;
            padding: 8px;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 10px;
            background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f09433', endColorstr='#bc1888', GradientType=1);
            color: #ffffff;
        }

        .uk-card-default {
            border-radius: 10px !important;
        }

        @media only screen and (max-width: 639px) {
            .btn-group{
                display: inline-block;
                gap: 10px;
            }
            .btn-group button{
                width: 100%;
            }
            .instagram{
                display: flex;
                width: 100%;
            }
        }
    </style>
</head>

<body class="page-profile ">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <h3 class="uk-text-lead">Contact Us</h3>

                <?php
                if (isset($_GET['success'])) { ?>
                    <div class="uk-alert-success" uk-alert>
                        <a class="uk-alert-close" href="contact.php" uk-close></a>
                        <p><?= $_GET['success'] ?></p>
                    </div>
                <?php }

                ?>
                <?php
                if (isset($_GET['err'])) { ?>
                    <div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" href="contact.php" uk-close></a>
                        <p><?= $_GET['err'] ?></p>
                    </div>
                <?php }

                ?>

                <!-- Contact Form -->
                <form class="uk-form-stacked" method="post" action="php/configs/actions.php?contact_form">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="contact-name">Name</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="contact-name" name="name" type="text" placeholder="Enter your name" required>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="contact-email">Email</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="contact-email" name="email" type="email" placeholder="Enter your email" required>
                        </div>
                    </div>

                    <div class="uk-margin ">
                        <label class="uk-form-label" for="contact-reason">Reason for Contact</label>
                        <div class="uk-form-controls ">
                            <select class="uk-select nice-select uk-width-1-1" name="reason" id="contact-reason" required>
                                <option value="" disabled selected>Select Your Query</option>
                                <option value="Contact Admin">Contact Admin</option>
                                <option value="Request a new Game">Request a new Game</option>
                                <option value="Refund">Refund</option>
                                <option value="Be a Partner">Be a Partner</option>
                                <option value="Order Details">Order Details</option>
                                <option value="Copyright Issue">Copyright Issue</option>
                                <option value="Report a Bug">Report a Bug</option>
                                <option value="Developer">Developer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-margin additional-field">
                        <label class="uk-form-label" for="contact-name">Other Reason</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="contact-name" name="other_reason" type="text" placeholder="Enter your other Reason ">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="contact-message">Message</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-textarea" id="contact-message" name="message" required rows="5" placeholder="Enter your message here..."></textarea>
                        </div>
                    </div>
                    <div class="btn-group">

                        <button class="uk-button uk-button-primary" name="contact_submit" type="submit">Submit</button> &nbsp;
                        <button class="uk-button uk-button-secoundary Developer" onclick="window.location.href = 'https://github.com/ayushsolanki29/'" type="button">Contact Developer</button>
                    </div>
                </form>

                <br id="other">
                <div id="contact_more" class="uk-heading-line uk-text-center"><span>or</span></div>
                <br>
                <div class="chat-options">
                    <button class="insta_chat" onclick="window.location.href = 'https://www.instagram.com/steamgames.in/'">
                        Chat on Instagram
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <path fill="#ffffff" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                            </svg>
                        </span>
                    </button>
                    <br>
                    <div class="uk-heading-line uk-text-center"><span>or</span></div>
                    <br>
                    <button class="admin_chat" onclick="window.location.href = 'chat.php'">
                        <div class="svg-wrapper-1">
                            <div class="svg-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                    <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
                                </svg>
                            </div>
                        </div>
                        <span>Direct Chat</span>
                    </button>
                </div>
                <hr>
                <div class="uk-card uk-card-default uk-card-body uk-margin-medium-bottom">
                    <h4 class="uk-card-title">Contact Information</h4>
                    <ul class="uk-list">
                        <li><strong>Founder:</strong> Abishek</li>
                        <li><strong>CEO:</strong> Akshaya</li>
                        <li><strong>Location :</strong> Tamil Nadu, India</li>
                        <li><strong>Social Media :</strong><span class="instagram" onclick="window.location.href = 'https://www.instagram.com/steamgames.in/'"> @steamgames.in</span></li>
                        <li><strong>Email:</strong> <a href="mailto:admin@steam-games.in">admin@steam-games.in</a> </li>
                    </ul>
                </div>
                <footer class=" uk-section-small uk-text-center">
                    <div class="uk-container">
                        <p>&copy; 2024-25 steam-games.in All rights reserved.</p>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('select.nice-select').niceSelect();
            $('.additional-field').hide();
            $('.Developer').hide();

            $('select.nice-select').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'other') {
                    $('.additional-field').show();
                } else {
                    $('.additional-field').hide();
                }
                if (selectedValue === 'Developer') {
                    $('.Developer').show();
                } else {
                    $('.Developer').hide();
                }

            });
        });
    </script>
</body>

</html>