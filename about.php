<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "About Us - Steam Games";
    $meta_desc = "Learn about Steam-Games.in - your go-to platform for buying Steam games at discounted prices. Discover our mission, vision, and commitment to providing top-notch customer service.";
    $meta_keywords = "about us, Steam Games, company information, mission statement, vision, customer service";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "about.php"; // URL of the about page
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
            <main class="page-main ">
                <div class="uk-container">
                    <h3 class="uk-text-lead">About Us</h3>
                    <p>Welcome to steam-games.in, your ultimate destination for highly discounted games available on Steam. Launched in August 2024, we are committed to providing the best gaming experience at the cheapest prices.</p>
                    <p>Our platform is designed for gamers who want to enjoy a wide selection of games without breaking the bank. We strive to offer the highest possible discounts on all games available on Steam.</p>

                    <h4 class="uk-heading-line"><span>Our Mission</span></h4>
                    <p>Our mission is to offer the best gaming experience possible by providing a vast selection of games at competitive prices. We aim to make gaming accessible to everyone by offering the highest discounts available.</p>

                    <h4 class="uk-heading-line"><span>Our Team</span></h4>
                    <ul class="uk-list uk-list-bullet">
                        <li>Abishek - Owner </li>
                        <li>Akshaya - CEO </li>
                        <li>Balaganesh - Editorial Head</li>
                    </ul>

                    <h4 class="uk-heading-line"><span>Frequently Asked Questions</span></h4>
                    <ul uk-accordion>
                        <li>
                            <a class="uk-accordion-title" href="#">What services do you offer?</a>
                            <div class="uk-accordion-content">
                                <p>We offer a wide variety of games for purchase and download, as well as a community platform for gamers to interact and share their gaming experiences.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">How can I contact support?</a>
                            <div class="uk-accordion-content">
                                <p>You can contact our support team via the "Chat with Admin" feature on our website or by emailing support@steam-games.in.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">Do you offer refunds?</a>
                            <div class="uk-accordion-content">
                                <p>Yes, we offer refunds under certain conditions. Please refer to our refund policy for more details.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">How can I stay updated with new offers?</a>
                            <div class="uk-accordion-content">
                                <p>To stay updated with our latest offers and discounts, you can subscribe to our newsletter or follow us on our social media channels.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">Can I suggest new games to be added?</a>
                            <div class="uk-accordion-content">
                                <p>Yes, we welcome suggestions from our community. You can suggest new games by contacting our support team or through our community platform.</p>
                            </div>
                        </li>
                    </ul>

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