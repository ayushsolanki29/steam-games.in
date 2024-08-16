<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Market - Most Selling Games Collection | Steam Games";
    $meta_desc = "Discover the most popular and best-selling Steam games on Steam-Games.in. Explore our top-selling game collection and find your next favorite game at unbeatable prices.";
    $meta_keywords = "most selling games, best-selling Steam games, popular games, top games, Steam game collection, game deals, Steam game discounts, popular game sales";
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
    <link rel="canonical" href="<?= $domain . "market.php" ?>">

    <?php include 'php/pages/header.php' ?>
</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>

            <main class="page-main">

                <div class="uk-width-auto">
                    <div class="widjet --filters">
                        <div class="widjet__head">
                            <h3 class="uk-text-lead">Most Selling Games</h3>
                        </div>

                    </div>
                    <div class="widjet --market">
                        <?php
                        $query = "
                          SELECT 
                              SUBSTRING_INDEX(SUBSTRING_INDEX(products, ',', numbers.n), ',', -1) AS product_id,
                              COUNT(*) AS product_count
                          FROM 
                              transition
                              INNER JOIN (
                                  SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
                                  UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
                              ) numbers ON CHAR_LENGTH(products) - CHAR_LENGTH(REPLACE(products, ',', '')) >= numbers.n - 1
                          GROUP BY 
                              product_id
                          ORDER BY 
                              product_count DESC
                          LIMIT 10"; // Limiting to top 10 trending products for example
                        $result = mysqli_query($con, $query);

                        $trending_product_ids = [];
                        if (mysqli_num_rows($result) > 0) {


                            while ($row = mysqli_fetch_assoc($result)) {
                                $trending_product_ids[] = $row['product_id'];
                            }
                            $product_details = fetchTrendingProducts($con, $trending_product_ids);

                        ?>


                            <?php foreach ($product_details as $product) :
                                $category_id = $product['category_id'];
                                $discounted_price =  $product['price'] * (1 - $product['discount'] / 100);
                                $fetch_cat1 = mysqli_query($con, "SELECT `category_name` FROM `category` WHERE `id` = '$category_id' LIMIT 1");
                                $category_data1 = mysqli_fetch_assoc($fetch_cat1);
                            ?>
                                <div class="widjet__body">
                                    <div class="widjet-game">
                                        <div class="widjet-game__media">
                                            <a href="game_profile.php?p=<?= $product['id'] ?>"><img src="assets/img/products/<?= htmlspecialchars($product['thumbnail']) ?>" alt="image"></a>
                                        </div>
                                        <div class="widjet-game__info">
                                            <div class="widjet-game__title"><a href="game_profile.php?p=<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></a></div>
                                            <div class="widjet-game__game-name"><?= htmlspecialchars($category_data1['category_name']) ?></div>
                                            <div class="widjet-game__starting">Starting at: <b>₹<?= htmlspecialchars($discounted_price) ?></b><span data-uk-icon="arrow-up"></span></div>
                                            <div class="widjet-game__quantity"><b><?= htmlspecialchars($product['discount']) ?>% </b> &nbsp; off</div>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach;
                        } else {
                            echo "we are working on it take maybe 2 days";
                        } ?>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
</body>

</html>