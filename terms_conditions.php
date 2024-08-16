<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Terms and Conditions - Steam Games";
    $meta_desc = "Read the terms and conditions for using Steam-Games.in. Understand your rights, responsibilities, and our policies to ensure a safe and enjoyable experience on our platform.";
    $meta_keywords = "terms and conditions, Steam Games terms, user agreement, policies, Steam game policies, terms of service";
    $meta_img = $domain . "assets/img/og-img.png"; // Ensure this image exists
    ?>
    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_desc ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $domain ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_desc ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $domain ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_desc ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $domain . "terms_conditions.php" ?>">

    <?php include 'php/pages/header.php' ?>
    <style>
        body.dark-theme .uk-accordion-title,
        body.dark-theme .uk-accordion-content {
            color: #ffffff;
        }

        .page-main {
            padding: 20px;
        }

        .page-main img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <h3 class="uk-text-lead">Terms and Conditions</h3>

                <h4 class="uk-heading-line"><span>Social Login</span></h4>
                <p>We provide social login options for accessing your account. Users can log in using their Google account. During this process, we collect your username, email, and profile photo from your Google account for verification purposes.</p>

                <h4 class="uk-heading-line"><span>Payments</span></h4>
                <p>Users are required to pay for the games they purchase. Payment tracking can be done using the link provided upon checkout.</p>

                <h4 class="uk-heading-line"><span>Chat with Admin</span></h4>
                <p>To chat with the admin, users must verify themselves every day.</p>

                <h4 class="uk-heading-line"><span>Order Processing</span></h4>
                <p>Your order may take up to 12 hours to process. Please allow this time for us to provide your order. If you order the same game twice, we will provide the same ID and password.</p>

                <h4 class="uk-heading-line"><span>Game Access</span></h4>
                <p>Upon order completion, you will receive the game along with the user ID and password of the original Steam account. Each ID and password can only be used by one user. If we detect multiple users using the same credentials, your account may be banned for a certain period.</p>

                <h4 class="uk-heading-line"><span>Contacting Admin</span></h4>
                <p>Users have the right to contact the admin for any issues or problems. We offer multiple options for contacting the admin, including:</p>
                <ul class="uk-list uk-list-bullet">
                    <li>By email: <a href="mailto:admin@admin.com">admin@admin.com</a></li>
                    <li>By using our <a href="contact.php">contact form</a></li>
                    <li>By using our <a href="chat.php">Chat with Admin feature</a></li>
                </ul>
                <footer class=" uk-section-small uk-text-center">
                    <div class="uk-container">
                        <p>&copy; 2024-25 steam-games.in All rights reserved.</p>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
</body>

</html>