<?php
$url = 'https://dsn.com.ua/export/yandex_yml4_group24.xml';
$xml = simplexml_load_file($url);
$host = '192.168.33.102:/var/lib/firebird/3.0/data/Sklad.tcb';
$username = 'SYSDBA';
$password = 'masterkey';

$dbh = ibase_connect($host, $username, $password, "WIN1251") or die ("error in db connect");
// var_dump($xml->shop->categories);

function utf8win1251($val)
{
    return iconv('utf-8', 'windows-1251//IGNORE', trim(mb_convert_encoding($val, 'utf-8', 'utf-8')));
}

$result = ibase_query($dbh, "INSERT INTO TIP (NAME, GRUPA, VISIBLE, SKLAD_ID) VALUES (?, ?, ?, ?) RETURNING NUM",
    utf8win1251("DSN"), 0, 1, "1,")
or die(ibase_errmsg());
$id = ibase_fetch_object($result);
$idDsnGroup = $id->NUM;
$tipIds = array();
foreach ($xml->shop->categories->category as $category) {
    $categoryName = trim(mb_convert_encoding($category, 'UTF-8', 'UTF-8'));
    $result = ibase_query($dbh, "INSERT INTO TIP (NAME, GRUPA, VISIBLE, SKLAD_ID) VALUES (?, ?, ?, ?) RETURNING NUM",
        utf8win1251($categoryName),
        $idDsnGroup, 1, "1,")
    or die(ibase_errmsg());
    $id = ibase_fetch_object($result);
    $tipIds[(string)$category->attributes()['id']] = $id->NUM;
}
foreach ($xml->shop->offers->offer as $offer) {
    $offerName = trim(mb_convert_encoding($offer->name, 'utf-8', 'utf-8'));
    $result = ibase_query($dbh, "INSERT INTO TOVAR_NAME (NAME, ED_IZM, TIP, CENA, CENA_CURR_ID, CENA_OUT_CURR_ID, VISIBLE, TOV_SCANCODE, TOV_PROIZV, DOPOLN1)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING NUM",
        utf8win1251($offerName),
        utf8win1251('шт'),
        $tipIds[(string)$offer->categoryId],
        $offer->price,
        0,
        0,
        1,
        utf8win1251($offer->vendorCode),
        utf8win1251($offer->vendor, 'utf-8', 'utf-8'),
        utf8win1251($offer->model, 'utf-8', 'utf-8')
    )
    or die(ibase_errmsg());

    $id = ibase_fetch_object($result);
    $tovarId = $id->NUM;
    ibase_query("INSERT INTO TOVAR_ZAL (FIRMA_ID, TOVAR_ID,SKLAD_ID, KOLVO) VALUES (?, ?, ?, ?)", 1, $tovarId, 1, $offer->stock_quantity) or die(ibase_errmsg());
}