<?php include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_POST['upload_img'])) {
    $title = mysqli_real_escape_string($con, $_POST['name']);
    $url = mysqli_real_escape_string($con, $_POST['url']);
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $filename = $_FILES['thumbnail']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid_extensions = ['png', 'jpg', 'jpeg'];
        $name = date("YmdHis");
        if (in_array(strtolower($file_ext), $valid_extensions)) {
            $destfile = '../assets/img/banner/' . $name . '.' . $file_ext;
            $new_file = $name . '.' . $file_ext;

            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destfile);

            $query = mysqli_query(
                $con,
                "INSERT INTO `banner`(`name`,`url`,`image`) 
                VALUES ('$title','$url','$new_file')"
            );
            if ($query) {
                $message = "Banner Added!";
                header("location:banner.php?success=$message");
                exit;
            }
        } else {
            $response = "Only supported extensions are JPG, PNG, JPEG.";
            header("location:banner.php?err=$response");
            exit;
        }
    } else {
        $response = "Something went wrong while uploading image";
        header("location:banner.php?err=$response");
        exit;
    }
}
if (isset($_POST['edit_banner'])) {
    $title = mysqli_real_escape_string($con, $_POST['name']);
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $url = mysqli_real_escape_string($con, $_POST['url']);
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $filename = $_FILES['thumbnail']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid_extensions = ['png', 'jpg', 'jpeg'];
        $name = date("YmdHis");
        if (in_array(strtolower($file_ext), $valid_extensions)) {
            $destfile = '../assets/img/banner/' . $name . '.' . $file_ext;
            $new_file = $name . '.' . $file_ext;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destfile)) {
                $query = mysqli_query(
                    $con,
                    "UPDATE `banner` SET `name`='$title',`url`='$url', `image`='$new_file' WHERE `id`=$id"
                );
                if ($query) {
                    $img = "../assets/img/banner/" . $_POST['img_name'];
                    if (file_exists($img)) {
                        unlink($img);
                    }
                    $message = "Banner Updated!";
                    header("location:banner.php?success=$message");
                    exit;
                } else {
                    $response = "Failed to update the database.";
                    header("location:banner.php?err=$response");
                    exit;
                }
            } else {
                $response = "Failed to move the uploaded file.";
                header("location:banner.php?err=$response");
                exit;
            }
        } else {
            $response = "Only supported extensions are JPG, PNG, JPEG.";
            header("location:banner.php?err=$response");
            exit;
        }
    } else {
        $response = "Something went wrong while uploading image";
        header("location:banner.php?err=$response");
        exit;
    }
}

if (isset($_GET['delete_banner']) && isset($_GET['id'])) {
    $img = "../assets/img/banner/" . $_GET['img'];

    $id = $_GET['id'];

    $stmt = $con->prepare("DELETE FROM `banner` WHERE `id` = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Banner Deleted!";
        if (file_exists($img)) {
            unlink($img);
        }
        header("Location: banner.php?success=$message");
        exit;
    } else {
        $message = "Failed to delete Banner!";
        header("Location: banner.php?err=$message");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Banners - steam-games.in</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Banners</h1>
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
                    <div class="mb-4">
                        <button type="button" data-toggle="modal" data-target="#uploadBanner" class="btn btn-primary">Add New Banner</button>
                    </div>
                    <div class="form-group">
                        <small id="discountHelpBlock" class="form-text text-muted info-text">
                            Banner Sixe Must Be 1080 x 413 px
                        </small>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php
                            $result = $con->query("SELECT COUNT(*) AS `totalmultiple_images` FROM `banner`");
                            $run = $result->fetch_assoc();
                            ?>
                            <h6 class="m-0 font-weight-bold text-primary">We have <strong><?= $run['totalmultiple_images'] ?> </strong> Banners</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-secondary text-white text-capitalize">
                                        <tr>

                                            <th>name</th>
                                            <th>url</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="img_List">
                                        <?php
                                        $bannerQ = mysqli_query($con, "SELECT * FROM `banner`");
                                        if ($bannerQ) {
                                            while ($banner = mysqli_fetch_array($bannerQ)) { ?>

                                                <tr>
                                                    <td><?= htmlspecialchars($banner['name']) ?></td>
                                                    <td><?= htmlspecialchars($banner['url']) ?></td>
                                                    <td><img src="../assets/img/banner/<?= htmlspecialchars($banner['image']) ?>" class="rounded mx-auto d-block img-thumbnail" alt="..." width="150"> </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#deleteModel<?= $banner['id'] ?>" class="btn btn-danger btn-sm btn-circle">
                                                            <i class="fas fa-trash"></i>
                                                        </a>

                                                        <a href="#" data-toggle="modal" data-target="#edit<?= $banner['id'] ?>" class="btn btn-info btn-sm btn-circle">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="deleteModel<?= $banner['id'] ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Are you sure you want to delete <strong>"<?= htmlspecialchars($banner['name']) ?>"</strong> ?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" onclick="window.location.href = 'banner.php?delete_banner=true&id=<?= $banner['id'] ?>&img=<?= htmlspecialchars($banner['image']) ?>'" class="btn btn-danger">Delete Now</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal" id="edit<?= $banner['id'] ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" enctype="multipart/form-data">
                                                            <input type="hidden" value="<?= $banner['image'] ?>" name="img_name">
                                                            <input type="hidden" value="<?= $banner['id'] ?> " name="id">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit Banner</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <div class="form-group">
                                                                        <label>Name</label>
                                                                        <input type="text" name="name" value="<?= $banner['name'] ?>" required class="form-control" placeholder="Enter Title Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>URL</label>
                                                                        <input type="text" name="url" value="<?= $banner['url'] ?>" required class="form-control" placeholder="Enter URL Here">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input type="file" name="thumbnail" required class="custom-file-input" id="inputGroupFile04">
                                                                            <label class="custom-file-label" for="inputGroupFile04">Choose Image</label>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="edit_banner" class="btn btn-success">upload Now</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='3'>No Banners Available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="modal" id="uploadBanner" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Upload New Banner</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" required class="form-control" placeholder="Enter Title Here">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>URL</label>
                                                        <input type="text" name="url" required class="form-control" placeholder="Enter URL Here">
                                                    </div>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="thumbnail" required class="custom-file-input" id="inputGroupFile04">
                                                            <label class="custom-file-label" for="inputGroupFile04">Choose Image</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="upload_img" class="btn btn-success">upload Now</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>