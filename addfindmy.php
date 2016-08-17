<?php

if( isset($_GET['tid']) and is_numeric($_GET['tid']) ){

    define('DRUPAL_ROOT', getcwd());
    
//    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    $ispath='findmy';
    if( $_GET['tid']>0 ){
        $ispath.='/'.$_GET['tid'];
    }

    if( isset($_REQUEST['op']) and strlen($_REQUEST['op']) ){
        if( !isset($_REQUEST['title']) or !strlen($_REQUEST['title']) ){
            drupal_set_message('Заголовок обязателен для заполнения.', 'error');
            drupal_goto($ispath);
        }
        if( !isset($_REQUEST['field_part']['und']) or !is_numeric($_REQUEST['field_part']['und']) ){
            drupal_set_message('Выберите раздел, к которому относится искомый предмет.', 'error');
            drupal_goto($ispath);
        }
    }

    echo '<div class="wrnblock"><ul><li>Заявки от анонимных пользователей автоматически удаляются через неделю. И публикуются на сайте только после модерации.</li><li>Заявки от зарегистрированных пользователей автоматически удаляются через месяц, если пользователь не удалил их до этого вручную. На сайте заявки публикуются без премодерации.</li></ul></div>';

    module_load_include('inc', 'node', 'node.pages');
    $form = node_add('findmy');
//    $form['#action']='findmy';
    echo render($form);
    echo ' <script type="text/javascript"> jQuery(\'#addfindmyis\').show(); jQuery(\'.addfindmy span.is_a\').removeClass(\'ajaxproc2\'); </script> ';

    if( isset($_REQUEST['op']) and strlen($_REQUEST['op']) ){
        drupal_goto($ispath);
    }
    
}


?>