<?php
define('APP_BASE', true);
require "environment.php";
include 'i18n_setup.php';
require_once 'src/User.php';
require_once 'src/Api.php';
require_once 'src/Db.php';

$user = new User();
$api = new Api();

$user->setApi($api);

if(!$user->isAuth())
    header('location: login.php');

$user->fetchEntity();
$c = $user->entity->comprador;
$db = new Db();
$paymentDetail = $db->fetchPayment($_GET['payment']);
$coupomObject = $db->getCoupomByProccess($paymentDetail['id']);
$drawDate = new DateTime($coupomObject['DrawDate']);
$drawDateDisplay = $drawDate->format('d/m/Y');
$prize = json_decode($coupomObject['Prize']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=_('RecNet - Doações')?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'template/navbar.php' ?>
<div class="container">
    <div class="row mt-5 bg-light py-3">
        <div class="col-12 col-md-8">
            <h1><?=_('Área do cliente')?></h1>
        </div>
        <div class="col-12 col-md-4 text-right">
            <p><?=_('Olá,')?> <span class="text-primary"><?= $c->nome?></span> (<a id="user-logout" class="user-logout"><?=_('sair')?></a>)</p>
        </div>
    </div>
    <div class="mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?=_('Home')?></a></li>
                <li class="breadcrumb-item"><a href="user-area.php"><?=_('Pagamentos')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=_('Detalhe')?> #<?=$paymentDetail['id']?></li>
            </ol>
        </nav>
    </div>
    <?php if($paymentDetail) :
        // data for HTML (user and QR Code)
        $amountDisplay = number_format($paymentDetail['transaction_amount'],2,',','.');
        $donorName = $paymentDetail['donor_name'];
        $donorEmail = $paymentDetail['donor_email'];
        $dateCreated = new DateTime($paymentDetail['created_at']);
        $dateDisplay = $dateCreated->format('d/m/Y H:i:s');
        $transactionId = $paymentDetail['trid'];
        ?>
        <div>
            <img src="assets/img/childfund-banner_rev_2_tinified-<?=$lang?>.jpg" class="img-fluid" alt="<?=_('Campanha de doações')?>">
        </div>

        <div class="mt-3">
            <h2>Cupom</h2>
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <div class="row p-3 text-center text-sm-left bg-light border border-dark recnet-coupon">
                        <div class="col-12 col-sm-5 col-md-4">
                            <div id="qrCode" class="mb-3" data-trid="<?=$transactionId?>" data-date="<?=$dateCreated->getTimestamp()?>"></div>
                        </div>
                        <div class="col-12 col-sm-7 col-md-8">
                            <p class="mb-0"><?=_('Série e número:')?></p>
                            <p class="mb-1 recnet-text-big"><?=$coupomObject['Series']?>-<?=$coupomObject['Number']?></p>
                            <p><?=_('Doação para')?> <strong>ChildFund Brasil</strong></p>
                            <p><strong><?=_('Doador:')?></strong><br />
                                <?=$donorName?> - <?= $donorEmail ?><br />
                                <small><?=_('Data do pagamento:')?> <?=$dateDisplay?></small></p>
                        </div>
                    </div>
                    <p class="text-center"><?=_('Este cupom está concorrendo ao sorteio de')?> <strong><?=$prize->name !== "" ?_($prize->name) :_('um brinde surpresa')?></strong> <?=_('no dia')?> <?=$drawDateDisplay?></p>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h2><?=_('Detalhes do pagamento')?></h2>
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <?php $responseArray = json_decode($paymentDetail['payment_gateway_response']);?>
                    <dl>
                        <dt><?=_('Doador')?></dt>
                        <dd><?=$paymentDetail['donor_name']?> - <?=$paymentDetail['donor_email']?></dd>

                        <dt><?=_('Pagador')?></dt>
                        <dd><?=$paymentDetail['email']?> - <?=$paymentDetail['doc_type']?>: <?=$paymentDetail['doc_number']?></dd>

                        <dt><?=_('Valor doado')?></dt>
                        <dd><?=_('R$')?> <?=number_format($paymentDetail['transaction_amount'], 2,',','.')?></dd>

                        <dt><?=_('Informações da transação')?></dt>
                        <dd><?=_('trid')?> <?=$responseArray->id?> bandeira <?=$paymentDetail['payment_method_id']?> - <?=$responseArray->status?> (<?=$responseArray->status_detail?>)</dd>

                        <dt><?=_('Data do pagamento')?></dt>
                        <dd><?=$dateDisplay?></dd>
                    </dl>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-3">
            <p><?=_('Não encontramos um pagamento com esse código.')?></p>
            <p><a href="user-area.php">&laquo; <?=_('Voltar')?></a></p>
        </div>
    <?php endif ?>
</div>
<footer class="mt-5 bg-dark text-center py-5 px-2">
    <p class="text-white">&copy; <?=date('Y')?> <?=_('RecNet - Todos os direitos reservados')?></p>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="assets/js/qrcode.min.js"></script>
<script src="assets/js/app.js"></script>
<?php if($paymentDetail) echo '<script>showQrCode()</script>'?>
</html>