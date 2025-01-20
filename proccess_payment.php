<?php
define('APP_BASE', true);
require "environment.php";
if($_SERVER['REQUEST_METHOD'] !== 'POST')
    header('location: index.php');

include 'i18n_setup.php';
require_once 'src/Helper.php';
if(array_key_exists('accept-terms', $_POST) !== true || $_POST['accept-terms'] !== '1')
    header('location: index.php');

require_once APP_ENV['VENDOR_PATH'];
require_once 'src/Api.php';
require_once 'src/Mailer.php';
require_once 'src/Db.php';
require_once 'src/AppError.php';
require_once 'src/User.php';

$error = new AppError();
MercadoPago\SDK::setAccessToken(APP_ENV['MERCADOPAGO_ACCESSTOKEN']);

$donorEmail = $_POST['donorEmail'];
$donorName = $_POST['donorName'];
$getCoupon = false;
$fakePayment = false;

if ($fakePayment) {
    $payment = Helper::fakePaymentObject();
    $payer = new MercadoPago\Payer();
    $payer->email = $_POST['email'];
    $payer->identification = array(
        "type" => $_POST['docType'],
        "number" => $_POST['docNumber']
    );
    $payment->payer = $payer;
    $response = array(
        'status' => $payment->status,
        'status_detail' => $payment->status_detail,
        'id' => $payment->id
    );
} else {
    $payment = new MercadoPago\Payment();
    // @todo tratar requisição post
    $payment->transaction_amount = (float)$_POST['transactionAmount'];
    $payment->token = $_POST['token'];
    $payment->description = $_POST['description'];
    $payment->installments = 1; //(int)$_POST['installments'];
    $payment->payment_method_id = $_POST['paymentMethodId'];
    $payment->issuer_id = (int)$_POST['issuer'];

    $payer = new MercadoPago\Payer();
    $payer->email = $_POST['email'];
    $payer->identification = array(
        "type" => $_POST['docType'],
        "number" => $_POST['docNumber']
    );
    $payment->payer = $payer;
    $payment->save();
    $response = array(
        'status' => $payment->status,
        'status_detail' => $payment->status_detail,
        'id' => $payment->id
    );
}

if($payment->error){
    $error->logger->error($payment->error->status . ' - ' . $payment->error->message);
    header('location: index.php');
    exit();
}

if($payment->status !== 'approved'){
    $payment->error = $payment->status_detail;
    $error->logger->error('3000 - '.$payment->error);
}
$getCoupon = !$payment->error;

try {
    //salvar tentativa de pagamento na base
    $db = new Db();
    $savedPayment = $db->savePaymentAttempt($donorName, $donorEmail, $payer, $payment, $response);

    // Verificar conta ou criar usuário
    $api = new Api();

    $user = new User();
    $mail = new Mailer();

    $user->setApi($api);
    $user->checkUser($donorEmail);

    // registro do usuário na tabela interna
    $user->primary_key = $db->getOrInsertUser($user->user_id);

    // salvar e retornar cupom
    $coupomObject = null;

    if($getCoupon && $db->getNewCoupon(3, $user->primary_key, $savedPayment->insert_id)){

        $coupomObject = $db->getCoupomByProccess($savedPayment->insert_id);
    }

    //se usuário não for encontrado, cria a nova conta com uma senha aleatória e envia por e-mail
    if(!$user->user_exists){
        $randomNumber = str_shuffle(preg_replace('/\D/','', microtime()));
        $password = substr($randomNumber,rand(0,6),6);
        $user->createAccount($donorEmail,$password);
        $mail->prepareNewAccountEmail($donorEmail, $donorName, $password, $coupomObject);
    }
    // se usuário existe, muda objeto user para alterar conteúdo a ser exibido no HTML
    if($user->user_exists){
        $mail->preparePaymentConfirmationEmail($donorEmail, $donorName, $coupomObject);
    }

    if(APP_ENV['ENV_NAME'] === 'PRD')

        $send = $mail->send();
    else
        $error->logger->info($mail->mailContent);
}
catch(Postmark\Models\PostmarkException $e){
    $error->logger->error($e->postmarkApiErrorCode.' - '.$e->message,[$e]);
    header('location:error.php?n=8000');
}
catch(Exception $e){
    $error->logger->error($e->getCode().' - '.$e->getMessage(),[$e]);
    header('location:error.php?n=' . $e->getCode());
}

// redirecionar se o pagamento estiver pendente
if($payment->status === 'in_process'){
    header('location:payment_proccessing.php?user=' . $user->user_exists);
}

// data para HTML (user e QR Code)
$amountDisplay = number_format($payment->transaction_amount,2,',','.');
$paymentDate = new DateTime($payment->date_created);
$paymentDateDisplay = $paymentDate->format('d/m/Y H:i:s');
$drawDate = new DateTime($coupomObject['DrawDate']);
$drawDateDisplay = $drawDate->format('d/m/Y');
$coupomDate = new DateTime($coupomObject['DateCreated']);
$coupomDateDisplay = $coupomDate->format('d/m/Y H:i:s');
$prize = json_decode($coupomObject['Prize']);
$transactionId = $payment->id;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Expires" content="Wed, 2 Oct 2021 22:00:00 -0300">
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
    <?php
    if($payment->error):?>
        <div class="mt-3">
            <h1><?=_('Tente novamente!')?></h1>
            <p><?=_('Houve uma falha ao tentar realizar seu pagamento.')?></p>
            <p><?=_('Por favor')?>, <a href="javascript:history.back()"><?=_('volte e tente mais uma vez.')?></a> <?=_('Se o problema persistir, entre em contato conosco informando o erro abaixo:')?> </p>
            <p class="alert alert-warning"><?=_($payment->error)?></p>
        </div>
    <?php else: ?>
        <div class="mt-3 text-center">
            <h1><?=_('Pagamento confirmado!')?></h1>
            <p><?=_('Sua doação para ChildFund Brasil no valor de')?> <?=_('R$')?> <?= $amountDisplay?> <?=_('foi')?> <strong><?=_('concluída com sucesso.')?></strong></p>
            <p><?=_('Abaixo está seu cupom.')?></p>

        </div>

        <div class="mt-3">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <div class="row p-3 text-center text-md-left bg-light border border-dark recnet-coupon">
                        <div class="col-12 col-md-7 col-lg-8">
                            <p class="mb-0"><?=_('Série e número:')?></p>
                            <p class="mb-1 recnet-text-big"><?=$coupomObject['Series']?>-<?=$coupomObject['Number']?></p>
                            <p><?=_('Doação para')?> <strong>ChildFund Brasil</strong></p>
                            <p class="mb-1"><strong><?=_('Doador:')?></strong><br />
                                <?=$donorName?> - <?= $donorEmail ?><br />
                        </div>
                        <div class="col-12 col-md-5 col-lg-4">
                            <div id="qrCode" class="mb-3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-12 text-center">
                    <p><small><?=_('Data do pagamento:')?> <?=$paymentDateDisplay?>, <?=_('Data de geração do cupom:')?> <?=$coupomDateDisplay?></small></p></p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <?php if($user->user_exists):?>
                <p><?=_('As informações acima foram enviadas para seu e-mail.')?><br />
                    <?=_('Você pode acessar com seu login e senha para ver seu cupom e os dados da compra.')?></p>
            <?php else: ?>
                <p><?=_('Uma nova senha foi gerada para você e enviada ao seu e-mail.')?><br />
                    <?=_('Verifique as informações da compra e como fazer login para ver seu cupom')?></p>
            <?php endif ?>
        </div>
    <?php endif; ?>
</div>
<footer class="mt-5 bg-dark text-center py-5 px-2">
    <p class="text-white">&copy; <?=date('Y')?> <?=_('RecNet - Todos os direitos reservados')?></p>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="assets/js/qrcode.min.js"></script>
<script type="text/javascript">
    let qrHolder = document.getElementById("qrCode")
    let qrcode = new QRCode(qrHolder, {
        text: '<?=$coupomObject['Series'].'|'.$coupomObject['Number'].'|'.$transactionId .'|'.$drawDate->getTimestamp()?>',
        width: 145,
        height: 145,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    qrHolder.querySelector('img').classList.add('mx-auto','mr-md-0');
</script>
</html>
