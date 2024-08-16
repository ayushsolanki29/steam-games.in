<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['uid']) && isset($_GET['block_user'])) {
    $uid = $_GET['uid'];
    $block_q = mysqli_query($con, "UPDATE `messages` SET `status` = '1' WHERE `user_id` = '$uid'");
    if ($block_q) {
        $message = "user blocked!";
        header("Location: chat.php?success=$message");
        exit;
    } else {
        $message = "user blocked Faild!";
        header("Location: chat.php?err=$message");
        exit;
    }
}
if (isset($_GET['userid']) && isset($_GET['seen_message'])) {
    $uid = $_GET['userid'];
    $block_q = mysqli_query($con, "UPDATE `messages` SET `read` = 'read' WHERE `user_id` = '$uid'");
    if ($block_q) {
        $message = "All Message seen!";
        header("Location: chat.php?success=$message");
        exit;
    } else {
        $message = "seen Faild!";
        header("Location: chat.php?err=$message");
        exit;
    }
}
if (isset($_GET['uid']) && isset($_GET['delete'])) {
    $uid = $_GET['uid'];
    $block_q = mysqli_query($con, "DELETE FROM `messages` WHERE `user_id` = '$uid'");
    if ($block_q) {
        $message = "user's chats is deleted!";
        header("Location: chat.php?success=$message");
        exit;
    } else {
        $message = "user chats delete Faild!";
        header("Location: chat.php?err=$message");
        exit;
    }
}
if (isset($_GET['uid']) && isset($_GET['unblock_user'])) {
    $uid = $_GET['uid'];
    $block_q = mysqli_query($con, "UPDATE `messages` SET `status` = '0' WHERE `user_id` = '$uid'");
    if ($block_q) {
        $message = "user unblocked!";
        header("Location: chat.php?success=$message");
        exit;
    } else {
        $message = "user unblocked Faild!";
        header("Location: chat.php?err=$message");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Users Chats - steam-games.in</title>
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

                    <h1 class="h3 mb-2 text-gray-800">Messages from users</h1>
                    <!-- <p class="mb-4">All product list is here. you want <a target="_blank" href="products_add.php">add more ?</a></p> -->
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
                    <div class="card shadow mb-4">
                        <?php
                        $query = "SELECT COUNT(DISTINCT user_id) AS total_users_chatted 
                    FROM `messages` 
                    WHERE type = 'user'";
                        $result = mysqli_query($con, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $total_users_chatted = $row['total_users_chatted'];
                            echo "<div class='card-header py-3'>";
                            echo "<h6 class='m-0 font-weight-bold text-primary'><strong>$total_users_chatted</strong> users chat Available</h6>";
                            echo "</div>";
                        } else {
                            echo "No results found.";
                        } ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Profile</th>
                                            <th>user</th>
                                            <th>messages</th>
                                            <th>Date</th>
                                            <th colspan="4">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        $query = "SELECT time, user_id,status, COUNT(*) as message_count FROM messages WHERE type = 'user' GROUP BY user_id ORDER BY MAX(time) DESC";
                                        $stmt = mysqli_prepare($con, $query);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_store_result($stmt);

                                        if (mysqli_stmt_num_rows($stmt) > 0) {
                                            mysqli_stmt_bind_result($stmt, $time, $user_id, $status, $message_count);

                                            while (mysqli_stmt_fetch($stmt)) {
                                                $user_data = getUserById($user_id);
                                        ?>

                                                <tr>
                                                    <td> <?php
                                                            if ($user_data['type'] == "google") { ?>
                                                            <img src="<?= $user_data['profile'] ?>" alt="Logo" class="team-logo rounded-circle" width="40">

                                                        <?php } else { ?>
                                                            <img src="../assets/img/profile/<?= $user_data['profile'] ?>" alt="Logo" class="team-logo rounded-circle" width="40">

                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <td style="cursor: pointer;" onclick="window.location.href ='messages.php?user_id=<?= htmlspecialchars($user_id) ?>' ">
                                                        <?= $user_data['username'] ?>
                                                    </td>
                                                    <td><?= number_format($message_count, 0) ?> Messages</td>
                                                    <td><?= date('M d, Y h:i A', strtotime($time)) ?></td>
                                                    <td>
                                                        <?php if ($status == 0) { ?>
                                                            <a href="#" data-toggle="modal" data-target="#deleteModel<?= $user_id ?>" class="btn btn-warning btn-sm ">
                                                                <i class="fas fa-exclamation-triangle" style="color: red;"></i> Block 
                                                            </a>
                                                        <?php } else {
                                                        ?>
                                                            <a href="#" data-toggle="modal" data-target="#unblockModel<?= $user_id ?>" class="btn btn-success btn-sm ">
                                                                <i class="fas fa-exclamation-triangle" style="color: green;"></i> Unblock
                                                            </a>
                                                        <?php
                                                        } ?>
                                                    

                                                    
                                                        <a href="#" data-toggle="modal" data-target="#deleteChat<?= $user_id ?>" class="btn btn-danger btn-sm ">
                                                            <i class="fas fa-trash"></i> 
                                                        </a>
                                                   
                                                    
                                                        <a href="chat.php?seen_message&userid=<?= $user_id ?>" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check-double"></i> 
                                                        </a>
                                                        <a href="messages.php?user_id=<?= $user_data['id']?>" class="btn btn-info btn-sm btn-circle">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <!-- Modal for delete confirmation -->
                                                <div class="modal" id="deleteModel<?= $user_id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure want to block <?= $user_data['username'] ?> ?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'chat.php?block_user=true&uid=<?= $user_id ?>'" class="btn btn-danger">block Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal" id="deleteChat<?= $user_id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure want to Delete <?= $user_data['username'] ?>'s chats ?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'chat.php?delete=true&uid=<?= $user_id ?>'" class="btn btn-danger">Delete Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal" id="unblockModel<?= $user_id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure want to Unblock <?= $user_data['username'] ?> ?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'chat.php?unblock_user=true&uid=<?= $user_id ?>'" class="btn btn-danger">block Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>No Chats Available</td></tr>";
                                        }


                                        ?>
                                    </tbody>

                                </table>
                                <!-- <div class="text-center">
                                    <button class="btn btn-primary btn-icon-split " id="loadMore">
                                        <input type="hidden" id="start" value="0">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-sync-alt"></i>
                                        </span>
                                        <span class="text">Load More</span>
                                    </button>
                                </div> -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>