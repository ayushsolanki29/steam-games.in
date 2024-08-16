<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['delete_all'])) {
    $stmt = $con->prepare("DELETE FROM `notification` WHERE 1");

    if ($stmt->execute()) {
        $message = "Notifications Deleted!";
        header("Location: notifications.php?success=$message");
        exit;
    } else {
        $message = "Failed to delete Notifications!";
        header("Location: notifications.php?err=$message");
        exit;
    }
}
if (isset($_GET['delete_single'])) {
    $pid = $_GET['id'];

    $stmt = $con->prepare("DELETE FROM `notification` WHERE `id` = ?");
    $stmt->bind_param("i", $pid);

    if ($stmt->execute()) {
        $message = "Notification Deleted!";
        header("Location: notifications.php?success=$message");
        exit;
    } else {
        $message = "Failed to delete Notification!";
        header("Location: notifications.php?err=$message");
        exit;
    }
}
if (isset($_GET['read_all'])) {
    $stmt = $con->prepare("UPDATE `notification` SET `status`='read' WHERE  `status`='unread'");

    if ($stmt->execute()) {
        $message = "Notifications Readed!";
        header("Location: notifications.php?success=$message");
        exit;
    } else {
        $message = "Failed to Readed Notifications!";
        header("Location: notifications.php?err=$message");
        exit;
    }
}
if (isset($_GET['read_single'])) {
    $pid = $_GET['id'];

    $stmt = $con->prepare("UPDATE `notification` SET `status`='read' WHERE `id`=?");
    $stmt->bind_param("i", $pid);

    if ($stmt->execute()) {
        $message = "Notification Readed!";
        header("Location: notifications.php?success=$message");
        exit;
    } else {
        $message = "Failed to Readed Notification!";
        header("Location: notifications.php?err=$message");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Notifications - steam-games.in</title>
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
                    <h1 class="h3 mb-2 text-gray-800">Notifications</h1>

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
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <?php
                            $result = $con->query("SELECT COUNT(*) AS `totalmultiple_images` FROM `notification`");
                            $run = $result->fetch_assoc();
                            ?>
                            <h6 class="m-0 font-weight-bold text-primary">Total <strong><?= $run['totalmultiple_images'] ?> </strong> Notifications</h6>
                            <div class="actions">

                                <a href="notifications.php?read_all" class="btn btn-sm bg-gradient-primary text-white">
                                    <i class="fas fa-check-double"></i> Read All
                                </a>
                                <a href="notifications.php?delete_all" class="btn btn-sm bg-gradient-danger text-white">
                                    <i class="fas fa-trash"></i> Delete All
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>title</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th colspan="3">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        $query = "SELECT * FROM `notification`  ORDER BY `notification`.`id` DESC";
                                        $result = $con->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($data = $result->fetch_assoc()) {
                                                $c_id = $data['id'];

                                        ?>

                                                <tr>

                                                    <td><?= htmlspecialchars($data['message']) ?></td>
                                                    <td><?= htmlspecialchars($data['date']) ?></td>
                                                    <td><?= htmlspecialchars($data['status']) ?></td>
                                                    <td>
                                                        <a href="notifications.php?delete_single&id=<?= $c_id ?>" class="btn btn-danger btn-sm btn-circle">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="notifications.php?read_single&id=<?= $c_id ?>" class="btn btn-warning btn-sm btn-circle">
                                                            <i class="fas fa-check-double"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= $data['url'] ?>" class="btn btn-primary btn-sm btn-circle">
                                                            <i class="fas fa-link"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php

                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No New Notification Available</td></tr>";
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