<?php
/**
 * Elgg captcha plugin graphics file generator
 *
 * @package ElggCaptcha
 */

global $CONFIG;
$token = get_input('captcha_token');

// Output captcha
if ($token)
{
    // Generate captcha
    $captcha = captcha_generate_captcha($token);

    // Pick a random background image
    $n = rand(1, $CONFIG->captcha_num_bg);
    $image = imagecreatefromjpeg($CONFIG->pluginspath . "captcha/backgrounds/bg$n.jpg");

    // Create a colour (black so its not a simple matter of masking out one colour and ocring the rest)
    $colour = imagecolorallocate($image, 0,0,0);

    // Write captcha to image
    //imagestring($image, 5, 30, 4, $captcha, $black);
    imagettftext($image, 30, 0, 10, 30, $colour, $CONFIG->pluginspath . "captcha/fonts/1.ttf", $captcha);

    // Output image
    imagejpeg($image);

    // Output image
    ob_start(); // start a new output buffer
    imagejpeg($image);
    $ImageData = ob_get_contents();
    $ImageDataLength = ob_get_length();
    ob_end_clean(); // stop this output buffer

    header("Content-Type: image/jpeg") ;
    header("Content-Length: ".$ImageDataLength);
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo $ImageData;

    // Free memory
    imagedestroy($image);
}

exit();