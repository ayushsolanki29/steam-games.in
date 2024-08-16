<?php
include "../php/configs/db.php";
session_start();

if (!isset($_SESSION['is_admin'])) {
    header("Location:login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard - steam-games.in</title>
  
    <?php include 'php/pages/head.php' ?>
</head>

<body id="page-top">

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div id="wrapper">
        <?php include 'php/pages/sidebar.php' ?>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <?php include 'php/pages/nav.php' ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

                    </div>
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Today)</div>
                                            <?php
                                            $today_date = date('Y-m-d');
                                            $query = "SELECT SUM(amount) AS today_total_amount FROM `transition` WHERE status = 'paid' AND DATE(date) = '$today_date'";
                                            $result = mysqli_fetch_assoc(mysqli_query($con, $query));
                                            $today_total_amount = $result['today_total_amount'] ?? 0;
                                            ?>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo $today_total_amount; ?></div>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Earnings (Monthly)</div>
                                                <?php
$today_date = date('Y-m-d');
$current_month = date('Y-m');

// Query to get the total amount for the current month
$query = "SELECT SUM(amount) AS monthly_total_amount 
          FROM `transition` 
          WHERE status = 'paid' AND DATE_FORMAT(date, '%Y-%m') = '$current_month'";

$result = mysqli_fetch_assoc(mysqli_query($con, $query));
$monthly_total_amount = $result['monthly_total_amount'] ?? 0;

?>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo $monthly_total_amount; ?></div>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (All Time)</div>
                                            <?php
                                            $query = "SELECT SUM(amount) AS total_amount FROM `transition` WHERE status = 'paid'";
                                            $result = mysqli_fetch_assoc(mysqli_query($con, $query));
                                            $total_amount = $result['total_amount'] ?? 0;
                                            ?>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?= $total_amount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Total Orders
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <?php $all_orders = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `all_orders` FROM `transition`")); ?>
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $all_orders['all_orders'] ?></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Orders</div>
                                            <?php $pending_orders = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `pending_orders` FROM `transition` WHERE `delivered`='no' AND `status`='paid'")); ?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pending_orders['pending_orders'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-cart-arrow-down fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">

                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Users</div>
                                            <?php $total_users = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `total_users` FROM `users`")); ?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_users['total_users'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Site visits</div>
                                            <?php $views = mysqli_fetch_assoc(mysqli_query($con,  "SELECT `data1` FROM `settings` WHERE `id` = '2'")); ?>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $views['data1'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="far fa-eye fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending carts</div>
                                            <?php $total_carts = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `total_carts` FROM `cart`")); ?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_carts['total_carts'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Track Media</h1>

                    </div>
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Games</div>
                                            <?php $all_products = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `all_products` FROM `products`")); ?>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $all_products['all_products'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-gamepad fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Categories</div>
                                            <?php $all_category = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `all_category` FROM `category`")); ?>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $all_category['all_category'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Games Images
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <?php $multiple_images = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `multiple_images` FROM `multiple_images`")); ?>
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $multiple_images['multiple_images'] ?></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-images fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Coupons</div>
                                            <?php $coupon_codes = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `coupon_codes` FROM `coupon_codes`")); ?>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $coupon_codes['coupon_codes'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Support</h1>
                    </div>
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Messages</div>
                                            <?php $messages = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `messages` FROM `messages`")); ?>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $messages['messages'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                contact submissions</div>
                                            <?php $contact = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `contact` FROM `contact`")); ?>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $contact['contact'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-headset fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Games Reviews
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <?php $reviews = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `reviews` FROM `reviews`")); ?>
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $reviews['reviews'] ?></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comment-dots fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>