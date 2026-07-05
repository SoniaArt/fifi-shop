<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFI — Возврат и обмен</title>

    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/footer_pages.css" rel="stylesheet">

</head>
<body>
    <?php include '../includes/menu.php';?>
    <?php include '../includes/header.php';?>
    
    <div class="forms" id="returnForm">
        <div class="d-flex justify-content-between align-items-center gap-4">
            <h3>Заявление на возврат</h3>
            <button class="form-close" id="returnFormClose">
                <img src="/FIFI/images/cross_icon.png" alt="Крестик" class="close-icon">
            </button>
        </div>
        <p>
            Нам важно, чтобы покупки в FIFI приносили только положительные эмоции. 
            Если товар не подошел, вы можете оформить возврат, заполнив эту форму. 
            Это займет всего несколько минут.           
        </p>

        
        <form class="d-flex flex-column justify-content-between gap-4">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Имя" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Фамилия" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" placeholder="Мобильный номер" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Номер заказа" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Артикул товара" required>
            </div>  
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Причина возврата" required>
            </div>
            
            <button type="submit" class="btn btn-dark ">
                ОТПРАВИТЬ ЗАПРОС
            </button>
        </form>
    </div>   

    <main class="footer-page"> 
        <h2>Возврат и обмен</h2>
        <p>Вы можете вернуть или обменять товар, не подошедший по каким-либо причинам (фасон, размер, цвет) в течение 7 календарных дней с момента получения заказа (п. 4 ст. 26.1 Закона о защите прав потребителей). Возврат товара возможен только в случае, если указанный товар не был в употреблении, сохранен его первоначальный товарный вид, потребительские свойства, оригинальные этикетки и документы, подтверждающие покупку. </p>

        <div class="accordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Как оформить возврат или обмен?
                    </button>
                </h3>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                    <div class="accordion-body">
                        <p>Оформить ВОЗВРАТ или ОБМЕН можно следующим способом:</p>
                        <ol>
                            <li>Заполнить 
                                <a href="#" class="form-link" id="returnFormOpen">электронную форму</a>
                                 и ожидать звонка оператора для получения накладной на бесплатный возврат посылки.</li>
                            <li>После получения письма с подтверждением заполнить отрывной бланк, полученный вместе с заказом, и вложить его в отправляемую посылку.</li>
                            <li>Обратиться в пункт службы доставки — СДЭК, 5Post или Почты России — для отправки товара на адрес распределительного центра. При отправке Почтой России потребуется приобрести упаковочный пакет. Обратите внимание, что распечатывать возвратную накладную не требуется, достаточно назвать сотруднику ее номер.</li>
                            <li>После поступления посылки на склад FIFI нашими сотрудниками будет произведена проверка товарного вида изделий. При подтверждении сохранности внешнего вида будет выполнена одна из процедур ниже:</li>
                            <ul type="disc">
                                <li>В случае, если был оформлен ВОЗВРАТ — запрос на возмещение денежных средств будет передан в банк.</li>
                                <li>В случае, если был оформлен ОБМЕН — вам будет отправлен товар, выбранный на замену возвращаемому.</li>
                            </ul> 
                        </ol> 
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        Если вы выбрали способ доставки С ПРИМЕРКОЙ в пункте выдачи СДЭК, то вы можете отказаться от неподошедших товаров на месте.
                    </button>
                </h3>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                    <div class="accordion-body">
                        <ul>
                            <li>Верните товары сотруднику ПВЗ для оформления накладной на возврат.</li>
                            <li>Посылка поступит на склад FIFI и после проверки товара нашими сотрудниками будет отправлен запрос в банк на возвращение денежных средств.</li>
                        </ul> 
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        Кто оплачивает стоимость доставки в случае возврата или обмена?
                    </button>
                </h3>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                    <div class="accordion-body">
                        <p>
                            Компания полностью берет на себя расходы за доставку до склада и за доставку обменного заказа обратно. 
                        </p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                        В какой срок осуществляется возврат денежных средств?
                    </button>
                </h3>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour">
                    <div class="accordion-body">
                        <p>
                            Распоряжение о перечислении денежных средств за возвращенный товар будет передано в банк-эквайер в течение 3 дней с момента его поступления на центральный склад.                      
                        </p>
                        <p>
                            Срок зачисления денежных средств зависит от банка-эмитента, выпустившего карту, и может составлять до 10 дней.
                        </p>
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