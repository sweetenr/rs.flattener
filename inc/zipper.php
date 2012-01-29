<?php
require_once('vars.php');

header('Content-type: application/json');

function addFolderToZip($dir, $zipArchive, $flag){
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			//Add the directory
			$zipArchive->addEmptyDir($dir);

			// Loop through all the files
			while (($file = readdir($dh)) !== false) {
				// Omit .svn, .project, eclipse and the zip files/folders
				
				if(strstr($file, '.svn') == false && strstr($file, '.project')==false && strstr($file, '~')== false && strstr($file, 'zips')== false){
					if($flag == '0'){
						if(strstr($file, 'php') == false){
							//If it's a folder, run the function again!
							if(!is_file($dir . $file)){
								// Skip parent and root directories
								if( ($file !== ".") && ($file !== "..")){
									addFolderToZip($dir . $file . "/", $zipArchive, $flag);
								}
							
							}else{
								// Add the files
								$zipArchive->addFile($dir .'/'. $file);
							}
						}	
					}else{
						//If it's a folder, run the function again!
						if(!is_file($dir . $file)){
							// Skip parent and root directories
							if( ($file !== ".") && ($file !== "..")){
								addFolderToZip($dir . $file . "/", $zipArchive, $flag);
							}
						
						}else{
							// Add the files
							$zipArchive->addFile($dir . $file);
						}
					}
					
				}
			}
		}
	}
}

$search = "\\";
$replace = "/";

$today = getdate();
$zipFolder = $root .'/'. $_POST['dir'].'/zips/';
$zipFileName = $_POST['dir'].'-'.$today['mon'].$today['mday'].$today['year'].'-'.$today['hours'].$today['minutes'].$today['seconds'].'.zip';

if(!file_exists($zipFolder)) mkdir($zipFolder);

$msg = array();

$zip = new ZipArchive();

if ($zip->open($zipFolder.$zipFileName, ZIPARCHIVE::CREATE) !== true){
	exit("cannot open <$zipFolder.$zipFileName>\n");
}else{
	addFolderToZip($root .'/'. $_POST['dir'].'/', $zip, $_POST['withPhp']);
	$msg['number'] = $zip->numFiles;
	//$msg['number'] = "status:" . $zip->status . "\n";
	$zip->close();
	echo json_encode($msg);
}
?>