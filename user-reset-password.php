<?php
define('APP_BASE', true);
require "environment.php";
require_once 'src/Helper.php';
require_once 'src/Api.php';
require_once 'src/User.php';

$user = new User();
$api = new Api();
// @todo parametrizar token

$user->setApi($api);

if(Helper::isPost(['email'])) {
    $resetPassword = $user->resetPassword($_POST['email']);
    if($resetPassword->successo === '1'){
        header('location: user-forgot-password.php?reset=1');
    }
}