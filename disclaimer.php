<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Disclaimer - Steam Games";
    $meta_desc = "Read the disclaimer for using Steam-Games.in. Understand the terms and limitations of liability when using our website.";
    $meta_keywords = "disclaimer, Steam Games disclaimer, liability disclaimer, terms of use, legal disclaimer";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "disclaimer.php"; // URL of the disclaimer page
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
                <h3 class="uk-text-lead">Disclaimer</h3>

                <p>This disclaimer governs your use of steam-games.in. By using this website, you accept this disclaimer in full. If you disagree with any part of this disclaimer, you must not use our website.</p>

                <h4 class="uk-heading-line"><span>No Warranties</span></h4>
                <p>This website is provided "as is" without any representations or warranties, express or implied. Steam Games makes no representations or warranties in relation to this website or the information and materials provided on this website.</p>

                <h4 class="uk-heading-line"><span>Limitation of Liability</span></h4>
                <p>Steam Games will not be liable to you (whether under the law of contact, the law of torts, or otherwise) in relation to the contents of, or use of, or otherwise in connection with, this website:
                <ul class="uk-list uk-list-bullet">
                    <li>for any indirect, special, or consequential loss; or</li>
                    <li>for any business losses, loss of revenue, income, profits, or anticipated savings, loss of contracts or business relationships, loss of reputation or goodwill, or loss or corruption of information or data.</li>
                </ul>
                </p>

                <h4 class="uk-heading-line"><span>Exceptions</span></h4>
                <p>Nothing in this disclaimer will exclude or limit any warranty implied by law that it would be unlawful to exclude or limit, and nothing in this disclaimer will exclude or limit Steam Games' liability in respect of any:</p>
                <ul class="uk-list uk-list-bullet">
                    <li>death or personal injury caused by Steam Games' negligence;</li>
                    <li>fraud or fraudulent misrepresentation on the part of Steam Games; or</li>
                    <li>matter which it would be illegal or unlawful for Steam Games to exclude or limit, or to attempt or purport to exclude or limit, its liability.</li>
                </ul>

                <h4 class="uk-heading-line"><span>Reasonableness</span></h4>
                <p>By using this website, you agree that the exclusions and limitations of liability set out in this disclaimer are reasonable. If you do not think they are reasonable, you must not use this website.</p>

                <h4 class="uk-heading-line"><span>Other Parties</span></h4>
                <p>You accept that, as a limited liability entity, Steam Games has an interest in limiting the personal liability of its officers and employees. You agree that you will not bring any claim personally against Steam Games' officers or employees in respect of any losses you suffer in connection with the website.</p>

                <h4 class="uk-heading-line"><span>Unenforceable Provisions</span></h4>
                <p>If any provision of this disclaimer is, or is found to be, unenforceable under applicable law, that will not affect the enforceability of the other provisions of this disclaimer.</p>
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