<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Copyright - Steam Games";
    $meta_desc = "Learn about the copyright policies of Steam-Games.in. Understand how content on our website is protected and used.";
    $meta_keywords = "copyright, Steam Games copyright, content protection, intellectual property rights";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "copyright.php"; // URL of the copyright page
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
            <main class="page-main">
                <h3 class="uk-text-lead">Copyright Notice</h3>

                <p>All content, including images, designs, colors, and other assets on steam-games.in, are protected by copyright laws and are the property of Steam Games unless otherwise stated.</p>

                <h4 class="uk-heading-line"><span>Ownership</span></h4>
                <p>Steam Games retains all ownership rights to the intellectual property and content displayed on this website. You may not reproduce, distribute, or use any content from this website without prior written permission from Steam Games.</p>

                <h4 class="uk-heading-line"><span>Legal Actions</span></h4>
                <p>Unauthorized use of any content from this website may violate copyright, trademark, and other laws. Steam Games reserves the right to take legal action against any unauthorized use or reproduction of its content.</p>

                <h4 class="uk-heading-line"><span>Third-Party Content</span></h4>
                <p>Some content on this website may be sourced from third parties under license. Such content remains the property of its respective owners and is used with permission or under license.</p>

                <h4 class="uk-heading-line"><span>Contact Us</span></h4>
                <p>If you have any questions or requests regarding the use of content from this website, please contact us:</p>
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