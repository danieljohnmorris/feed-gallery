<?
define ("_THUMBALIZR",1);

require_once("config.inc.php"); // get config and functions

$image=new thumbalizrRequest($thumbalizr_config,$thumbalizr_defaults); 

if (isset($_REQUEST['url'])) {
	$image->request($_REQUEST['url']); // send request	
	$image->output(); //dump binary image data	
} else {
//	print_r($image->headers);
	$imagepath="queued.jpg";
	$image=imagecreatefromjpeg($imagepath);
	header('Content-Type: image/jpeg');
	imagejpeg($image);
}
?>