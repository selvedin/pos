<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

require_once('lang/' . ((@$_COOKIE['lng'] == 'ar') ? 'ar' : 'bs') . '.php');
if(isset($_COOKIE['lng'])){
    switch ($_COOKIE['lng']){
	case 'en':
	    require_once('lang/en.php');
	    break;
	case 'bs':
	    require_once('lang/bs.php');
	    break;
	case 'ar':
	    require_once('lang/ar.php');
	    break;
	default:
	    require_once('lang/en.php');
	    break;
    }
}
function __($t, $param1 = '',$param2='',$param3='') {
    global $lang__;
    if (!isset($_COOKIE['lng']))
        return sprintf($t, $param1,$param2,$param3);

    if (!isset($lang__[$t])) {
        $a = file('lang/missing.txt', FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
        $x = '"' . $t . '"=>\'\',';
        if (!in_array($x, $a)) {
            $fh = fopen('lang/missing.txt', 'a') or die("can't open file");
            fwrite($fh, $x . "\n");
            fclose($fh);
        }
    }

    if (isset($lang__[$t]) && $lang__[$t] != '')
        return sprintf($lang__[$t], $param1,$param2,$param3);
    else
        return sprintf($t, $param1,$param2,$param3);
}


function bsToEn($word)
{
    $search = [
                "ž", 
                "Ž",
                "š",
                "Š",
                "ć",
                "Ć",
                "č",
                "Č",
                "đ",
                "Đ",

    ];

    $replacements = [
                "z", 
                "z",
                "s",
                "s",
                "c",
                "c",
                "c",
                "c",
                "d",
                "d",
    ];

    return str_replace($search, $replacements, $word);
}
if (isset($_GET['lang'])) {
    setcookie("lng", $_GET['lang'], time() + 60 * 60 * 24 * 30, "/");
    header("Location: ?");
    die();
}

(new yii\web\Application($config))->run();
