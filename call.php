<?php

define('DRUPAL_ROOT', getcwd() );
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);


    include DRUPAL_ROOT . '/api/BaseJsonRpcServer.php';
    include DRUPAL_ROOT . '/api/tests/lib/DateTimeService.php';
    include DRUPAL_ROOT . '/api/tests/lib/DateTimeRpcService.php';

    /** @var BaseJsonRpcServer $server */
    $server = null;

    // inheritance mode
    if ( array_key_exists( 'v2', $_GET ) ) {
        $server = new DateTimeRpcService();
    } else {
        // Instance Mode
        $server = new BaseJsonRpcServer( new DateTimeService() );
    }

    $server->ContentType = null;
    $server->Execute();
?>