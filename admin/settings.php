<?php
include "../php/configs/db.php";
session_start();
if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 'true') {
    header("Location:login.php");
    exit();
}
if (isset($_GET['value'])) {
    $value = $_GET['value'];
    $stmt = $con->prepare("UPDATE `settings` SET `data1`=? WHERE `id`='1'");
    if ($stmt) {
        $stmt->bind_param("s", $value);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: settings.php");
        } else {
            header("Location: settings.php?err=failed to change");
        }

        $stmt->close();
    } else {
        echo "Failed to prepare statement: " . $con->error;
    }
    exit;
}
if (isset($_POST['coupun_set_value'])) {
    $value = $_POST['coupun_id'];
    $day_name = $_POST['day_name'];
    $run = mysqli_query($con, "UPDATE `settings` SET `data2`='$value',`data3`='$day_name' WHERE `id`='1'");
    if ($run) {
        header("Location: settings.php?success=banner activated!");
    } else {
        header("Location: settings.php?err=failed to change");
    }
}
if (isset($_POST['update_Taxes_data'])) {
    $tax_value = $_POST['tax_value'];

    $run = mysqli_query($con, "UPDATE `settings` SET `data1`='$tax_value' WHERE `id`='3'");
    if ($run) {
        header("Location: settings.php?success=tax updated!");
    } else {
        header("Location: settings.php?err=failed to change");
    }
}
if (isset($_POST['update_upi'])) {
    $upi_value = $_POST['upi_value'];

    $run = mysqli_query($con, "UPDATE `settings` SET `data1`='$upi_value' WHERE `id`='5'");
    if ($run) {
        header("Location: settings.php?success=UPI updated!");
    } else {
        header("Location: settings.php?err=failed to update");
    }
}
if (isset($_POST['update_users_info'])) {

    $Username = mysqli_real_escape_string($con, $_POST['Username']);
    $Display = mysqli_real_escape_string($con, $_POST['Display']);
    $Username = mysqli_real_escape_string($con, $_POST['Username']);
    $profile_org = mysqli_real_escape_string($con, $_POST['profile_org']);
    $pass_org = mysqli_real_escape_string($con, $_POST['pass_org']);
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $filename = $_FILES['thumbnail']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $valid_extensions = ['png', 'jpg', 'jpeg'];
        $name = date("YmdHis");
        if (in_array(strtolower($file_ext), $valid_extensions)) {
            $destfile = '../assets/img/profile/' . $name . '.' . $file_ext;
            $profile_org = $name . '.' . $file_ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destfile);
        } else {
            $response = "Only supported extensions are JPG, PNG, JPEG.";
            header("location:settings.php?err=$response");
            exit;
        }
    }
    if (isset($_POST['Passowrd'])) {
        $pass_org = password_hash($_POST['Passowrd'], PASSWORD_BCRYPT);
    }
    $query = mysqli_query($con, "UPDATE `settings` SET `data`='$Username',`data1`='$pass_org',`data2`='$Display',`data3`='$profile_org' WHERE `id`='4'");
    if ($query) {
        $message = "Profile Updated!";
        header("location:settings.php?success=$message");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>settings - steam-games.in</title>
    <?php include 'php/pages/head.php'; ?>
</head>

<body id="page-top">

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div id="wrapper">
        <?php include 'php/pages/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <?php include 'php/pages/nav.php'; ?>
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Settings</h1>

                    <?php if (isset($_GET['err'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($_GET['err'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group" id="profile">
                        <label for="couponSelect">Admin Profile</label>
                        <?php
                        $Profile = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = 4");
                        $Profile_data = mysqli_fetch_array($Profile);
                        ?>
                        <div class="d-flex gap-2 align-items-center">
                            <form class="form-inline" method="post" enctype="multipart/form-data">
                                <img src="../assets/img/profile/<?= $Profile_data['data3'] ?>" alt="Logo" class="team-logo rounded-circle" width="25"> &nbsp;
                                <input type="text" class="form-control mr-2" name="Username" placeholder="Username" value="<?= $Profile_data['data'] ?>">
                                <input type="text" class="form-control mr-2" name="Display" placeholder="Display Name" value="<?= $Profile_data['data2'] ?>">
                                <input type="Passowrd" class="form-control mr-2" name="Passowrd" placeholder="Passowrd">
                                <input type="hidden" name="profile_org" value="<?= $Profile_data['data3'] ?>">
                                <input type="hidden" name="pass_org" value="<?= $Profile_data['data1'] ?>">
                                <input type="file" class="form-control mr-2" name="thumbnail">
                                <button type="submit" name="update_users_info" class="btn btn-primary btn-md">Update Profile</button>
                            </form>
                        </div>
                        <small>Display Name is Show in Public </small>
                    </div>
                    <hr>
                    <?php
                    $coupon_q = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = 1");
                    $coupon_data = mysqli_fetch_array($coupon_q);
                    ?>

                    <div class="form-group">
                        <label for="couponSelect">Coupon Code Banner</label>
                        <div class="d-flex gap-2 align-items-center">

                            <?php if ($coupon_data['data1'] == "active") : ?>
                                <a href="settings.php?value=deactive" class="btn btn-danger btn-md mr-2">Deactivate</a>
                            <?php else : ?>
                                <a href="settings.php?value=active" class="btn btn-success btn-md mr-2">Activate</a>
                            <?php endif; ?>

                            <?php if ($coupon_data['data1'] == "active") : ?>
                                <form class="form-inline" method="post">
                                    <select class="form-control mr-2" name="coupun_id">
                                        <option value="">Choose coupon</option>
                                        <?php $select_cuopon = mysqli_query($con, "SELECT `name`,`id` FROM `coupon_codes`");
                                        while ($options = mysqli_fetch_array($select_cuopon)) {
                                        ?>
                                            <option <?= $coupon_data['data2'] == $options['id'] ? "selected" : ""; ?> value="<?= $options['id'] ?>"><?= $options['name'] ?></option>
                                        <?php  }
                                        ?>
                                    </select>
                                    <input type="text" class="form-control mr-2" name="day_name" placeholder="Enter Name" value="<?= $coupon_data['data3'] ?>">
                                    <button type="submit" name="coupun_set_value" class="btn btn-info btn-md">Set Value</button>
                                </form>
                            <?php else : ?>
                                <span>Banner is Disabled!</span>
                            <?php endif; ?>

                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="couponSelect">Manages Taxes</label>
                        <?php
                        $Taxes_q = mysqli_query($con, "SELECT `id`,`data1` FROM `settings` WHERE `id` = 3");
                        $Taxes_data = mysqli_fetch_array($Taxes_q);
                        ?>
                        <div class="d-flex gap-2 align-items-center">
                            <form class="form-inline" method="post">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">₹</div>
                                    </div>
                                    <input type="text" class="form-control  mr-2" placeholder="Enter Tax" name="tax_value" value="<?= $Taxes_data['data1'] ?>">
                                </div>
                                <button type="submit" name="update_Taxes_data" class="btn btn-primary btn-md">Update Tax</button>
                            </form>
                        </div>
                        <small>taxes is in Rupees </small>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="couponSelect">UPI ID</label>
                        <?php
                        $upi_1 = mysqli_query($con, "SELECT `id`,`data1` FROM `settings` WHERE `id` = 5");
                        $upi_id = mysqli_fetch_assoc($upi_1);
                        ?>
                        <div class="d-flex gap-2 align-items-center">
                            <form class="form-inline" method="post">
                                <div class="input-group mb-2 mr-sm-2">
                                                                    <input type="text" class="form-control  mr-2" placeholder="Enter UPI" name="upi_value" value="<?= $upi_id['data1'] ?>">
                                </div>
                                <button type="submit" name="update_upi" class="btn btn-primary btn-md">Update UPI</button>
                            </form>
                        </div>
                        <small>UPI ID for Payment Gateway</small>
                    </div>
                </div>
            </div>

            <?php include 'php/pages/footer.php' ?>