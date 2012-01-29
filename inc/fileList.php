<?php
require_once('vars.php');

header('Content-type: application/json');

$search = "\\";
$replace = "/";

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);

foreach($objects as $name => $object){
	$info = pathinfo($name);
	$ext = $info['extension'];
	//echo "$ext\n";
	if(strstr($name, '.svn') == false && strstr($name, '.project')==false && strstr($name, '~')== false  && strstr($name, 'tpl')==false && strstr($name, 'includes')==false && strstr($name, 'inc')==false&& strstr($name, 'php')==false){
		$a = str_replace($search, $replace, $name); // replace backslashes with forward slashes (if your on windows)
		$fileName = str_replace('/', ' / ', str_replace($root.'/'.$_POST['dir'], '', $a));
		$justFileName = end(explode('/', str_replace($root.'/'.$_POST['dir'], '', $a)));
		$filePath = str_replace($root.'/'.$_POST['dir'], 'http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'], $a);
		$filePath = str_replace($root.'/'.$_POST['dir'], 'http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'], $a);

		if ($ext == 'html'){
			$fileArray[] = array('type' => 'html', 'path' => $filePath, 'file' => substr($fileName, 2, strlen($fileName)));
		}
		if ($ext == 'zip'){
			$fileArray[] = array('type' => 'zip', 'path' => $filePath, 'file' => $justFileName);
		}
	}
}
echo stripslashes(json_encode($fileArray));
?>