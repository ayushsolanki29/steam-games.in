<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Developer - Steam Games";
    $meta_desc = "Meet the developer - your go-to platform for buying Steam games at discounted prices. Discover our mission, vision, and commitment to providing top-notch customer service.";
    $meta_keywords = "Developer , Steam Games, company information, mission statement, vision, customer service";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "developer.php"; // URL of the about page
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
        <h3 class="uk-text-lead">Meet the Developer</h3>
        <p>Ayush Solanki is the talented developer behind steam-games.in. With a strong background in web development, Ayush uses PHP as the main backend language, ensuring a robust and efficient platform for our users.</p>

        <h4 class="uk-heading-line"><span>Skills and Technologies</span></h4>
        <ul class="uk-list uk-list-bullet">
            <li><i class="fas fa-code"></i> <strong>Backend Language:</strong> PHP</li>
            <li><i class="fas fa-html5"></i> <strong>Frontend:</strong> HTML, CSS</li>
            <li><i class="fas fa-cogs"></i> <strong>Libraries:</strong> jQuery, UI Kit</li>
            <li><i class="fas fa-database"></i> <strong>Database:</strong> Apache, SQL</li>
            <li><i class="fas fa-tools"></i> <strong>Other Technologies:</strong> Various other tools and libraries</li>
        </ul>

        <h4 class="uk-heading-line"><span>Contact Information</span></h4>
        <p>If you would like to get in touch with Ayush for any development inquiries or collaboration opportunities, feel free to contact him via the following methods:</p>
        <ul class="uk-list uk-list-bullet">
            <li><i class="fas fa-envelope"></i> <strong>Email:</strong> <a href="mailto:ayushsolanki2901@gmail.com">ayushsolanki2901@gmail.com</a></li>
            <li><i class="fab fa-instagram"></i> <strong>Instagram:</strong> <a href="https://instagram.com/ayushsolanki.exe">@ayushsolanki.exe</a></li>
            <li><i class="fab fa-github"></i> <strong>GitHub:</strong> <a href="https://github.com/ayushsolanki29">ayushsolanki29</a></li>
        </ul>

        <h4 class="uk-heading-line"><span>Projects and Contributions</span></h4>
        <p>Ayush has been instrumental in developing and maintaining steam-games.in, a platform offering highly discounted Steam games. His expertise in PHP, coupled with his proficiency in frontend technologies like HTML, CSS, and jQuery, ensures a seamless user experience. Ayush is also experienced in using UI Kit for styling and layout, and manages the database using Apache and SQL.</p>

        <footer class="uk-section-small uk-text-center">
            <div class="uk-container">
                <p>&copy; 2024-25 steam-games.in. All rights reserved.</p>
            </div>
        </footer>
    </div>
</main>

<!-- Add FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
</body>

</html>