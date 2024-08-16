<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['add_coupon'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $value = mysqli_real_escape_string($con, $_POST['value']);
    $min = mysqli_real_escape_string($con, $_POST['min']);
    $max = mysqli_real_escape_string($con, $_POST['max']);

    $query = mysqli_query($con, "INSERT INTO `coupon_codes`(`name`, `value`, `min`, `max`) 
    VALUES ('$name','$value','$min','$max')");
    if ($query) {
        $message = "coupon code Added!";
        header("location:coupon_codes_add.php?success=$message");
        exit;
    } else {
        $message = "coupon Faild to add!";
        header("location:coupon_codes_add.php?err=$message");
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add new Coupon code - steam-games.in</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Add Coupon Code</h1>
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


                    <form method="post">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>coupon Name</label>
                                    <input type="text" name="name" required class="form-control" placeholder="Enter cupoun Title Here">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>coupon value</label>
                                    <input type="text" name="value" required class="form-control" placeholder="enter value in %">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>min amount</label>
                                    <input type="text" name="min" required class="form-control" placeholder="Enter min amount in ₹">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>max amount</label>
                                    <input type="text" name="max" required class="form-control" placeholder="Enter max amount in ₹">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="add_coupon" class="btn btn-primary">Add coupon</button>
                    </form>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>