<?php
include 'i18n_setup.php';
require_once 'src/Helper.php';
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
    <div class="row mt-5">
        <div class="col-8 offset-2 col-sm-6 offset-sm-3">
            <?php
            if (Helper::isParamValid($_GET['reset'],'int') && (int)$_GET['reset'] === 1) : ?>
            <p class="text-center"><strong><?=_('Verifique seu e-mail!')?></strong><br /><?=_('Enviamos uma nova senha para você acessar seus cupons.')?></p>
            <?php else: ?>
            <p><strong><?=_('Informe seu e-mail para receber uma nova senha')?></strong></p>
            <form method="post" action="user-reset-password.php" class="p-3 bg-light">
                <div class="form-group">
                    <label for="email"><?=_('E-mail')?></label>
                    <input type="email" class="form-control" id="email" name="email" required="">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?=_('Enviar')?></button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<footer class="mt-5 bg-dark text-center py-5 px-2">
    <p class="text-white">&copy; <?=date('Y')?> <?=_('RecNet - Todos os direitos reservados')?></p>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</html>