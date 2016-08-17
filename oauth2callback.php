<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$fp = fopen( 'tttt.txt', 'w');
fwrite($fp, '<pre>'.print_r($_POST, true). '</pre><pre>'.print_r($_GET, true). '</pre>');
fclose($fp);

?>