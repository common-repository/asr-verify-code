<?PHP
/*
  FileName: verifyingcode.php
  Function: generate a code of image
  Code:     Rungao Gu (Asr) gu@lostinbeijing.com
  WebSite : http://www.lostinbeijing.com
*/


@session_start();
//require("../../../wp-load.php");
//session_register('verifyingcode');
$_SESSION['asr_verifycode'] = "";
$code="";
$width = "50";//image width
$height = "20";//image height

//$len = get_option("asrverifycode_length");//code bit
$len = $_SESSION['asrverifycode_length'];
if(!is_numeric($len)||$len<1)
{
	$len=4;
}
$width = 50 + ($len-4)*8;
$bgcolor = "#ffffff";//background color
$noise = true;//generate or not noise points
$noisenum = 10;//noise number
$border = true;// set or not border
$bordercolor = "#000000";
$image = imageCreate($width, $height);
$back = getcolor($bgcolor);
imageFilledRectangle($image, 0, 0, $width, $height, $back);
$size = $width/$len;
if($size>$height) $size=$height;
$left = ($width-$len*($size+$size/10))/$size;

$textall=range('a','z');
$textall=array_merge(range('A','Z'),$textall);
$textall=array_merge(range('0','9'),$textall);
//$textall.=range('0','9');
for ($i=0; $i<$len; $i++) {
   
    $tmptext=rand(0, 61);
	$randtext = $textall[$tmptext];
    $code .= $randtext;
}
$textColor = imageColorAllocate($image, 0, 0, 0);
imagestring($image, $size, 4, 1, $code, $textColor); 

if($noise == true) setnoise();
$_SESSION['asr_verifycode'] = $code;
$bordercolor = getcolor($bordercolor); 
if($border==true) imageRectangle($image, 0, 0, $width-1, $height-1, $bordercolor);
header("Content-type: image/png");
imagePng($image);
imagedestroy($image);
function getcolor($color)
{
     global $image;
     $color = eregi_replace ("^#","",$color);
     $r = $color[0].$color[1];
     $r = hexdec ($r);
     $b = $color[2].$color[3];
     $b = hexdec ($b);
     $g = $color[4].$color[5];
     $g = hexdec ($g);
     $color = imagecolorallocate ($image, $r, $b, $g); 
     return $color;
}
function setnoise()
{
	global $image, $width, $height, $back, $noisenum;
	for ($i=0; $i<$noisenum; $i++){
		$randColor = imageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));  
		imageSetPixel($image, rand(0, $width), rand(0, $height), $randColor);
	} 
}
?> 