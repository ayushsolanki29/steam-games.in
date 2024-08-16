<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

// Check if category IDs are provided
if (isset($_GET['c'])) {
    $category_ids = explode(',', $_GET['c']);
    $category_ids = array_map('intval', $category_ids); // Sanitize category IDs
    $get_cats = fetch_categories($con, $category_ids);

    // Redirect to store.php if no categories are found
    if (mysqli_num_rows($get_cats) == 0) {
        header("location: store.php");
        exit();
    }
} else {
    header("location: store.php");
    exit();
}

function fetch_categories($con, $category_ids)
{
    $category_ids = implode(",", $category_ids); // Convert array to comma-separated string
    return mysqli_query($con, "SELECT * FROM `category` WHERE `id` IN ($category_ids)");
}

function fetch_products($con, $category_ids, $order = 'ASC')
{
    $category_ids = implode(",", array_map('intval', $category_ids)); // Sanitize and format category IDs
    $query = "SELECT `id`, `title`, `price`, `discount`, `thumbnail`
              FROM `products`
              WHERE `category_id` IN ($category_ids)
              ORDER BY `title` $order
              LIMIT 0,10";
    return mysqli_query($con, $query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php

    $meta_title =  "Explore Categories - Steam Games";
    $meta_desc = "Explore various game categories on Steam-Games.in. Discover a wide range of games in different categories, updated regularly.";
    $meta_keywords = "game categories, Steam Games categories, explore categories, game genres, popular game categories";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "categories.php?c=" . $_GET['c']; // URL of the dedicated category page

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

    <?php include 'php/pages/header.php'; ?>
</head>

<body class="page-profile">
    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php'; ?>
        <div class="page-content">
            <?php include 'php/pages/navbar.php'; ?>
            <main class="page-main">
                <div class="widget --filters">
                    <div class="widget__head" style="display: flex; justify-content:center;">
                        <?php while ($cat_d = mysqli_fetch_assoc($get_cats)) { ?>
                            <h3 class="uk-text-lead"><?= $cat_d['category_name']; ?></h3>
                        <?php } ?>
                    </div>
                </div>
                <div id="products-container">
                    <?php
                    mysqli_data_seek($get_cats, 0); // Reset pointer to fetch categories again
                    while ($category = mysqli_fetch_assoc($get_cats)) {
                        $products = fetch_products($con, [$category['id']]);
                        echo "<div style='padding: 10px;'>
                                <div id='product_list' class='uk-grid uk-child-width-1-4@l uk-child-width-1-3@s uk-grid-small' data-uk-grid>";
                        if (mysqli_num_rows($products) > 0) {
                            while ($product = mysqli_fetch_assoc($products)) {
                                $discounted_price = $product['price'] * (1 - $product['discount'] / 100);
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
                                                        <div class='game-card__rating'><span>{$product['discount']}</span>%</div>
                                                        <div class='game-card__price'>
                                                            <span class='dic_price'>₹ $discounted_price</span>
                                                            <span><del>₹ {$product['price']}</del></span>
                                                        </div>
                                                    </div>
                                                    <div class='game-card__bottom'>
                                                        <button onclick=\"window.location.href='php/configs/actions.php?loggeduser&addtocart&product_id={$product['id']}&token=INCODE&cb=cart.php'\" class='uk-button uk-button-danger uk-width-1-1' type='button'><span class='ico_shopping-cart'></span><span>Add</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                            }
                        } else {
                            echo "<div style='display: flex; justify-content:center;'>No Games Available</div>";
                        }
                        echo "</div>
                              <br>
                              <div style='display: flex; justify-content:center;'>
                                  <button class='uk-button uk-button-danger' id='loadMore' type='button'><span id='load_text'>Load More</span></button>
                              </div>
                          </div>";
                    }
                    ?>
                </div>
                <input type="hidden" id="start" value="0">
            </main>
        </div>
    </div>
    <?php include 'php/pages/footer.php'; ?>
    <script>
        $(document).ready(function() {
            $("#loadMore").click(function() {
                $("#load_text").text("Loading...");
                let start = parseInt($("#start").val());
                const perpage = 10; // Number of products to load per click
                start += perpage;
                $("#start").val(start);

                // Get category ID from URL parameter
                let c_id = <?= json_encode($_GET['c']); ?>;

                $.ajax({
                    url: `php/configs/load_more.php?c=${c_id}&cid=${c_id}`,
                    method: "POST",
                    data: {
                        starting: start
                    },
                    success: function(response) {
                        if (response) {
                            $("#product_list").append(response);
                            $("#load_text").text("Load More");
                        } else {
                            $("#load_text").text("You've seen all");
                            $("#loadMore").attr('disabled', 'disabled');
                        }
                    },
                    error: function() {
                        alert('Error loading products. Please try again.');
                        $("#load_text").text("Load More");
                    }
                });
            });
        });
    </script>
</body>

</html>