<?php
ob_start();
session_start();

try {
	$db=new PDO("mysql:host=localhost;dbname=site;charset=utf8",'root','');
	 //echo "veritabanı bağlantısı başarılı";
} catch (PDOException $e) {
	echo $e->getMessage();	
}

?>