<?php

if( isset( $_GET['nid'] ) and is_numeric($_GET['nid']) and $_GET['nid']>0 ){

    if( file_exists('pdxcache/findmy/'.$_GET['nid']) and filesize('pdxcache/findmy/'.$_GET['nid']) ){
        echo @file_get_contents('pdxcache/findmy/'.$_GET['nid']);
    }else{
        
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        

        $out='';

        $node=node_load($_GET['nid']);
        
        if( isset($node->nid) and $node->nid>0 ){

            if( isset( $node->field_str2['und'][0]['value'] ) and strlen( $node->field_str2['und'][0]['value'] ) ){
                $out.= '<div class="more"><strong>Готов заплатить: </strong>'.$node->field_str2['und'][0]['value'].'</div>';
            }
            $out.= '<div class="more"><strong>Автор: </strong>';
            if( $node->uid>0 ){
                $name=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$node->uid);
                $name=$name->fetchAssoc();
                $out.= '<a target="_blank" href="/user/'.$node->uid.'">'.$name['field_name_value'].'</a>';
            }else{
                $out.= 'гость';
            }
            $out.= '</div>';

            if( $node->uid>0 ){
                $num=db_query('select field_userrat2_value from {field_data_field_userrat2} where entity_type=\'user\' and entity_id='.$node->uid);
                $num=$num->fetchAssoc();
                if( isset($num['field_userrat2']) and is_numeric($num['field_userrat2']) and $num['field_userrat2']>0 ){
                    $out.= '<div class="user_rat">';
                    $num['field_userrat2']=intval($num['field_userrat2']/10);
    
                    if( $num['field_userrat2']>=1 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=2 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=3 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=4 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=5 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=6 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=7 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=8 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']>=9 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $num['field_userrat2']==10 ){ $out.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $out.= '<div class="ratis ratn">&nbsp;</div>'; }
                    $out.= '</div>';
                }
            }
            $out.= '<div>&nbsp;</div>';


            $outtmp='';
            if( isset($node->field_skype['und'][0]['value']) and strlen($node->field_skype['und'][0]['value']) ){
                $node->field_skype['und'][0]['value']=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($node->field_skype['und'][0]['value']))));
                $outtmp.= '<div class="user_contact"><a rel="nofollow" title="Skype пользователя" class="user_skype" href="skype:'.$node->field_skype['und'][0]['value'].'">'.$node->field_skype['und'][0]['value'].'</a></div>';
            }
            if( isset($node->mail) and strlen($node->mail) and ( !isset( $node->field_notemail['und'][0]['value'] ) or $node->field_notemail['und'][0]['value']!=1 ) ){
                $outtmp.= '<div class="user_contact"><a rel="nofollow" class="user_email" href="mailto:'.$node->field_email['und'][0]['email'].'">'.$node->field_email['und'][0]['email'].'</a></div>';
            }
            if( isset($node->field_phones['und'][0]['value']) and strlen($node->field_phones['und'][0]['value']) ){
                $outtmp.= '<div class="user_contact">';
                foreach( $node->field_phones['und'] as $phone ){
                    $phone['value']=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($phone['value']))));
                    $outtmp.= '<div><a rel="nofollow" class="user_phone" href="tel:'.str_replace('-','',str_replace(' ','',str_replace('(','',str_replace(')','',$phone['value'])))).'">';
                    $outtmp.= $phone['value'];
                    $outtmp.= '</a></div>';
                }
                $outtmp.= '</div>';
            }
            


            if( isset($outtmp) and strlen($outtmp) ){
                
                $out.= '<div class="user_contacts"><noindex>';
                $out.= '<div class="user_ttl">Для связи:</div>';
                $out.= '<script type="text/javascript"> ';
                $out.= 'var carr = new Array();';
                $cnt=0;
                while(1==1){
                    if( !mb_strlen($outtmp) ){
                        break;
                    }
                    $tmp=mb_substr($outtmp, 0, 3);
                    $outtmp=mb_substr($outtmp, 3);
                    $out.= 'carr['.($cnt++).']=\''.$tmp.'\';';
                }
                $out.= 'if( carr.length ){
                    var cares=\'\';
                    for( var i=0; i<carr.length; i++ ){
                        cares+=carr[i];
                    }';
                if( $node->uid>0 ){
                    $out.= '
                    cares+=\'<div class="user_contact sendpm" style="display: none;"><a href="/messages/new?to='.$node->uid.'" onclick=" sendpmgen(this); ">Написать личное сообщение</a></div>\';';
                }
                $out.= 'jQuery(\'#findmy_nid_'.$_GET['nid'].' .user_contacts_cnt\').html(cares); 
                }';
                $out.= ' </script>';
                $out.= '<div class="user_contacts_cnt">';
                $out.= '</div>';
                $out.= '</noindex></div>';
            }


            
            if(isset($node->field_image2['und'][0]['uri']) and strlen($node->field_image2['und'][0]['uri'])){
                $uri = image_style_url('news_preview', $node->field_image2['und'][0]['uri']);
                if(isset($uri) and strlen($uri)){
                    $out.= '<div class="findmy_image"><img alt="" src="'.$uri.'" /></div>';
                }
            }
            if( isset( $node->field_ask['und'][0]['value'] ) and strlen( $node->field_ask['und'][0]['value'] ) ){
                $out.= '<div class="about"><strong>Подробнее: </strong>'.nl2br($node->field_ask['und'][0]['value']).'</div>';
            }
            
            $out.= '<div class="link"><strong>Ссылка на заявку: </strong><input type="text" value="'.$GLOBALS['base_url'].'/findmy#findmy_nid_'.$node->nid.'" size="27" onclick=" jQuery(this).select(); " /></div>';
            $out.= '<div class="clear">&nbsp;</div>';

        }else{
            $out='&nbsp;';
        }

        if( isset($out) and strlen($out) ){
            echo $out;
            $fp=fopen('pdxcache/findmy/'.$_GET['nid'], "w");
            if($fp){
                fwrite($fp, $out); fclose($fp);
            }
        }

//        echo '<div class="user_flag">'.flag_create_link('users', $_GET['nid']).'</div>';    
        
    }
    
    
}


?>