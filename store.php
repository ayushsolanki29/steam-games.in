<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

function fetch_categories($con)
{
    return mysqli_query($con, "SELECT * FROM `category` ORDER BY RAND()");
}

function fetch_products($con, $cat_ids, $order = 'ASC')
{
    // Prepare the category IDs string
    $cat_ids = implode(",", array_map('intval', explode(",", $cat_ids)));

    // Query to fetch products from all specified categories
    $query = "SELECT `id`, `title`, `price`, `discount`, `thumbnail`, `category_id` FROM `products` WHERE `category_id` IN ($cat_ids) ORDER BY price $order LIMIT 12";
    return mysqli_query($con, $query);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Store - All Games & Categories | Steam Games";
    $meta_desc = "Explore the vast collection of Steam games available at Steam-Games.in. Browse through various categories and find your favorite games at unbeatable prices.";
    $meta_keywords = "Steam games, game store, game categories, buy Steam games, cheap Steam games, game collection, discounted games, Steam game deals";
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
    <link rel="canonical" href="<?= $domain . "store.php" ?>">

    <?php include 'php/pages/header.php' ?>
</head>

<body class="page-profile">
    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>
        <div class="page-content">
            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <div class="widjet --filters">

                    <div class="widjet__body">
                        <div class="widjet__head" style="display: flex; justify-content:center;">
                            <h3 class="uk-text-lead">Game Store</h3>
                        </div>
                        <?php
                        $totalProducts = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `totalProducts` FROM `products`"));
                        ?>
                        <div class="uk-text-right"><a href="#!">Total: <?= $totalProducts['totalProducts'] ?> Games</a></div>
                    </div>
                </div>
                <div id="products-container">
                    <?php
                    $categories = fetch_categories($con);
                    while ($category = mysqli_fetch_assoc($categories)) {
                        $cat_view_btn = "flex";
                        $products = fetch_products($con, $category['id']);
                        if (mysqli_num_rows($products) > 0) {
                            echo "<div>
                        <div class='widjet__head'>
                            <h3 class='uk-text-lead'>{$category['category_name']}</h3>
                        </div>
                        <div class='uk-grid uk-child-width-1-4@l uk-child-width-1-3@s uk-grid-small' data-uk-grid>";
                            if (mysqli_num_rows($products) > 0) {
                                while ($product = mysqli_fetch_assoc($products)) {
                                    $discounted_price =  $product['price'] * (1 - $product['discount'] / 100);
                                    echo "<div>
                                <div class='game-card'>
                                    <div class='game-card__box'>
                                        <div class='game-card__media'>
                                            <a href='game_profile.php?s=onsale&p={$product['id']}'><img src='assets/img/products/{$product['thumbnail']}' alt='{$product['title']}' /></a>
                                        </div>
                                        <div class='game-card__info'>
                                            <a class='game-card__title' href='game_profile.php?s=onsale&p={$product['id']}'>{$product['title']}</a>
                                            <div class='game-card__genre'>{$category['category_name']}</div>
                                            <div class='game-card__rating-and-price'>
                                                <div class='game-card__rating'><span>{$product['discount']}</span>% off</div>
                                                <div class='game-card__price'>
                                                    <span class='dic_price'>₹ " . number_format($discounted_price, 0) . "</span>
                                                    <span><del>₹ {$product['price']}</del></span>
                                                </div>
                                            </div>
                                            <div class='game-card__bottom'>
                                                <button class='uk-button uk-button-danger uk-width-1-1' type='button' onclick=\"window.location.href='php/configs/actions.php?loggeduser&addtocart&product_id={$product['id']}&token=INCODE&cb=cart.php'\">
                                                    <span class='ico_shopping-cart'></span><span> Add  </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                                }
                            } else {
                                $cat_view_btn = "none";
                                echo "<div style='display: flex; justify-content:center;'>No Games Available</div>";
                            }
                            echo "</div>
                    <br>
                    <div style='display: $cat_view_btn; justify-content:center;'>
                        <button class='uk-button uk-button-danger' type='button' onClick=\"window.location.href='category.php?c={$category['id']}'\">
                            <span>View All</span>
                        </button>
                    </div>
                </div>";
                        }
                    }
                    ?>
                </div>
            </main>

        </div>
    </div>
    <?php include 'php/pages/footer.php' ?>

</body>

</html>