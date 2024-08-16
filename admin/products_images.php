<?php include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['uploadImages'])) {
    $product_id = $_POST['product_id'];

    if (isset($_FILES['related_images'])) {
        $files = $_FILES['related_images'];
        $valid_extensions = ['png', 'jpg', 'jpeg'];

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === 0) {
                $filename = $files['name'][$i];
                $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
                $name = date("YmdHis") . '_' . $i;

                if (in_array(strtolower($file_ext), $valid_extensions)) {
                    $destfile = '../assets/img/related_images/' . $name . '.' . $file_ext;
                    $new_file = $name . '.' . $file_ext;
                    if (move_uploaded_file($files['tmp_name'][$i], $destfile)) {
                        $query = mysqli_query($con, "INSERT INTO `multiple_images`(`product_id`, `image`) VALUES ('$product_id','$new_file')");
                        if (!$query) {
                            $response = "Failed to insert $filename into database.";
                            header("location:products_images.php?err=$response");
                            exit;
                        }
                    } else {
                        $response = "Failed to upload $filename.";
                        header("location:products_images.php?err=$response");
                        exit;
                    }
                } else {
                    $response = "Only supported extensions are JPG, PNG, JPEG.";
                    header("location:products_images.php?err=$response");
                    exit;
                }
            } else {
                $response = "Error uploading file $filename.";
                header("location:products_images.php?err=$response");
                exit;
            }
        }

        $message = "Images added to database successfully.";
        header("location:products_images.php?success=$message");
        exit;
    } else {
        $response = "No files were uploaded.";
        header("location:products_images.php?err=$response");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add New Images - steam-games.in</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Add Multiple Images</h1>
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



                            <div class="form-group">
                                <label for="exampleFormControlSelect1">choose product</label>
                                <select class="form-control" name="product_id" id="exampleFormControlSelect1">
                                    <option selected disabled>select products</option>
                                    <?php
                                    $selectQ = mysqli_query($con, "SELECT `id`, `title` FROM `products` ORDER BY `id` DESC");
                                    if ($selectQ) {
                                        while ($products = mysqli_fetch_array($selectQ)) { ?>
                                            <option value="<?= $products['id'] ?>"><?= $products['title'] ?></option>
                                    <?php }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Product Images</label>

                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" multiple class="custom-file-input" name="related_images[]" id="inputGroupFile04">
                                        <label class="custom-file-label" for="inputGroupFile04">Choose Multiple Images</label>
                                    </div>
                                </div>
                            </div>


                            <button type="submit" name="uploadImages" class="btn btn-primary">Upload Images</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>