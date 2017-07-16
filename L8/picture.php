<?php
require ("includes/functions.php");

/*Settings*/
$storageDir="./uploads/";
$requiredFields=['word','favcol','font_size'];

$message ="";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(!checkPresenceOfInput($_POST, $requiredFields) || empty($_FILES['file']['tmp_name'])){
		$message = wrapAlert("All inputs are required!");
	} else {
		//inputs present so handle'em
  if($src_img = handle_image( $_FILES['file']['tmp_name'] ) ){
      $text_size = htmlspecialchars($_POST['font_size']);
      $width = imagesx($src_img);
      $text_char_lim = floor($width / $text_size * 1.75 ); // related to text wrap
      $height = imagesy($src_img); // not used yet
      $text_angle = 0;
      $text_x = 0.5 * $text_size;
      $text_y = 1.5 * $text_size;
      $favcol = hex2RGB($_POST['favcol']);
      $text_color = imagecolorallocate($src_img, $favcol['red'], $favcol['green'], $favcol['blue']);
      $font = './includes/RobotoCondensed-Regular.ttf';
      $text = wordwrap( htmlspecialchars($_POST['word']), $text_char_lim, "\n" );
      imagettftext($src_img, $text_size, $text_angle, $text_x, $text_y, $text_color, $font, $text);
      $file_dest = $storageDir . $_FILES['file']['name'];
      imageJPEG($src_img, $file_dest, 100);
      $img = "<h2>Preview (click for viewer)</h2><a href=\"viewer.php?name=". $_FILES['file']['name'] . "\"><img src=\"$file_dest\"/></a><br>";
      imageDestroy($src_img);
    }
	}
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
      form{
        display: inline-block;
        max-width: 100%;
      }
      body * {
        margin: 0.5em;
      }
      legend{
        font-size: 1.4em;
      }
      img {
        width: 360px;
        max-width: 90%;
        margin-right: 1.5em;
        height: 300px;
        max-height: 100%;
        object-fit: cover;
        object-position: top left;
      }
    </style>
  </head>
  <body>
    <?php if(!empty($img)){
      echo $img;
    } ?>
    <form id="combininator" action="picture.php" method="post" enctype="multipart/form-data">
      <fieldset>
        <?php echo $message; ?>
        <legend>Lab 8 - Combininator</legend>
        <label for="word">Word(s)</label>
        <input type="text" id="word" name="word" ><br>
        <label for="pic">Picture</label>
        <input type="file" id="pic" name="file" accept="image/*" ><br>
        <label for="favcol">Color</label>
        <input type="color" id="favcol" name="favcol" value="#ff0000"><br>
        <label for="font_size">Font size</label>
        <input type="number" id="font_size" min="1" max="200" name="font_size" value="20"><br>
        <input type="submit">
      </fieldset>
    </form>
  </body>
</html>
