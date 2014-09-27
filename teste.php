<?php
/*

    echo DIRECTORY_SEPARATOR. " ".dirname(__FILE__)."<br>";

    define('APP_DIR', 'app');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);
echo WWW_ROOT."<br>";
echo APP_DIR . DS . WEBROOT_DIR . DS . 'index.php<br>';
echo dirname(dirname(dirname(__FILE__)));
echo "<br>".basename(dirname(dirname(__FILE__)));
echo "<br>".PATH_SEPARATOR;
echo "<br>".php_sapi_name();
echo "<br>--<br>";
*/
//echo dirname(__FILE__);
//echo DIRECTORY_SEPARATOR;
//echo $_SERVER['SERVER_NAME'];
//echo $_SERVER ['REQUEST_URI'];

$texto = "dfbs/\"'''9u021ro0'213-i124=9SELECT''";
$link = mysql_connect('localhost', 'root', 'toor');
 $test = mysql_real_escape_string($texto, $link);
echo $test;
echo '<br>';
// echo htmlspecialchars($test);
$test = '1';
echo (int)$test;
?>