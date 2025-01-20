<?php
define('APP_BASE', true);
require "environment.php";
include 'i18n_setup.php';
require_once 'src/Helper.php';
require_once 'src/User.php';
require_once 'src/Api.php';

$user = new User();
$api = new Api();
// @todo parametrizar token

$user->setApi($api);

if(Helper::isPost(['email', 'password'])) {
    $user->attemptLogin($_POST['email'], $_POST['password']);
    if($user->loginAndRedirect){
        $location = 'user-area.php';
        if($user->seller)
            $location = 'admin-area.php';
        header('location: '.$location);
    }
}
if($user->isAuth())
    header('location: user-area.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=_('RecNet - Doações')?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'template/navbar.php' ?>
<div class="container">
    <div class="row mt-5">
        <div class="col-8 offset-2 col-sm-6 offset-sm-3">
            <p><strong><?=_('Faça login para continuar')?></strong></p>
            <form method="post" action="login.php" class="p-3 bg-light recnet-form-cookie-dependent">
                <div class="form-group">
                    <label for="email"><?=_('E-mail')?></label>
                    <input type="email" class="form-control" id="email" name="email" required="">
                </div>
                <div class="form-group">
                    <label for="password"><?=_('Senha')?></label>
                    <input type="password" class="form-control" id="password" name="password" required="">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?=_('Entrar')?></button>
                </div>
            </form>
            <p><a href="user-forgot-password.php"><?=_('esqueci minha senha')?></a></p>
        </div>
    </div>
</div>
<?php include 'template/cookies-notification.php'?>
<footer class="mt-5 bg-dark text-center py-5 px-2">
    <p class="text-white">&copy; <?=date('Y')?> <?=_('RecNet - Todos os direitos reservados')?></p>
</footer>
<?php include 'template/modal-alert-cookies-accept.php'?>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="assets/js/app.js"></script>
<script>
    <?php
    echo 'cookies_path="' . APP_ENV['APPLICATION_URL'] . '"';
    ?>
</script>
</html>