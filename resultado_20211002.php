<?php include 'i18n_setup.php' ?>

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

        <div class="row">

            <div class="col-12 col-md-10 offset-md-1 text-center">

                <h1 class="my-4"><?=_('Sorteio RecNet dia das crianças 2021')?></h1>

                <img src="assets/img/brinde-kit-melia-melia.jpg" class="img-fluid" alt="<?=_('Kit mini geleias Mélia Mélia')?>">

                <p class="recnet-text-medium"><?=_('O número do cupom vencedor conforme sorteio do dia 02/10/2021 é:')?></p>

                <p class="recnet-text-bigger">88296</p>

                <p>O cupom é de uma doação da cidade de <strong>Guarulhos</strong></p>

            </div>

        </div>

        <p class="recnet-text-medium"><strong>Você também pode ganhar!</strong></p>

        <p>Acesse nossa página de doação e garanta seu cupom. A RecNet está organizando novos sorteios com parceiros e grandes marcas. Todo cupom gerado a partir de 02/10/2021 irá participar do próximo sorteio</p>

        <p><a href="index.php" class="btn btn-primary btn-lg">Quero doar agora!</a></p>

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

</html>