<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Track Your Order - Steam Games";
    $meta_desc = "Easily track your Steam game orders on Steam-Games.in. Enter your transaction ID to get detailed information about your order status and history.";
    $meta_keywords = "track order, Steam game order tracking, transaction ID, order status, Steam Games, track Steam order, order details";
    $meta_img = $domain . "assets/img/og-img.png"; // Ensure this image exists
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
    <link rel="canonical" href="<?= $domain . "track_order.php"?>">

    <?php include 'php/pages/header.php' ?>
    <style>
        body.dark-mode .transaction-id {
            background: #1a2634;
        }

        .order-details-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            overflow: auto;
        }

        .order-details-table th,
        .order-details-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        body.dark-theme .order-details-table th {
            background-color: #1a2634;

        }

        .order-details-table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .order-summary {
            margin-top: 20px;
        }

        .order-summary div {
            margin-bottom: 10px;
        }

        body.dark-theme .uk-alert-success {
            color: #fff;
            background: green;
        }

        body.dark-theme .uk-alert-danger {
            color: #fff;
            background: #bb2124;
        }

        body.dark-theme .nice-select {
            color: #fff;

        }

        body.dark-theme .uk-input {
            background: #1a2634;
        }

        .uk-input {
            background: #fff;
        }

        body.dark-theme .uk-form-label {
            color: #fff;
        }

        .uk-alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .uk-alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .nice-select {
            width: 100%;
        }
    </style>

</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <h3 class="uk-text-lead">Order Details</h3>

                <!-- Form for transaction ID input -->
                <form class="uk-form-stacked">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="transaction-id">Transaction ID</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" required id="transaction-id" type="text" name="txt_id" placeholder="Enter your transaction ID">
                        </div>
                    </div>
                    <button class="uk-button uk-button-secondary" type="submit">Track Order</button>
                </form>
                <?php if (isset($_GET['txt_id'])) {
                    $txt_id = mysqli_real_escape_string($con, $_GET['txt_id']);
                    $query = mysqli_query($con, "SELECT * FROM `transition` WHERE `txt_id` ='$txt_id'");
                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {

                            if ($data['delivered'] == "yes") { ?>
                                <div class="uk-alert-success" uk-alert>
                                    <a class="uk-alert-close" href="contact.php" uk-close></a>
                                    <p>Your Order is Delivered</p>
                                </div>
                            <?php }
                            ?>


                            <table class="order-details-table uk-table uk-table-divider">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Delivered</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $txt_id ?></td>
                                        <td><?= $data['delivered'] ?></td>
                                        <td><?= $data['status'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="order-summary">
                                <div><strong>User : </strong> <?php $t_user['user'] = getUserById($data['user_id']);
                                                                echo $t_user['user']['username'] ?></div>
                                <div><strong>Payment Id: </strong> <?= $data['payment_id'] ?></div>
                                <div><strong>Games Delivered ?: </strong> <?= $data['delivered'] ?></div>
                                <div><strong>Used Coupon Code: </strong> <?= $data['coupon_code'] ?></div>
                                <div><strong>Amount : </strong> ₹<?= $data['amount'] ?></div>
                                <div><strong>Status: </strong> <?= $data['status'] ?></div>
                                <div><strong>Payment Method : </strong> <?= $data['method'] ?></div>
                                <?php
                                $product_ids = fetchProductIDs($con, $data['user_id'], $data['status'], $data['delivered']);
                                $product_details = fetchProductDetails($con, $product_ids);

                                ?>

                                <div><strong>Games : </strong> <?php foreach ($product_details as $product) : ?> <?= $product['title'] . " | ";  ?> <?php endforeach; ?></div>

                            </div>
                        <?php }
                    } else { ?>
                        <div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" href="contact.php" uk-close></a>
                            <p>Transaction id is invalid</p>
                        </div>
                <?php }
                } ?>
            </main>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
</body>

</html>