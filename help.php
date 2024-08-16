<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Help - Steam Games";
    $meta_desc = "Find answers to your questions about using Steam-Games.in. Get help with purchasing, payment IDs, and more to enhance your experience.";
    $meta_keywords = "help, Steam Games help, purchase help, payment ID help, customer support, FAQ, support center";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "";
    ?>
    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_desc ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $domain . "help.php" ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_desc ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $domain . "help.php" ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_desc ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $domain . "help.php" ?>">

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
    <div class="uk-container">
        <h3 class="uk-text-lead">Your Questions Answered</h3>
        <p>Welcome to the steam-games.in Help Page. Here you will find answers to common questions and detailed information on how to make the most of our services.</p>
        <p>Our mission is to provide you with the best gaming experience by offering a wide selection of games at the highest possible discounts.</p>

        <h4 class="uk-heading-line"><span>Contact Us</span></h4>
        <p>If you need further assistance, you can reach us through the following methods:</p>
        <ul class="uk-list uk-list-bullet">
            <li>Use our <a href="contact.php">Contact Form</a></li>
            <li>Chat with us using our <a href="chat.php">Chat with Admin Feature</a></li>
            <li>Email us at: <a href="mailto:admin@admin.com">admin@admin.com</a></li>
        </ul>

        <h4 class="uk-heading-line"><span>Frequently Asked Questions</span></h4>
        <ul uk-accordion>
            <li>
                <a class="uk-accordion-title" href="#">How can I purchase a game?</a>
                <div class="uk-accordion-content">
                    <p>To purchase a game, follow these steps:</p>
                    <ol class="uk-list uk-list-decimal">
                        <li>Browse our wide selection of games and select the one you want to purchase.</li>
                        <li>Add the game to your cart.</li>
                        <li>Proceed to checkout and complete the payment using our secure payment gateway.</li>
                        <li>Once the payment is confirmed, you will receive a download link for the game.</li>
                    </ol>
                    <p>Our platform ensures a seamless and secure transaction process, providing you with instant access to your purchased games.</p>
                </div>
            </li>
            <li>
                <a class="uk-accordion-title" href="#">How do I find my transaction ID?</a>
                <div class="uk-accordion-content">
                    <p>After completing a purchase, you will receive a confirmation email containing your transaction ID. You can also find the transaction ID in your account under the "Order History" section.</p>
                    <p>If you have any issues finding your transaction ID, please contact our support team via the "Chat with Admin" feature or email us at support@steam-games.in.</p>
                </div>
            </li>
            <li>
                <a class="uk-accordion-title" href="#">Need more help?</a>
                <div class="uk-accordion-content">
                    <p>If you need additional assistance, please don't hesitate to reach out to us. You can use our <a href="contact.php">Contact Form</a> or chat with our admin for real-time support.</p>
                </div>
            </li>
            <li>
                <a class="uk-accordion-title" href="#">Do you offer refunds?</a>
                <div class="uk-accordion-content">
                    <p>Yes, we offer refunds under specific conditions. Please review our <a href="refund-policy.php">Refund Policy</a> for detailed information on eligibility and the refund process.</p>
                </div>
            </li>
            <li>
                <a class="uk-accordion-title" href="#">How do I stay updated with new offers?</a>
                <div class="uk-accordion-content">
                    <p>To stay informed about our latest offers and discounts, subscribe to our newsletter or follow us on social media. We regularly update our community with the newest deals and promotions.</p>
                </div>
            </li>
            <li>
                <a class="uk-accordion-title" href="#">Can I suggest new games to be added?</a>
                <div class="uk-accordion-content">
                    <p>Absolutely! We welcome suggestions from our community. Please contact us through our support channels to suggest new games you would like to see on our platform.</p>
                </div>
            </li>
        </ul>

        <h4 class="uk-heading-line"><span>How to Purchase</span></h4>
        <iframe src="https://scribehow.com/shared/How_to_Purchase_Game_in_steam-gamesin__6hCGUq2KTwSl8qELpDhuiA" width="100%" height="640" allowfullscreen frameborder="0"></iframe>

        <footer class="uk-section-small uk-text-center">
            <div class="uk-container">
                <p>&copy; 2024-25 steam-games.in. All rights reserved.</p>
            </div>
        </footer>
    </div>
</main>

        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
</body>

</html>