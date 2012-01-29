<?php
$host  = $_SERVER['SERVER_NAME'];
include('inc/vars.php');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>page flattener</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox-1.3.4.css">
	</head>
	<body>
		<header id="top">
			<div class="wrap">
				<div id="header">
					<div id="rtHead">
						<div id="logo">page flattener</div>
						<a href="#eyeHate" id="warp">( Warp Mode )</a>
					</div>
				</div>				
			</div>
		</header>
		
		<section class="wrap">
			<div id="contPane" class="sect">
				<div class="boxPane">
					<h4>Project</h4>
					<div id="options" class="round">
						<div>Select directory</div>
						<?php
							if($parentDir){
								echo '<select id="dir" name="dir">';
								foreach($parentDir as $folder){
									$fileinfo = pathinfo($folder);
									$ext = $fileinfo['extension'];
									if(strstr($ext, '~') == false && $fileinfo['extension'] == ''){
										if(file_exists($root.'/'.$folder.'/php/.flats')){
											echo '<option value="'.$folder.'">'.$folder.'</option>';
										}
									}
								}
								echo '</select>';
							}
						?>
						<div id="flats">
							<!-- input type="submit" id="zipper" name="zipper" value="flatten !" / -->
							<input title="Generate HTML files from the PHP files in your PHP folder" type="submit" id="recurse" name="recurse" value="flatten !" />
							<div>
								<hr/>
								<input type="submit" title="Makes a zip file of this project in the zips directory. If the zips directory does not exist for this project, it will be created automatically. Extraneous files (.svn and eclipse files) are omitted as well." id="zipit" name="zipit" value="Zip it up !" />
								<br/>
								<label for="withPhp" title="If this is selected, the php files in the php folder will be included in the ZIP archive, otherwise, they will be omitted.">Include PHP folder?</label>
								<input type="checkbox" title="If this is selected, the php files in the php folder will be included in the ZIP archive, otherwise, they will be omitted." id="withPhp" name="withPhp" />
							</div>
							<div id="result">result</div>
						</div>
					</div>
				</div>
				<div class="boxPane last">
					<h4>Pages</h4>
					<div id="fileList" class="round listBox col2"></div>
					<br/>
					<h4>ZIPs</h4>
					<div id="zipList" class="round listBox"></div>
				</div>
			</div>
			
		</section>
<div style="display: none;">
	<form method="post" action="#" id="emailForm">
		<h4>Email <span id="zipName">[zip name]</span> To Someone</h4>
		<input type="hidden" name="zip" id="zip" />
		<input type="hidden" name="dir" id="dirSend" />
		<input name="myname" title="First Last" id="myname" type="text" placeholder="Your Name" pattern="^[a-zA-Z ]+[a-zA-Z]$" required="required" />
		<input name="myemail" id="myemail" type="email" placeholder="Your Email" pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" required="required" />
		<input name="to" title="To send to multiple emails, separate emails with a comma" id="toemail" type="email" multiple="multiple" placeholder="To Email(s)" required="required" />
		<input type="submit" value="Send File" />
	</form>
</div>
<script type="text/javascript" src="js/jq6.js"></script>
<script type="text/javascript" src="js/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="js/common.js"></script>
	</body>
</html>
