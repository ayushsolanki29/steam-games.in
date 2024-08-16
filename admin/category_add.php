<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['add_category'])) {
    $categories = $_POST['category'];

    foreach ($categories as $category_name) {
        $category_name = mysqli_real_escape_string($con, $category_name);
        $query = mysqli_query($con, "INSERT INTO `category`(`category_name`) VALUES ('$category_name')");

        if (!$query) {
            $message = "Category failed to add: " . mysqli_error($con);
            header("Location: category_add.php?err=$message");
            exit;
        }
    }

    $message = "Categories added successfully!";
    header("Location: category_add.php?success=$message");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add New Categories - steam-games.in</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Add New Category</h1>
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
                                    <input type="text" name="category[]" required class="form-control" placeholder="Enter category Title Here">
                                </div>
                            </div>
                            <div class="col">
                                <button type="button" id="addCategory" class="btn btn-success"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>

                        <div id="categoryContainer"></div>

                        <button type="submit" name="add_category" class="btn btn-primary">Add category</button>
                    </form>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>
            <script>
                $(document).ready(function() {
                    $("#addCategory").click(function() {
                        var newCategoryInput = `
                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                <input type="text" name="category[]" required class="form-control" placeholder="Enter category Title Here">
                            </div>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger removeCategory"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                `;
                        $("#categoryContainer").append(newCategoryInput);
                    });

                    // Use event delegation to handle the removal of dynamically added input fields
                    $(document).on('click', '.removeCategory', function() {
                        $(this).closest('.row').remove();
                    });
                });
            </script>