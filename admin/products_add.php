<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['addproducts'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $twofactor = mysqli_real_escape_string($con, $_POST['twofactor']);
    $desciption = mysqli_real_escape_string($con, $_POST['desciption']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $keywords = mysqli_real_escape_string($con, $_POST['keywords']);

    // Handle multiple category IDs
    $categoryIdsArray = $_POST['categoryId'];
    $categoryIdsSanitized = array_map(function ($categoryId) use ($con) {
        return mysqli_real_escape_string($con, $categoryId);
    }, $categoryIdsArray);
    $categoryIds = implode(',', $categoryIdsSanitized);

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $filename = $_FILES['thumbnail']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid_extensions = ['png', 'jpg', 'jpeg'];
        $name = date("YmdHis");
        if (in_array(strtolower($file_ext), $valid_extensions)) {
            $destfile = '../assets/img/products/' . $name . '.' . $file_ext;
            $new_file = $name . '.' . $file_ext;

            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destfile);

            $query = mysqli_query(
                $con,
                "INSERT INTO `products`(`title`, `price`, `discount`, `description`, `thumbnail`, `category_id`, `keywords`, `twofactor`) 
                VALUES ('$title','$price','$discount','$desciption','$new_file','$categoryIds','$keywords','$twofactor')"
            );
            if ($query) {
                $message = "Product Added!";
                header("location:products_add.php?success=$message");
                exit;
            }
        } else {
            $response = "Only supported extensions are JPG, PNG, JPEG.";
            header("location:products_add.php?err=$response");
            exit;
        }
    } else {
        $response = "Something went wrong while uploading image";
        header("location:products_add.php?err=$response");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add New Products - steam-games.in</title>
    <?php include 'php/pages/head.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

                    <h1 class="h3 mb-4 text-gray-800">Add New Product</h1>
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


                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label>Title</label>
                                    <input type="text" name="title" required class="form-control" placeholder="Enter Title Here ...">
                                </div>
                                <div class="col">
                                    <label>Two Factor Authantication </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" required name="twofactor" value="1">
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" readonly disabled value="Yes">
                                        &nbsp;

                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" required name="twofactor" value="0" checked>
                                            </div>
                                        </div>
                                        <input type="text" value="No" readonly disabled class="form-control" aria-label="Text input with radio button">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">choose category</label>
                                        <select class="form-control categorySelect" name="categoryId[]" required multiple>
                                            <option>select category</option>
                                            <?php
                                            $selectQ = mysqli_query($con, "SELECT `id`, `category_name` FROM `category`");
                                            if ($selectQ) {
                                                while ($category_ = mysqli_fetch_array($selectQ)) { ?>
                                                    <option value="<?= $category_['id'] ?>"><?= $category_['category_name'] ?></option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="exampleFormControlSelect1">thumbnail</label>

                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" required class="custom-file-input" id="inputGroupFile04">
                                            <label class="custom-file-label" for="inputGroupFile04">Choose Image</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="textarea">Description</label>
                                    <textarea class="form-control" name="desciption" required id="textarea" placeholder="Enter Description here"></textarea>
                                </div>
                                <div class="col">
                                    <label for="textarea">Keywords</label>
                                    <textarea class="form-control" name="keywords" required placeholder="Enter keywords here"></textarea>
                                </div>

                            </div>
                            <div class="form-group">

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Price">Price</label>
                                            <input type="number" name="price" required class="form-control" id="Price" placeholder="Enter original Price" oninput="updateFinalPrice()">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Discount">Discount (%)</label>
                                            <input type="number" name="discount" required class="form-control" id="Discount" placeholder="Enter Discount in %" oninput="updateFinalPrice()">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Final">Final Price</label>
                                            <input type="text" readonly class="form-control" id="Final" placeholder="Final Price">
                                        </div>
                                    </div>
                                </div>
                                <small id="discountHelpBlock" class="form-text text-muted info-text">
                                    For example, if the original price is ₹100 and you want to sell it for ₹80, enter 20 as the discount (20% off).
                                </small>
                            </div>
                            <button type="submit" name="addproducts" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    $(".categorySelect").chosen();

                    function updateFinalPrice() {
                        let price = parseInt($('#Price').val());
                        let discount = parseInt($('#Discount').val());

                        if (!isNaN(price) && !isNaN(discount)) {
                            let discountedPrice = price * (1 - discount / 100);
                            $('#Final').val('₹' + parseInt(discountedPrice));
                        } else {
                            $('#Final').val('');
                        }
                    }

                    $('#Price, #Discount').on('input', updateFinalPrice);
                });
            </script>