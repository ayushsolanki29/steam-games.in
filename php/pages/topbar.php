<?php
if (isset($_COOKIE['rememberme'])) {
    $token = mysqli_real_escape_string($con, $_COOKIE['rememberme']);
    $query = "SELECT * FROM rememberme_tokens WHERE token = '$token' AND expiration > NOW()";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $row['user_id'];
    } else {
        echo "<script>alert('Login Session is ended Please Login Again')</script>";
        setcookie("rememberme", "", time() - 3600, "/");

        echo "<script>window.location.href = 'auth/logout.php';</script>";
    }
}

if (isset($_SESSION['user'])) {
    $user = getUserById($_SESSION['user']);
}
?>

<header class="page-header">
    <div class="page-header__inner">
        <div class="page-header__sidebar">
            <div class="page-header__menu-btn"><button class="menu-btn ico_menu is-active"></button></div>
            <div onclick="window.location.href='index.php'" class="page-header__logo">
                <img src="assets/img/logo-white.png" alt="logo" id="logo_head">
            </div>
        </div>
        <div class="page-header__content">
            <div class="page-header__search">
                <div class="search">
                    <div class="search__input"><i class="ico_search"></i><input type="search" name="search" class="search-input" autocomplete="off" placeholder="Search"></div>
                    <div class="search__btn"></div>
                </div>
                <div class="autocomplete-suggestions"></div>
            </div>

            <div onclick="window.location.href='index.php'" class="page-header__logo mobile_logo" style="display: none;">
                <img src="assets/img/logo-white.png" alt="logo" id="logo_head_m">
            </div>

            <?php

            $user_id = isset($user['id']) ? $user['id'] : "0";

            // Fetch the cart item count from the database
            $result = mysqli_query($con, "SELECT COUNT(*) AS `totalCartItems` FROM `cart` WHERE `user_id` = '$user_id'");
            $cartItems = mysqli_fetch_assoc($result);

            // Set the item count to zero if there are no items
            $totalCartItems = $cartItems ? (int)$cartItems['totalCartItems'] : 0;
            ?>
            <div class="page-header__action"><?php
                                                if (isset($_SESSION['user'])) { ?>
                    <a class="action-btn" href="cart.php">
                        <i class="ico_shopping-cart"></i>
                        <?php if ($totalCartItems > 0) : ?>
                            <span class="cart-count"><?= $totalCartItems ?></span>
                        <?php endif; ?>
                    </a>

                <?php } ?>
                <a class="action-btn mobile_search" data-uk-toggle href="#search-mobile"><i class="ico_search"></i></a>
                <?php
                if (isset($_SESSION['user'])) { ?>
                    <a class="profile" class="uk-subnav uk-subnav-pill" uk-margin href="#!"><img src="<?php if ($user['type'] == "email") {
                                                                                                            echo 'assets/img/profile/' . $user['profile'];
                                                                                                        } else {
                                                                                                            echo $user['profile'];
                                                                                                        } ?>" alt="<?= $user['username'] ?>" alt="profile"></a>
                <?php } else { ?>
                    <a class="action-btn" href="auth/login.php"><i class="ico_ fa-solid fa-right-to-bracket"></i></a>
                <?php }
                if (isset($_SESSION['user'])) { ?>


                    <div uk-dropdown="mode: click">
                        <ul class="uk-nav uk-dropdown-nav">
                            <li><a href="profile.php">My account</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="auth/logout.php">Log Out</a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>