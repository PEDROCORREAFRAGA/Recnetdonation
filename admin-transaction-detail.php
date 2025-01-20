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

if(!Helper::isParamValid($_GET['id'], 'int'))
    header('location: admin-transactions.php');
$db = new Db();
$paymentDetail = $db->fetchPayment((int)$_GET['id']);
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
            <h2><?=_('Detalhes da transação')?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?=_('Home')?></a></li>
                    <li class="breadcrumb-item"><a href="admin-area.php"><?=_('Dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="admin-transactions.php"><?=_('Transações')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=_('Detalhe')?> #<?=$paymentDetail['id']?></li>
                </ol>
            </nav>
            <?php
            $amountDisplay = number_format($paymentDetail['transaction_amount'],2,',','.');
            $donorName = $paymentDetail['donor_name'];
            $donorEmail = $paymentDetail['donor_email'];
            $dateCreated = new DateTime($paymentDetail['created_at']);
            $dateDisplay = $dateCreated->format('d/m/Y H:i:s');
            $transactionId = $paymentDetail['trid'];
            ?>
            <dl>
                <dt><?=_('Doador')?></dt>
                <dd><?=$paymentDetail['donor_name']?> - <?=$paymentDetail['donor_email']?></dd>

                <dt><?=_('Pagador')?></dt>
                <dd><?=$paymentDetail['email']?> - <?=$paymentDetail['doc_type']?>: <?=$paymentDetail['doc_number']?></dd>

                <dt><?=_('Valor da doação')?></dt>
                <dd><?=_('R$')?> <?=number_format($paymentDetail['transaction_amount'], 2,',','.')?></dd>

                <dt><?=_('Informações da transação')?></dt>
                <dd><?=$paymentDetail['payment_gateway_response']?></dd>

                <dt><?=_('Data do processamento')?></dt>
                <dd><?=$dateDisplay?></dd>
            </dl>

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