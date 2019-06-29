<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "На странице представлена информация по условиям и способах вовзрата и обмена товара.");
$APPLICATION->SetPageProperty("title", "Условия возврата товаров в интернет-магазине Sexfancy");
$APPLICATION->SetTitle("Условия возврата");
?>
    <div class="text_block">
        <p>Несмотря на то, что бракованные товары составляют менее одного процента от всего объема продаж, мы считаем необходимым проверять работоспособность каждой модели, чтобы исключить вероятность покупки бракованного товара. Перед отправкой покупателю все товары визуально (без вскрытия упаковки) проверяются администратором на наличие брака.</p>
        <p>Если все же Вы получили товар, производственный дефект которого очевиден, мы готовы обменять его на идентичную модель в течение недели после того, как Вам доставили заказ. Для этого просьба не позднее 7 дней с даты получения заказа сообщить нам по е-мейл <a href="mailto:<?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_TEMPLATE_PATH."/inc/areas/mail.php",
                    "EDIT_TEMPLATE" => ""
                ),
                false
            );?>"><?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_TEMPLATE_PATH."/inc/areas/mail.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?></a> о бракованном товаре, с максимально подробным описанием дефекта, который Вы обнаружили. Возврат бракованных товаров производится за счет магазина.</p>
        <p>Практически все товары, представленные в нашем интернет-каталоге, предназначены для интимного использования, поэтому, согласно постановлению Правительства РФ от 19.01.1998 N 55, возврату и обмену не подлежат.</p>
        <p>При формировании заказов мы не заменяем без одобрения заказчика один товар другим. Замена без уведомления возможна только в том случае, если заменитель другого цвета или в иной упаковке, чем товар на фото в каталоге. Т.е. замена производится на товар, абсолютно идентичный по функциональным возможностям, составу и характеристикам.</p>
    </div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>