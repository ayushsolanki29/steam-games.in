<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Refund Policy - Steam Games";
    $meta_desc = "Read the refund policy for purchases made on Steam-Games.in. Understand the conditions and process for requesting a refund for your Steam game purchases.";
    $meta_keywords = "refund policy, Steam Games refund, game refund, refund process, purchase refund, Steam game return policy, Steam game refund conditions";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
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
    <link rel="canonical" href="<?= $domain . "refund.php" ?>">

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
                <h3 class="uk-text-lead">Refund Policy</h3>

                <h4 class="uk-heading-line"><span>Refund Eligibility</span></h4>
                <p>Users can request a refund for their game purchase within 2 to 3 days of the transaction. After this period, refunds will no longer be available.</p>

                <h4 class="uk-heading-line"><span>Refund Process</span></h4>
                <p>To initiate a refund, users must contact the admin via the <a href="contact.php">contact form</a> and select the refund option. Please provide your transaction ID (txt_id) and any other details requested by the admin. Once your details are verified, your refund will be processed within 2 to 3 days.</p>
                <p>If the transaction fails, our banking partner Razorpay will process the refund within 3 to 4 business days.</p>

                <h4 class="uk-heading-line"><span>Game Access After Refund</span></h4>
                <p>Once your refund is processed, access to the game will be revoked, and you will no longer be able to play the game using the provided credentials.</p>
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