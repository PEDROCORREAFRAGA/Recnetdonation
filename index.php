<?php
define('APP_BASE', true);
require "environment.php";
include 'i18n_setup.php';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'template/navbar.php' ?>
    <div class="container">
        <div>
            <img src="assets/img/childfund-banner_rev_2_tinified-<?=$lang?>.jpg" class="img-fluid" alt="<?=_('Campanha de doações')?>">
        </div>
        <h1 class="my-4"><?=_('Seu gesto pode salvar vidas')?></h1>

        <p class="recnet-text-medium"><?=_('Sua doação irá ajudar crianças em seu desenvolvimento e crescimento através do ChildFund Brasil.')?></p>

        <form method="POST" action="proccess_payment.php" id="paymentForm" class="recnet-form-cookie-dependent">
            <div>
                <h2><?=_('Valor da doação')?></h2>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><?=_('R$')?></div>
                    </div>
                    <input type="number" class="form-control" min="5" required name="transactionAmount" id="transactionAmount" />

                </div>            
                <small id="amountHelp" class="form-text"><?=_('Qualquer valor a partir de R$ 5')?></small>
            </div>
            <hr />
            <div class="mt-3">
                <h2><?=_('Quem está doando')?></h2>
                <div class="row">
                    <div class="col col-lg-6">
                        <label for="donorName"><?=_('Nome')?></label>
                        <input type="text" class="form-control" id="donorName" name="donorName" required />
                        <small id="nameHelp" class="form-text"><?=_('Quem doa recebe um cupom em seu nome.')?></small>
                    </div>
                    <div class="col col-lg-6">
                        <label for="donorEmail"><?=_('E-mail')?></label>
                        <input type="email" class="form-control" id="donorEmail" name="donorEmail" required />
                        <small id="emailHelp" class="form-text"><?=_('O e-mail serve para criar sua conta e validar a entrega do seu cupom. Não precisa ser o mesmo do pagador')?></small>
                    </div>
                </div>
            </div>
            <hr />
            <div class="mt-3">
                <h2><?=_('Informações do pagador')?></h2>
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="email"><?=_('E-mail')?></label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    <div class="col col-lg-4">
                        <label for="docType"><?=_('Tipo de documento')?></label>
                        <select class="form-control" id="docType" name="docType" data-checkout="docType"></select>
                    </div>
                    <div class="col col-lg-8">
                        <label for="docNumber"><?=_('Número do documento')?></label>
                        <input class="form-control" id="docNumber" name="docNumber" data-checkout="docNumber" type="number" data-targetmsg="#docNumberAlert" />
                        <small id="docNumberAlert" class="recnet-input-error alert alert-danger d-none"></small>
                        <small id="docNumberHelp" class="form-text"><?=_('Informe apenas números')?></small>
                    </div>
                </div>
            </div>
            <hr />
            <div class="mt-3">
                <h2><?=_('Detalhes do cartão')?></h2>
                <div class="row">
                    <div class="col-12 col-lg-6 form-group">
                        <label for="cardholderName"><?=_('Titular do cartão')?></label>
                        <input class="form-control" id="cardholderName" data-checkout="cardholderName" type="text" data-targetmsg="#cardholderNameAlert">
                        <small id="cardholderNameAlert" class="recnet-input-error alert alert-danger d-none"></small>
                    </div>

                    <div class="col-12 col-lg-6 form-group">
                        <label for="cardNumber"><?=_('Número do cartão')?></label>
                         <input class="form-control" type="text" id="cardNumber" data-checkout="cardNumber" data-targetmsg="#cardNumberAlert"
                            onselectstart="return false" onpaste="return false"
                            oncopy="return false" oncut="return false"
                            ondrag="return false" ondrop="return false" autocomplete=off>
                        <small id="cardNumberAlert" class="recnet-input-error alert alert-danger d-none"></small>
                        <small id="cardNumberHelp" class="form-text"><?=_('Informe apenas números')?></small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-6 col-md-3">
                        <label for=""><?=_('Data de vencimento')?></label>
                        <div class="form-group form-row">
                            <div class="col-5">
                                <input class="form-control" type="text" placeholder="<?=_('MM')?>" id="cardExpirationMonth" data-checkout="cardExpirationMonth" data-targetmsg="#cardExpirationMonthAlert"
                                onselectstart="return false" onpaste="return false"
                                oncopy="return false" oncut="return false"
                                ondrag="return false" ondrop="return false" autocomplete=off>

                            </div>
                            <div class="col text-center">
                                <span class="date-separator">/</span>
                            </div>
                            <div class="col-5">
                                <input class="form-control" type="text" placeholder="<?=_('AA')?>" id="cardExpirationYear" data-checkout="cardExpirationYear" data-targetmsg="#cardExpirationYearAlert"
                                onselectstart="return false" onpaste="return false"
                                oncopy="return false" oncut="return false"
                                ondrag="return false" ondrop="return false" autocomplete=off>

                            </div>
                        </div>
                        <p><small id="cardExpirationMonthAlert" class="recnet-input-error alert alert-danger d-none"></small></p>
                        <p class="mt-2"><small id="cardExpirationYearAlert" class="recnet-input-error alert alert-danger d-none"></small></p>
                    </div>
                    <div class="form-group col-6 col-md-3">
                        <label for="securityCode"><?=_('Código de segurança')?></label>
                        <input class="form-control" id="securityCode" data-checkout="securityCode" type="text" data-targetmsg="#securityCodeAlert"
                        onselectstart="return false" onpaste="return false"
                        oncopy="return false" oncut="return false"
                        ondrag="return false" ondrop="return false" autocomplete=off>
                        <small id="securityCodeAlert" class="recnet-input-error alert alert-danger d-none"></small>
                    </div>
                    <div class="col-12 col-md-6">
                        <p class="alert alert-info"><strong><?=_('Seus dados estão seguros.')?></strong> <?=_('Toda informação do seu cartão será processada e criptografada. Não armazenamos seu número de cartão.')?></p>
                    </div>
                </div>

                <input type="hidden" name="issuer" id="issuer" data-targetmsg="#issuerAlert" />
                <small id="issuerAlert" class="recnet-input-error alert alert-danger d-none"></small>
                <input type="hidden" name="installments" id="installments" value="1" />
                <input type="hidden" name="paymentMethodId" id="paymentMethodId" />
                <input type="hidden" name="description" id="description" />

            </div>
            <div class="my-3 alert alert-info">
                <p><?=_('Você deve ler e concordar com nossos')?> <a href="termos.php" target="_blank" title="<?=_('Este link abre uma nova aba')?>">
                    <?=_('termos de uso e política de privacidade')?> <small><i class="bi bi-box-arrow-up-right"></i></small></a> <?=_('para continuar')?>
                </p>
                <div class="form-check">
                    <input required class="form-check-input" type="checkbox" value="1" id="accept-terms" name="accept-terms">
                    <label class="form-check-label" for="accept-terms">
                        <strong><?=_('Li e aceito os termos de uso e política de privacidade da RecNet')?></strong>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?=_('Continuar')?></button>
        </form>
    </div>
    <?php include 'template/cookies-notification.php'?>
    <footer class="mt-5 bg-dark text-center py-5 px-2">
        <p class="text-white">&copy; <?=date('Y')?> <?=_('RecNet - Todos os direitos reservados')?></p>
    </footer>
    <?php include 'template/modal-alert-cookies-accept.php'?>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/mercadopago-<?=$lang?>.js"></script>
<script>
    <?php
        echo 'window.Mercadopago.setPublishableKey("' . APP_ENV['MERCADOPAGO_PUBLICKEY'] . '");';
        echo 'cookies_path="' . APP_ENV['APPLICATION_URL'] . '"';
    ?>
</script>
<?php $test = $_SERVER['HTTP_HOST'] === 'localhost';
if($test){?>
    <script>
        document.querySelector("#transactionAmount").value = 5;
        document.querySelector("#donorName").value = 'TESTE';
        document.querySelector("#donorEmail").value = 'teste@teste.com';
        document.querySelector("#email").value = 'teste@teste.com';
        document.querySelector("#docNumber").value = '38485503007';
        document.querySelector("#cardholderName").value = 'APRO';
        document.querySelector("#cardNumber").value = 5031433215406351;
        document.querySelector("#cardExpirationMonth").value = 11;
        document.querySelector("#cardExpirationYear").value = 25;
        document.querySelector("#securityCode").value = 123;
    </script>
<?php }?>
</html>