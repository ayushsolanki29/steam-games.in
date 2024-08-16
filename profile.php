<?php
include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location:auth/login.php?cb=profile");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "User Profile - Manage Your Games & Orders | Steam Games";
    $meta_desc = "Access your user profile on Steam-Games.in. Manage your game collection, track your orders, and get your transaction ID for payment tracking.";
    $meta_keywords = "user profile, manage games, track orders, Steam Games profile, game collection, transaction ID, payment tracking, Steam game orders";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    ?>
    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_desc ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $domain ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_desc ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $domain ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_desc ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $domain . "profile.php" ?>">

    <?php include 'php/pages/header.php' ?>
</head>

<body class="page-profile ">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>

            <main class="page-main">
                <div class="uk-grid" data-uk-grid>
                    <div class="uk-width">
                        <div class="widjet --profile">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Profile</h3>
                            </div>
                            <div class="widjet__body" style="overflow: hidden">
                                <div class="user-info">
                                    <div class="user-info__avatar"><img src="<?php if ($user['type'] == "email") {
                                                                                    echo 'assets/img/profile/' . $user['profile'];
                                                                                } else {
                                                                                    echo $user['profile'];
                                                                                } ?>" alt="<?= $user['username'] ?>"></div>
                                    <div class="user-info__box">
                                        <div class="user-info__title"><?= $user['username'] ?></div>
                                        <div class="user-info__text">
                                            <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8')  ?> <br>
                                            Member since <?= (new DateTime($user['created_at']))->format('m-Y') ?>
                                        </div>

                                    </div>
                                </div>
                                <!-- <a class="uk-button uk-button-danger" href="#update-profile" data-uk-toggle><i class="ico_edit"></i><span class="uk-margin-small-left">Edit Profile</span></a> -->
                            </div>
                        </div>
                        <br>

                        <div class="widjet --bio">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Total Games</h3>
                            </div>
                            <div class="widjet__body">
                                <div class="wallet-info">
                                    <?php $product_count = fetchProductCount($con, $user_id); ?>
                                    <div class="wallet-value"><?php echo $product_count; ?></div>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="widjet --activity">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">All Games</h3>
                            </div>

                            <?php
                            $user_id = $user['id'];

                            $txt = getTxtId($user_id);
                            $product_ids = fetchProductIDs($con, $user_id, 'paid');
                            // Fetch product details
                            $product_details = fetchProductDetails($con, $product_ids);

                            ?>

                            <?php if (!empty($product_details)) : ?>
                                <?php foreach ($product_details as $product) :
                                    $product_id = $product['id'];
                                    $user_data_available = false;
                                    $stmt = $con->prepare("SELECT * FROM delivery WHERE product_id=? AND user_id=? AND txt_id=?");
                                    $stmt->bind_param("iii", $product_id, $user_id, $txt['txt_id']);
                                    $stmt->execute();
                                    $delivery_result = $stmt->get_result();
                                    if ($delivery_result->num_rows > 0) {
                                        $user_datas = $delivery_result->fetch_assoc();
                                        $user_data_available = true;
                                    }
                                ?>
                                    <div class="widjet__body" >
                                        <div class="widjet-game" href="#game<?= $product['id'] . $txt['txt_id'] ?>" data-uk-toggle>
                                            <div class="widjet-game__media">
                                                <a href="#!"><img src="assets/img/products/<?= htmlspecialchars($product['thumbnail']) ?>" alt="<?= htmlspecialchars($product['title']) ?>"></a>
                                            </div>
                                            <div class="widjet-game__info">
                                                <a class="widjet-game__title" href="game_profile.php?p=<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></a>
                                                <div class="widjet-game__record">      <br>
                                                       <pre style="padding-top: 15px;"><?= $user_data_available ? $user_datas['message'] : "We are Working on it.\nPlease Wait.\nYour Order is Almost Ready" ?></pre>
                                                   </div>
                                                <div class="widjet-game__last-played"><?= $user_data_available ? "Delivered" : "Not Delivered" ?></div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-flex-top" id="game<?= $product['id'] . $txt['txt_id'] ?>" data-uk-modal>
                                        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
                                            <h2 class="uk-modal-title"><?php echo htmlspecialchars($product['title']); ?></h2>
                                            <div class="uk-margin">
                                                <div class="uk-form-label">txt_id</div>
                                                <div class="uk-margin">
                                                    <div class="search__input">
                                                        <input type="text" value="<?= $txt['txt_id'] ?>" readonly name="username">
                                                    </div>
                                                </div>
                                            </div>
                                           
                                         
                                            <div class="uk-margin">
                                                <div class="uk-form-label">Your Credentials</div>
                                                <div class="uk-margin">
                                                    <div class="search__input" style="height:auto">
                                                        <br>
                                                       <pre style="padding-top: 15px;"><?= $user_data_available ? $user_datas['message'] : "We are Working on it.\nPlease Wait.\nYour Order is Almost Ready" ?></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($product['twofactor']) { ?>
                                                <div class="uk-margin">
                                                    <div class="uk-form-label">OTP</div>
                                                    <div class="uk-margin">
                                                        <div class="search__input">
                                                            <input type="text" value="For OTP, contact us on WhatsApp or more options" readonly name=" username">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($product['twofactor']) { ?>
                                                <div class="" style="display: flex; gap: 10px; ">
                                                    <input type="hidden" id="wa_game_name" value="<?php echo htmlspecialchars($product['title']); ?>">
                                                    <input type="hidden" id="wa_txt_id" value="<?= $txt['txt_id'] ?>">
                                                    <input type="hidden" id="wa_username" value="<?= $user['username'] ?>">
                                                    <button style="background: #075E54;" class="uk-button uk-width-1-2" type="button" id="wa_button">
                                                        WhatsApp
                                                    </button>
                                                    <button style="font-size: 12px !important; " class="uk-button uk-button-danger uk-width-1-2" onclick="window.location.href = 'contact.php#other'" type="button">
                                                        More Options
                                                    </button>
                                                </div>
                                            <?php } elseif ($user_data_available) { ?>
                                                <button style="font-size: small !important" class="uk-button uk-button-danger uk-width-1-1" onclick="window.location.href = 'game_profile.php?p=<?= $product['id'] ?>#review_game'" type="button">
                                                    write a game review
                                                </button>
                                            <?php   } else { ?>
                                                <button  style="font-size: small !important" class="uk-button uk-button-danger uk-width-1-2" onclick="window.location.href = 'contact.php#other'" type="button">
                                                    Need Help ?
                                                </button>
                                            <?php      } ?>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No Games found.</p>
                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <div class="uk-flex-top" id="update-profile" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">Update Profile</h2>
            <form class="uk-form-stacked" action="#">
                <div class="uk-margin">
                    <div class="uk-form-label">Profile Image</div>

                    <div class="uk-form-controls uk-margin-small-top">
                        <div data-uk-form-custom><input name="profile" type="file"><button class="uk-button uk-button-default" type="button" tabindex="-1"><i class="ico_attach-circle"></i><span>Choose Photo</span></button></div>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="search__input">
                        <input type="text" value="<?= $user['username'] ?>" name="username" placeholder="username">
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="search__input">
                        <input type="email" value="<?= $user['email'] ?>" name="email" placeholder="email">
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="search__input">
                        <input type="password" name="password" placeholder="password">
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-grid uk-flex-right" data-uk-grid>
                        <div><button class="uk-button uk-button-small uk-button-link" style="border: 0 !important;">Cancel</button></div>
                        <div><button disabled class="uk-button uk-button-small uk-button-danger" style="border: 0 !important;">Update Profile</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#wa_button').on('click', function() {
                var phone = "+918015856968";
                //rplace space with _  in game name





                var gameName = $('#wa_game_name').val();
                gameName = gameName.replace(/\s/g, '_');
                var username = $('#wa_username').val();
                var txtId = $('#wa_txt_id').val();


                var message = `----------OTP Query For : *${gameName.toUpperCase()}*---------\n` +

                    "Hi, I'm *" + username + "*,\n\n" +
                    `I just recently purchased *${gameName}* , and my txt_id is *${txtId}.\n` +
                    "I am trying to log in with the given credentials but I need the OTP. Please provide me with the OTP.\n\n" +
                    "Thank you.";

                var url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
                window.open(url, '_blank');
            });
        });
    </script>
    <script>

    </script>
</body>

</html>