<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_GET['go']) ){
    if( count($_GET)>1 ){
        unset($_GET['go']);
        
        $sqlto='';
        $sqlinner='';
        $sqlwhere='';
        $res=array();
        $fornames=array();
        $names=array();

        foreach($_GET as $name=>$val ){
            $name=str_replace('_selective','',$name);
            if( strpos($name,'field_')===false ){}else{
                $sqlto.=', '.str_replace('field_','',$name).'.'.$name;
                $forlabel=str_replace('field_','',$name);
                $res[$name]=array();
                
                $sqlinner.=' inner join {field_data_'.str_replace('_tid','',$name).'} as '.$forlabel.' on u.nid='.$forlabel.'.entity_id';
                
                if( is_numeric($val) and $val>0 ){
                    $sqlwhere.=' and '.$forlabel.'.'.$name.'='.$val;
                }
            }
        }

        $sql='select u.nid'.$sqlto.' from {node} as u'.$sqlinner.' where u.type=\'item\''.$sqlwhere;

        $nids=db_query($sql);
        while($nid=$nids->fetchAssoc()){
            foreach($nid as $name=>$val){
                if( isset($res[$name]) ){
                    if( !isset($res[$name][$val]) ){
                        $res[$name][$val]=1;
                        $fornames[]=$val;
                    }
                }
            }
        }
        $anames=db_query('select name, tid from {taxonomy_term_data} where tid IN ('.implode(',',$fornames).')');
        while($aname=$anames->fetchAssoc()){
            $names[$aname['tid']]=$aname['name'];
        }
        echo '<script type="text/javascript">';
        foreach($res as $name=>$vals){
            echo ' jQuery("select[name=\''.$name.'_selective\'] option, select[name=\''.$name.'\'] option").each(
                function(){
                    if(jQuery(this).attr("value")!="All"){
                        jQuery("select[name=\''.$name.'_selective\'] option[value="+jQuery(this).attr("value")+"], select[name=\''.$name.'\'] option[value="+jQuery(this).attr("value")+"]").remove();
                    }
                }
            );';
            if( isset($vals) and is_array($vals) and count($vals) ){
                foreach($vals as $val=>$i){
                    echo 'jQuery("select[name=\''.$name.'_selective\'], select[name=\''.$name.'\']").append("<option value=\''.$val.'\'';
                    if( isset($_GET[$name.'_selective']) and $_GET[$name.'_selective']==$val ){
                        echo ' selected=\'selected\'';
                    }elseif( isset($_GET[$name]) and $_GET[$name]==$val ){
                        echo ' selected=\'selected\'';
                    }
                    echo '>';
                    if( isset($names[$val]) ){
                        echo $names[$val];
                    }else{
                        echo 'none';
                    }
                    echo '</option>"); ';
                }
            }
        }
        echo '</script>';
        
        echo '<script type="text/javascript"> jQuery("#fliterajaxprocess").remove(); </script>';
    }
}

?>