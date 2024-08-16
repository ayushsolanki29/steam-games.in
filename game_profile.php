<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();
if (!isset($_GET['p'])) {
    header("location:index.php");
    exit;
}

if (!isset($_COOKIE['product_views'])) {
    $productViews = [];
} else {
    $productViews = json_decode($_COOKIE['product_views'], true);
}
$pid = $_GET['p'];
if (!in_array($pid, $productViews)) {
    // Make sure to include database connection before this
    $sql = "UPDATE `products` SET `views` = `views` + 1 WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->close();

    $productViews[] = $pid;
    setcookie('product_views', json_encode($productViews), time() + (1 * 24 * 60 * 60));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'php/pages/header.php';

    if (isset($_GET['p']) && $_GET['p']) {
        $pid = $_GET['p'];


        $product_query = mysqli_query($con, "SELECT `id`, `title`, `price`, `discount`, `description`, `thumbnail`, `category_id`, `date`,`keywords` FROM `products` WHERE `id`='$pid'");
        if (mysqli_num_rows($product_query) > 0) {
            while ($product_data = mysqli_fetch_assoc($product_query)) {
                $cat_ids = $product_data['category_id'];

                $meta_title = $product_data['title'] . " - Steam Games";
                $meta_desc = "only ₹" . $product_data['price'] * (1 - $product_data['discount'] / 100) . " | " . $product_data['description'];
                $meta_keywords =  $product_data['keywords'];
                $meta_img = $domain . "assets/img/products/" . $product_data['thumbnail']; // Using the single image for all pages

            }
        }
    } ?>
    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_desc ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $domain . "game_profile.php?p=" . $pid ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_desc ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $domain . "game_profile.php?p=" . $pid ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_desc ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $domain . "game_profile.php?p=" . $pid ?>">

    <style>

    </style>

</head>

<body class="page-profile ">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php';
            if (isset($_GET['p']) && $_GET['p']) {
                $pid = $_GET['p'];


                $product_query = mysqli_query($con, "SELECT `id`, `title`, `price`, `discount`, `description`, `thumbnail`, `category_id`, `date`,`keywords` FROM `products` WHERE `id`='$pid'");
                if (mysqli_num_rows($product_query) > 0) {
                    while ($product_data = mysqli_fetch_assoc($product_query)) {
                        $cat_ids = $product_data['category_id'];
            ?>
                        <main class="page-main">
                            <ul class="uk-breadcrumb">
                                <li><a href="store.php"><span data-uk-icon="chevron-left"></span><span>Back to Store</span></a></li>
                                <li><span><?= $product_data['title'] ?></span></li>
                            </ul>
                            <h3 class="uk-text-lead"><?= $product_data['title'] ?></h3>
                            <div class="uk-grid uk-grid-small" data-uk-grid>
                                <div class="uk-width-2-3@s">
                                    <div class="gallery">

                                        <div class="js-trending gallery-big">
                                            <div class="swiper">
                                                <div class="swiper-wrapper">
                                                    <?php
                                                    $image_query = mysqli_query($con, "SELECT `image` FROM `multiple_images` WHERE `product_id`='$pid'");
                                                    if (mysqli_num_rows($image_query) > 0) {
                                                        while ($image_data = mysqli_fetch_assoc($image_query)) { ?>
                                                            <div class="swiper-slide" style="text-align: center;"><img style="max-width:100%;" src="assets/img/related_images/<?= $image_data['image'] ?>" alt="<?= $product_data['title'] ?>"></div>
                                                    <?php }
                                                    } else {
                                                        echo "no Image Available";
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

                                        <!-- <div class="js-gallery-small gallery-small">
                                            <div class="swiper">
                                                <div class="swiper-wrapper">
                                                    <?php
                                                    $image_query = mysqli_query($con, "SELECT `image` FROM `multiple_images` WHERE `product_id`='$pid'");
                                                    if (mysqli_num_rows($image_query) > 0) {
                                                        while ($image_data = mysqli_fetch_assoc($image_query)) { ?>
                                                            <div class="swiper-slide bottom" style="cursor: pointer;"><img style="object-fit: cover; float: left;width:  250px;  height: 100px;  filter: grayscale(50%);" src="assets/img/related_images/<?= $image_data['image'] ?>" alt="<?= $product_data['title'] ?>"></div>
                                                    <?php }
                                                    } else {
                                                        echo "no Image Available";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="swiper-pagination"></div>
                                        </div> -->
                                    </div>
                                    <br><br>
                                    <div class="uk-width">
                                        <div class="game-profile-card">
                                            <p><?= $product_data['description'] ?></p>
                                            <h2 class="uk-text-lead">How can I get this game?</h2>
                                            <p>Simply complete your purchase, and you will receive the <b> Steam ID and password via email and in your profile </b>section.</p>
                                        </div>

                                    </div>
                                </div>

                                <div class="uk-width-1-3@s">
                                    <div class="game-profile-card">
                                        <div class="game-profile-card__media"><img src="assets/img/products/<?= $product_data['thumbnail'] ?>" alt="<? $product_data['thumbnail'] ?>"></div>
                                        <div class="game-profile-card__intro"><span><? $product_data['thumbnail'] ?></span></div>
                                        <ul class="game-profile-card__list">
                                            <li>
                                                <div>Discount:</div>
                                                <div class="game-card__rating"><span><?= $product_data['discount'] . '%' ?></span><span class="rating-vote">Applied</span></div>
                                            </li>

                                            <li>
                                                <div>Game Software :</div>
                                                <div>Steam </div>
                                            </li>
                                            <li>
                                                <div>Platforms:</div>
                                                <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                                            </li>

                                        </ul>
                                        <?php

                                        $fetch_cat = mysqli_query($con, "SELECT `id`, `category_name` FROM `category` WHERE `id` IN ($cat_ids)");

                                        if ($fetch_cat) {
                                            echo '<ul class="game-profile-card__type">';
                                            while ($category_data = mysqli_fetch_assoc($fetch_cat)) {
                                        ?>
                                                <li style="border: 1px solid #eee;"><span><?= $category_data['category_name'] ?></span></li>
                                        <?php
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo "No categories found.";
                                        }
                                        ?>




                                    </div>
                                    <div class="game-profile-price">
                                        <div class="game-profile-price__value"> ₹ <?= $product_data['price'] * (1 - $product_data['discount'] / 100) ?> <sup style="font-size: 11px; color:red;"> <del>₹ <?= $product_data['price'] ?></del> </sup> </div>
                                        <button class="uk-button uk-button-danger uk-width-1-1" type="button" onclick="window.location.href ='php/configs/actions.php?loggeduser&addtocart&product_id=<?= $product_data['id'] ?>&token=INCODE&cb=cart.php'">
                                            <span class="ico_shopping-cart"></span>
                                            <span>Buy Now</span>
                                        </button>
                                        <button class="uk-button uk-button-primary uk-width-1-1" onclick="window.location.href ='php/configs/actions.php?loggeduser&addtocart&product_id=<?= $product_data['id'] ?>&token=INCODE&cb=game_profile.php?p=<?= $product_data['id'] ?>'" type="button">
                                            <span class="ico_add-square"></span><span>Add to Cart</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <section class="review-section" id="review_game">
                                <div class="uk-margin-large-top">
                                    <h3 class="uk-text-center uk-text-lead">Leave a Review</h3>
                                    <form class="uk-form-stacked" method="POST" action="php/configs/actions.php">
                                        <input type="hidden" name="product_id" value="<?= $pid ?>">
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="comment">Comment</label>
                                            <div class="uk-form-controls">
                                                <textarea class="uk-textarea" id="comment" name="comment" rows="5" required></textarea>
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <button class="uk-button uk-button-primary" type="submit" name="submit_review">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="uk-container uk-margin-large-top">
                                    <h2 class="uk-text-center uk-text-lead">Customer Reviews</h2>
                                    <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m" data-uk-grid>
                                        <?php
                                        // Fetch reviews and display them in a grid view.
                                        // Using prepared statements to prevent SQL injection

                                        // Prepare the SQL statement
                                        $stmt = $con->prepare("SELECT * FROM `reviews` WHERE `product_id` = ?");
                                        $stmt->bind_param("i", $pid); // Assuming $pid is an integer. Use "s" if it's a string.

                                        // Execute the statement
                                        $stmt->execute();

                                        // Get the result
                                        $reviews_query = $stmt->get_result();

                                        if ($reviews_query->num_rows > 0) {
                                            while ($review_data = $reviews_query->fetch_assoc()) {
                                        ?>
                                                <div class="uk-width-1-1">
                                                    <div class="uk-card uk-card-default uk-card-body ">
                                                        <div class="uk-flex uk-flex-middle uk-margin-bottom">
                                                            <div class="uk-margin-small-right">
                                                                <span class="fa fa-user-circle-o fa-lg"></span>
                                                            </div>
                                                            <div>
                                                                <div class="uk-text-bold"><?= htmlspecialchars($review_data['user_id'], ENT_QUOTES, 'UTF-8') ?></div>
                                                                <div class="uk-text-meta"><?= date('M d, Y', strtotime($review_data['date'])) ?></div>
                                                            </div>
                                                        </div>

                                                        <!-- Show as string -->
                                                        <!-- Secure output with htmlspecialchars to prevent XSS -->
                                                        <p><?= htmlspecialchars($review_data['comment'], ENT_QUOTES, 'UTF-8') ?></p>
                                                        <!-- Additional fields if needed, e.g., star rating -->
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo '<p>No reviews yet.</p>';
                                        }

                                        // Close the statement
                                        $stmt->close();
                                        ?>

                                    </div>

                                </div>
                            </section>
                        </main>
            <?php      }
                }
            }



            ?>


        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
    <script>
        $(document).ready(function() {
            function toggleClasses() {
                if (window.innerWidth <= 639) {
                    // For screens smaller than 640px
                    $('.js-gallery-big').removeClass('js-gallery-big').addClass('js-trending');
                } else {
                    // For screens larger than 639px
                    $('.js-trending').removeClass('js-trending').addClass('js-gallery-big');
                }
            }

            // Initial check
            toggleClasses();

            // Check on window resize
            $(window).resize(function() {
                toggleClasses();
            });
        });
    </script>
</body>

</html>