<?php
include 'i18n_setup.php';
include 'src/Helper.php';
if(!Helper::isParamValid($_GET['n'],'int'))
    header('location:index.php');

switch ((int)$_GET['n']){
    case 22:
        $errorMsg = _('E-mail inválido para criação da conta');
        break;
    case 28:
        $errorMsg = _('Senha inválida para criação da conta');
        break;
    case 56:
        $errorMsg = _('Usuário já existe');
        break;
    case 1045:
        $errorMsg = _('Erro na conexão com banco de dados.');
        break;
    case 1048:
        $errorMsg = _('Nem todos os campos obrigatórios foram informados');
        break;
    case 2031:
        $errorMsg = _('Falha ao enviar requisição para o banco de dados.');
        break;
    case 8000:
        $errorMsg = _('Não foi possível enviar o e-mail de confirmação');
        break;
    case 9000:
        $errorMsg = _('Endpoint não informado');
        break;
    case 9001:
        $errorMsg = _('Falha na execução da API');
        break;
    case 9002:
        $errorMsg = _('Não foi possível validar o usuário');
        break;
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
        <div class="alert alert-danger my-5">
            <p class="recnet-text-medium"><?=_('Houve um erro com sua solicitação')?></p>
            <p><em><?=$errorMsg?></em></p>
            <p><a href="javascript:history.back()" class="btn btn-secondary">voltar</a></p>
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