<?php
define('APP_BASE', true);
require "environment.php";
include 'i18n_setup.php';
require_once 'src/User.php';
require_once 'src/Api.php';
require_once 'src/Db.php';
require_once 'src/Helper.php';

$user = new User();
$api = new Api();

$user->setApi($api);

if(!$user->isAuth())
    header('location: login.php');

$user->fetchEntity();
$v = $user->entity->vendedor;

if($v->razao === null)
    header('location: index.php');

$db = new Db();
$transactions = $db->dashboardFetchTransactions();
$coupons = $db->dashboardFetchCoupons();
$sumTransactions = 0;
while($row = mysqli_fetch_assoc($transactions)){
    $sumTransactions += $row['transaction_amount'];
}
$countCouponOwners = [];
while($row = mysqli_fetch_assoc($coupons)){
    $countCouponOwners[] = $row['owner_id'];
}
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
<?php include 'template/navbar-admin.php' ?>
<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-3 border-right">
            <?php include 'template/admin-inner-menu.php'; ?>
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col col-sm-5 offset-sm-1 col-md-4 offset-md-1">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center recnet-text-medium"><strong><?=_('Total de transações')?></strong></p>
                            <p class="row">
                                <span class="col-4 recnet-text-bigger text-right"><?php echo $transactions->num_rows?></span>
                                <span class="col-8">
                                    <?=_('R$')?> <?php echo number_format($sumTransactions, 2, ',', '.')?><br />
                                    <small><?=_('últimos 7 dias')?></small>
                                </span>
                            </p>
                            <p class="m-0 p-0 text-right"><a href="admin-transactions.php"><?=_('ver mais')?></a></p>
                        </div>
                    </div>
                </div>
                <div class="col col-sm-5 offset-sm-1 col-md-4 offset-md-1">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center recnet-text-medium"><strong><?=_('Total de cupons')?></strong></p>
                            <p class="row">
                                <span class="col-4 recnet-text-bigger text-right"><?php echo $coupons->num_rows?></span>
                                <span class="col-8">
                                    <strong><?php echo count(array_unique($countCouponOwners))?></strong> <?=_('doadores')?><br />
                                    <small><?=_('últimos 7 dias')?></small>
                                </span>
                            </p>
                            <p class="m-0 p-0 text-right"><a href="admin-coupons.php"><?=_('ver mais')?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<footer class="mt-5 bg-dark text-center py-5 px-2">
    <p class="text-white">&copy; <?=date('Y')?> <?=_('RecNet - Todos os direitos reservados')?></p>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="assets/js/app.js"></script>
</html>