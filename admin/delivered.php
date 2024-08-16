<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['markasread'])) {
    $_txt_id = $_POST['txt_id'];
    $update_ = mysqli_query($con, "UPDATE `transition` SET `delivered`='no' WHERE `txt_id`='$_txt_id'");
    if ($update_) {
        header("Location: pending_deliveries.php?success=Order Mark as Read successfully");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Delivered orders - steam-games.in</title>
    <?php include 'php/pages/head.php'; ?>
</head>

<body id="page-top">
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div id="wrapper">
        <?php include 'php/pages/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'php/pages/nav.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Delivered orders</h1>
                    <br>
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="s" placeholder="Search by username | email | txt_id | UPI_ID | coupon_code | phone " aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= isset($_GET['s']) ? $_GET['s'] : "" ?>">
                            <div class="input-group-append">
                                <div class="input-group-append">
                                    <?php if (isset($_GET['s'])) { ?>
                                        <button class="btn btn-outline-danger" onclick="window.location.href = 'delivered.php'" type="button">Reset</button>
                                    <?php } else { ?>
                                        <button class="btn btn-outline-primary" type="submit">search</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </form>

                    <br>
                    <?php if (isset($_GET['err'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($_GET['err']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['success'])) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($_GET['success']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php
                            $stmt = $con->prepare("SELECT COUNT(*) AS totalProducts FROM `transition` WHERE `status`='success' AND `delivered`='yes'");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $run = $result->fetch_assoc();
                            ?>
                            <h6 class="m-0 font-weight-bold text-primary"><strong><?= $run['totalProducts'] ?></strong> Orders Delivered</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Games</th>
                                            <th>Username</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="img_List">
                                        <?php
                                        if (isset($_GET['s'])) {
                                            $search_value = $_GET['s'];
                                            $stmt = $con->prepare("SELECT t.*, u.username, u.email 
                                      FROM `transition` t 
                                      LEFT JOIN `users` u ON t.user_id = u.id 
                                      WHERE status='success' AND delivered='no' AND t.`txt_id` LIKE '%$search_value%' 
                                      OR t.`contact` LIKE '%$search_value%' 
                                      OR t.`upi` LIKE '%$search_value%' 
                                      OR t.`coupon_code` LIKE '%$search_value%' 
                                      OR u.`username` LIKE '%$search_value%' 
                                       OR t.`txt_id` LIKE '%$search_value%' 
                                      OR u.`email` LIKE '%$search_value%'");
                                        } else {

                                            $stmt = $con->prepare("SELECT * FROM transition WHERE status='paid' AND delivered='yes' ORDER BY `id` DESC");
                                        }
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($txt_data = $result->fetch_assoc()) {
                                                $user = getUserById($txt_data['user_id']);
                                                $user_id = $user['id'];
                                                $product_ids = fetchProductIDs($con, $txt_data['user_id'], $txt_data['status'], $txt_data['delivered']);
                                                $product_details = fetchProductDetails($con, $product_ids);
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php foreach ($product_details as $product) : ?>
                                                            <?php
                                                            $product_id = $product['id'];
                                                            $user_data_available = false;
                                                            $stmt = $con->prepare("SELECT * FROM delivery WHERE product_id=? AND user_id=? AND txt_id=?");
                                                            $stmt->bind_param("iii", $product_id, $user_id, $txt_data['txt_id']);
                                                            $stmt->execute();
                                                            $delivery_result = $stmt->get_result();
                                                            if ($delivery_result->num_rows > 0) {
                                                                $user_datas = $delivery_result->fetch_assoc();
                                                                $user_data_available = true;
                                                            }
                                                            ?>
                                                            <p data-toggle="modal" data-target="#send<?= $product['id'] . $txt_data['txt_id'] ?>" style="cursor: pointer;">
                                                                <?= htmlspecialchars($product['title']) . " - OTP: " . ($product['twofactor'] ? "Yes" : "No") ?>
                                                            </p>

                                                            <div class="modal fade" id="send<?= $product['id'] . $txt_data['txt_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel<?= $product['id'] ?>" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <form action="../php/configs/actions.php" method="POST">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="sendModalLabel<?= $product['id'] ?>">
                                                                                    <?= htmlspecialchars($product['title']) . " - OTP: " . ($product['twofactor'] ? "Yes" : "No") ?>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <div class="form-group">
                                                                                    <label for="message<?= $product['id'] ?>">Credicials Here</label>
                                                                                    <textarea id="message<?= $product['id'] ?>" name="message" required class="form-control" rows="10" placeholder="message Here"><?= $user_data_available ? htmlspecialchars($user_datas['message']) : "Use Steam Software For This Game." ?></textarea>
                                                                                </div>


                                                                                <?php if ($product['twofactor']) : ?>
                                                                                    <div class="form-group">
                                                                                        <label for="otp<?= $product['id'] ?>">OTP</label>
                                                                                        <input type="text" id="otp<?= $product['id'] ?>" name="otp" class="form-control" value="<?= $user_data_available ? htmlspecialchars($user_datas['otp']) : "" ?>" placeholder="OTP Here">
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="txt_id" value="<?= htmlspecialchars($txt_data['txt_id']) ?>">
                                                                                <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                                                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                                                <input type="hidden" name="gamename" value="<?= htmlspecialchars($product['title']) ?>">
                                                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td>₹<?= htmlspecialchars($txt_data['amount']) ?></td>
                                                    <td><?= htmlspecialchars($txt_data['date']) ?></td>
                                                    <td>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="txt_id" value="<?= htmlspecialchars($txt_data['txt_id']) ?>">

                                                            <button type="submit" name="markasread" class="btn btn-danger">Modify</button>
                                                        </form>
                                                    </td>
                                                    <td> <a href="orders.php?s=<?= $txt_data['txt_id'] ?>" class="btn btn-warning btn-sm btn-circle">
                                                            <i class="far fa-credit-card"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No Order Info Available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'php/pages/footer.php'; ?>
        </div>
    </div>
</body>

</html>