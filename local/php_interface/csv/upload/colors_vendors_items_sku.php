<?
//$_SERVER["DOCUMENT_ROOT"] = '/var/www/u0593169/data/www/sexfancy.ru';
///local/php_interface/csv/upload/colors_vendors_items_sku.php
$_SERVER["DOCUMENT_ROOT"] = '/home/s/sexfancy/public_html';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$file_name['colors'] = 'http://feed.p5s.ru/data/5d0ddc4ee8da58.99975948?colors';
//$file_name['vendors'] = 'http://feed.p5s.ru/data/5d0ddc4ee8da58.99975948?vendors';
$file_name['sku'] = 'http://feed.p5s.ru/data/5d0ddc4ee8da58.99975948?stock';
$file_name['items'] = 'http://feed.p5s.ru/data/5d0ddc4ee8da58.99975948';

foreach($file_name as $name => $path){

    file_put_contents($_SERVER["DOCUMENT_ROOT"].'/upload/'.$name.'.csv', file_get_contents($path));
    echo 'File '.$name.' upload.<br>';
}