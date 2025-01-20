<?php
define('APP_BASE', true);
require "environment.php";
include 'i18n_setup.php';
require_once APP_ENV['VENDOR_PATH'];
require_once 'src/User.php';
require_once 'src/Api.php';
require_once 'src/Db.php';
require_once 'src/AppError.php';

$user = new User();
$api = new Api();

$user->setApi($api);

if(!$user->isAuth())
    header('location: login.php');

$user->fetchEntity();
$c = $user->entity->comprador;
try {
    $db = new Db();
    $paymentsList = $db->fetchDonorEmailPayments($user->user_id);
} catch (Exception $e){
    $error = new AppError();
    $error->logger->error($e->getMessage());
    header('location:error.php?n=' . $e->getCode());
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
                <li class="breadcrumb-item active" aria-current="page"><?=_('Pagamentos')?></li>
            </ol>
        </nav>
    </div>
    <div class="mt-3">
        <p><?=_('Aqui você pode consultar seus pagamentos e os respectivos cupons')?></p>
    </div>
    <div class="mt-3">
        <?php if(count($paymentsList) === 0):?>
            <p><strong><?=_('Nenhum registro encontrado...')?></strong> <?=_('Volte após efetuar um pagamento!')?></p>
        <?php else: ?>
            <table class="table table-striped mx-auto">
                <thead>
                <tr>
                    <th><?=_('Data')?></th>
                    <th><?=_('Valor doado')?></th>
                    <th><?=_('Situação')?></th>
                    <th><?=_('Cupom')?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($paymentsList as $p):
                        $date = new DateTime($p['created_at']);
                        $dateDisplay = $date->format('d/m/Y H:i:s');
                        $responseArray = json_decode($p['payment_gateway_response']);
                        switch ($responseArray->status){
                            case 'approved':
                                $responseDisplay = _('Aprovado');
                                break;
                            case 'rejected':
                                $responseDisplay = _('Negado');
                                break;
                            case 'in_proccess':
                                $responseDisplay = _('Pendente');
                                break;
                            default:
                                $responseDisplay = _('Desconhecido');
                        }
                        ?>
                        <tr>
                            <td><?=$dateDisplay?></td>
                            <td><?=_('R$')?> <?=number_format($p['transaction_amount'], 2,',','.')?></td>
                            <td><?=$responseDisplay?></td>
                            <td><a href="user-payment.php?payment=<?=$p['id']?>"><?=_('Clique para ver')?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif ?>
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