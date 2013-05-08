<?
/*******************************************************
* image.php
* Copyright 2010, LBJWillStay.com
*******************************************************/
/**
 * These functions are used to manipulate images
 *
 */
include_once('globalvars.php');

function image_resize($original_path, $new_path, $ext='jpg', $max_dimension=0){
	$thumbsdir = pathinfo($new_path, PATHINFO_DIRNAME);
	if(!is_dir($thumbsdir)){
		//create thumbs dir
		mkdir($thumbsdir);
	}
  //get original width&height
  list($width, $height) = getimagesize($original_path);
  //if no $max_dimension specified we use the orig values
  if(!$max_dimension) {
    $max_dimension = ($width >= $height) ? $width : $height;
  }
  //determine the new height * width
  if($width >= $height) {
    //width is bigger - landscape
    $max_dimension = ($max_dimension>$width) ? $width : $max_dimension; //dont size up
    $small_size = round( ($height / $width) * $max_dimension );
    $newwidth = $max_dimension;
    $newheight = $small_size;
  } else {
    //height is bigger - portrait
    $max_dimension = ($max_dimension>$height) ? $height : $max_dimension; //dont size up
    $small_size = round( ($width / $height) * $max_dimension );
    $newwidth = $small_size;
    $newheight = $max_dimension;
  }
  //get image resource for new image
  $res_thumb = imagecreatetruecolor($newwidth, $newheight);
  //get image resource for original
  switch($ext) {
    case 'image/png':
    $res_original = imagecreatefrompng($original_path);
    // Resize
    imagecopyresized($res_thumb, $res_original, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // save the image with name of $new_path
    imagepng($res_thumb, $new_path);	    
    break; 
     
    case 'image/gif':
    $res_original = imagecreatefromgif($original_path);
    // Resize
    imagecopyresized($res_thumb, $res_original, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // save the image with name of $new_path
    imagegif($res_thumb, $new_path);	    
    break;  
    
    case 'image/jpg':
    case 'image/jpe':
    case 'image/jpeg':
    default:
    $res_original = imagecreatefromjpeg($original_path);
    // Resize
    imagecopyresized($res_thumb, $res_original, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // save the image with name of $new_path
    imagejpeg($res_thumb, $new_path);
    break;  
  }
  // Free up memory
  imagedestroy($res_thumb);
  imagedestroy($res_original);
  // send back new path
  return $new_path;		
}

?>