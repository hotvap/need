<?php


if( isset( $_POST['tid'] ) and strlen( $_POST['tid'] ) and isset( $_POST['cat'] ) and strlen( $_POST['cat'] ) and isset( $_POST['city'] ) and is_numeric( $_POST['city'] ) ){
    
    $mtid=$mcat=array();
    $_POST['tid']=explode('|', $_POST['tid']);
    if( isset($_POST['tid']) and is_array($_POST['tid']) and count($_POST['tid']) ){
        foreach($_POST['tid'] as $tmpid=>$tmpval ){
            if( !is_numeric($tmpval) ){
                unset($_POST['tid'][$tmpid]);
                break;
            }
            if( $tmpval>0 ){
                $mtid[]=$tmpval;
            }
        }
    }
    $_POST['cat']=explode('|', $_POST['cat']);
    if( isset($_POST['cat']) and is_array($_POST['cat']) and count($_POST['cat']) ){
        foreach($_POST['cat'] as $tmpid=>$tmpval ){
            if( !is_numeric($tmpval) ){
                unset($_POST['cat'][$tmpid]);
                break;
            }
            if( $tmpval>0 ){
                $mcat[]=$tmpval;
            }
        }
    }

    if( file_exists('pdxcache/maps/'.$_POST['city'].'_'.implode('--',$_POST['tid']).'_'.implode('--',$_POST['cat'])) and filesize('pdxcache/maps/'.$_POST['city'].'_'.implode('--',$_POST['tid']).'_'.implode('--',$_POST['cat'])) ){
        echo @file_get_contents('pdxcache/maps/'.$_POST['city'].'_'.implode('--',$_POST['tid']).'_'.implode('--',$_POST['cat']));
        
    }else{

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    
    $out=$sqlinner=$sqlwhere='';
    if( isset( $mtid ) and is_array($mtid) and count($mtid) ){
        $sqlinner.=' inner join {field_data_field_rn} as r on (o.entity_id=r.entity_id and r.entity_type=\'user\')';
        $sqlwhere=' and r.field_rn_nid IN ('.implode(',', $mtid).')';
    }
    if( isset( $mcat ) and is_array($mcat) and count($mcat) ){
        $sqlinner.=' inner join {field_data_field_parts} as p on (o.entity_id=p.entity_id and p.entity_type=\'user\')';
        $sqlwhere=' and p.field_parts_tid IN ('.implode(',', $mcat).')';
    }
    
    $points=array();
    $num=$longitude=$latitude=0;
    $uids=db_query('select o.entity_id from {field_data_field_longitude} as o inner join {field_data_field_latitude} as a on (o.entity_id=a.entity_id and a.entity_type=\'user\' and o.delta=a.delta) inner join {field_data_field_address} as d on (o.entity_id=d.entity_id and d.entity_type=\'user\' and o.delta=d.delta) inner join {field_data_field_items_active} as v on (o.entity_id=v.entity_id and v.entity_type=\'user\')'.$sqlinner.' where v.field_items_active_value>0 and o.entity_type=\'user\''.$sqlwhere.' limit 0, 77');
    while( $uid=$uids->fetchAssoc() ){
        $usr=prepuser($uid['entity_id']);

        $companyname='';
        if( isset($usr->field_who['und'][0]['value']) and $usr->field_who['und'][0]['value']==2 ){
            if( isset($usr->field_company_name['und'][0]['value']) and strlen($usr->field_company_name['und'][0]['value']) ){
                $companyname=$usr->field_company_name['und'][0]['value'].' ('.$usr->field_name['und'][0]['value'].')';
            }else{
                $companyname=$usr->field_name['und'][0]['value'];
            }
        }else{
            $companyname=$usr->field_name['und'][0]['value'];
        }
        $companyname=str_replace('"', '', $companyname);
        
        $txtout='';

        if( isset($usr->picture->uri) and strlen($usr->picture->uri) ){
            $uri = image_style_url('user', $usr->picture->uri);
        }else{
            if( isset($usr->field_company_logo['und'][0]['uri']) and strlen($usr->field_company_logo['und'][0]['uri']) ){
                $uri = image_style_url('logosm', $usr->field_company_logo['und'][0]['uri']);
            }else{
                $uri = PDX_IMGPATH.'/img/happy2.png';
            }
        }
        if( isset($uri) and strlen($uri) ){
            $txtout.='<img style=\'float: left; margin: 0 11px 11px 0; \' alt=\'\' src=\''.$uri.'\' />';
        }
        
        $txtout.='<div><a href=\''.url('user/'.$usr->uid, array('absolute'=>TRUE)).'\'>Компания <strong>'.$companyname.'</strong></a></div><div class="m_addr">'.$usr->field_address['und'][0]['value'].'</div><div class=\'clear\'>&nbsp;</div>';

        $isset=db_query('select uid from {pm_disable} where uid='.$usr->uid);
        $isset=$isset->fetchAssoc();
        if( !isset($isset['uid']) ){
            $txtout.='<div class=\'sendpm\' style=\'display: none;\'><a href=\''.$GLOBALS['base_url'].'/messages/new?to='.$usr->uid.'\' onclick=\' sendpmgen(this); \'>Отправить сообщение</a></div>';
        }

        $points[$num]['title']=str_replace('"', '', $txtout);
        if( mb_strlen($companyname)>19 ){
            $companyname=mb_substr($companyname,0,16).'...';
        }
        $points[$num]['htitle']=$companyname;
        $points[$num]['longitude']=$usr->field_longitude['und'][0]['value'];
        $points[$num]['latitude']=$usr->field_latitude['und'][0]['value'];
        $points[$num]['address']=$usr->field_address['und'][0]['value'];

        $longitude=$usr->field_longitude['und'][0]['value'];
        $latitude=$usr->field_latitude['und'][0]['value'];
        
        $num++;
    }
    if( isset($points) and is_array($points) and count($points) ){ 
                        
                        $out.= '
<script type="text/javascript">
   ymaps.ready(init);
   var myMap;
   function init () {
       myMap = new ymaps.Map("map_canvas_profiles", {
            center: ['.$longitude.', '.$latitude.'],
            behaviors: [\'default\', \'scrollZoom\'],
            zoom: ';
            if( isset($mtid) and is_array($mtid) and count($mtid) and isset($mtid[0]) and $mtid[0]==0 ){
                $out.='10';
            }else{
                $out.='12';
            }
$out.='
       }, {
            searchControlProvider: \'yandex#search\'
       });
';

if(is_array($points) and count($points)){
    foreach($points as $num=> $point){
        $out.= 'myPlacemark'.$num.' = new ymaps.Placemark(['.$point['longitude'].','.$point['latitude'].'], {
        iconContent: "'.$point['htitle'].'", balloonContent: "'.$point['title'].'"
	}, {
    preset: "islands#blueStretchyIcon",
    openEmptyBalloon: true
});

myMap.geoObjects
	.add(myPlacemark'.$num.');';
//        if( $num==0 ){
//            $out.= 'myPlacemark'.$num.'.balloon.open();';
//        }
    }
}
        $out.= 'myMap.behaviors.disable(\'scrollZoom\');';
        $out.= '
   }

jQuery(\'#map_canvas_profiles\').show();

</script> ';
    
 }



        if( isset($out) and strlen($out) ){
            echo $out;
            $fp=fopen('pdxcache/maps/'.$_POST['city'].'_'.implode('--',$_POST['tid']).'_'.implode('--',$_POST['cat']), "w");
            if($fp){
                fwrite($fp, $out); fclose($fp);
            }
        }

        
    }

}


?>
