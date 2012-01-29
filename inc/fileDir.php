<?php
require_once('vars.php');

if($contents){
	foreach($contents as $item){
		$fileinfo = pathinfo($item);
		$ext = $fileinfo['extension'];
		if(strstr($ext, '~') == false && $fileinfo['extension'] == 'html'){
			$fileArray[] = array('host' => $uri,  'path' => $_POST['dir'], 'file' => $item);
		}
	}
	if($fileArray){
		$return = array();
		foreach($fileArray as $filename){
			$return[] = array('host' => $uri, 'path' => $_POST['dir'], 'file'=>$filename);
		}
		echo json_encode($fileArray);
	}
}
?>