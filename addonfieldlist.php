<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['field']) and strlen($_POST['field']) and isset($_POST['type']) and strlen($_POST['type']) and isset($_POST['nids']) and strlen($_POST['nids']) ){
    $_POST['field']= filter_xss( strip_tags($_POST['field']) );
    $_POST['type']= str_replace( "'", '', str_replace( '"', '', filter_xss( strip_tags($_POST['type']) ) ));
    $_POST['nids']=explode('_', $_POST['nids']);
    if( isset($_POST['nids']) and is_array($_POST['nids']) and count($_POST['nids']) ){
        foreach($_POST['nids'] as $nidid=>$nidval ){
            $_POST['nids'][$nidid]=trim($nidval);
            if( !is_numeric($_POST['nids'][$nidid]) ){
                unset($_POST['nids'][$nidid]);
            }
        }
        if( isset($_POST['nids']) and is_array($_POST['nids']) and count($_POST['nids']) ){
            $list=db_query('select i.data, f.type, f.data as data2 from {field_config_instance} as i inner join {field_config} as f on i.field_id=f.id where i.field_name=:combo2 and i.bundle=:combo', array(':combo'=>$_POST['type'], ':combo2'=>$_POST['field']));
            $list=$list->fetchAssoc();
            if( isset($list['type']) and strlen($list['type']) ){
                $list['data']=unserialize($list['data']);
                echo '<script type="text/javascript"> 
                jQuery(\'#isaddonfieldlabel\').html(\''.str_replace("'", "", $list['data']['label']).'\');
                 </script>';
                 
                 switch( $list['type'] ){
                 case 'list_boolean':
                    echo '<script type="text/javascript"> ';
                    foreach($_POST['nids'] as $nid ){
                        echo 'jQuery(\'#addonfield_'.$nid.'\').html(\'<input id="addonfieldis'.$nid.'" type="checkbox"';
                        $curval=db_query('select '.$_POST['field'].'_value from {field_data_'.$_POST['field'].'} where entity_id='.$nid);
                        $curval=$curval->fetchAssoc();
                        if( isset($curval[$_POST['field'].'_value']) and $curval[$_POST['field'].'_value']==1 ){
                            echo ' checked="checked"';
                        }
                        echo ' /> ';
                        echo '\'); ';
                        echo ' jQuery(\'#addonfieldis'.$nid.'\').bind(\'change\',
                            function(){
                                var status=0;
                                if( jQuery(this).attr(\'checked\')==true ){ status=1; }
                                admchange('.$nid.', \'field\', \''.$_POST['field'].'\', status, \'value\');
                            }
                        ); ';
                        
                    }
                    echo ' </script>';
                    break;
                 case 'text_long':
                    echo '<script type="text/javascript"> ';
                    foreach($_POST['nids'] as $nid ){
                        echo 'jQuery(\'#addonfield_'.$nid.'\').html(\'<textarea style="border: 1px solid #000;" id="addonfieldis'.$nid.'" cols="21" rows="9" >';
                        $curval=db_query('select '.$_POST['field'].'_value from {field_data_'.$_POST['field'].'} where entity_id='.$nid);
                        $curval=$curval->fetchAssoc();
                        if( isset($curval[$_POST['field'].'_value']) and strlen($curval[$_POST['field'].'_value']) ){
                            echo $curval[$_POST['field'].'_value'];
                        }
                        echo ' </textarea> ';
                        echo '\'); ';
//*
                        echo ' jQuery(\'#addonfieldis'.$nid.'\').bind(\'change\',
                            function(){
                                if( jQuery(this).val() ){ 
                                    admchange('.$nid.', \'field\', \''.$_POST['field'].'\', jQuery(this).val(), \'value\');
                                }else{
                                    admdel('.$nid.', \'field\', \''.$_POST['field'].'\', \'value\');
                                }
                            }
                        ); ';
//*/                        
                    }
                    echo ' </script>';
                    break;
                 case 'node_reference':
                    $list['data2']=unserialize($list['data2']);
                    $terms=array();
                    $items=array();
                    if( isset($list['data2']['settings']['referenceable_types']) and is_array($list['data2']['settings']['referenceable_types']) and count($list['data2']['settings']['referenceable_types']) ){
                        foreach( $list['data2']['settings']['referenceable_types'] as $item ){
                            if( isset($item) and strlen($item) and $item<>'0' ){
                                $items[]=$item;
                            }
                        }
                    }
                    if( isset($items) and is_array($items) and count($items) ){
                        
                        $addoninner='';
                        $addonwhere='';
                        switch($_POST['field']){
                        case 'field_isico':
                            $addoninner=' inner join {field_data_field_alltype} as f on n.nid=f.entity_id';
                            $addonwhere=' f.field_alltype_value=\''.$_POST['type'].'\' and ';
                            break;
                        }
                        
                        $items=db_query('select n.nid, n.title from {node} as n'.$addoninner.' where'.$addonwhere.' n.status=1 and n.type in (\''.implode('\',\'',$items).'\')');
                        while( $item=$items->fetchAssoc() ){
                            $terms[$item['nid']]=$item['title'];
                        }
                        
                        foreach($_POST['nids'] as $nid ){
                            $items=array();
                            $outs=db_query('select '.$_POST['field'].'_nid from {field_data_'.$_POST['field'].'} where entity_type=\'node\' and entity_id='.$nid);
                            while($out=$outs->fetchAssoc()){
                                $items[]=$out[$_POST['field'].'_nid'];
                            }

                                    echo '<script type="text/javascript"> ';
                                    echo 'jQuery(\'#addonfield_'.$nid.'\').html(\'';
                                    echo '<select size="11" multiple="multiple" id="addonfieldis'.$nid.'">';
                                    foreach($terms as $termnid=>$term){
                                        echo '<option value="'.$termnid.'"';
                                        if( array_search($termnid, $items)===false ){}else{
                                            echo ' selected="selected"';
                                        }
                                        echo '>'.$term.'</option>';
                                    }
                                    echo '</select>';
                                    echo '\'); ';
    
                                    echo ' jQuery(\'#addonfieldis'.$nid.'\').bind(\'change\',
                                        function(){
                                            if( jQuery(this).val() ){
                                                admchangemulti('.$nid.', \'fieldmulti\', \''.$_POST['field'].'\', this, \'nid\' );
                                            }else{
                                                admdel('.$nid.', \'field\', \''.$_POST['field'].'\', \'nid\');
                                            }
                                        }
                                    ); ';
                                    
                                    echo ' </script>';

                        }
//                        foreach( as ){ }
                    }
                    break;
                 case 'taxonomy_term_reference':
                    $list['data2']=unserialize($list['data2']);
                    if( isset($list['data2']['settings']['allowed_values'][0]['vocabulary']) and strlen($list['data2']['settings']['allowed_values'][0]['vocabulary']) ){
                        $vid=db_query('select vid from {taxonomy_vocabulary} where machine_name=:combo', array(':combo'=>$list['data2']['settings']['allowed_values'][0]['vocabulary']));
                        $vid=$vid->fetchAssoc();
                        if( isset($vid['vid']) and is_numeric($vid['vid']) ){
                            $vid=$vid['vid'];

                            $terms = taxonomy_get_tree($vid);
                            foreach($_POST['nids'] as $nid ){

                                $items=array();
                                $outs=db_query('select '.$_POST['field'].'_tid from {field_data_'.$_POST['field'].'} where entity_type=\'node\' and entity_id='.$nid);
                                while($out=$outs->fetchAssoc()){
                                    $items[]=$out[$_POST['field'].'_tid'];
                                }
    
                                if($terms){
                                    echo '<script type="text/javascript"> ';
                                    echo 'jQuery(\'#addonfield_'.$nid.'\').html(\'';
                                    echo '<select size="11" multiple="multiple" id="addonfieldis'.$nid.'">';
                                    foreach($terms as $term){
                                        echo '<option value="'.$term->tid.'"';
                                        if( array_search($term->tid, $items)===false ){}else{
                                            echo ' selected="selected"';
                                        }
                                        echo '>'.$term->name.'</option>';
                                    }
                                    echo '</select>';
                                    echo '\'); ';
    
                                    echo ' jQuery(\'#addonfieldis'.$nid.'\').bind(\'change\',
                                        function(){
                                            if( jQuery(this).val() ){
                                                admchangemulti('.$nid.', \'fieldmulti\', \''.$_POST['field'].'\', this, \'tid\' );
                                            }else{
                                                admdel('.$nid.', \'field\', \''.$_POST['field'].'\', \'tid\');
                                            }
                                        }
                                    ); ';
                                    
                                    echo ' </script>';
                                }
                                
                            }
                        }
                    }
                    break;
                 case 'number_decimal':
                 case 'text':
                 case 'number_integer':
                    echo '<script type="text/javascript"> ';
                    foreach($_POST['nids'] as $nid ){
                        $countvals=array();
                        echo 'jQuery(\'#addonfield_'.$nid.'\').html(\'';
                        $curvals=db_query('select '.$_POST['field'].'_value, delta from {field_data_'.$_POST['field'].'} where entity_id='.$nid);
                        while( $curval=$curvals->fetchAssoc() ){
                            $countvals[]=$curval['delta'];
                            echo '<input size="7" style="border: 1px solid #000;" type="text" id="addonfieldis'.$nid.'_'.$curval['delta'].'" value="';
                            echo str_replace('"', "'", $curval[$_POST['field'].'_value']);
                            echo '" />';
                        }
                        echo '\'); ';
                        if( isset($countvals) and is_array($countvals) and count($countvals) ){
                            foreach( $countvals as $countval ){
                                echo ' jQuery(\'#addonfieldis'.$nid.'_'.$countval.'\').bind(\'change\',
                                    function(){
                                        if( jQuery(this).val() ){ 
                                            admchangedelta('.$nid.', \'field\', \''.$_POST['field'].'\', jQuery(this).val(), \'value\', '.$countval.');
                                        }
                                    }
                                ); ';
                            }
                        }else{
                            echo 'jQuery(\'#addonfield_'.$nid.'\').html(\'';
                            echo '<input size="7" style="border: 1px solid #000;" type="text" id="addonfieldis'.$nid.'_0" value="" />';
                            echo '\'); ';
                            echo ' jQuery(\'#addonfieldis'.$nid.'_0\').bind(\'change\',
                                function(){
                                    if( jQuery(this).val() ){ 
                                        admchangedelta('.$nid.', \'field\', \''.$_POST['field'].'\', jQuery(this).val(), \'value\', 0);
                                    }
                                }
                            ); ';
                        }

//*
//*/                        
                    }
                    echo ' </script>';
                    break;
                 default:
                    echo 'Поля типа "'.$list['type'].'" не поддерживаются';
                 }
            }
        }
    }
    echo '';
}

?>