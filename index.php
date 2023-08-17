<?php
require_once('./SantechParser.php');
require_once('./ArmsnabParser.php');
require_once('./Dbconn.php');

var_dump($_POST);
?>

<div>
    <form method="post" action="index.php">
        <input type="submit" value="Парсинг Santech" name="santech_upload">
        <input type="submit" value="Парсинг Armsnab" name="armsnab_upload">
        <div style="margin-top: 25px">
            <input type="submit" value="Выгрузить данные Santech в базу данных" name="santech">
            <input type="submit" value="Выгрузить Armsnab в базу данных" name="armsnab">
        </div>
    </form>
</div>

<?php
if(isset($_POST['santech_upload'])) {
    $parser = new SantechParser();
    $parser->getCategoriesLinks();
    $parser->getGroupLinks();
    $parser->getProductsLinks();
    $parser->getProductsInfo();
}

if(isset($_POST['armsnab_upload'])) {
    $parser = new ArmsnabParser();
    $parser->getGroupsLinks();
    $parser->getProductsInfo();
}

if(isset($_POST['santech'])) {
    Dbconn::updoadSantech();
}
if(isset($_POST['armsnab'])) {
    Dbconn::updoadArmsnab();
}