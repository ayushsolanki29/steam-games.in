<?php

include 'php/configs/db.php';
include 'php/configs/functions.php';
session_start();
$_SESSION['error'] = [];


if (!isset($_SESSION['user'])) {
    $_SESSION['error']['msg'] = "You need to login to for See Your Cart!";
    header("Location:auth/login.php?cb=cart");
    exit();
}
$user_id = $_SESSION['user'];
$cart_total = 0;
$Taxes_q = mysqli_query($con, "SELECT `data1` FROM `settings` WHERE `id` = 3");
$Taxes_data = mysqli_fetch_array($Taxes_q);
$taxes = $Taxes_data['data1'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $meta_title = "Cart - Steam Games";
    $meta_desc = "Manage your shopping cart and proceed to payment securely on Steam-Games.in. Explore various payment gateways for a smooth checkout experience.";
    $meta_keywords = "shopping cart, Steam Games cart, payment gateways, checkout, manage cart, secure payment";
    $meta_img = $domain . "assets/img/og-img.png"; // Using the single image for all pages
    $url = $domain . "cart.php"; // URL of the cart page
    ?>

    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="description" content=<?= $meta_desc ?>>
    <meta name="keywords" content=<?= $meta_keywords ?>>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $url ?>">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="<?= $meta_desc ?>">
    <meta property="og:image" content="<?= $meta_img ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $url ?>">
    <meta property="twitter:title" content="<?= $meta_title ?>">
    <meta property="twitter:description" content=<?= $meta_desc ?>>
    <meta property="twitter:image" content="<?= $meta_img ?>">
    <link rel="canonical" href="<?= $url ?>">

    <?php include 'php/pages/header.php' ?>
    <style>
        .loading_payment {
            position: fixed;
            display: block;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            text-align: center;
            background: #1a263491;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .empty-cart {
            width: 100%;
            max-width: 400px;

            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin: 0 auto;
        }

        .empty-cart .cart-img {
            max-width: 80%;
            max-height: 80%;
        }

        .empty-cart h2 {
            margin-top: 10px;
            font-size: 1.5em;
            color: #555;
        }

        .empty-cart button {
            background-color: #f46119;
            color: #fff;
            border: none;

            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .empty-cart button:hover {
            background-color: #f47119;
        }

        /* Cart Bottom (Total and Promo) Styles */
        .cart-bottom {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .cart-promo {
            float: left;
        }

        .cart-total,
        .cart-promo {
            flex: 1;

            border-radius: 5px;
            padding: 20px;
        }

        .cart-total h3,
        .cart-promo p {
            font-size: 1.2em;
            color: #333;
        }

        .cart-total-details {
            display: flex;
            justify-content: space-between;

            color: #555;
        }

        .cart-total-details p {
            margin: 0;
        }

        .cart-total-details b {
            font-weight: bold;
        }

        .cart-total hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        body.dark-theme .cart-promo-input {
            background-color: #1a2634;
        }

        body.dark-theme p {
            color: white;
        }

        .cart-promo-input {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #eaeaea;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }

        body.dark-theme .cart-promo-input input {
            background: #1a2634;
            color: white;
        }

        .cart-promo-input input {
            flex: 1;
            padding: 8px;
            border: none;
            background-color: transparent;
        }

        .cart-promo-input button {
            background-color: #f46119;
            color: #fff;
            border: none;
            padding: 10px 10px;
            border-radius: 5px;
            text-wrap: nowrap;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart-promo-input button:disabled {
            cursor: not-allowed;
            opacity: .4;
            background-color: #f47119;
        }

        .cart-promo-input button:hover {
            background-color: #f47119;
        }

        /* Checkout Button */
        .checkout-btn {
            background-color: #28a745;
            float: right;
            gap: 5px;
            display: flex;
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            align-items: center;
            cursor: pointer;
            font-size: 1.2em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .checkout-btn p {
            margin: 0 !important;
        }

        .checkout-btn:disabled {
            cursor: not-allowed;
            opacity: .4;
            background-color: #28a741;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        #proceed:disabled {
            opacity: .8;
            cursor: not-allowed;

        }

        @media only screen and (max-width: 767px) {
            .cart-bottom {
                flex-direction: column;
            }

            .checkout-btn {
                width: 100%;
            }

            #apply_copoun {
                margin-left: -35px;
            }
        }



        .bubble {
            text-align: center;
            justify-content: center;
            display: flex;
            cursor: pointer;

        }

        .bubble strong {
            color: #f47119;
            font-weight: 600;
        }

        .bubble .send {
            max-width: auto;
            word-wrap: break-word;
            margin-bottom: 12px;
            position: relative;
            padding: 7px 10px;
            border-radius: 25px;
            color: black;
            margin-top: 10px;
            font-size: 15px;
            font-weight: 300;

            background: #0B93F6;
            color: white
        }
    </style>
</head>

<body class="page-profile">

    <div class="page-wrapper">
        <?php include 'php/pages/topbar.php' ?>

        <div class="page-content">

            <?php include 'php/pages/navbar.php' ?>
            <main class="page-main">
                <div class="loading_payment">
                    <div class="ui-abstergo">
                        <div class="abstergo-loader">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="ui-text">
                            Just a moment...
                            <div class="ui-dot"></div>
                            <div class="ui-dot"></div>
                            <div class="ui-dot"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid>
                    <div class="uk-width">
                        <div class="widjet --filters">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Hello! <?= $user['username'] ?> your Cart is here</h3>
                            </div>

                        </div>
                        <?php
                        $cart_query = mysqli_query($con, "SELECT * FROM `cart` WHERE `user_id`= '$user_id'");
                        if (mysqli_num_rows($cart_query) > 0) {
                            while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                                $product_info = getProductData($fetch_cart['product_id']);
                                $category_info = getCategory($product_info['category_id']);
                        ?>
                                <div class="game-card --horizontal favourites-game">
                                    <div class="game-card__box">
                                        <div class="game-card__media"><a href="game_profile.php?s=onsale&p=<?= $product_info['id'] ?>"><img src="assets/img/products/<?= $product_info['thumbnail'] ?>" alt="<?= $product_info['title'] ?>" /></a></div>
                                        <div class="game-card__info"><a class="game-card__title" href="game_profile.php?s=onsale&p=<?= $product_info['id'] ?>"> <?= $product_info['title'] ?></a>
                                            <div class="game-card__genre"><?= $category_info['category_name'] ?></div>
                                            <div class="game-card__rating-and-price">
                                                <div class="game-card__rating"><span><span> <?= $product_info['discount'] ?>%</span> off</div>
                                            </div>
                                            <div class="game-card__bottom">
                                                <div class="game-card__price">
                                                    <span style="color:green; font-size:large;">₹<?= number_format($product_info['price'] * (1 - $product_info['discount'] / 100), 0) ?></span>
                                                    <span>₹ <del><?= $product_info['price'] ?> </del></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="game-card__more" onclick="window.location.href='php/configs/actions.php?loggeduser&removefromcart&product_id&token=<?= $fetch_cart['id'] ?>'">
                                            <div class="friend-requests-item__action"><button class="reject ico_trash" type="button"></button></div>
                                        </div>
                                        <div uk-dropdown="mode: click">
                                            <ul class="uk-nav uk-dropdown-nav">
                                                <li><a href="">Remove</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $cart_total = $cart_total + number_format($product_info['price'] * (1 - $product_info['discount'] / 100), 0);
                            }
                        } else {
                            ?>
                            <div class="empty-cart">
                                <img class="cart-img" src="assets/img/empty-cart.png" alt="Empty Cart">
                                <h2>Your cart is empty</h2>
                                <button class="creat-list-btn" onclick="window.location.href = 'store.php'"><span>explore store</span></button>
                            </div>
                        <?php }
                        ?>
                    </div>
                    <?php
                    if ($cart_total != "0") { ?>
                        <div class="uk-width">
                            <div class="cart-bottom">
                                <div class="cart-promo">
                                    <div class="uk-margin">
                                        <p class="error" style="color:white; display:none;">Invalid code</p>
                                    </div>
                                    <p>If you have a promo code, enter it here</p>
                                    <div class='cart-promo-input'>
                                        <input type="text" id="coupoun_code_input" placeholder="Enter Promo Code" />
                                        <button id="apply_copoun">Apply Code</button>
                                    </div>
                                    <div class="coupon_codes">
                                        <?php
                                        $get_c_q = mysqli_query($con, "SELECT `name`,`value` FROM `coupon_codes` ORDER BY RAND() LIMIT 1");
                                        while ($fetch_c = mysqli_fetch_assoc($get_c_q)) {
                                        ?>
                                            <div class="bubble">
                                                <p class="send">use <strong><?= strtoupper($fetch_c['name']) ?></strong> for <?= $fetch_c['value'] ?>% discount</p>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <!-- Cart Total Section -->
                                <div class="cart-total">
                                    <h3 class="game-card__title">Total</h3>
                                    <?php
                                    $grand_total = totalAmount($cart_total, $taxes, 0);
                                    ?>
                                    <div>
                                        <div class="cart-total-details">
                                            <p>Subtotal</p>
                                            <p>₹<?= number_format($cart_total, 0) ?></p>
                                        </div>
                                        <hr />
                                        <div class="cart-total-details discount" style="color: green;">
                                            <p>Discount</p>
                                            <p></p>
                                            <p>0%</p>
                                        </div>

                                        <hr />
                                        <div class="cart-total-details">
                                            <p>other taxes*</p>
                                            <p>₹<?= $taxes ?></p>
                                        </div>
                                        <hr />
                                        <div class="cart-total-details total">
                                            <b>Total</b>
                                            <p style="color: red; display: none;">Before discount :₹<?= $grand_total ?></p>
                                            <b style="color: green;"><span id="total">₹<?= $grand_total ?></span></b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="cart_id" value="hgvasduJHG_jv21_jaf">
                            <input type="hidden" id="coupon_code" value="">
                            <button class="checkout-btn" id="rzp-paynow">
                                <p class="continue_checkout" style="width: 100%;">Continue to Checkout</p>
                            </button>
                        </div>

                    <?php    }
                    ?>

                </div>
            </main>
        </div>
    </div>
    <div id="paymentPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <div id="firstPage" class="page">
                <div class="header">
                    <h2>steam-games.in</h2>
                    <div class="trusted">
                        <img src="assets/img/secured.png" alt="Trusted Business">
                        <span>Trusted Business</span>
                    </div>
                </div>
                <div class="amount">
                    <h3>Total Amount</h3>
                    <p id="grand_total">₹00</p>
                </div>
                <div class="uk-margin">
                    <p class="error" style="color:white; display:block;">Invalid code</p>
                </div>
                <div class="contact-details">
                    <label for="upi">Contact Details : <?= $user['username'] ?></label>
                    <br>
                    <div class="phone-input">
                        <input type="text" id="upi" placeholder="UPI ID" value="<?= isset($_COOKIE['upi_id']) ? $_COOKIE['upi_id'] : "" ?>">
                    </div>
                    <div class="phone-input">
                        <input type="text" id="phone" placeholder="Phone Number" value="<?= isset($_COOKIE['phone_number']) ? $_COOKIE['phone_number'] : "" ?>">
                    </div>
                </div>
                <button id="proceed">Continue</button>
                <div class="secured">
                    <img src="assets/img/ss.png" alt="Secured by Razorpay">
                </div>
            </div>

            <div id="secondPage" class="page" style="display: none;">
                <p style="    padding: 10px;
    background-color: darkgreen;
    border-radius: 6px;
    font-size: 14px;">use this UPI : <span id="merchant" style="font-weight: bolder;">loading..</span> and amount <span style="font-weight: bolder;" id="qr_amount">calulating...</span> if QR or Button Not Works</p>
                <div class="header">
                    <h2>steam-games.in</h2>
                    <div class="trusted">
                        <img src="assets/img/secured.png" alt="Trusted Business">
                        <span>Trusted Business</span>
                    </div>
                </div>
                <div class="payment-method">
                    <h3>Pay With UPI QR</h3>
                    <div class="qr-section">
                        <img src="assets/img/qr.gif" alt="<?= $grand_total ?>" class="qr-code" id="qr_code_img">
                        <div class="qr-info">
                            <p>Scan the QR using any UPI app on your phone.</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="upi-id-section">
                    <h3>use Direct App</h3>
                </div>
                <button id="payNowBtn"></button>
                <p style="font-size: 14px;text-align: center;"> complete Payment under <span class="qr-time-starts"><span class="qr-time">000</span>seconds</span></p>
            </div>

        </div>
    </div>

    <?php include 'php/pages/footer.php' ?>
    <script>
        $(document).ready(function() {
            let coupon_code = "";

            let grand_total = <?= $grand_total; ?>;
            let messageElement = $(".uk-margin .error");
            let cart_id = $("#cart_id").val();
            $(".bubble").click(function() {
                let bubble = $(this);
                let couponName = bubble.find("strong").text().trim();
                $("#coupoun_code_input").val(couponName);
                bubble.find("p").text("Applied!");

                setTimeout(function() {
                    bubble.hide();
                }, 2000);
            });

            $(".error").hide();

            $("#apply_copoun").click(function() {
                $("#apply_copoun").attr('disabled', 'disabled');
                let input_copoun = $("#coupoun_code_input").val().trim();
                if (input_copoun == "") {
                    messageElement.text("Please Enter Code").css("color", "white").removeClass("success").addClass("error").show();
                    $("#apply_copoun").removeAttr('disabled');
                    return false;
                }
                let total = <?= str_replace(",", "", number_format($cart_total + 20, 0)) ?>;
                $.ajax({
                    url: "php/configs/actions.php?copoun_code=find",
                    method: "POST",
                    dataType: "json",
                    data: {
                        subtotal: total,
                        copoun: input_copoun
                    },
                    success: function(response) {
                        if (response.status) {
                            messageElement.text(response.msg).css("color", "white").removeClass("error").addClass("success").show();
                            $("#total").text('₹' + response.discounted_total);
                            $(".cart-total-details.discount p").eq(1).text(response.discount + "%");
                            $(".cart-total-details.discount").show();
                            grand_total = response.discounted_total;
                            $("#coupoun_code_input").val("");
                            coupon_code = input_copoun;
                            $(".cart-total-details.discount").find("p").eq(0).text("Discount (" + input_copoun.toUpperCase() + ")");
                            $(".cart-total-details.discount").find("p").eq(2).text(response.discount + "%");
                            $(".cart-total-details.discount").find("p").eq(1).text("you just saved ₹" + response.less_amount).css("color", "green");
                            $(".cart-total-details.total").find("p").eq(0).show();
                            $("#coupon_code").val(input_copoun.toUpperCase());
                            $("#apply_copoun").removeAttr('disabled');
                        } else {
                            messageElement.text(response.msg).css("color", "white").removeClass("success").addClass("error").show();
                            $("#apply_copoun").removeAttr('disabled');
                        }
                    }
                });
            });

            $('.close').on('click', function() {
                $('#paymentPopup').fadeOut();
                $(".loading_payment").css("display", "none");
                $("#rzp-paynow").prop('disabled', false).text("Continue to checkout");
            });

            $(document).ready(function() {
                $('#rzp-paynow').click(function() {
                    $("#grand_total").text("₹ " + grand_total);
                    $(".loading_payment").css("display", "flex");
                    $('#paymentPopup').fadeIn();
                    $("#rzp-paynow").prop('disabled', true).text("Payment Processing...");
                });

                $('#proceed').click(function() {
                    handlePaymentProcess();
                });

                function handlePaymentProcess() {
                    isFormSubmitted = true;
                    $("#proceed").prop('disabled', true).text("Verifying...");
                    let phone = $("#phone").val().replace(/\s/g, '');
                    let upi = $("#upi").val().replace(/\s/g, '');

                    $.post('php/configs/actions.php?txt_start=true', {
                        cart_id: cart_id
                    }, function(start_response) {
                        var start_res = JSON.parse(start_response);
                        if (start_res.success) {
                            storeSessionData(phone, upi);
                            proceedPayment(start_res.txt_id, phone, upi);
                        } else {
                            $("#proceed").prop('disabled', false).text("Continue");
                        }
                    });
                }

                function proceedPayment(txt_id, phone, upi) {
                    $.post('php/configs/start_payment.php?add_contact_info=true', {
                        phone_number: phone,
                        upi_id: upi
                    }, function(contact_response) {
                        var contact_res = JSON.parse(contact_response);
                        if (contact_res.success) {
                            displayMessage(contact_res.message, "success");
                            $.post('php/configs/start_payment.php?add_payment_info=true', {
                                cart_id: cart_id,
                                txt_id: txt_id,
                                upi_id: contact_res.upi_id,
                                phone_number: contact_res.phone_number,
                                coupon_code: coupon_code,
                                amount: grand_total
                            }, function(payment_response) {
                                handlePaymentResponse(payment_response, txt_id);
                            });
                        } else {
                            $("#proceed").prop('disabled', false).text("Continue");
                            displayMessage(contact_res.message, "error");
                        }
                    });
                }

                function handlePaymentResponse(payment_response, txt_id) {
                    var payment_res = JSON.parse(payment_response);
                    if (!payment_res) {
                        alert("Failed to start payment");
                        window.location.href = "payment.php?success=false&message=Failed to start payment";
                    } else {
                        const merchantId = encodeURIComponent(payment_res.merchant_id);
                        const companyName = encodeURIComponent(payment_res.company_name);
                        const amount = grand_total;
                        const transactionId = txt_id.toLowerCase();
                        const pay_URL = `${payment_res.domain}payment.php?check=${transactionId}&txid=${transactionId}`;
                        const URL = `upi://pay?pa=${merchantId}&pn=${companyName}&tn=${transactionId}&am=${amount}&cu=INR&url=${pay_URL}`;

                        displayQRCode(URL, amount, payment_res.merchant_id);
                        handleQRPayment(URL, transactionId);
                    }
                }

                function displayQRCode(URL, amount, merchantId) {
                    $("#qr_amount").text("₹" + amount);
                    $("#merchant").text(merchantId);
                    $("#qr_code_img").attr("src", `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(URL)}`);
                }

                function handleQRPayment(URL, txt_id) {
                    let seconds = 100;
                    const timer = setInterval(() => {
                        seconds--;
                        $(".qr-time").text(`${seconds} `);
                        if (seconds < 0) {
                            clearInterval(timer);
                            window.location.href = `payment.php?success=true&txid=${txt_id}`;
                        }
                    }, 1000);

                    $("#payNowBtn").on('click', function() {
                        var initialLocation = window.location.href;
                        window.location.href = URL;
                        setTimeout(function() {
                            if (window.location.href === initialLocation) {
                                alert('Unable to open UPI payment. Please ensure you have a UPI app installed and try again on your mobile device.');
                            }
                        }, 1000);
                    });

                    $('#firstPage').hide();
                    $('#secondPage').show();
                }

                function displayMessage(message, type) {
                    const messageElement = $("#messageElement");
                    messageElement.text(message).css("color", "white").removeClass("error success").addClass(type).show();
                }  

                function storeSessionData( phone, upi) {
                    localStorage.setItem('phone', phone);
                    localStorage.setItem('upi', upi);
                }
            });

        });
    </script>
</body>

</html>