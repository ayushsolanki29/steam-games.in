<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['delete_product'])) {
    $pid = $_GET['pid'];
    $img = "../assets/img/related_images/" . $_GET['img'];

    // Prepare the statement to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM `multiple_images` WHERE `id` = ?");
    $stmt->bind_param("i", $pid);

    if ($stmt->execute()) {
        $message = "Image Deleted!";
        if (file_exists($img)) {
            unlink($img);
        }
        header("Location: products_image_list.php?success=$message");
        exit;
    } else {
        $message = "Failed to delete Image!";
        header("Location: products_image_list.php?err=$message");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Products Relatalted Images- steam-games.in</title>
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
                    <h1 class="h3 mb-2 text-gray-800">Images List</h1>
                    <p class="mb-4">All product realated Images list is here. You want to <a target="_blank" href="products_images.php">add more?</a></p>

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
                            $result = $con->query("SELECT COUNT(*) AS `totalmultiple_images` FROM `multiple_images`");
                            $run = $result->fetch_assoc();
                            ?>
                            <h6 class="m-0 font-weight-bold text-primary">We have <strong><?= $run['totalmultiple_images'] ?> </strong> Images</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        $query = "SELECT `id`, `product_id`, `image` FROM `multiple_images` ORDER BY `id` DESC limit 9";
                                        $result = $con->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($data = $result->fetch_assoc()) {
                                                $i_id = $data['id'];
                                                $product_id = $data['product_id'];
                                                $image = $data['image'];

                                                $productQuery = $con->prepare("SELECT `title` FROM `products` WHERE `id` = ?");
                                                $productQuery->bind_param("i", $product_id);
                                                $productQuery->execute();
                                                $productResult = $productQuery->get_result();
                                                $product = $productResult->fetch_assoc();
                                        ?>

                                                <tr>
                                                    <td onclick="window.location.href = '../assets/img/related_images/<?= htmlspecialchars($image) ?>'">
                                                        <img src="../assets/img/related_images/<?= htmlspecialchars($image) ?>" class="img-thumbnail" width="100" alt="img">
                                                    </td>
                                                    <td><?= htmlspecialchars($product['title']) ?></td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#deleteModel<?= $i_id ?>" class="btn btn-danger btn-sm btn-circle">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="deleteModel<?= $i_id ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure you want to delete <?= htmlspecialchars($product['title']) ?>?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'products_image_list.php?delete_product=true&pid=<?= $i_id ?>&img=<?= $image ?>'" class="btn btn-danger">Delete Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        <?php
                                                $productQuery->close();
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No Images Available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button class="btn btn-primary btn-icon-split " id="loadMore">
                                        <input type="hidden" id="start" value="0">
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
            <div id="modals-container"></div>
            <?php include 'php/pages/footer.php' ?>
            <script>
                $(document).ready(function() {
                    $("#loadMore").click(function() {
                        let start = parseInt($("#start").val());
                        const perpage = 9;
                        start = start + perpage;
                        $("#start").val(start);

                        $.ajax({
                            url: "php/lib/get_imglist.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                starting: start
                            },
                            success: function(response) {
                                if (response.rows.trim() !== "") {
                                    $("#img_List").append(response.rows);
                                    $("#modals-container").append(response.modals);
                                } else {
                                    $("#loadMore").attr('disabled', 'disabled');
                                    $(".text").text("You've seen all");
                                }
                            }
                        });
                    });
                });
            </script>