<html><head>
<title>тест запросов</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head><body>
<?php
define('DRUPAL_ROOT', getcwd());

    include DRUPAL_ROOT . '/api/BaseJsonRpcClient.php';

    $client = new BaseJsonRpcClient( 'http://'.$_SERVER['HTTP_HOST'].'/call.php?v2' );
    $key='$S$DS1jIqDJVENfF0xESHdFEjeQjYdCZAqMutiNGSoK.Z0DOhOKNKiH';
    
    $projects = $client->GetInfo($key, 1550);
    if( isset($projects->Result) ){
        echo '<pre>'.print_r($projects->Result, true). '</pre>';
    }

    
?>
</body></html>