<?php
include('vars.php'); 
# Is the OS Windows or Mac or Linux
if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
  $eol="\r\n";
} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
  $eol="\r";
} else {
  $eol="\n";
} ?>

<?php
# File for Attachment
$f_name = $root.'/'.$_POST['dir'].'/zips/'.$_POST['zip'];    // use relative path OR ELSE big headaches. $letter is my file for attaching.
$handle = fopen($f_name, 'rb');
$f_contents = fread($handle, filesize($f_name));
$f_contents = chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
$f_type = filetype($f_name);
fclose($handle);

# To Email Address
$emailaddress=$_POST['to'];
# Message Subject
$emailsubject=$_POST['zip'].' '.date("Y/m/d H:i:s");

# Common Headers
$headers .= 'From: '.$_POST['myname'].' <'.$_POST['myemail'].'>'.$eol;
$headers .= 'Reply-To: '.$_POST['myname'].' <'.$_POST['myemail'].'>'.$eol;
$headers .= 'Return-Path: '.$_POST['myname'].' <'.$_POST['myemail'].'>'.$eol;     // these two to set reply address
$headers .= "Message-ID:<".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters
# Boundry for marking the split & Multitype Headers
$mime_boundary=md5(time());
$headers .= 'MIME-Version: 1.0'.$eol;
$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol;
$msg = "";

# Attachment
$msg .= "--".$mime_boundary.$eol;
$msg .= "Content-Type: application/octet-stream; name=\"".$_POST['zip']."\"".$eol;   // sometimes i have to send MS Word, use 'msword' instead of 'pdf'
$msg .= "Content-Transfer-Encoding: base64".$eol;
$msg .= "Content-Disposition: attachment; filename=\"".$_POST['zip']."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
$msg .= $f_contents.$eol.$eol;
# Setup for text OR html
$msg .= "Content-Type: multipart/alternative".$eol;

# Text Version
$msg .= "--".$mime_boundary.$eol;
$msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;
$msg .= "Content-Transfer-Encoding: 8bit".$eol;
$msg .= "This is a multi-part message in MIME format.".$eol;
$msg .= "If you are reading this, please update your email-reading-software.".$eol;
$msg .= "+ + Text Only Email from Genius Jon + +".$eol.$eol;

# Finished
$msg .= "--".$mime_boundary."--".$eol.$eol;   // finish with two eol's for better security. see Injection.

# SEND THE EMAIL
//ini_set(sendmail_from,$_POST['myemail']);  // the INI lines are to force the From Address to be used !
$sent = mail($emailaddress, $emailsubject, $msg, $headers);
echo "$sent\n\n";
echo "$emailaddress\n";
echo "$emailsubject\n\n";
echo "$msg\n\n";
echo "$headers\n";
//ini_restore(sendmail_from);
?> 