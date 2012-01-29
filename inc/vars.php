<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$uri = $_SERVER['SERVER_HOST'];
$ip = $_SERVER['SERVER_HOST'];
$dir = $root.'/'.$_POST['dir'];
$http = $_SERVER['SERVER_HOST'].'/' . $_POST['dir']. '/php';
//$type = array($_POST['type']);
$type = array($_POST['filetype']);
$contents = scandir($dir);
$parentDir = scandir($root);
$fileArray = array();
?>