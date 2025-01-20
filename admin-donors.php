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
$donors = $db->fetchAllDonors();

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
            <h2><?=_('Todos os doadores')?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?=_('Home')?></a></li>
                    <li class="breadcrumb-item"><a href="admin-area.php"><?=_('Dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=_('Doadores')?></li>
                </ol>
            </nav>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th><?=_('Nome')?></th>
                        <th><?=_('E-mail')?></th>
                        <th><?=_('Data de Criação')?></th>
                        <th><?=_('Última doação')?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // p.donor_name "Name", p.donor_email "E-mail", u.created_at "Creation Date", p.transaction_amount "Amount", p.created_at "Donation Date"
                while($row = mysqli_fetch_assoc($donors)):
                    $creationtDate = new DateTime($row['Creation Date']);
                    $creationtDateDisplay = $creationtDate->format('d/m/Y H:i:s');
                    $donationtDate = new DateTime($row['Donation Date']);
                    $donationtDateDisplay = $donationtDate->format('d/m/Y H:i:s');
                    ?>
                    <tr>
                        <td><a href="admin-donor-detail.php?id=<?= $row['Id']?>"><?= $row['Id']?></a></td>
                        <td nowrap="nowrap"><?= $row['Name']?></td>
                        <td nowrap="nowrap"><?= $row['E-mail']?></td>
                        <td nowrap="nowrap"><?= $creationtDateDisplay?></td>
                        <td>R$<?= number_format($row['Amount'], 2, ',', '.')?> <?=_('em')?> <?= $donationtDateDisplay?></td>
                    </tr>
                <?php endwhile;?>
                </tbody>
            </table>
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