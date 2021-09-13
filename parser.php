<?php
require __DIR__ . '/db.php';

require __DIR__ . '/phpQuery/phpQuery/phpQuery.php';

$db = new db();


function parseEngine($db,$url)
{
    $html = file_get_contents("https://avtomarket.ru".$url);
    $dom= phpQuery::newDocument($html);
    foreach($dom->find("#centercol5")->find('ul')->find('li') as $key => $value)   // Парсим марки машин
    {
        $pq = pq($value);
        if(trim(explode(':',$pq->find('div')->text())[0] )== "Тип двигателя")
        {
            $arr["engineType"] =trim($pq->find('div')->next()->text());
        }
        if(trim(explode(':',$pq->find('div')->text())[0]) == "Марка топлива")
        {
            $arr["FuelGrade"] = trim($pq->find('div')->next()->text());
        }
        if(trim(explode(':',$pq->find('div')->text())[0]) == "Объем двигателя, куб. см.")
        {
            $arr["EngineVolume"] = trim($pq->find('div')->next()->text());
        }
        if(trim(explode(':',$pq->find('div')->text())[0]) == "Мощность, л.с.")
        {
            $arr["Power"]  = trim($pq->find('div')->next()->text());
        }
    }
    $db->engine($arr);

}

function getInfo($model,$cusov,$url,$db)
{
    $html = file_get_contents("https://avtomarket.ru".$url);
    $dom= phpQuery::newDocument($html);

    foreach ($dom->find("#mod-list")->find('a') as $key =>$value)
    {
        $pq = pq($value);
        parseEngine($db,$pq->attr("href"));
        //$db->models($model,$cusov,$pq->text());

    }
}

function ParseCusovType($url,$model,$db)
{
    $html = file_get_contents("https://avtomarket.ru".$url);
    $dom= phpQuery::newDocument($html);

    foreach ($dom->find("#centercol5")->find('.grcont') as $key =>$value)
    {
        $pq = pq($value);
        getInfo($model,$pq->find('.xctr')->text(),$pq->find('a')->attr("href"),$db);
//        echo $pq->find('.groupx')->find('a')->attr("href");
//        echo "<br>";
    }
}

function ParseModel($url,$mark,$db)    //Проходится по каждой модели
{
    $html = file_get_contents("https://avtomarket.ru".$url);
    $dom= phpQuery::newDocument($html);
    foreach ($dom->find("#name-list")->find("li") as $key =>$value)
    {
        $pq = pq($value);
    //   $db->marks($mark,$pq->text());
        ParseCusovType($pq->find('a')->attr('href'),$pq->text(),$db);
    }
}



$html = file_get_contents("https://avtomarket.ru/catalog/");

$dom = phpQuery::newDocument($html);

foreach($dom->find("#centercol5")->find("li") as $key => $value)   // Парсим марки машин
{
    $pq = pq($value);
    ParseModel($pq->find("a")->attr("href"),$pq->text(),$db); //Вызываем функцию, отправляем марку, href , и объект соединения
//    print_r($pq->find("a")->attr("href")); //дебаг
//    echo "<br>";
}

