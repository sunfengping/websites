<?php
/***************************************************************************
 *                          class.image.php
 *                            -------------------
 *   begin                : October 3, 2005
 *   copyright            : (C) 2003  Peak Software
 *   email                : marc@peaksoftware.com.au

 ***************************************************************************/

class image {

	var $quality 	= 75;
	var $image 		= "";
	var $width 		= 0;
	var $height 	= 0;
	var $type 		= "";
	var $attr 		= "";

	function image($image = "") {
		if($image != "") {
			$this->setImage($image);
		}
	}
	
	function setImage($image) {
		if(!is_file($image)) die ("image not found: " . $image);
		list($width, $height, $type, $attr) = getimagesize($image);
		
		$this->image 	= $image;
		$this->width 	= $width;
		$this->height 	= $height;
		$this->type 	= $type;
		$this->attr 	= $attr;
	}
	
	function resize($newImage, $newW, $newH, $scale= true) {
		
		$image = imagecreatetruecolor($newW, $newH);
	
		$color = imagecolorallocate( $image, 255, 255, 255 ); //white
   		imagefill($image,0,0,$color);
   
   		$source = imagecreatefromjpeg($this->image);
	
		$xoffset = 0;
		$yoffset = 0;
		$scaleH = $newH;
		$scaleW = $newW;
		
		//either scale or fit image on resize
		if($scale == true) { 
			if($this->width > $this->height) {
				$percent 	= $newW / $this->width;
				$scaleH 	= ( $this->height * $percent );
				$yoffset 	= (($newH-$scaleH)/2);
			} else if( $this->height > $this->width) {
				$percent 	= $newH / $this->height;
				$scaleW 	= ( $this->width * $percent );
				$xoffset 	= (($newW-$scaleW)/2);
			}
		} else {
			if($this->width > $this->height) {
				$percent 	= $newH / $this->height;
				$scaleW 	= ( $this->width * $percent );
				$xoffset 	= (($newW-$scaleW)/2);
			} else if( $this->height > $this->width) {
				$percent 	= $newW / $this->width;
				$scaleH 	= ( $this->height * $percent );
				$yoffset 	= (($newH-$scaleH)/2);
			}		
		
		}
		
		imagecopyresampled($image, $source, $xoffset, $yoffset, 0, 0, $scaleW, $scaleH, $this->width, $this->height);
		imageinterlace($image,1);
		imagejpeg($image, $newImage, $this->quality);
		chmod($newImage,0777);
		
		imagedestroy($image);
		imagedestroy($source);
	}
	
	//print out valid image information
	function html($alt = "", $title = "") {
		return "<img src=\"/".$this->image."\" ".$this->attr." alt=\"$alt\"  title=\"$title\" border=\"0\" />";
	}
	
	function merge($mergeImage) {
		$im = imagecreatefromjpeg($this->image);
		$merge = imagecreatefrompng($mergeImage);
		
		imagecopymerge($im, $merge, 0,0,0,0,$this->width,$this->height,100);
		imageinterlace($im,1);
		imagejpeg($im, $this->image, $this->quality);
		
		imagedestroy($merge);
		imagedestroy($im);
	}
	
	function getWidth() {
		return $this->width;
	}
	
	function getHeight() {
		return $this->height;
	}
	
}
?>