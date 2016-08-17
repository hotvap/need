<?php // node template
global $user; if( $user->uid ){
    $skipgoto=0;
    if( isset( $node->field_user1['und'][0]['uid'] ) and is_numeric( $node->field_user1['und'][0]['uid'] ) and $node->field_user1['und'][0]['uid']==$user->uid ){
        $skipgoto=1;
    }elseif( isset( $node->field_user2['und'][0]['uid'] ) and is_numeric( $node->field_user2['und'][0]['uid'] ) and $node->field_user2['und'][0]['uid']==$user->uid ){
        $skipgoto=2;
    }
    if( !$skipgoto ){
        drupal_goto('node', array(), 301);
    }
}else{
    drupal_goto('node', array(), 301);
}
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <div class="node-container">
    <?php if ($display_submitted): ?>
      <div class="submitted-info">
      <span class="node-date"><?php echo date('d', $node->created) .' '. pdxneedto_month_declination_ru(format_date($node->created,'custom','F'),date('n',$node->created)) .' '. date('Y', $node->created); ?></span>
      <?php if(!$node->status): ?>
        <span class="node-status-unpublished"><?php print t('unpublished'); ?></span>
      <?php endif; ?>
      </div>
    <?php endif; ?>

    <article class="nodecontent"<?php print $content_attributes; ?>>
      <?php
         hide($content['comments']);
         hide($content['links']);
      ?>
      <?php print render($title_prefix); ?>
        <div id="site_title"><?php
        echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/ostavlyaem-otzyv-o-kachestve-tovara-i-samoy-uslugi.html">&nbsp;</a>';
        ?><h1 id="page-title">Сделка от <?php
        echo date('j', $node->created).' '.mb_strtolower(pdxneedto_month_declination_ru(format_date($node->created,'custom','F'),date('n',$node->created))).' '.date('Y', $node->created).' года';
        ?></h1></div>
      <?php print render($title_suffix); ?>
      <?php print render($content);
      
      if( isset( $node->field_period_ed['und'][0]['value'] ) and is_numeric( $node->field_period_ed['und'][0]['value'] ) and isset( $node->field_period_num['und'][0]['value'] ) and is_numeric( $node->field_period_num['und'][0]['value'] ) ){
        $edstr='';
        switch($node->field_period_ed['und'][0]['value']){
        case 1:
            $edstr=pdxnumfoot($node->field_period_num['und'][0]['value'], 'час', 'часов', 'часа');
            break;
        case 2:
            $edstr=pdxnumfoot($node->field_period_num['und'][0]['value'], 'день', 'дней', 'дня');
            break;
        case 3:
            $edstr=pdxnumfoot($node->field_period_num['und'][0]['value'], 'неделя', 'недель', 'недели');
            break;
        case 4:
            $edstr=pdxnumfoot($node->field_period_num['und'][0]['value'], 'месяц', 'месяцев', 'месяца');
            break;
        }
        if( isset($edstr) and strlen($edstr) ){
            echo '<div><strong>Период: </strong>'.$edstr.'</div>';
        }
      }
      
      if( isset( $node->field_user1['und'][0]['uid'] ) and is_numeric($node->field_user1['und'][0]['uid']) ){
        $us1=prepuser($node->field_user1['und'][0]['uid']);
        
        $val='Неизвестно';
        echo '<div class="dealuserblock">';
        echo '<div class="dealuserblock_ttl">';
        if( isset($us1->field_name['und'][0]['value']) and strlen($us1->field_name['und'][0]['value']) ){
            $val=$us1->field_name['und'][0]['value'];
        }
        $path=path_load('user/'.$node->field_user1['und'][0]['uid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='user/'.$node->field_user1['und'][0]['uid'];
        echo '<div class="text_user_'.$node->field_user1['und'][0]['uid'].'"><strong>Арендодатель: </strong><br />';

        if( isset($us1->field_userrat['und'][0]['value']) and is_numeric($us1->field_userrat['und'][0]['value']) and $us1->field_userrat['und'][0]['value']>0 ){
            $us1->field_userrat['und'][0]['value']=intval($us1->field_userrat['und'][0]['value']/10);
                echo '<div class="user_rat">';
                if( $us1->field_userrat['und'][0]['value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us1->field_userrat['und'][0]['value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
            }
                    
        echo '<a target="_blank" href="/'.$path.'">'.$val.'</a>';


        
        echo '</div>';
        echo '</div>';
        
        if( isset( $node->field_agree1['und'][0]['value'] ) and $node->field_agree1['und'][0]['value']==1 ){
            if( isset($us1->mail) and strlen($us1->mail) and valid_email_address($us1->mail) ){
                $us1->mail=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($us1->mail))));
                echo '<div class="dealcontacts dealcontacts_mail"><a rel="nofollow" class="user_email" href="mailto:'.$us1->mail.'">'.$us1->mail.'</a></div>';
            }
    
            echo '<div class="dealcontacts dealcontacts_phone">';
            if( isset($us1->field_phones['und']) and is_array($us1->field_phones['und']) and count($us1->field_phones['und']) ){
                foreach( $us1->field_phones['und'] as $val ){
                    $us1->field_phones['und'][0]['value']=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($us1->field_phones['und'][0]['value']))));
                    echo '<div><a rel="nofollow" class="user_phone" href="tel:'.str_replace('-','',str_replace(' ','',str_replace('(','',str_replace(')','',$us1->field_phones['und'][0]['value'])))).'">'.$us1->field_phones['und'][0]['value'].'</a></div>';
                }
            }
            echo '</div>';
    
            if( isset($us1->field_skype['und'][0]['value']) and strlen($us1->field_skype['und'][0]['value']) ){
                $us1->field_skype['und'][0]['value']=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($us1->field_skype['und'][0]['value']))));
                echo '<div class="dealcontacts dealcontacts_skype"><a rel="nofollow" title="Skype пользователя" class="user_skype" href="skype:'.$us1->field_skype['und'][0]['value'].'">'.$us1->field_skype['und'][0]['value'].'</a></div>';
            }
 
            echo '<div class="dealcontacts dealcontacts_pm dealcontacts_pm_'.$us1->uid.'"><a rel="nofollow" title="Личное сообщение" class="user_pm" href="/messages/new?to='.$us1->uid.'">Написать личное сообщение</a></div>';
 
            
            $paypref=0;
            if( isset($us1->field_preferrer['und'][0]['value']) and strlen($us1->field_preferrer['und'][0]['value']) ){
                $paypref=$us1->field_preferrer['und'][0]['value'];
            }
            
            echo '<div class="deal_pay"><div class="ttl">Способы оплаты:</div><ul>';
            echo '<li class="pay_nal">Наличные';
            if( $paypref==1 ){
                echo ' <span>(предпочитаемый)</span>';
            }
            echo '</li>';

            if( isset($us1->field_webmoney['und'][0]['value']) and strlen($us1->field_webmoney['und'][0]['value']) ){
                $us1->field_webmoney['und'][0]['value']=str_replace('"','',$us1->field_webmoney['und'][0]['value']);
                echo '<li title="WebMoney" class="pay_wm"><input size="11" onclick=" jQuery(this).select(); " type="text" value="'.$us1->field_webmoney['und'][0]['value'].'" />';
                if( $paypref==2 ){
                    echo ' <span>(предпочитаемый)</span>';
                }
                echo '</li>';
            }

            if( isset($us1->field_yad['und'][0]['value']) and strlen($us1->field_yad['und'][0]['value']) ){
                $us1->field_yad['und'][0]['value']=str_replace('"','',$us1->field_yad['und'][0]['value']);
                echo '<li title="Яндекс.Деньги" class="pay_yd"><input size="11" onclick=" jQuery(this).select(); " type="text" value="'.$us1->field_yad['und'][0]['value'].'" />';
                if( $paypref==3 ){
                    echo ' <span>(предпочитаемый)</span>';
                }
                echo '</li>';
            }

            if( isset($us1->field_qiwi['und'][0]['value']) and strlen($us1->field_qiwi['und'][0]['value']) ){
                $us1->field_qiwi['und'][0]['value']=str_replace('"','',$us1->field_qiwi['und'][0]['value']);
                echo '<li title="QIWI" class="pay_qiwi"><input size="11" onclick=" jQuery(this).select(); " type="text" value="'.$us1->field_qiwi['und'][0]['value'].'" />';
                if( $paypref==4 ){
                    echo ' <span>(предпочитаемый)</span>';
                }
                echo '</li>';
            }

            if( isset($us1->field_card['und'][0]['value']) and strlen($us1->field_card['und'][0]['value']) ){
                $us1->field_card['und'][0]['value']=str_replace('"','',$us1->field_card['und'][0]['value']);
                echo '<li title="Кредитная карта" class="pay_card"><input size="11" onclick=" jQuery(this).select(); " type="text" value="'.$us1->field_card['und'][0]['value'].'" />';
                if( $paypref==5 ){
                    echo ' <span>(предпочитаемый)</span>';
                }
                echo '</li>';
            }

            echo '</ul></div>';
            
            
        }else{
            echo '<div class="dealuserwait">Ждем согласия на сделку <br />от арендодателя...</div>';
        }

        echo '</div>';
      }
      
      if( isset( $node->field_user2['und'][0]['uid'] ) and is_numeric($node->field_user2['und'][0]['uid']) ){
        $us2=prepuser($node->field_user2['und'][0]['uid']);

        echo '<div class="dealuserblock">';
        echo '<div class="dealuserblock_ttl">';
        $val='Неизвестно';

        if( isset($us2->field_name['und'][0]['value']) and strlen($us2->field_name['und'][0]['value']) ){
            $val=$us2->field_name['und'][0]['value'];
        }
        $path=path_load('user/'.$node->field_user2['und'][0]['uid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='user/'.$node->field_user2['und'][0]['uid'];
        echo '<div class="text_user_'.$node->field_user2['und'][0]['uid'].'"><strong>Арендатор: </strong><br />';

        
        if( isset($us2->field_userrat2['und'][0]['value']) and is_numeric($us2->field_userrat2['und'][0]['value']) and $us2->field_userrat2['und'][0]['value']>0 ){
            $us2->field_userrat2['und'][0]['value']=intval($us2->field_userrat2['und'][0]['value']/10);
                echo '<div class="user_rat">';
                if( $us2->field_userrat2['und'][0]['value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $us2->field_userrat2['und'][0]['value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
            }else{
                echo '<div class="user_rat">';
                echo '&nbsp;';
                echo '</div>';
            }
        echo '<a target="_blank" href="/'.$path.'">'.$val.'</a>';
        

                    
        
        echo '</div>';
        echo '</div>';

        if( isset( $node->field_agree2['und'][0]['value'] ) and $node->field_agree2['und'][0]['value']==1 ){
            if( isset($us2->mail) and strlen($us2->mail) and valid_email_address($us2->mail) ){
                $us2->mail=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($us2->mail))));
                echo '<div class="dealcontacts dealcontacts_mail"><a rel="nofollow" class="user_email" href="mailto:'.$us2->mail.'">'.$us2->mail.'</a></div>';
            }
    
            echo '<div class="dealcontacts dealcontacts_phone">';
            if( isset($us2->field_phones['und']) and is_array($us2->field_phones['und']) and count($us2->field_phones['und']) ){
                foreach( $us2->field_phones['und'] as $val ){
                    $us2->field_phones['und'][0]['value']=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($us2->field_phones['und'][0]['value']))));
                    echo '<div><a rel="nofollow" class="user_phone" href="tel:'.str_replace('-','',str_replace(' ','',str_replace('(','',str_replace(')','',$us2->field_phones['und'][0]['value'])))).'">'.$us2->field_phones['und'][0]['value'].'</a></div>';
                }
            }
            echo '</div>';
    
            if( isset($us2->field_skype['und'][0]['value']) and strlen($us2->field_skype['und'][0]['value']) ){
                $us2->field_skype['und'][0]['value']=str_replace("'",'',str_replace('"','',filter_xss(strip_tags($us2->field_skype['und'][0]['value']))));
                echo '<div class="dealcontacts dealcontacts_skype"><a rel="nofollow" title="Skype пользователя" class="user_skype" href="skype:'.$us2->field_skype['und'][0]['value'].'">'.$us2->field_skype['und'][0]['value'].'</a></div>';
            }

            echo '<div class="dealcontacts dealcontacts_pm dealcontacts_pm_'.$us2->uid.'"><a rel="nofollow" title="Личное сообщение" class="user_pm" href="/messages/new?to='.$us2->uid.'">Написать личное сообщение</a></div>';

        }else{
            echo '<div class="dealuserwait">Ждем согласия на сделку <br />от арендатора...</div>';
        }

        echo '</div>';
      }
      
      echo '<div class="clear">&nbsp;</div>';
      echo '<div class="item_block">';
      echo '<div class="item_block_ttl">Ход сделки:</div><ul>';
      
      if( isset( $node->field_delete['und'][0]['value'] ) and $node->field_delete['und'][0]['value']==1 ){
            echo '<li class="is_no"><div>В сделке отказано ';
            if( isset( $node->field_moderate_reason['und'][0]['value'] ) and strlen( $node->field_moderate_reason['und'][0]['value'] ) ){
                echo 'по причине: <br />';
                echo '<div class="deal_cancel_reason">'.nl2br($node->field_moderate_reason['und'][0]['value']).'</div>';
            }
            echo '</div></li>';
      }else{
      
          if( isset( $node->field_agree1['und'][0]['value'] ) and $node->field_agree1['und'][0]['value']==1 and isset( $node->field_agree2['und'][0]['value'] ) and $node->field_agree2['und'][0]['value']==1 ){
            echo '<li class="is_yes">Арендатор <br />согласился на сделку</li>';
            echo '<li class="is_yes">Арендодатель <br />согласился на сделку</li>';
            
                $dealreview1='<div class="skipgoto_about skipreview">';
                $dealreview1.= '<div class="skipgoto_ttl">Отзыв об арендаторе</div>';
                $dealreview1.= '<div>';
                if( isset( $node->field_rat2['und'][0]['value'] ) and is_numeric( $node->field_rat2['und'][0]['value'] ) and $node->field_rat2['und'][0]['value']>0 and $node->field_rat2['und'][0]['value']<6 ){
                    $node->field_rat2['und'][0]['value']*=2;

                    $dealreview1.= '<div class="user_rat">';
                    if( $node->field_rat2['und'][0]['value']>=1 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=2 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=3 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=4 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=5 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=6 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=7 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=8 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']>=9 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat2['und'][0]['value']==10 ){ $dealreview1.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview1.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    $dealreview1.= '</div>';
                    
                }else{
                    echo '<div class="user_rat">';
                    echo '&nbsp;';
                    echo '</div>';
                }
                if( isset( $node->field_reason2['und'][0]['value'] ) and strlen( $node->field_reason2['und'][0]['value'] ) ){
                    $dealreview1.= '<div class="dealreasonis">'.nl2br($node->field_reason2['und'][0]['value']).'</div>';
                }
                $dealreview1.= '<div class="clear">&nbsp;</div></div>';
                $dealreview1.= '</div>';




                $dealreview2='<div class="skipgoto_about skipreview">';
                $dealreview2.= '<div class="skipgoto_ttl">Отзыв об арендодателе</div>';
                $dealreview2.= '<div>';
                if( isset( $node->field_rat1['und'][0]['value'] ) and is_numeric( $node->field_rat1['und'][0]['value'] ) and $node->field_rat1['und'][0]['value']>0 and $node->field_rat1['und'][0]['value']<6 ){
                    $node->field_rat1['und'][0]['value']*=2;

                    $dealreview2.= '<div class="user_rat">';
                    if( $node->field_rat1['und'][0]['value']>=1 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=2 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=3 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=4 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=5 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=6 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=7 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=8 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']>=9 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    if( $node->field_rat1['und'][0]['value']==10 ){ $dealreview2.= '<div class="ratis raty">&nbsp;</div>';
                    }else{ $dealreview2.= '<div class="ratis ratn">&nbsp;</div>'; }
        
                    $dealreview2.= '</div>';
                    
                }
                if( isset( $node->field_reason1['und'][0]['value'] ) and strlen( $node->field_reason1['und'][0]['value'] ) ){
                    $dealreview2.= '<div class="dealreasonis">'.nl2br($node->field_reason1['und'][0]['value']).'</div>';
                }
                $dealreview2.= '<div class="clear">&nbsp;</div></div>';
                $dealreview2.= '</div>';                
        
            
            if( isset( $node->field_end1['und'][0]['value'] ) and $node->field_end1['und'][0]['value']==1 and isset( $node->field_end2['und'][0]['value'] ) and $node->field_end2['und'][0]['value']==1 ){
                echo '<li class="is_yes">Арендатор согласен, <br />что сделка завершена';
                echo $dealreview2;
                echo '</li>';
                echo '<li class="is_yes">Арендодатель согласен, <br />что сделка завершена';
                echo $dealreview1;
                echo '</li>';
                echo '<li class="is_dealend">Сделка <br />завершена =)</li>';
            }else{

                $dealend1='<div class="skipgoto_about skipend">';
                $dealend1.= '<div class="skipgoto_ttl">Для завершения сделки, пожалуйста, оцените Ваш опыт работы с данным арендатором:</div>';
                $dealend1.= '<div>';
                $dealend1.= '<div class="skipgoto_yes">
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating1" value="1" /> ужас</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating2" value="2" /> плохо</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating3" value="3" /> нормально</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating4" value="4" checked="checked" /> хорошо</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating5" value="5" /> отлично</label></div>
                </div>';
                $dealend1.= '<div class="skipgoto_no"><div>Ваш отзыв:</div><textarea id="skipgoto_end1" rows="3" cols="27"></textarea><div><span class="nobtn" id="skipgoto_end1_txt" onclick=" deal_step1_end('.$node->nid.') ">Завершить сделку</span></div></div>';
                $dealend1.= '<div class="clear">&nbsp;</div></div>';
                $dealend1.= '</div>';


                $dealend2='<div class="skipgoto_about skipend">';
                $dealend2.= '<div class="skipgoto_ttl">Для завершения сделки, пожалуйста, оцените Ваш опыт работы с данным арендодателем:</div>';
                $dealend2.= '<div>';
                $dealend2.= '<div class="skipgoto_yes">
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating1" value="1" /> ужас</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating2" value="2" /> плохо</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating3" value="3" /> нормально</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating4" value="4" checked="checked" /> хорошо</label></div>
                <div class="myrating"><label><input type="radio" name="myrating" id="myrating5" value="5" /> отлично</label></div>
                </div>';
                $dealend2.= '<div class="skipgoto_no"><div>Ваш отзыв:</div><textarea id="skipgoto_end2" rows="3" cols="27"></textarea><div><span class="nobtn" id="skipgoto_end2_txt" onclick=" deal_step2_end('.$node->nid.') ">Завершить сделку</span></div></div>';
                $dealend2.= '<div class="clear">&nbsp;</div></div>';
                $dealend2.= '</div>';


                if( isset( $node->field_end1['und'][0]['value'] ) and $node->field_end1['und'][0]['value']==1 ){
                    echo '<li class="is_yes">Арендодатель согласен, <br />что сделка завершена';
//                    echo $dealreview1;
                    echo '<div class="deal_wait_end">Оставьте свой отзыв, чтобы увидеть отзыв арнндодателя...</div>';
                    echo '</li>';
                    echo '<li class="is_wait">Ждем завершения сделки <br />от арендатора...';
                    if( $skipgoto==2 ){
                        echo $dealend2;
                    }
                    echo '</li>';
                }elseif( isset( $node->field_end2['und'][0]['value'] ) and $node->field_end2['und'][0]['value']==1 ){
                    echo '<li class="is_yes">Арендатор согласен, <br />что сделка завершена';
//                    echo $dealreview2;
                    echo '<div class="deal_wait_end">Оставьте свой отзыв, чтобы увидеть отзыв арендатора...</div>';
                    echo '</li>';
                    echo '<li class="is_wait">Ждем завершения сделки <br />от арендодателя...';
                    if( $skipgoto==1 ){
                        echo $dealend1;
                    }
                    echo '</li>';
                }else{
                    echo '<li class="is_wait">Ждем завершения сделки <br />от арендатора...';
                    if( $skipgoto==2 ){
                        echo $dealend2;
                    }
                    echo '</li>';
                    echo '<li class="is_wait">Ждем завершения сделки <br />от арендодателя...';
                    if( $skipgoto==1 ){
                        echo $dealend1;
                    }
                    echo '</li>';
                }
            }
          }else{
            if( isset( $node->field_agree1['und'][0]['value'] ) and $node->field_agree1['und'][0]['value']==1 ){
                echo '<li class="is_yes">Арендодатель <br />согласился на сделку</li>';
                echo '<li class="is_wait">Ждем Вашего согласия, <br />как арендатора...';
                echo '</li>';
                echo '<li class="is_wait">Ждем завершения сделки <br />от арендодателя...</li>';
                echo '<li class="is_wait">Ждем завершения сделки <br />от арендатора...</li>';
            }else{
                echo '<li class="is_yes">Арендатор <br />согласился на сделку</li>';
                if( $skipgoto==1 ){
                    echo '<li class="is_wait">Ждем Вашего согласия, <br />как арендодателя...';
                    echo '<div class="skipgoto_about">';
                    echo '<div class="skipgoto_ttl">Вы согласны сдать товар в прокат данному арендатору?</div>';
                    echo '<div>';
                    echo '<div class="skipgoto_yes"><span class="yesbtn" onclick=" deal_step1_yes('.$node->nid.'); ">Да</span></div>';
                    echo '<div class="skipgoto_no"><div><strong>Нет</strong>, по причине:</div><textarea id="skipgoto_no1" rows="3" cols="27"></textarea><div><span class="nobtn" id="skipgoto_no1_txt" onclick=" deal_step1_no('.$node->nid.') ">Отказать, по данной причине</span></div></div>';
                    echo '<div class="clear">&nbsp;</div></div>';
                    echo '</div>';
                }else{
                    echo '<li class="is_wait">Ждем согласия <br />от арендодателя...';
                }
                echo '</li>';
                echo '<li class="is_wait">Ждем завершения сделки <br />от арендатора...</li>';
                echo '<li class="is_wait">Ждем завершения сделки <br />от арендодателя...</li>';
            }
          }
      }
        
      echo '</ul></div>';
      
      ?>

<?php
global $user;
if(isset($user->roles[3])){
    $pdxshowhelp2=variable_get('pdxshowhelp2', 0);
    if(!$pdxshowhelp2){
        echo '<div class="manage_part"><ul>';
        echo '<li><a href="/node/'.$node->nid.'/delete"><img alt="Уд" title="Удалить данную публикацию" src="/sites/all/libraries/img/adm_del.png" /></a></li>';
        echo '<li><a href="/node/add/'.$node->type.'"><img alt="+" title="Добавить новую публикацию данного типа" src="/sites/all/libraries/img/add.png" /></a></li>';
        echo '<li><a href="/admin/store/search?type='.$node->type.'"><img alt="Все" title="Все публикации данного типа" src="/sites/all/libraries/img/all.png" /></a></li>';
        echo '</ul></div>';
    }
}

?>
    <div class="clear">&nbsp;</div></article>
    <?php
    if( isset($content['links']['statistics']) ){
        unset($content['links']['statistics']);
    }
    if( isset($content['links']['translation']) ){
        unset($content['links']['translation']);
    }
    if( isset( $content['links']['print_mail'] ) ){
        unset($content['links']['print_mail']);
    }
      if($content['links']): ?>
      <div class="links-container">
        <?php print render($content['links']); ?>
      </div>
    <?php endif; ?>
    <?php if (!$teaser): ?>
      <div class="clearfix">
        <?php print render($content['comments']); ?>
      </div>
    <?php endif; ?>
  </div><!--end node container-->
</div><!--end node-->