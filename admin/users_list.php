<?php include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['update_status'])) {
    $user_id = $_GET['user_id'];
    $run = mysqli_query($con, "UPDATE `users` SET `ac_status` = 'true',`code`='' WHERE `id`='$user_id'");
    if ($run) {
        header("Location: users_list.php?success=user activated!");
    } else {
        header("Location: users_list.php?err=failed to active");
    }
}
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['user_id'];
    $run = mysqli_query($con, "DELETE FROM `users` WHERE `id`='$user_id'");
    if ($run) {
        header("Location: users_list.php?success=user Deleted!");
    } else {
        header("Location: users_list.php?err=failed to Deleted");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Users - steam-games.in</title>
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

                    <h1 class="h3 mb-2 text-gray-800">Users List</h1>

                    <?php
                    if (isset($_GET['err'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_GET['err'] ?>
                        </div>
                    <?php }
                    if (isset($_GET['success'])) { ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_GET['success'] ?>
                        </div>
                    <?php }
                    ?>
                    <br>
                    <form action="">
                        <div class="input-group mb-3">

                            <input type="text" value="<?= isset($_GET['s']) ? $_GET['s'] : "" ?>" class="form-control" name="s" placeholder="Search by username | email" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <?php if (isset($_GET['s'])) { ?>
                                    <button class="btn btn-danger" onclick="window.location.href = 'users_list.php'" type="button">Reset</button>
                                <?php } else { ?>
                                    <button class="btn btn-outline-primary" type="submit">search</button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php

                            $run = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `totalProducts` FROM `users`"));   ?>
                            <h6 class="m-0 font-weight-bold text-primary"><strong><?= $run['totalProducts'] ?> </strong> users</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Profile</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>type</th>
                                            <th>status</th>
                                            <th>Join Date</th>
                                            <th colspan="3">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        if (isset($_GET['s'])) {
                                            $search_value = "%" . $_GET['s'] . "%";
                                            $query = "SELECT `id`, `username`, `email`, `profile`, `ac_status`, `type`, `created_at` FROM `users` WHERE username LIKE ? OR email LIKE ?";
                                            $stmt = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt, "ss", $search_value, $search_value);
                                        } else {
                                            $query = "SELECT `id`, `username`, `email`, `profile`, `ac_status`, `type`, `created_at` FROM `users` ORDER BY `users`.`id` DESC";
                                            $stmt = mysqli_prepare($con, $query);
                                        }

                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_store_result($stmt);
                                        mysqli_stmt_bind_result($stmt, $u_id, $u_name, $u_email, $profile, $ac_status, $type, $created_at);

                                        if (mysqli_stmt_num_rows($stmt) > 0) {
                                            while (mysqli_stmt_fetch($stmt)) {
                                        ?>

                                                <tr>
                                                    <td> <?php
                                                            if ($type == "google") { ?>
                                                            <img src="<?= $profile ?>" alt="<?= $u_name ?>" class="team-logo rounded-circle" width="25">

                                                        <?php } else { ?>
                                                            <img src="../assets/img/profile/<?= $profile ?>" alt="<?= $u_name ?>" class="team-logo rounded-circle" width="25">

                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($u_name) ?></td>
                                                    <td><?= htmlspecialchars($u_email) ?></td>
                                                    <td><?= htmlspecialchars($type) ?></td>
                                                    <td class="bg-<?= $ac_status == "true" ? "success" : "danger" ?> text-white"><?= $ac_status == "true" ? "Active" : "Inactive" ?></td>
                                                    <td><?= htmlspecialchars($created_at) ?></td>
                                                    <?php if ($ac_status != "true") { ?>
                                                        <td> <a href="users_list.php?update_status=true&user_id=<?= $u_id ?>" class="btn btn-success btn-sm btn-circle">
                                                                <i class="fas fa-check-square"></i>
                                                            </a>
                                                        </td>
                                                        <td> <a href="users_list.php?delete_user=true&user_id=<?= $u_id ?>" class="btn btn-danger btn-sm btn-circle">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td> <a href="orders.php?s=<?= $u_name ?>" class="btn btn-warning btn-sm btn-circle">
                                                                <i class="far fa-credit-card"></i>
                                                            </a>
                                                        </td>
                                                        <td> <a href="messages.php?user_id=<?= $u_id ?>" class="btn btn-info btn-sm btn-circle">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </a>
                                                        </td>

                                                    <?php   }

                                                    ?>
                                                    <td> <a href="mailto:<?= $u_email ?>" class="btn btn-primary btn-sm btn-circle">
                                                            <i class="fas fa-envelope"></i>
                                                        </a>
                                                    </td>

                                                </tr>


                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>No Users Available</td></tr>";
                                        }


                                        ?>
                                    </tbody>

                                </table>
                                <div class="text-center">

                                    <button class="btn btn-primary btn-icon-split ">
                                        <input type="hidden" id="start">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-sync-alt"></i>
                                        </span>
                                        <span class="text">Load More</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>