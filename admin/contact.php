<?php include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['delete_product'])) {
    $pid = $_GET['pid'];

    $query_d = mysqli_query($con, "DELETE FROM `contact` WHERE `id`='$pid'");
    if ($query_d) {
        $message = "Message Deleted!";
        header("location:contact.php?success=$message");
        exit;
    }
}

?>
<!DOCTYPE html>


<html lang="en">

<head>
    <title>Contact Submissions - steam-games.in</title>
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

                    <h1 class="h3 mb-2 text-gray-800">contact submissions</h1>
                    <br>
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="<?= isset($_GET['s']) ? $_GET['s'] : "" ?>" name="s" placeholder="Search by name | email | reason">
                            <div class="input-group-append">
                                <div class="input-group-append">
                                    <?php if (isset($_GET['s'])) { ?>
                                        <button class="btn btn-outline-danger" onclick="window.location.href = 'contact.php'" type="button">Reset</button>
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
                            <?php $run = mysqli_fetch_assoc(mysqli_query($con,  "SELECT COUNT(*) AS `totalProducts` FROM `contact` ORDER BY `id` DESC"));   ?>
                            <h6 class="m-0 font-weight-bold text-primary"><strong><?= $run['totalProducts'] ?> </strong> submissions</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Reason</th>
                                            <th>Other Reason</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th colspan="2">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        if (isset($_GET['s'])) {
                                            $search_value = $_GET['s'];
                                            $query = "SELECT * FROM `contact` WHERE `name` LIKE '%$search_value%' OR `email` LIKE '%$search_value%' OR `reason` LIKE '%$search_value%'";
                                        } else {
                                            $query = "SELECT * FROM `contact`";
                                        }
                                        $stmt = mysqli_prepare($con, $query);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_store_result($stmt);

                                        if (mysqli_stmt_num_rows($stmt) > 0) {
                                            mysqli_stmt_bind_result($stmt, $id, $name, $email, $reason, $other_reason, $message, $date);

                                            while (mysqli_stmt_fetch($stmt)) {

                                        ?>

                                                <tr>
                                                    <td title=""><?= htmlspecialchars($name) ?></td>
                                                    <td title=""><?= htmlspecialchars($email) ?></td>
                                                    <td title=""><?= htmlspecialchars($reason) ?></td>
                                                    <td title=""><?= htmlspecialchars($other_reason) ?></td>
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
                                                                <h5 class="modal-title"><?= htmlspecialchars($reason) . " : " . htmlspecialchars($other_reason) ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><?= htmlspecialchars($message) ?></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'mailto:<?= $email ?>'" class="btn btn-primary">Reply</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
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
                                                                <button type="button" onclick="window.location.href = 'contact.php?delete_product=true&pid=<?= $p_id ?>'" class="btn btn-danger">Delete Now</button>
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