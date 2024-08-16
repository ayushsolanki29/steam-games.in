<?php
// $user = "steamgam_steamgam";
// $pass = ";89s6#7tvnCXGB";
// $host = "localhost";
// $db_name = "steamgam_main";
// $domain = "https://steam-games.in/";

$user = "root";
$pass = "";
$host = "localhost";
$db_name = "games";
$domain = "http://localhost/steamgames/";

$con = mysqli_connect($host, $user, $pass, $db_name);
if (!$con) {
    echo "db connection faild";
}

if (!isset($_COOKIE['steamgames-viewed'])) {
    // Update database and set cookie
    $sql = "UPDATE settings SET `data1` = `data1` + 1 WHERE id = 2";
    $con->query($sql);

    setcookie('steamgames-viewed', true, time() + (7 * 24 * 60 * 60));
}
