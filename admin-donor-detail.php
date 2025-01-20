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
    header('location: admin-donors.php');
$db = new Db();
$donor = $db->fetchDonorInfo((int)$_GET['id']);
if(!$donor)
    header('location: admin-donors.php');
$coupons = $db->fetchDonorCoupons($donor['Id']);
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
            <h2><?=_('Doador')?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?=_('Home')?></a></li>
                    <li class="breadcrumb-item"><a href="admin-area.php"><?=_('Dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="admin-donors.php"><?=_('Doadores')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=_('Detalhe')?> #<?=$donor['Id']?></li>
                </ol>
            </nav>
            <?php
            $dateCreated = new DateTime($donor['DateCreated']);
            $dateCreatedDisplay = $dateCreated->format('d/m/Y H:i:s');
            ?>
            <dl>
                <dt><?=_('Nome')?></dt>
                <dd><?=$donor['Name']?></dd>

                <dt><?=_('E-mail')?></dt>
                <dd><?=$donor['E-mail']?></dd>

                <dt><?=_('Criado em')?></dt>
                <dd><?=$dateCreatedDisplay?></dd>
            </dl>
            <?php if($coupons !==null && count($coupons)>0) :?>
            <p><strong><?=_('Cupons')?></strong></p>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th><?=_('Valor doado')?></th>
                    <th><?=_('Série')?></th>
                    <th><?=_('Número')?></th>
                    <th><?=_('Data do sorteio')?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($coupons as $coupon):
                    $drawDate = new DateTime($coupon['DrawDate']);
                    $drawDateDisplay = $drawDate->format('d/m/Y H:i:s');
                    ?>
                    <tr>
                        <td><?= $coupon['Id']?></td>
                        <td><?= $coupon['Amount']?></td>
                        <td><?= $coupon['Series']?></td>
                        <td><?= $coupon['Number']?></td>
                        <td><?= $drawDateDisplay?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php endif;?>
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