<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFI — Оплата</title>

    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/footer_pages.css" rel="stylesheet">

</head>
<body>
    <?php include '../includes/menu.php';?>
    <?php include '../includes/header.php';?>
    
    <main class="footer-page">
        <h2>Оплата</h2>
        <p>Оформляя заказ на нашем сайте, вы можете осуществить оплату с помощью следующих сервисов:</p>
        <ul>
            <li>Яндекс Пэй (банковская карта, СБП);</li>
            <li>SberPay;</li>
            <li>ЮКасса.</li>
        </ul>
        <div class="accordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Оплата картой
                    </button>
                </h3>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                    <div class="accordion-body">
                        <p>Для оплаты банковской картой вам необходимо выбрать способ оплаты: «Яндекс Пэй» либо «ЮКасса». Далее вы будете перенаправлены на платежный шлюз, где сможете указать реквизиты карты. Соединение с платежным шлюзом и передача информации осуществляется в защищенном режиме с использованием протокола шифрования SSL.

Если банк-эмитент вашей карты поддерживает технологию безопасного проведения интернет-платежей, будьте готовы указать специальный пароль, необходимый для успешной оплаты. Способы и возможность получения пароля для совершения интернет-платежа вы можете уточнить в банке, выпустившем карту.</p>
                        <p>Реквизиты, необходимые для оплаты:</p>
                        <ul>
                            <li>номер карты;</li>
                            <li>срок действия карты;</li>
                            <li>имя владельца карты;</li>
                            <li>CVV2 или CVC2 код карты (указан на обратной стороне банковской карты).</li>
                        </ul> 
                        <p>
                            Для оплаты СБП вы будете перенаправлены на приложение своего банка, где будет необходимо подтвердить операцию либо отсканировать из него сформированный QR-код.
                        </p>
                        <p>Для оплаты SberPay вы будете перенаправлены на приложение Сбербанка, где будет необходимо подтвердить операцию.</p>
                    </div>
                </div>
            </div>
        </div>

        <p>Если у вас остались вопросы или возникли проблемы при оформлении заказа, пожалуйста, свяжитесь с нами. Мы всегда рады вам помочь!</p>
        <a href="mailto:support@fifi.com" class="support-link">support@fifi.com</a>
    </main>

    <?php include '../includes/footer.php';?>
</body>
</html>