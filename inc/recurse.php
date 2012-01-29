<?php
/**
 * Author: Ray Sweeten
 * 
 * This file recursively generates html files from php files within the php folder of a project, and makes all links relative.
 * 
 */
require_once('vars.php');

$search = "\\";
$replace = "/";


$find = array('http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'].'/php/', 'http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'].'/', '.php');

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir.'/php'), RecursiveIteratorIterator::SELF_FIRST);

foreach($objects as $name => $object){
	$info = pathinfo($name);
	$ext = $info['extension'];

	if(strstr($name, '.svn') == false && strstr($name, '.project')==false && strstr($name, '~')== false  && strstr($name, 'tpl')==false && strstr($name, 'includes')==false && strstr($name, 'inc')==false){
		$a = str_replace($search, $replace, $name); // replace backslashes with forward slashes (if your on windows)
		$fileName = end(explode('/', str_replace($root.'/'.$_POST['dir'].'/php/', '', $a)));
		$phpFilePath = str_replace($root.'/'.$_POST['dir'], 'http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'], $a);
		$htmlFilePath = str_replace(array('/php', '.php'), array('', '.html'), $a);
		$level = str_replace('/', '', str_replace($root.'/'.$_POST['dir'].'/php', '', $a), $count); // $count is what we're actually after in this line.
		
		if(!$ext){ //If it's a folder, and the folder does not yet exist, make that folder in the main project directory
			// echo "folder $count: $a\n"; //sanity check
			if(!file_exists($a)) mkdir($a);
		}elseif ($ext == 'php'){
			// echo "file $count: $phpFilePath -- $htmlFilePath\n"; // sanity check
			
			// Set relative path based on directory level of current file
			if($count == '1'){
				$replace2 = array('', '', '.html');
			}else{
				$replace2 = array(str_repeat("../", $count-1), str_repeat("../", $count-1), '.html');
			}
			
			//If we have previously generated an html file from a php file,
			if(file_exists($htmlFilePath)){
				//Delete that html file (deletes all html pages)
				unlink($htmlFilePath);
			}
			
			//Read php file via http, which processes all php code to html
			$php = file_get_contents($phpFilePath, 'r');
			
			// Make all paths relative
			$newphp = str_replace($find, $replace2, $php);
			
			//Opens new, empty file with name
			$html = fopen($htmlFilePath, 'w');
			
			//Dump the contents from the pre-processed php file into the target html file
			fwrite($html, $newphp);
			
			//Clear the $html placeholder for next iteration
			fclose($html);
		}
	}
}
clearstatcache();
?>