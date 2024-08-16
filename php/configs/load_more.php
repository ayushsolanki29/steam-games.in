<?php
include 'db.php';

// Initialize variables
$start = isset($_POST['starting']) ? (int)$_POST['starting'] : 0; // Starting point for fetching products
$perpage = 10; // Number of products to fetch per request
$output = "";
$category = $_GET['c']; // Category name (passed via URL)
$cid = $_GET['cid']; // Category ID (passed via URL)

// Query to fetch products based on category ID, sorted by price, with pagination
$query = "SELECT `id`, `title`, `price`, `discount`, `thumbnail`
              FROM `products`
              WHERE `category_id`= $cid
              ORDER BY `title`
          LIMIT $start, $perpage";

$result = mysqli_query($con, $query);

// Check if there are products fetched
if (mysqli_num_rows($result) > 0) {
    while ($product = mysqli_fetch_assoc($result)) {
        $discounted_price = $product['price'] * (1 - $product['discount'] / 100);

        // Build HTML output for each product
        $output .= "<div>
                        <div class='game-card'>
                            <div class='game-card__box'>
                                <div class='game-card__media'>
                                    <a href='game_profile.php?s=onsale&p={$product['id']}'><img src='assets/img/products/{$product['thumbnail']}' alt='{$product['title']}' /></a>
                                </div>
                                <div class='game-card__info'>
                                    <a class='game-card__title' href='game_profile.php?s=onsale&p={$product['id']}'>{$product['title']}</a>
                                    <div class='game-card__genre'>{$category}</div>
                                    <div class='game-card__rating-and-price'>
                                        <div class='game-card__rating'><span>{$product['discount']}</span>% off</div>
                                        <div class='game-card__price'>
                                            <span class='dic_price'>₹ $discounted_price</span>
                                            <span><del>₹ {$product['price']}</del></span>
                                        </div>
                                    </div>
                                    <div class='game-card__bottom'>
                                        <button class='uk-button uk-button-danger uk-width-1-1' type='button' onclick=\"window.location.href='php/configs/actions.php?loggeduser&addtocart&product_id={$product['id']}&token=INCODE&cb=cart.php'\">
                                            <span class='ico_shopping-cart'></span><span>Add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
    }
}

// Output the HTML for products
echo $output;
