<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Privacy Policy - Steam Games";
    $meta_desc = "Read the privacy policy of Steam-Games.in to understand how we collect, use, and protect your personal information. Your privacy is important to us.";
    $meta_keywords = "privacy policy, Steam Games privacy, data protection, personal information, data collection, user privacy, privacy practices";
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
    <link rel="canonical" href="<?= $domain . "privacy_policy.php" ?>">

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
                <h3 class="uk-text-lead">Privacy Policy</h3>
                <p>Welcome to steam-games.in. We are committed to protecting your privacy and ensuring that your personal information is handled in a safe and responsible manner. This Privacy Policy outlines how we collect, use, and protect your data.</p>

                <h4 class="uk-heading-line"><span>Data Collection</span></h4>
                <p>We collect the following user data:</p>
                <ul class="uk-list uk-list-bullet">
                    <li><strong>Email Address</strong>: Collected for verification purposes.</li>
                    <li><strong>Phone Number</strong>: Collected for contact purposes regarding payment and delivery.</li>
                </ul>

                <h4 class="uk-heading-line"><span>Use of Data</span></h4>
                <p>Your data is used for the following purposes:</p>
                <ul class="uk-list uk-list-bullet">
                    <li><strong>Verification</strong>: Your email address is used to verify your identity and ensure account security.</li>
                    <li><strong>Contact</strong>: Your phone number is used to communicate with you regarding payment and delivery details.</li>
                </ul>

                <h4 class="uk-heading-line"><span>Data Protection</span></h4>
                <p>We implement a variety of security measures to maintain the safety of your personal information. Your data is stored securely and is never shared with any third-party services.</p>

                <h4 class="uk-heading-line"><span>Transaction Security</span></h4>
                <p>We take the security of your transactions very seriously. Every transaction is protected with advanced encryption technology to ensure your information is secure.</p>

                <h4 class="uk-heading-line"><span>Cookies</span></h4>
                <p>We use cookies to enhance your browsing experience. Cookies are small files that a site or its service provider transfers to your computer's hard drive through your web browser (if you allow) that enables the site's or service provider's systems to recognize your browser and capture and remember certain information. You can choose to disable cookies through your browser settings, but this may affect your ability to use some features of our site.</p>

                <h4 class="uk-heading-line"><span>Third-Party Links</span></h4>
                <p>Occasionally, at our discretion, we may include or offer third-party products or services on our website. These third-party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</p>

                <h4 class="uk-heading-line"><span>Changes to This Policy</span></h4>
                <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>

                <h4 class="uk-heading-line"><span>Contact Information</span></h4>
                <p>If you have any questions about this Privacy Policy, please contact us:</p>
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