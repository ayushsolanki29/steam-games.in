<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['delete_product'])) {
    $pid = $_GET['pid'];

    // Prepare the statement to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM `coupon_codes` WHERE `id` = ?");
    $stmt->bind_param("i", $pid);

    if ($stmt->execute()) {
        $message = "coupoun Deleted!";
        header("Location: coupon_codes_list.php?success=$message");
        exit;
    } else {
        $message = "Failed to delete coupoun!";
        header("Location: coupon_codes_list.php?err=$message");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>coupon code list - steam-games.in</title>
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
                    <h1 class="h3 mb-2 text-gray-800">coupon codes List</h1>
                    <p class="mb-4">All coupon code list is here. You want to <a target="_blank" href="coupon_codes_add.php">add more?</a></p>

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
                            $result = $con->query("SELECT COUNT(*) AS `totalmultiple_images` FROM `coupon_codes`");
                            $run = $result->fetch_assoc();
                            ?>
                            <h6 class="m-0 font-weight-bold text-primary">We have <strong><?= $run['totalmultiple_images'] ?> </strong> coupon codes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Coupon Name</th>
                                            <th>value</th>
                                            <th>min</th>
                                            <th>max</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        $query = "SELECT * FROM `coupon_codes` ORDER BY `id` DESC";
                                        $result = $con->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($data = $result->fetch_assoc()) {
                                                $c_id = $data['id'];
                                        ?>

                                                <tr>

                                                    <td><?= htmlspecialchars($data['name']) ?></td>
                                                    <td><?= htmlspecialchars($data['value']) ?></td>
                                                    <td><?= htmlspecialchars($data['min']) ?>₹</td>
                                                    <td><?= htmlspecialchars($data['max']) ?>₹</td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#deleteModel<?= $c_id ?>" class="btn btn-danger btn-sm btn-circle">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="deleteModel<?= $c_id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure you want to delete <strong>"<?= htmlspecialchars($data['name']) ?>"</strong> ?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'coupon_codes_list.php?delete_product=true&pid=<?= $c_id ?> ?>'" class="btn btn-danger">Delete Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        <?php

                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No coupon codes Available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'php/pages/footer.php' ?>