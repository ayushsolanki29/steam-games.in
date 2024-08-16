<?php
function asset_path($path)
{
    $script_name = basename($_SERVER['PHP_SELF']);
    $auth_pages = ['login.php', 'register.php', 'forget-password.php', 'change_password.php'];

    if (in_array($script_name, $auth_pages)) {
        return '../' . $path;
    } else {
        return $path;
    }
}
?>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="IE=edge" http-equiv="X-UA-Compatible">


<link rel="stylesheet" href="<?php echo asset_path('assets/css/libs.min.css'); ?>">
<link rel="stylesheet" href="<?php echo asset_path('assets/css/main.css'); ?>">

<link rel="apple-touch-icon" sizes="180x180" href="<?= asset_path("assets/img/favicons/apple-touch-icon.png") ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= asset_path("assets/img/favicons/favicon-32x32.png") ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= asset_path("assets/img/favicons/favicon-16x16.png") ?>">
<link rel="mask-icon" href="<?= asset_path("assets/img/favicons/safari-pinned-tab.svg") ?>" color="#5bbad5">
<meta name="msapplication-TileColor" content="#2b5797">
<meta name="theme-color" content="#f46119">

<link rel="manifest" href="manifest.json">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/bb1d7c6423.js" crossorigin="anonymous"></script>