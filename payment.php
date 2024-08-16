<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();

if (isset($_GET['txid'])) {
    $txt_id = $_GET['txid'];
    $empty_cart = mysqli_query($con, "DELETE FROM `cart` WHERE `txt_id` = '$txt_id'");
}
if (isset($_GET['check'])) {
    $txt_id = $_GET['check'];
    $query_check = mysqli_query($con, "SELECT * FROM `transition` WHERE `txt_id` = '$txt_id'");
    if (mysqli_num_rows($query_check) > 0) {
        $row = mysqli_fetch_assoc($query_check);
        if ($row['status'] == 'processing') {
            $query_update = mysqli_query($con, "UPDATE `transition` SET `status`='paid' WHERE `txt_id` = '$txt_id'");
            if ($query_update) {
                $response = "Payment successful";
                header("location: payment.php?py=$response&txid=$txt_id");
                exit;
            } else {
                $response = "Failed to update payment status";
                header("location: payment.php?err=$response&txid=$txt_id");
                exit;
            }
        }
    }
}
if (isset($_POST['upload_file'])) {
    $txt_id = $_POST['txt_id'];
    if (isset($_FILES['screenshot_pay']) && $_FILES['screenshot_pay']['error'] === 0) {
        $filename = $_FILES['screenshot_pay']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid_extensions = ['png', 'jpg', 'jpeg'];
        $name = $txt_id;
        if (in_array(strtolower($file_ext), $valid_extensions)) {
            $destfile = 'assets/img/payment_proof/' . $name . '.' . $file_ext;
            $new_file = $name . '.' . $file_ext;

            move_uploaded_file($_FILES['screenshot_pay']['tmp_name'], $destfile);

            $query = mysqli_query(
                $con,
                "UPDATE `transition` SET `file`='$new_file' WHERE `txt_id` = '$txt_id'"
            );
            if ($query) {
                $message = "Uploaded successfully";
                header("location:payment.php?uploaded=$message&txid=$txt_id");
                exit;
            }
        } else {
            $response = "Only supported extensions are JPG, PNG, JPEG.";
            header("location:payment.php?err=$response&txid=$txt_id");
            exit;
        }
    } else {
        $response = "Something went wrong while uploading image";
        header("location:payment.php?err=$response&txid=$txt_id");
        exit;
    }
}
//  else {
//     header("location:profile.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Payment Review - Steam Games";
    $meta_desc = "Complete your payment securely on Steam-Games.in. Enjoy your new game purchase with our fast and reliable payment process.";
    $meta_keywords = "payment, Steam Games payment, secure payment, game purchase, transaction";
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
    <link rel="canonical" href="<?= $domain . "payment.php" ?>">

    <?php include 'php/pages/header.php' ?>
    <style>
        .page-main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .page-main img {
            max-width: 100%;
            height: 50%;
            object-fit: cover;
            border-radius: 20px;
        }

        .countdown {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        body.dark-theme {
            background-color: #1a1a1a;
            color: #fff;
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
            text-align: start;
        }

        .order-summary div {
            margin-bottom: 10px;
        }

        @media only screen and (max-width: 767px) {
            .uk-width-1-3 {
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>

            <main class="page-main">
                <?php
                if (isset($_GET['err'])) {
                    echo '<div class="uk-margin"><p class="error">' . $_GET['err'] . '</p></div>';
                }
                if (isset($_GET['uploaded'])) {
                    echo '<div class="uk-margin"><p class="success">' . $_GET['uploaded'] . '</p></div>';
                }
                if (isset($_GET['py'])) {
                    echo '<div class="uk-margin"><p class="success">' . $_GET['py'] . '</p></div>';
                }
                ?>
                <h3 class="uk-text-lead">Payment Proccess is Done</h3>

                <p></p>

                <img src="assets/img/ORDER.gif" alt="Payment Success Image">
                <br> <br>
                <?php
                $query = mysqli_query($con, "SELECT * FROM `transition` WHERE `txt_id` ='$txt_id' LIMIT 1");
                if (mysqli_num_rows($query) > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                        $pay_status =  $data['status'];
                        $file =  $data['file'];
                ?>
                        <section class="payment_data">
                            <h3 class="uk-text-lead">Please Take a screenshot or copy txt id</h3>
                            <table class="order-details-table uk-table uk-table-divider">
                                <thead>
                                    <tr>
                                        <th>TXT ID</th>
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
                                <div><strong>UPI Id: </strong> <?= $data['upi'] ?></div>
                                <div><strong>Games Delivered ?: </strong> <?= $data['delivered'] ?></div>
                                <div><strong>Used Coupon Code: </strong> <?= empty($data['coupon_code']) ? "not used" : $data['coupon_code'] ?></div>
                                <div><strong>Amount : </strong> ₹<?= $data['amount'] ?></div>
                                <div><strong>Status: </strong> <?= $data['status'] ?></div>
                                <div><strong>Payment Method : </strong> <?= $data['method'] ?></div>
                                <?php
                                $product_ids = fetchProductIDs($con, $data['user_id'], $data['status'], $data['delivered']);
                                $product_details = fetchProductDetails($con, $product_ids);
                                ?>

                                <div><strong>Games : </strong> <?php foreach ($product_details as $product) : ?> <?= $product['title'] . " | ";  ?> <?php endforeach; ?></div>

                            </div>
                        </section>

                        <div class="uk-margin">
                            <br>
                            <button class="uk-button uk-button-danger uk-width-1-3" id="confirmation_btn" type="button">
                                Continue to Profile
                            </button>
                        </div>

            </main>

        <?php }

                    if (empty($file) && $pay_status == "processing") { ?>
            <div class="screensshot_upload">
                <form class="form" enctype="multipart/form-data" method="post">
                    <span class="form-title">Upload Payment Proof</span>
                    <input type="hidden" name="txt_id" value="<?= $txt_id ?>">
                    <p class="form-paragraph">
                        We need to verify your payment
                    </p>
                    <label for="file-input" class="drop-container">
                        <span class="drop-title">Upload Screenshot of payment</span>
                        <input type="file" accept="image/*" required name="screenshot_pay" class="file-input">
                    </label>
                    <br>
                    <button class="uk-button uk-button-danger uk-width-1-1" type="submit" name="upload_file">save</button>
                    <br><br>
                    <a href="#" id="later_upload" style="color: #000;">I will upload Later</a>
                </form>
            </div>
    <?php }
                } ?>
        </div>

    </div>

    <?php include 'php/pages/footer.php' ?>
    <script>
        $(document).ready(function() {
            // when clicking later_upload button hide div
            $("#later_upload").click(function() {
                $(".screensshot_upload").hide();
            });


            $("#confirmation_btn").click(function() {
                window.location.href = "profile.php";
            });
        });
    </script>

</body>

</html>
<?php unset($_SESSION['txt_id']) ?>