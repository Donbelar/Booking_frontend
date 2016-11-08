<?php
// PDO connect *********
//require("includes/config.php");

function connect() {
    return new PDO('mysql:host=localhost;dbname=cl50-petwa', 'root', '',
     array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}

$pdo = connect();
$keyword = '%'.$_POST['keyword'].'%';
$sql = "SELECT * FROM location_cities WHERE CityName LIKE (:keyword) ORDER BY CityId ASC LIMIT 0, 10";
//$query = $pdo->prepare($sql);
$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$list = $query->fetchAll();
foreach ($list as $rs) {
	// put in bold the written text
	$location = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['CityName']);
	// add new option
    echo '<li onclick="set_item(\''.str_replace("'", "\'", $rs['CityName']).'\')">'.$location.'</li>';
}
?>