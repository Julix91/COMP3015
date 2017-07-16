<?php
function checkPresenceOfInput($inputs, $requiredFields)
{
	foreach ($requiredFields as $required) {
		if(!isset($inputs[$required]) || trim($inputs[$required]) == ''){
      var_dump($inputs[$required]);
			return false;
		}
	}
	return true;
}

function wrapAlert($message, $type = NULL)
{
	$type = is_null($type)?"warning":$type;
	ob_start(); ?>
	<div class="alert alert-<?php echo $type; ?> alert-dismissable text-center">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo $message?>
	</div>
	<?php return ob_get_clean();
}


//from http://php.net/manual/en/function.imagecreatefromjpeg.php#110547
//handles more cases apparently - useless currently since I don't check type before saving / submitting the jpeg - future reference
function imageCreateFromAny($filepath) {
    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
    $allowedTypes = array(
        1,  // [] gif
        2,  // [] jpg
        3,  // [] png
        6   // [] bmp
    );
    if (!in_array($type, $allowedTypes)) {
        return false;
    }
    switch ($type) {
        case 1 :
            $im = imageCreateFromGif($filepath);
        break;
        case 2 :
            $im = imageCreateFromJpeg($filepath);
        break;
        case 3 :
            $im = imageCreateFromPng($filepath);
        break;
        case 6 :
            $im = imageCreateFromBmp($filepath);
        break;
    }
    return $im;
}

//modified from http://php.net/manual/en/function.imagecreatefromjpeg.php#112902
//maintains orientation, apparently, is that relevant for other formats too?
//Otherwise I should have had the jpg case call this one and not other way around :D
function handle_image($filename)
{
   $img = imageCreateFromAny($filename);
   $exif = exif_read_data($filename);
   if ($img && $exif && isset($exif['Orientation']))
   {
       $ort = $exif['Orientation'];

       if ($ort == 6 || $ort == 5)
           $img = imagerotate($img, 270, null);
       if ($ort == 3 || $ort == 4)
           $img = imagerotate($img, 180, null);
       if ($ort == 8 || $ort == 7)
           $img = imagerotate($img, 90, null);

       if ($ort == 5 || $ort == 4 || $ort == 7)
           imageflip($img, IMG_FLIP_HORIZONTAL);
   }
   return $img;
}


//from http://php.net/manual/en/function.hexdec.php#99478
/**
* Convert a hexa decimal color code to its RGB equivalent
*
* @param string $hexStr (hexadecimal color value)
* @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
* @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
* @return array or string (depending on second parameter. Returns False if invalid hex color value)
*/
function hex2RGB($hexStr, $returnAsString = false, $seperator = ', ') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}
