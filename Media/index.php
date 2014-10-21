<?php

/**
 * The absolute path to the image folder
 */
 $var_array = explode("/",$_SERVER['REDIRECT_URL']);
 	

 
$_GET['image']=  $var_array[3].".png";
$imgLocation = $_SERVER['DOCUMENT_ROOT'].'/Media/AppSettings/'.$var_array[2]."/";

/**
 * This fetches a file name from the URL in this example it's holiday.jpg
 * http://yoursite.com/fetch.php?image=holiday.jpg
 * The "basename" function is there for security, to make sure
 * only a filename is passed, not a path.
 */
$imgName = basename($_GET['image']);
 
/**
 * Construct the actual image path.
 */
$imgPath = $imgLocation . $imgName;
 
/**
 * Make sure the file exists if not kill the script
 */
if(!file_exists($imgPath) || !is_file($imgPath)) {
    header('HTTP/1.0 404 Not Found');
    die('The file does not exist');
}
 
/**
 * Make sure the file is an image if not kill the script
 */
$imgData = getimagesize($imgPath);
if(!$imgData) {
    header('HTTP/1.0 403 Forbidden');
    die('The file you requested is not an image.');
}
 
/**
 * Set the appropriate content-type and provide the content-length.
 */
header('Content-type: ' . $imgData['mime']);
header('Content-length: ' . filesize($imgPath));
 
/**
 * Print the image data
 */
readfile($imgPath);

?>