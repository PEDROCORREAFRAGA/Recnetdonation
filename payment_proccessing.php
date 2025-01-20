<?php include 'i18n_setup.php' ?>
<?php
$user = true;
if(isset($_GET['user']))
    $user = $_GET['user'];
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
        <div>
            <img src="assets/img/childfund-banner_rev_2_tinified-<?=$lang?>.jpg" class="img-fluid" alt="<?=_('Campanha de doações')?>">
        </div>
        <div class="mt-3">
            <h1><?=_('Pagamento enviado')?></h1>
            <p class="alert alert-info"><?=_('Sua doação para o ChildFund Brasil está')?> <strong><?=_('em processamento.')?></strong></p>
            <p><?=_('Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado e você receberá seu cupom.')?></p>
            <?php if($user):?>
                <p><?=_('As informações da doação foram enviadas para seu e-mail. Você pode acessar com seu login e senha para ver seu cupom e os dados da compra.')?></p>
            <?php else: ?>
                <p><?=_('Uma nova senha foi gerada para você e enviada ao seu e-mail. Verifique as informações da doação e como fazer login para ver seu cupom')?></p>
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