<?php
require_once('vars.php');
//This rewrites all html files in a directory from the php pre-processor
//
$phpFiles = $root.'/'.$_POST['dir'].'/php';
$phpFilesDir = scandir($phpFiles);

$find = array('http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'].'/php/', 'http://'.$_SERVER['HTTP_HOST'].'/'.$_POST['dir'].'/', '.php');
$replace = array('', '', '.html');

header('Content-type: application/json');

if(!is_writable($dir)){
	echo '0';
}else{
	echo 'top-level: ';
	foreach($phpFilesDir as $item){
		$fileinfo = pathinfo($item);
		//First, Look for php files in the dir ($contents)
		if(array_key_exists('extension', $fileinfo) && $fileinfo['extension'] == 'php'){
			//the php filename becomes the target html filename
			$targetHtmPage = str_ireplace(".php", ".html", $item);
			//If we have previously generated an html file from a php file,
			if(file_exists($dir.'/'.$targetHtmPage)){
				//Delete that html file (deletes all html pages)
				unlink($dir.'/'.$targetHtmPage);
			}
			//Read php file from $contents via http, which processes all php tags to html
			$php = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/' . $_POST['dir']. '/php' .'/'. $item, 'r');
			$newphp = str_replace($find, $replace, $php);

			//Opens new, empty file with name
			$html = fopen($dir.'/'.$targetHtmPage, 'w');
			//Dump the contents from the pre-processed php file into the target html file
			fwrite($html, $newphp);
			//Clear the $html placeholder for next iteration
			fclose($html);
	
			echo  "{$targetHtmPage}, ";
		}
		
		// For sub-folders w/o .noflats file (for any folder you want the flattener to ignore, make an empty .noflats file in that parent folder)
		if(!array_key_exists('extension', $fileinfo) && $fileinfo['extension'] != 'svn'){
			if(!file_exists($dir.'/php/'.$item.'/.noflats')){
				if(!file_exists($dir.'/'.$item)) mkdir($dir.'/'.$item);
				$subcontents = scandir($phpFiles.'/'.$item);
				$replace = array('../', '../', '.html');
				echo 'second-level: ';
				foreach($subcontents as $subitem){
					$subfileinfo = pathinfo($subitem);
					
					if(array_key_exists('extension', $subfileinfo) && $subfileinfo['extension'] == 'php'){
						//the php filename becomes the target html filename
						$targetHtmPage = str_ireplace(".php", ".html", $subitem);
						//If we have previously generated an html file from a php file,
						if(file_exists($dir.'/'.$item.'/'.$targetHtmPage)){
							//Delete that html file (deletes all html pages)
							unlink($dir.'/'.$item.'/'.$targetHtmPage);
						}
						//Read php file from $contents via http, which processes all php tags to html
						$php = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/' . $_POST['dir']. '/php' .'/'.$item.'/'. $subitem, 'r');
						$newphp = str_replace($find, $replace, $php);
						//Opens new, empty file with name
						$html = fopen($dir.'/'.$item.'/'.$targetHtmPage, 'w');
						//Dump the contents from the pre-processed php file into the target html file
						fwrite($html, $newphp);
						//Clear the $html placeholder for next iteration
						fclose($html);
					
						echo  $item.'/'.$targetHtmPage.', ';
						}
					
					if(!array_key_exists('extension', $subfileinfo) && $subfileinfo['extension'] != 'svn'){
						if(!file_exists($dir.'/php/'.$item.'/'.$subitem.'/.noflats')){
							
							if(!file_exists($dir.'/'.$item.'/'.$subitem)) mkdir($dir.'/'.$item.'/'.$subitem);
							$subsubcontents = scandir($phpFiles.'/'.$item.'/'.$subitem);
							$replace = array('../../', '../../', '.html');
							echo 'third-level: ';
							foreach($subsubcontents as $subsubitem){
								$subsubfileinfo = pathinfo($subsubitem);
								if(array_key_exists('extension', $subsubfileinfo) && $subsubfileinfo['extension'] == 'php'){
									//the php filename becomes the target html filename
									$targetHtmPage = str_ireplace(".php", ".html", $subsubitem);
									//If we have previously generated an html file from a php file,
									if(file_exists($dir.'/'.$item.'/'.$subitem.'/'.$targetHtmPage)){
										//Delete that html file (deletes all html pages)
										unlink($dir.'/'.$item.'/'.$subitem.'/'.$targetHtmPage);
									}
									//Read php file from $contents via http, which processes all php tags to html
									$php = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/' . $_POST['dir']. '/php' .'/'.$item.'/'.$subitem.'/'. $subsubitem, 'r');
									$newphp = str_replace($find, $replace, $php);
									//Opens new, empty file with name
									$html = fopen($dir.'/'.$item.'/'.$subitem.'/'.$targetHtmPage, 'w');
									//Dump the contents from the pre-processed php file into the target html file
									fwrite($html, $newphp);
									//Clear the $html placeholder for next iteration
									fclose($html);
								
									echo  $item.'/'.$subitem.'/'.$targetHtmPage.', ';
								}
							}
							clearstatcache();
						}
					}
				}
				clearstatcache();
			}
			
		}
	}
	clearstatcache();
}

?>