<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['updateProduct'])) {

    $pid = mysqli_real_escape_string($con, $_POST['pid']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $twofactor = mysqli_real_escape_string($con, $_POST['twofactor']);

    // Handle multiple category IDs
    $categoryIdsArray = $_POST['categoryId'];
    $categoryIdsSanitized =
        array_map(function ($categoryId) use ($con) {
            return mysqli_real_escape_string($con, $categoryId);
        }, $categoryIdsArray);

    $categoryIds = implode(',', $categoryIdsSanitized);

    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
    $new_image = mysqli_real_escape_string($con, $_POST['image']);

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $filename = $_FILES['thumbnail']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid_extensions = ['png', 'jpg', 'jpeg'];
        $name =  date("YmdHis");
        if (in_array(strtolower($file_ext), $valid_extensions)) {
            $destfile = '../assets/img/products/' . $name . '.' . $file_ext;
            $new_image = $name . '.' . $file_ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destfile);
        } else {
            $response = "Only supported extensions are JPG, PNG, JPEG.";
            header("location:products_edit.php?err=$response&p=$pid");
            exit;
        }
    }

    $query = mysqli_query(
        $con,
        "UPDATE `products` SET `title`='$title',`price`='$price',`discount`='$discount',`description`='$description',`thumbnail`='$new_image',`category_id`='$categoryIds',`keywords`='$keywords',`twofactor`='$twofactor'  WHERE `id`='$pid'"
    );
    if ($query) {
        $message =   "Product Updated!";
        header("location:products_edit.php?success=$message&p=$pid");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<title>Edit Products - steam-games.in</title>
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

                    <?php
                    if (isset($_GET['p'])) {
                        $_pid = $_GET['p'];
                        $query = mysqli_query($con, "SELECT `title`, `price`, `thumbnail`, `discount`, `description`, `category_id`, `keywords`, `twofactor` FROM `products` WHERE `id` ='$_pid'");
                        if (mysqli_num_rows($query) > 0) {
                            $data = mysqli_fetch_array($query);
                    ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <img src="../assets/img/products/<?= $data['thumbnail'] ?>" class="rounded mx-auto d-block img-thumbnail" alt="..." width="200">
                                </div>
                                <input type="hidden" name="pid" value="<?= $_pid ?>">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label>Title</label>
                                            <input type="text" name="title" required class="form-control" value="<?= $data['title'] ?>" placeholder="Enter Title Here ...">
                                        </div>
                                        <div class="col">
                                            <label>Two Factor Authentication</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="radio" required name="twofactor" value="1" <?= $data['twofactor'] == 1 ? "checked" : "" ?>>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" readonly disabled value="Yes">
                                                &nbsp;

                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="radio" required name="twofactor" value="0" <?= $data['twofactor'] == 0 ? "checked" : "" ?>>
                                                    </div>
                                                </div>
                                                <input type="text" value="No" readonly disabled class="form-control" aria-label="Text input with radio button">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Choose Category</label>
                                                <select class="form-control categorySelect" name="categoryId[]" required multiple>
                                                    <?php
                                                    $selected_categories = explode(",", $data['category_id']);
                                                    $selectQ = mysqli_query($con, "SELECT `id`, `category_name` FROM `category`");
                                                    if ($selectQ) {
                                                        while ($category = mysqli_fetch_array($selectQ)) {
                                                            $selected = (in_array($category['id'], $selected_categories)) ? "selected" : "";
                                                    ?>
                                                            <option <?= $selected ?> value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                                                    <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label>Thumbnail</label>
                                            <input type="hidden" name="image" value="<?= $data['thumbnail'] ?>">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="thumbnail" class="custom-file-input" id="inputGroupFile04">
                                                    <label class="custom-file-label" for="inputGroupFile04">Choose Image</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" required placeholder="Enter Description here"><?= $data['description'] ?></textarea>
                                        </div>
                                        <div class="col">
                                            <label>Keywords</label>
                                            <textarea class="form-control" name="keywords" required placeholder="Enter keywords here"><?= $data['keywords'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Price</label>
                                                    <input type="number" name="price" value="<?= $data['price'] ?>" required class="form-control" id="Price" placeholder="Enter original Price" oninput="updateFinalPrice()">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Discount (%)</label>
                                                    <input type="number" name="discount" value="<?= $data['discount'] ?>" required class="form-control" id="Discount" placeholder="Enter Discount in %" oninput="updateFinalPrice()">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Final Price</label>
                                                    <input type="text" readonly class="form-control" id="Final" placeholder="Final Price">
                                                </div>
                                            </div>
                                        </div>
                                        <small id="discountHelpBlock" class="form-text text-muted info-text">
                                            For example, if the original price is ₹100 and you want to sell it for ₹80, enter 20 as the discount (20% off).
                                        </small>
                                    </div>
                                    <button type="submit" name="updateProduct" class="btn btn-primary">Update Product</button>
                            </form>
                    <?php
                        }
                    }
                    ?>

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