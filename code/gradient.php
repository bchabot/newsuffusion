<?php
/**
 * Dynamic gradient generator
 *
 * @package Suffusion
 * @subpackage Templates
 */

$mime_type = "image/png";
$extension = ".png";
$send_buffer_size = 4096;

// check for GD support
if(!function_exists('imagecreatetruecolor')) {
    wp_die( esc_html__( 'Error: Server does not support PHP image generation', 'suffusion' ) );
}
else {
	$req_start = isset($_GET["start"]) ? sanitize_hex_color($_GET["start"]) : "#FFFFFF";
	$req_finish = isset($_GET["finish"]) ? sanitize_hex_color($_GET["finish"]) : "#FFFFFF";
	
	$width = isset($_GET["width"]) ? absint($_GET["width"]) : 1000;
	$height = isset($_GET["height"]) ? absint($_GET["height"]) : 120;
	
	// Constraints to prevent DOS
	$width = min(max($width, 1), 2000);
	$height = min(max($height, 1), 2000);

	$allowed_directions = array("top-down", "down-top", "left-right", "right-left");
	$direction = (isset($_GET["direction"]) && in_array($_GET["direction"], $allowed_directions)) ? $_GET["direction"] : "top-down";

	$grad_image = imagecreatetruecolor($width, $height);

	$rgb_start = hex_to_rgb($req_start);
	$rgb_finish = hex_to_rgb($req_finish);

	$startx = 0;
	$endx = $width;
	$starty = 0;
	$endy = $height;

	switch ($direction) {
	case "top-down":
		$start = $rgb_start;
		$finish = $rgb_finish;
		$limit = $height;
		break;
	case "down-top":
		$start = $rgb_finish;
		$finish = $rgb_start;
		$limit = $height;
		break;
	case "left-right":
		$start = $rgb_start;
		$finish = $rgb_finish;
		$limit = $width;
		break;
	case "right-left":
		$start = $rgb_finish;
		$finish = $rgb_start;
		$limit = $width;
		break;
	default:
		$start = $rgb_start;
		$finish = $rgb_finish;
		$limit = $height;
	}

	$step_r = ($finish['red'] - $start['red']) / $limit;
	$step_g = ($finish['green'] - $start['green']) / $limit;
	$step_b = ($finish['blue'] - $start['blue']) / $limit;

	for ($i=0; $i<$limit; $i++) {
		$r = floor(($finish['red'] - $start['red'] != 0) ? $start['red'] + $step_r * $i : $start['red']);
		$g = floor(($finish['green'] - $start['green'] != 0) ? $start['green'] + $step_g * $i : $start['green']);
		$b = floor(($finish['blue'] - $start['blue'] != 0) ? $start['blue'] + $step_b * $i : $start['blue']);
		//echo "$i -> $r $g $b<br/>";
		//echo ($i/$limit)."<br />";
		$color = imagecolorallocate($grad_image, $r, $g, $b);
		switch ($direction) {
		case "top-down":
		case "down-top":
			$x1 = $startx;
			$y1 = $i;
			$x2 = $endx;
			$y2 = $i;
			break;
		case "left-right":
		case "right-left":
			$x1 = $i;
			$y1 = $starty;
			$x2 = $i;
			$y2 = $endy;
			break;
		default:
			$start = $rgb_start;
			$finish = $rgb_finish;
			$limit = $height;
		}

		$success = imageline($grad_image, $x1, $y1, $x2, $y2, $color);
	}

	header("Content-Type: image/jpeg");
	imagejpeg($grad_image, NULL, 70);
	imagedestroy($grad_image);
	exit;
}

/* 
    decode an HTML hex-code into an array of R,G, and B values.
    accepts these formats: (case insensitive) #ffffff, ffffff, #fff, fff 
*/    
function hex_to_rgb($hex) {
    // remove '#'
    if(substr($hex,0,1) == '#')
        $hex = substr($hex,1) ;

    // expand short form ('fff') color
    if(strlen($hex) == 3)
    {
        $hex = substr($hex,0,1) . substr($hex,0,1) .
               substr($hex,1,1) . substr($hex,1,1) .
               substr($hex,2,1) . substr($hex,2,1) ;
    }

	if(strlen($hex) != 6) {
		$rgb['red'] = hexdec("ff");
		$rgb['green'] = hexdec("ff");
		$rgb['blue'] = hexdec("ff");
	}
	else {
	    // convert
		$rgb['red'] = hexdec(substr($hex,0,2)) ;
	    $rgb['green'] = hexdec(substr($hex,2,2)) ;
		$rgb['blue'] = hexdec(substr($hex,4,2)) ;
	}

    return $rgb ;
}
?>
