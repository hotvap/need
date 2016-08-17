<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


global $user; if(isset($user->roles[3]) or isset($user->roles[4])){

$nids=db_query('select nid, type from {node} where type like \'item\' order by nid');
while( $nid=$nids->fetchAssoc() ){
    $entity=node_load($nid['nid']);
    if( !isset($entity->nid) ){
        continue;
    }
    $avals= $entity->title.' N1'.$entity->nid;

if( isset($entity->field_brand['und'][0]['tid']) and is_numeric($entity->field_brand['und'][0]['tid']) ){
    $name=db_query('select name from {taxonomy_term_data} where tid='.$entity->field_brand['und'][0]['tid']);
    $name=$name->fetchAssoc();
    if( isset($name['name']) and strlen($name['name']) ){
        $avals .= " ".$name['name'];
    }
}

if( isset($entity->field_tags['und'][0]['tid']) and is_numeric($entity->field_tags['und'][0]['tid']) ){
    $name=db_query('select name from {taxonomy_term_data} where tid='.$entity->field_tags['und'][0]['tid']);
    $name=$name->fetchAssoc();
    if( isset($name['name']) and strlen($name['name']) ){
        $avals .= " ".$name['name'];
    }
}

if( isset($entity->field_subpart['und'][0]['tid']) and is_numeric($entity->field_subpart['und'][0]['tid']) ){
    $name=db_query('select name from {taxonomy_term_data} where tid='.$entity->field_subpart['und'][0]['tid']);
    $name=$name->fetchAssoc();
    if( isset($name['name']) and strlen($name['name']) ){
        $avals .= " ".$name['name'];
    }
}

if( isset($entity->taxonomy_catalog['und'][0]['tid']) and is_numeric($entity->taxonomy_catalog['und'][0]['tid']) ){
    $name=db_query('select name from {taxonomy_term_data} where tid='.$entity->taxonomy_catalog['und'][0]['tid']);
    $name=$name->fetchAssoc();
    if( isset($name['name']) and strlen($name['name']) ){
        $avals .= " ".$name['name'];
    }
}

$avals=strip_tags($avals);
$avals=filter_xss($avals);
$avals=str_replace("\r","\n",$avals);
$avals=str_replace("\n\n","\n",$avals);
$avals=str_replace("\n", " ", $avals);
$avals=str_replace("	", " ",  $avals);
$avals=str_replace('  ', ' ', $avals);
$avals=str_replace('  ', ' ', $avals);
$avals=str_replace('  ', ' ', $avals);
$avals=str_replace('  ', ' ', $avals);
$avals=str_replace('  ', ' ', $avals);
$avals=str_replace('  ', ' ', $avals);
$avals=str_replace('  ', ' ', $avals);

if( mb_strlen($avals)>3333 ){
    $avals=mb_substr($avals, 0, 3327).'...';
}


    
    if( isset($avals) and strlen($avals) ){
        db_query('delete from {field_data_field_search} where entity_type=\'node\' and entity_id='.$nid['nid']);
        db_query('insert into {field_data_field_search} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_search_value) values (\'node\', \'item\', 0, '.$nid['nid'].', '.$nid['nid'].', \'und\', 0, :combo)', array(':combo'=>$avals));
//            $entity_field[$delta]['value']=$aval;
        echo $nid['nid'].'<br />';
    }

 

/*
        $pr=db_query('select field_search_value from {field_data_field_search} where entity_id='.$nd->nid);
        $pr=$pr->fetchAssoc();
        if( isset($pr['field_search_value']) ){
            db_query('update {field_data_field_search} set field_search_value=:combo where entity_type=\'node\' and entity_id='.$nd->nid, array(':combo'=>$out));
        }else{
            db_query('insert into {field_data_field_search} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_search_value) values (\'node\', \''.$nd->type.'\', 0, '.$nd->nid.', '.$nd->nid.', \'und\', 0, :combo)', array(':combo'=>$out));
        }
*/

}
}

?>