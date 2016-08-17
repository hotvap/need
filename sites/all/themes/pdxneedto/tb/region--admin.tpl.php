  <div class="region_item" id="region_<?php echo $region; ?>"><div class="region_admin_label" onclick=" jQuery('.admin_region_content').toggle(); "> -- Настройки страницы Администратору -- </div><div class="admin_region_content" style="display: none;">
  <?php
  global $user; if(isset($user->roles[3])){
    $pdxshowhelp=variable_get('pdxshowhelp3', 0);
    if(!$pdxshowhelp){
        echo '<div class="region_admin"><a href="/admin/structure/block/add?region='.$region.'" title="Добавить блок в данный регион">+Б</a></div>';
    }

    if(module_exists('metatags_quick')){

?>
<fieldset class='admin_item admin_seo'><legend><a href="javascript:void(0);" onclick="jQuery(this).parent().parent().find('.admin_seo_post').toggle();">SEO-возможности</a></legend><div class="admin_seo_post">
<?php

$pdxseo=array();
$pdxseo_title=$pdxseo_h1=$pdxseo_keyword=$pdxseo_desc='';
$curpath=implode('/',arg());

if( isset($_POST['pathsynonym']) and strlen($_POST['pathsynonym']) ){
    $curpath=filter_xss(strip_tags($_POST['pathsynonym']));
}

$modified=0;

if($pdxseo=variable_get('pdxseo')){
    $pdxseo=unserialize($pdxseo);
}

if(isset($pdxseo[urlencode($curpath)]['keyword']) and strlen($pdxseo[urlencode($curpath)]['keyword'])){
    $pdxseo_keyword=$pdxseo[urlencode($curpath)]['keyword'];
}
if(isset($pdxseo[urlencode($curpath)]['desc']) and strlen($pdxseo[urlencode($curpath)]['desc'])){
    $pdxseo_desc=$pdxseo[urlencode($curpath)]['desc'];
}
if( isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) and isset($_SESSION['pdxpdx_node_type']) ){
    $numyear=db_query('select meta_keywords_metatags_quick from {field_revision_meta_keywords} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
    $numyear=$numyear->fetchAssoc();
    if( isset($numyear['meta_keywords_metatags_quick']) ){
        $pdxseo_keyword=$numyear['meta_keywords_metatags_quick'];
    }

    $numyear=db_query('select meta_description_metatags_quick from {field_revision_meta_description} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
    $numyear=$numyear->fetchAssoc();
    if( isset($numyear['meta_description_metatags_quick']) ){
        $pdxseo_desc=$numyear['meta_description_metatags_quick'];
    }
    
}elseif( ( arg(0)=='catalog' and is_numeric(arg(1)) ) or ( arg(0)=='taxonomy' and is_string(arg(1)) and arg(1)=='term' and is_numeric(arg(2)) ) ){
    if(arg(0)=='catalog'){
        $tid=arg(1);
    }else{
        $tid=arg(2);
    }

    $numyear=db_query('select meta_description_metatags_quick from {field_revision_meta_description} where entity_type=\'taxonomy_term\' and entity_id='.$tid);
    $numyear=$numyear->fetchAssoc();
    if( isset($numyear['meta_description_metatags_quick']) ){
        $pdxseo_desc=$numyear['meta_description_metatags_quick'];
    }

    $numyear=db_query('select meta_keywords_metatags_quick from {field_revision_meta_keywords} where entity_type=\'taxonomy_term\' and entity_id='.$tid);
    $numyear=$numyear->fetchAssoc();
    if( isset($numyear['meta_keywords_metatags_quick']) ){
        $pdxseo_keyword=$numyear['meta_keywords_metatags_quick'];
    }

}

if( isset($_REQUEST['pdxseo_h1']) ){
    $_REQUEST['pdxseo_h1']=strip_tags($_REQUEST['pdxseo_h1']);
    $_REQUEST['pdxseo_h1']=str_replace("\n","",$_REQUEST['pdxseo_h1']);
    $_REQUEST['pdxseo_h1']=str_replace("\r","",$_REQUEST['pdxseo_h1']);
    $modified=1;
    echo '<div>Заголовок H1 для страницы '.$curpath.' изменен</div>';
    if( strlen($_REQUEST['pdxseo_h1']) ){
        $pdxseo[urlencode($curpath)]['h1']=$_REQUEST['pdxseo_h1'];
    }else{
        $pdxseo[urlencode($curpath)]['h1']='';
    }
}

if( isset($_REQUEST['pdxseo_title']) ){
    $_REQUEST['pdxseo_title']=strip_tags($_REQUEST['pdxseo_title']);
    $_REQUEST['pdxseo_title']=str_replace("\n","",$_REQUEST['pdxseo_title']);
    $_REQUEST['pdxseo_title']=str_replace("\r","",$_REQUEST['pdxseo_title']);
    $modified=1;
    echo '<div>Заголовок TITLE для страницы '.$curpath.' изменен</div>';
    if( strlen($_REQUEST['pdxseo_title']) ){
        $pdxseo[urlencode($curpath)]['title']=$_REQUEST['pdxseo_title'];
    }else{
        $pdxseo[urlencode($curpath)]['title']='';
    }
}

if( isset($_REQUEST['pdxseo_keyword']) ){
    $_REQUEST['pdxseo_keyword']=strip_tags($_REQUEST['pdxseo_keyword']);
    $_REQUEST['pdxseo_keyword']=str_replace("\n","",$_REQUEST['pdxseo_keyword']);
    $_REQUEST['pdxseo_keyword']=str_replace("\r","",$_REQUEST['pdxseo_keyword']);
    echo '<div>Ключевые слова для страницы '.$curpath.' изменены</div>';
    $pdxseo_keyword=$_REQUEST['pdxseo_keyword'];

    if( isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) and isset($_SESSION['pdxpdx_node_type']) ){
        if( strlen($_REQUEST['pdxseo_keyword']) ){
            $numyear=db_query('select meta_keywords_metatags_quick from {field_revision_meta_keywords} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
            $numyear=$numyear->fetchAssoc();
            if( !isset($numyear['meta_keywords_metatags_quick']) ){
                db_query('insert into {field_data_meta_keywords} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_keywords_metatags_quick) values (\'node\', \''.$_SESSION['pdxpdx_node_type'].'\', 0, '.$_SESSION['pdxpdx_node_nid'].', '.$_SESSION['pdxpdx_node_nid'].', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_keyword']));
                db_query('insert into {field_revision_meta_keywords} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_keywords_metatags_quick) values (\'node\', \''.$_SESSION['pdxpdx_node_type'].'\', 0, '.$_SESSION['pdxpdx_node_nid'].', '.$_SESSION['pdxpdx_node_nid'].', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_keyword']));
            }else{
                db_query('update {field_data_meta_keywords} set meta_keywords_metatags_quick=:combo where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid'], array(':combo' =>$_REQUEST['pdxseo_keyword']));
                db_query('update {field_revision_meta_keywords} set meta_keywords_metatags_quick=:combo where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid'], array(':combo' =>$_REQUEST['pdxseo_keyword']));
            }

        }else{
            db_query('delete from {field_revision_meta_keywords} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
            db_query('delete from {field_data_meta_keywords} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
        }
    }elseif( ( arg(0)=='catalog' and is_numeric(arg(1)) ) or ( arg(0)=='taxonomy' and is_string(arg(1)) and arg(1)=='term' and is_numeric(arg(2)) ) ){
        if(arg(0)=='catalog'){
            $tid=arg(1);
        }else{
            $tid=arg(2);
        }
        $tid=taxonomy_term_load($tid);

        if( strlen($_REQUEST['pdxseo_keyword']) ){
            $numyear=db_query('select meta_keywords_metatags_quick from {field_revision_meta_keywords} where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid);
            $numyear=$numyear->fetchAssoc();
            if( !isset($numyear['meta_keywords_metatags_quick']) ){
                db_query('insert into {field_data_meta_keywords} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_keywords_metatags_quick) values (\'taxonomy_term\', \''.$tid->vocabulary_machine_name.'\', 0, '.$tid->tid.', '.$tid->tid.', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_keyword']));
                db_query('insert into {field_revision_meta_keywords} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_keywords_metatags_quick) values (\'taxonomy_term\', \''.$tid->vocabulary_machine_name.'\', 0, '.$tid->tid.', '.$tid->tid.', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_keyword']));
            }else{
                db_query('update {field_data_meta_keywords} set meta_keywords_metatags_quick=:combo where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid, array(':combo' =>$_REQUEST['pdxseo_keyword']));
                db_query('update {field_revision_meta_keywords} set meta_keywords_metatags_quick=:combo where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid, array(':combo' =>$_REQUEST['pdxseo_keyword']));
            }

        }else{
            db_query('delete from {field_revision_meta_keywords} where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid);
            db_query('delete from {field_data_meta_keywords} where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid);
        }

        
    }else{
        if( strlen($_REQUEST['pdxseo_keyword']) ){
            $pdxseo[urlencode($curpath)]['keyword']=$_REQUEST['pdxseo_keyword'];
        }else{
            $pdxseo[urlencode($curpath)]['keyword']='';
        }
        $modified=1;
    }
}

if( isset($_REQUEST['pdxseo_desc']) ){
    $_REQUEST['pdxseo_desc']=strip_tags($_REQUEST['pdxseo_desc']);
    $_REQUEST['pdxseo_desc']=str_replace("\n","",$_REQUEST['pdxseo_desc']);
    $_REQUEST['pdxseo_desc']=str_replace("\r","",$_REQUEST['pdxseo_desc']);
    echo '<div>Описание для страницы '.$curpath.' изменено</div>';
    $pdxseo_desc=$_REQUEST['pdxseo_desc'];


    if( isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) and isset($_SESSION['pdxpdx_node_type']) ){
        if( strlen($_REQUEST['pdxseo_desc']) ){
            $numyear=db_query('select meta_description_metatags_quick from {field_revision_meta_description} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
            $numyear=$numyear->fetchAssoc();
            if( !isset($numyear['meta_description_metatags_quick']) ){
                db_query('insert into {field_data_meta_description} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_description_metatags_quick) values (\'node\', \''.$_SESSION['pdxpdx_node_type'].'\', 0, '.$_SESSION['pdxpdx_node_nid'].', '.$_SESSION['pdxpdx_node_nid'].', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_desc']));
                db_query('insert into {field_revision_meta_description} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_description_metatags_quick) values (\'node\', \''.$_SESSION['pdxpdx_node_type'].'\', 0, '.$_SESSION['pdxpdx_node_nid'].', '.$_SESSION['pdxpdx_node_nid'].', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_desc']));
            }else{
                db_query('update {field_data_meta_description} set meta_description_metatags_quick=:combo where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid'], array(':combo' =>$_REQUEST['pdxseo_desc']));
                db_query('update {field_revision_meta_description} set meta_description_metatags_quick=:combo where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid'], array(':combo' =>$_REQUEST['pdxseo_desc']));
            }

        }else{
            db_query('delete from {field_revision_meta_description} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
            db_query('delete from {field_data_meta_description} where entity_type=\'node\' and entity_id='.$_SESSION['pdxpdx_node_nid']);
        }
    }elseif( ( arg(0)=='catalog' and is_numeric(arg(1)) ) or ( arg(0)=='taxonomy' and is_string(arg(1)) and arg(1)=='term' and is_numeric(arg(2)) ) ){
        if(arg(0)=='catalog'){
            $tid=arg(1);
        }else{
            $tid=arg(2);
        }
        $tid=taxonomy_term_load($tid);

        if( strlen($_REQUEST['pdxseo_desc']) ){
            $numyear=db_query('select meta_description_metatags_quick from {field_revision_meta_description} where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid);
            $numyear=$numyear->fetchAssoc();
            if( !isset($numyear['meta_description_metatags_quick']) ){
                db_query('insert into {field_data_meta_description} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_description_metatags_quick) values (\'taxonomy_term\', \''.$tid->vocabulary_machine_name.'\', 0, '.$tid->tid.', '.$tid->tid.', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_desc']));
                db_query('insert into {field_revision_meta_description} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, meta_description_metatags_quick) values (\'taxonomy_term\', \''.$tid->vocabulary_machine_name.'\', 0, '.$tid->tid.', '.$tid->tid.', \'und\', 0, :combo)', array(':combo' =>$_REQUEST['pdxseo_desc']));
            }else{
                db_query('update {field_data_meta_description} set meta_description_metatags_quick=:combo where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid, array(':combo' =>$_REQUEST['pdxseo_desc']));
                db_query('update {field_revision_meta_description} set meta_description_metatags_quick=:combo where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid, array(':combo' =>$_REQUEST['pdxseo_desc']));
            }

        }else{
            db_query('delete from {field_revision_meta_description} where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid);
            db_query('delete from {field_data_meta_description} where entity_type=\'taxonomy_term\' and entity_id='.$tid->tid);
        }

        
    }else{
        if( strlen($_REQUEST['pdxseo_desc']) ){
            $pdxseo[urlencode($curpath)]['desc']=$_REQUEST['pdxseo_desc'];
        }else{
            $pdxseo[urlencode($curpath)]['desc']='';
        }
        $modified=1;
    }

}

if(isset($pdxseo[urlencode($curpath)]['h1']) and strlen($pdxseo[urlencode($curpath)]['h1'])){
    $pdxseo_h1=$pdxseo[urlencode($curpath)]['h1'];
}
if(isset($pdxseo[urlencode($curpath)]['title']) and strlen($pdxseo[urlencode($curpath)]['title'])){
    $pdxseo_title=$pdxseo[urlencode($curpath)]['title'];
}

if($modified){
    variable_set('pdxseo', serialize($pdxseo));
}

cache_clear_all('*', 'cache_field', true);

if( isset($_REQUEST['pdxseo_all_delete']) and strlen($_REQUEST['pdxseo_all_delete']) ){
    echo '<div>Все данные удалены</div>';
    variable_del('pdxseo');
}

echo '<form method="post" action="">';
echo 'Страница: <input type="text" value="'.$curpath.'" name="pathsynonym" size="57" />';
echo '<div>Заголовок TITLE</div><textarea style="width:100%;" name="pdxseo_title" rows="2">'.$pdxseo_title.'</textarea>';
echo '<div>Заголовок H1</div><textarea style="width:100%;" name="pdxseo_h1" rows="2">'.$pdxseo_h1.'</textarea>';
echo '<div>Ключевые слова</div><textarea style="width:100%;" name="pdxseo_keyword" rows="2">'.$pdxseo_keyword.'</textarea>';
echo '<div>Описание</div><textarea style="width:100%;" name="pdxseo_desc" rows="2">'.$pdxseo_desc.'</textarea>';
echo '<input type="submit" value="Отправить" />';
echo '</form>';
echo '<form method="post" action="">';
echo '<input type="hidden" value="1" name="pdxseo_all_delete" />';
//echo '<input type="submit" value="Удалить данные для всех страниц" />';
echo '</form>';
echo '<h3>Дополнительно</h3><ul>';
    if(module_exists('page_title')){
        echo '<li><a target="_blank" href="/admin/config/search/page-title">Изменение TITLE для группы страниц</a></li>';
    }
    if(module_exists('metatags_quick')){
        echo '<li><a target="_blank" href="/admin/config/search/metatags_quick">Изменение других данных для группы страниц</a></li>';
    }
    if(module_exists('metatag')){
        echo '<li><a target="_blank" href="/admin/config/search/metatags">Изменение данных для группы страниц</a></li>';
    }
    if(module_exists('pathauto')){
        echo '<li><a target="_blank" href="/admin/config/search/path/patterns">Шаблоны синонимов Pathauto</a></li>';
        echo '<li><a target="_blank" href="/admin/config/search/path/settings">Настройки Pathauto</a></li>';
        echo '<li><a target="_blank" href="/admin/config/search/path/update_bulk">Массовое обновление синонимов Pathauto</a></li>';
        echo '<li><a target="_blank" href="/admin/config/search/path/delete_bulk">Удаление синонимов Pathauto</a></li>';
    }
    if(module_exists('xmlsitemap')){
        echo '<li><a target="_blank" href="/admin/config/search/xmlsitemap/settings">Настройки XML Sitemap</a></li>';
    }
    if(module_exists('xmlsitemap_custom')){
        echo '<li><a target="_blank" href="/admin/config/search/xmlsitemap/custom">Добавить пользовательскую ссылку в XML Sitemap</a></li>';
    }
    if(module_exists('xmlsitemap_engines')){
        echo '<li><a target="_blank" href="/admin/config/search/xmlsitemap/engines">Поисковые системы для XML Sitemap</a></li>';
    }
echo '</ul>';
?>
</div></fieldset>

<?php

}

if( isset($_POST['deletesynonym']) and strlen($_POST['deletesynonym']) ){
    path_delete(array('source' => implode('/',arg())));
    echo 'Синоним удален';
}
if( isset($_POST['setsynonym']) and strlen($_POST['setsynonym']) ){
    $lang=LANGUAGE_NONE;
    if( is_numeric(arg(1)) ){
        $nd=db_query('select language from {node} where nid='.arg(1));
        $nd=$nd->fetchAssoc();
        if( isset($nd['language']) and strlen($nd['language']) ){
            $lang=$nd['language'];
        }
    }
    
    $_POST['setsynonym']=filter_xss(strip_tags($_POST['setsynonym']));
    path_delete(array('source' => implode('/',arg())));
    unset($path);
    $path['alias'] = $_POST['setsynonym'];
    $path['source'] = implode('/',arg());
    $path['language'] = $lang;
    path_save($path);    
    echo 'Синоним установлен';
}
echo '<form action="" method="post">';

echo '<input type="text" name="setsynonym" value="';
$path=db_query('select alias from {url_alias} where source=\''.implode('/',arg()).'\'');
$path=$path->fetchAssoc();
if( isset($path['alias']) and strlen($path['alias']) ){
    echo $path['alias'];
}
echo '" />';
echo '<input type="submit" value="Задать синоним для страницы '.implode('/',arg()).'" />';
echo '</form>';
echo '<form action="" method="post">';
echo '<input type="submit" name="deletesynonym" value="Удалить синоним для страницы '.implode('/',arg()).'" />';
echo '</form>';

  }
  print $content;
  ?>
  </div><div class="clear">&nbsp;</div></div>