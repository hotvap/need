<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

global $user;
if(isset($user->roles[3]) or isset($user->roles[4])){
    
    if( isset( $_POST['op'] ) ){
        $anids=array();
        foreach($_POST as $tmpid=>$tmpval ){
            if( strpos($tmpid, 'nidis_')===false ){
                continue;
            }
            $tmpid=str_replace('nidis_', '', $tmpid);
            if( !is_numeric($tmpid) ){
                continue;
            }
            if( $tmpval==0 ){
                $anids[$tmpid]=array();
            }
        }
        if( isset($anids) and is_array($anids) and count($anids) ){
            foreach($_POST as $tmpid=>$tmpval ){
                if( strpos($tmpid, 'nidis_')===false ){
                    continue;
                }
                $tmpid=str_replace('nidis_', '', $tmpid);
                if( !is_numeric($tmpid) ){
                    continue;
                }

                foreach($anids as $tmpid1=>$tmpval1 ){
                    if( $tmpval==$tmpid1 ){
                        $anids[$tmpid1][]=$tmpid;
                    }
                }
            }
        echo '<pre>'.print_r($anids, true). '</pre>';
        }
    }
    
    $anids=pdxgethelpmenu();
    $rnids=array();
    if( isset($anids) and is_array($anids) and count($anids) ){
        foreach($anids as $tmpid1=>$tmpval1 ){
            if( isset($tmpval1) and is_array($tmpval1) and count($tmpval1) ){
                foreach($tmpval1 as $tmpid2=>$tmpval2 ){
                    if( isset($tmpval2) and is_array($tmpval2) and count($tmpval2) ){
                        foreach($tmpval2 as $tmpid3=>$tmpval3 ){
                        }
                    }elseif( is_numeric($tmpval2) ){
                        $rnids[$tmpval2]=$tmpid1;
                    }
                }
            }
        }
    }
    
    $items=array();
    
    echo '<form action="" method="post"><table><tr><th>Статья</th><th>Вложена в</th></tr>';
    $outs=db_query('select nid, title from {node} where type=\'article\' and status=1');
    while( $out=$outs->fetchAssoc() ){
        $items[$out['nid']]=$out;
    }
    if( isset($items) and is_array($items) and count($items) ){
        foreach($items as $tmpid=>$tmpval ){
            echo '<tr>';
            echo '<td><a target="_blank" href="/node/'.$tmpval['nid'].'/edit">'.$tmpval['title'].'</a></td><td><select name="nidis_'.$tmpval['nid'].'"><option value="0">нет</option>';
            foreach($items as $tmpid1=>$tmpval1 ){
                echo '<option';
                if( isset($rnids[$tmpval['nid']]) and $rnids[$tmpval['nid']]==$tmpval1['nid'] ){
                    echo ' selected="selected"';
                }
                echo ' value="'.$tmpval1['nid'].'">'.$tmpval1['title'].'</option>';
            }
            echo '</select></td></tr>';
        }
    }
    echo '</table><div style="padding-top: 21px;"><input type="submit" name="op" value="Изменить" /></div></form>';
    
}


//    @file_get_contents('http://help.needto.me/render/start.php');

?>