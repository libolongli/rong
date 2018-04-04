<?php
define('IN_CB', true);
include_once('include/function.php');


$params = array(
	'filetype'=>'PNG',
	'dpi'=>'72',
	'scale'=>2,
	'rotation'=>0,
	'font_family'=>'Arial.ttf',
	'font_size'=>'12',
	'text'=>$merchantNo,
	'thickness'=>30,
	'start'=>NULL,
	'code'=>'BCGcode128',
);

$code = $params['code'];

include_once('config' . DIRECTORY_SEPARATOR . $code . '.php');
$class_dir = DIRECTORY_SEPARATOR . 'class';
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGColor.php');
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGBarcode.php');
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGDrawing.php');
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGFontFile.php');

if (!include_once($class_dir . DIRECTORY_SEPARATOR . $classFile)) {
    showError();
}
include_once('config' . DIRECTORY_SEPARATOR . $baseClassFile);
$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);

$drawException = null;
try {
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $code_generated = new $className();
    if (function_exists('baseCustomSetup')) {
        baseCustomSetup($code_generated, $params);
    }

    if (function_exists('customSetup')) {
        customSetup($code_generated, $params);
    }

    $code_generated->setScale(max(1, min(4, $params['scale'])));
    $code_generated->setBackgroundColor($color_white);
    $code_generated->setForegroundColor($color_black);

    if ($params['text'] !== '') {
        $text = convertText($params['text']);
        $code_generated->parse($text);
    }
} catch(Exception $exception) {
    $drawException = $exception;
}
$drawing = new BCGDrawing('qrcode/bar.png', $color_white);
if($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code_generated);
    $drawing->setRotationAngle($params['rotation']);
    $drawing->setDPI($params['dpi'] === 'NULL' ? null : max(72, min(300, intval($params['dpi']))));
    $drawing->draw();
}

$drawing->finish($filetypes[$params['filetype']]);
?>