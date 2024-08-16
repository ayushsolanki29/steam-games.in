<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Steam Games - Best Deals & Discounts on All Steam Games";
    $meta_dec = "Get over 90% discount on all Steam games at Steam-Games.in. Enjoy amazing customer support and explore a wide range of new, old, and viral games. Shop now for unbeatable deals!";
    $meta_keywords = "steam-games.in, Steam games, discounted Steam games, buy Steam games, cheap Steam games, best Steam game deals, game discounts, Steam sale, Steam game offers";
    $meta_img =  $domain . "assets/img/og-img.png";
    ?>

    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_dec ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $domain ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_dec ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $domain ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_dec ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $domain ?>">

    <?php include 'php/pages/header.php' ?>

</head>

<body class="page-home">
    <?php
    $promo = getpromo();
    $copoun_data = getcoupon_code($promo['data2']);
    if ($promo['data1'] == "active") {
    ?>
        <div class="top_banner" style="font-size: small;">
            <p class="text">
                <?= $promo['data3'] ?> Special Use
                <a href="store.php" style="font-size: small !important;"> <?= strtoupper($copoun_data['name']) ?> </a> for <?= strtoupper($copoun_data['value']) ?> % disccount
            </p>
        </div>
    <?php
    }
    ?>



    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>
        <div class="page-content">
            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <div class="uk-grid" data-uk-grid>
                    <div class="uk-width-2-3@l uk-width-3-3@m uk-width-3-3@s">
                        <h3 class="uk-text-lead">Recommended & Featured</h3>
                        <div class="js-recommend">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <?php $banner_query = mysqli_query($con, "SELECT * FROM `banner`");
                                    if (mysqli_num_rows($banner_query) > 0) {
                                        while ($banners = mysqli_fetch_array($banner_query)) { ?>
                                            <div class="swiper-slide">
                                                <div class="recommend-slide">
                                                    <div class="tour-slide__box">
                                                        <a href="<?= $banners['url'] ?>"><img src="assets/img/banner/<?= $banners['image'] ?>" alt="<?= $banners['name'] ?>"></a>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php }
                                    }

                                    ?>



                                </div>
                                <div class="swipper-nav">
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-3@l uk-width-3-3@m uk-width-3-3@s">
                        <h3 class="uk-text-lead">Suggested For You</h3>
                        <div class="js-trending">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <?php
                                    $product_query1 = mysqli_query($con, "SELECT `id`, `title`, `price`, `discount`, `thumbnail`, `category_id` FROM `products` ORDER BY RAND() LIMIT 9");
                                    if (mysqli_num_rows($product_query1) > 0) {
                                        while ($product_data1 = mysqli_fetch_assoc($product_query1)) {
                                            $cat_ids1 = $product_data1['category_id'];
                                            $cat_ids1_array = explode(",", $cat_ids1);
                                    ?>
                                            <div class="swiper-slide">
                                                <div class="game-card --horizontal">
                                                    <div class="game-card__box">
                                                        <div class="game-card__media"><a href="game_profile.php?s=onsale&p=<?= $product_data1['id'] ?>"><img src="assets/img/products/<?= $product_data1['thumbnail'] ?>" alt="Alien Games" /></a></div>
                                                        <div class="game-card__info"><a class="game-card__title" href="game_profile.php?s=onsale&p=<?= $product_data1['id'] ?>"> <?= $product_data1['title'] ?></a>
                                                            <?php
                                                            $first_cat_id = reset($cat_ids1_array);
                                                            $fetch_cat1 = mysqli_query($con, "SELECT `category_name` FROM `category` WHERE `id` = $first_cat_id LIMIT 1");
                                                            if ($fetch_cat1 && mysqli_num_rows($fetch_cat1) > 0) {
                                                                $category_data1 = mysqli_fetch_assoc($fetch_cat1);
                                                                $category_name = $category_data1['category_name'];


                                                                echo '<div class="game-card__genre">' . htmlspecialchars($category_name) . '</div>';
                                                            }
                                                            ?>

                                                            <div class="game-card__rating-and-price">
                                                                <div class="game-card__rating"><?= $product_data1['discount'] ?> %</div>
                                                                <div class="game-card__price">₹ <?= $discounted_price = $product_data1['price'] - ($product_data1['price'] * $product_data1['discount'] / 100)?></div>
                                                            </div>

                                                            <div class="game-card__bottom">
                                                                <button class="uk-button uk-button-danger uk-width-1-1" onclick="window.location.href ='game_profile.php?s=onsale&p=<?= $product_data1['id'] ?>'" type="button">view</button>
                                                            </div>
                                                            <br><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="swipper-nav">
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="products-container">
                    <br>
                    <?php
                    echo "<div>
                <div class='widget__head'>
                    <h3 class='uk-text-lead'>Newly Added</h3>
                </div>
                <div class='uk-grid uk-child-width-1-4@l uk-child-width-1-3@s uk-grid-small' data-uk-grid>";

                    $product_query = mysqli_query($con, "SELECT `id`, `title`, `price`, `discount`, `thumbnail`, `category_id` FROM `products` ORDER BY `id` DESC LIMIT 10");
                    if (mysqli_num_rows($product_query) > 0) {
                        while ($product_data = mysqli_fetch_assoc($product_query)) {
                            $cat_ids = $product_data['category_id'];
                            $cat_ids_array = explode(",", $cat_ids);
                            $first_cat_id1 = reset($cat_ids_array);

                            // Fetch category name
                            $fetch_cat = mysqli_query($con, "SELECT `category_name` FROM `category` WHERE `id` = $first_cat_id1 LIMIT 1");
                            $category_name = '';
                            if ($fetch_cat && mysqli_num_rows($fetch_cat) > 0) {
                                $category_data = mysqli_fetch_assoc($fetch_cat);
                                $category_name = htmlspecialchars($category_data['category_name']);
                            }

                            // Calculate discounted price
                            $discounted_price = $product_data['price'] - ($product_data['price'] * $product_data['discount'] / 100);

                            echo "
                    <div class='game-card'>
                        <div class='game-card__box'>
                            <div class='game-card__media'>
                                <a href='game_profile.php?s=onsale&p={$product_data['id']}'><img src='assets/img/products/{$product_data['thumbnail']}' alt='{$product_data['title']}' /></a>
                            </div>
                            <div class='game-card__info'>
                                <a class='game-card__title' href='game_profile.php?s=onsale&p={$product_data['id']}'>{$product_data['title']}</a>
                                <div class='game-card__genre'>{$category_name}</div>
                                <div class='game-card__rating-and-price'>
                                    <div class='game-card__rating'><span>{$product_data['discount']}</span>% off</div>
                                    <div class='game-card__price'>
                                        <span class='dic_price'>₹ " . number_format($discounted_price, 0) . "</span>
                                        <span><del>₹ {$product_data['price']}</del></span>
                                    </div>
                                </div>
                                <div class='game-card__bottom'>
                                    <button class='uk-button uk-button-danger uk-width-1-1' type='button' onclick=\"window.location.href='php/configs/actions.php?loggeduser&addtocart&product_id={$product_data['id']}&token=INCODE&cb=cart.php'\">
                                        <span class='ico_shopping-cart'></span><span> Add </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            ";
                        }
                    } else {
                        echo "<div style='display: flex; justify-content:center;'>No Games Available</div>";
                    }

                    echo "</div>
          <br>
          <div style='display:flex; justify-content:center; '> <!-- Update as necessary -->
              <a class='uk-button uk-button-danger'  href='store.php'>
                  <span>View More</span>
              </a>
          </div>
        </div>";
                    ?>
                </div>

            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
</body>

</html>