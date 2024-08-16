<?php
require_once('../vendor/autoload.php');

$clientID = "999813567577-tu8ko961670jal8pjksaimjmia3j348e.apps.googleusercontent.com";
$secret = "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";

$gclient = new Google_Client();

$gclient->setClientId($clientID);
$gclient->setClientSecret($secret);
$gclient->setRedirectUri('https://steam-games.in/auth/login.php');

$gclient->addScope('email');
$gclient->addScope('profile');?>