<?php include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['delete_product'])) {
    $pid = $_GET['id'];

    $query_d = mysqli_query($con, "DELETE FROM `reviews` WHERE `id`='$pid'");
    if ($query_d) {
        $message = "Message Deleted!";
        header("location:game_reviews.php?success=$message");
        exit;
    }
}
if (isset($_POST['change_review'])) {
    $pid = $_POST['id'];
    $message = $_POST['message'];

    $stmt = $con->prepare("UPDATE `reviews` SET `comment`='$message' WHERE `id`=?");
    $stmt->bind_param("i", $pid);

    if ($stmt->execute()) {
        $message = "comment Edited!";
        header("Location: game_reviews.php?success=$message");
        exit;
    } else {
        $message = "Failed to Edit!";
        header("Location: game_reviews.php?err=$message");
        exit;
    }
}
?>
<!DOCTYPE html>


<html lang="en">

<head>
    <title>Game Reviews - steam-games.in</title>
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

                    <h1 class="h3 mb-2 text-gray-800">Game Reviews</h1>
                    <br>
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="s" placeholder="Search by name">
                            <div class="input-group-append">
                                <div class="input-group-append">
                                    <?php if (isset($_GET['s'])) { ?>
                                        <button class="btn btn-outline-danger" onclick="window.location.href = 'game_reviews.php'" type="button">Reset</button>
                                    <?php } else { ?>
                                        <button class="btn btn-outline-primary" type="submit">search</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </form>

                    <br>
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
                        <div class="card-header py-3">
                            <?php $run = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `totalProducts` FROM `reviews` ORDER BY `id` DESC"));   ?>
                            <h6 class="m-0 font-weight-bold text-primary"><strong><?= $run['totalProducts'] ?> </strong> submissions</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Name</th>
                                            <th>Game</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th colspan="2">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        if (isset($_GET['s'])) {
                                            $search_value = $_GET['s'];
                                            $query = "SELECT * FROM `reviews` WHERE `user_id` LIKE '%$search_value%' ";
                                        } else {
                                            $query = "SELECT * FROM `reviews` ORDER BY `reviews`.`id` DESC";
                                        }
                                        $stmt = mysqli_prepare($con, $query);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_store_result($stmt);

                                        if (mysqli_stmt_num_rows($stmt) > 0) {
                                            mysqli_stmt_bind_result($stmt, $id, $game_id, $name, $message, $date);

                                            while (mysqli_stmt_fetch($stmt)) {

                                        ?>

                                                <tr>
                                                    <td title=""><?= htmlspecialchars($name) ?></td>
                                                    <td> <?php
                                                            $fetch_cat = mysqli_query($con, "SELECT `title` FROM `products` WHERE `id` = '$game_id'");
                                                            while ($cat_n = mysqli_fetch_assoc($fetch_cat)) { ?>
                                                            <?= htmlspecialchars($cat_n['title']) ?>
                                                        <?php }
                                                        ?></td>
                                                    <td title="<?= htmlspecialchars($message) ?>">Mouse Hover here</td>
                                                    <td title=""><?= htmlspecialchars($date) ?></td>

                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#deleteModel<?= $id ?>" class="btn btn-danger btn-sm btn-circle">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#infomesage<?= $id ?>" class="btn btn-primary btn-sm btn-circle">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                    </td>

                                                </tr>

                                                <!-- Modal for delete confirmation -->
                                                <div class="modal" id="infomesage<?= $id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?= htmlspecialchars($name) ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="post">
                                                                <div class="modal-body">

                                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                                    <textarea class="form-control" name="message"><?= $message ?></textarea>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="change_review" class="btn btn-primary">save</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal" id="deleteModel<?= $id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure want to delete <?= htmlspecialchars($name) ?>'s message?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'game_reviews.php?delete_product=true&id=<?= $id ?>'" class="btn btn-danger">Delete Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>No Messages Available</td></tr>";
                                        }


                                        ?>
                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="modals-container"></div>

            <?php include 'php/pages/footer.php' ?>