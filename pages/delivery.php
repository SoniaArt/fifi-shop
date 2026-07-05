<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFI — Доставка</title>

    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/footer_pages.css" rel="stylesheet">

</head>
<body>
    <?php include '../includes/menu.php';?>
    <?php include '../includes/header.php';?>

    <main class="footer-page"> 
        <h2> Доставка </h2>
        <p> Мы предоставляем нашим клиентам лучший сервис и гарантируем максимально быструю и безопасную доставку покупок в любую точку мира. </p>

        <div class="accordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Сколько стоит доставка?
                    </button>
                </h3>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                    <div class="accordion-body">
                        <ul>
                            <li>Доставка заказов по России и Беларуси -  бесплатно.</li>
                            <li>Доставка в течение дня по Москве - 499 р.</li>
                        </ul> 
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        Каким способом осуществляется доставка?
                    </button>
                </h3>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                    <div class="accordion-body">
                        <p><strong>По Росии.</strong></p>
                        <ul>
                            <li>Курьерской службой.</li>
                            <li>В пункт выдачи СДЭК и 5Post без примерки.</li>
                            <li>В пункт выдачи СДЭК с примеркой и возможностью отказа при получении.</li>
                        </ul> 
                        <p><strong>По Беларуси.</strong></p>
                        <ul>
                            <li>Курьерской службой.</li>
                            <li>В пункт выдачи СДЭК без примерки.</li>
                            <li>В пункт выдачи СДЭК с примеркой и возможностью отказа при получении.</li>
                        </ul> 
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        В какие сроки осуществляется доставка?
                    </button>
                </h3>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                    <div class="accordion-body">
                        <p>
                            Стандартный срок доставки по Москве, Московской области, Санкт-Петербургу и Ленинградской области – от 2 до 4 дней.
                        </p>
                        <p><strong>Доставка в течение дня по Москве:</strong></p>
                        <ul>
                            <li>При оформлении заказа до 16:00 – доставка в тот же день в течение 5 часов.</li>
                            <li>При оформлении заказа после 16:00 – на следующий день (с 9:00 до 13:00).</li>
                        </ul> 
                        <p><strong>Для остальных регионов России и Беларуси </strong>срок доставки может быть увеличен до 7 дней. Срок доставки может составлять до 10 дней в случае, если товар есть в наличии только на удаленном складе или если отправка осуществляется в отдаленный населенный пункт.</p>
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