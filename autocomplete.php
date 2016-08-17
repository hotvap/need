<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['req']) and strlen($_POST['req']) ){
    $_POST['req']=trim(filter_xss(strip_tags($_POST['req'])));
}
if( isset($_POST['req']) and strlen($_POST['req']) ){
    $req=explode(' ',$_POST['req']);
    $allres=0;
    $outs=array();
    $emptycat=1;

    $combo=array();
    $addon=array();
    $addon2=array();
    $addon4=array();
    $icombo=1;
    $skip=0;
    $terms=array();
    $terms2=array();
    $already=array();
    
    if( isset($req) and is_array($req) and count($req) ){
        foreach( $req as $re ){
            $re=trim(filter_xss(strip_tags($re)));
            if( strlen($re) ){
                if( is_numeric($re) ){
                    $skip=1;
                    break;
                }
                
                $addon[]='name LIKE :combo'.$icombo;
                $addon4[]='d.name LIKE :combo'.$icombo;
                $combo[':combo'.$icombo]='%'.$re.'%';
                
                $re=str_replace('"', '',$re);
                $re=str_replace("'", '',$re);
                $addon2[]=mb_strtolower($re);
                
                $icombo++;
            }
        }
    }
    
    if( !$skip and isset($addon) and is_array($addon) and count($addon) ){
        
        //разделы
        
        $n=0;

        $res=db_query('SELECT DISTINCT tid, name FROM {taxonomy_term_data} WHERE vid = 2 AND ( '.implode(' AND ', $addon).' ) order by weight asc', $combo);
        while( $re=$res->fetchAssoc() ){
            $already[$re['tid']]=1;
            $emptycat=0;
        
            $out='';
            $out.='<div class="searchresult_part';
            if( $n%2 ){
                $out.= ' odd';
            }
            $out.='">';

            $path=path_load('catalog/'.$re['tid']);
            if(strlen($path['alias'])) $path=$path['alias']; else $path='catalog/'.$re['tid'];
                    
            $out.='<a href="/'.$path.'"><span>';
            $out.=$re['name'];
            $out.='</span></a>';
    
            $out.='<div class="clear">&nbsp;</div></div>';
            $outs[]=$out;
            $n++;
        }
        
    }



    
        $emptybrand=1;
    if( isset($addon4) and is_array($addon4) and count($addon4) ){
        
        //теги
        
        $n=0;

        $res=db_query('SELECT DISTINCT d.tid, d.name, fp.field_part_tid, fs.field_subpart_tid FROM {taxonomy_term_data} as d inner join {field_data_field_tags} as b on (d.tid=b.field_tags_tid and b.entity_type=\'node\') inner join {node} as n on b.entity_id=n.nid inner join {field_data_field_part} as fp on (d.tid=fp.entity_id and fp.entity_type=\'taxonomy_term\') inner join {field_data_field_subpart} as fs on (d.tid=fs.entity_id and fs.entity_type=\'taxonomy_term\') WHERE n.status=1 and n.type=\'item\' and d.vid = 1 AND ( '.implode(' AND ', $addon4).' ) order by d.weight asc', $combo);
        while( $re=$res->fetchAssoc() ){
            $already[$re['tid']]=1;
            $emptybrand=0;
        
            $out='';
            $out.='<div class="searchresult_part';
            if( $n%2 ){
                $out.= ' odd';
            }
            $out.='">';

            $out.='<a href="/catalog/'.$re['field_part_tid'].'/'.$re['field_subpart_tid'].'/'.$re['tid'].'"><span>';
            $out.=$re['name'];
            $out.='</span></a>';
    
            $out.='<div class="clear">&nbsp;</div></div>';
            $outs[]=$out;
            $n++;
        }
    }
        

                    if( $emptybrand ){
                        //обратный поиск по тегу

                        $res=db_query('SELECT DISTINCT d.tid, d.name, fp.field_part_tid, fs.field_subpart_tid FROM {taxonomy_term_data} as d inner join {field_data_field_tags} as b on (d.tid=b.field_tags_tid and b.entity_type=\'node\') inner join {node} as n on b.entity_id=n.nid inner join {field_data_field_part} as fp on (d.tid=fp.entity_id and fp.entity_type=\'taxonomy_term\') inner join {field_data_field_subpart} as fs on (d.tid=fs.entity_id and fs.entity_type=\'taxonomy_term\') WHERE n.status=1 and n.type=\'item\' and d.vid = 1 order by d.weight asc');

                        while( $re=$res->fetchAssoc() ){
                            $terms2['catalog/'.$re['field_part_tid'].'/'.$re['field_subpart_tid'].'/'.$re['tid']]=$re['name'];
                        }         
                        
                        if( isset($terms2) and is_array($terms2) and count($terms2) ){
                            foreach( $terms2 as $path => $catbrand ){
                                $skipme=0;
                                
                                $addon3=mb_strtolower(implode(' ',$addon2));
                                
                                $req2=explode(' ',mb_strtolower($catbrand));    
                                if( isset($req2) and is_array($req2) and count($req2) ){
                                    foreach( $req2 as $re ){
                                        $re=trim(filter_xss(strip_tags($re)));
                                        if( !strlen($re) or mb_strpos($addon3, $re)===false ){
                                            $skipme=1;
                                            break;
                                        }
                                    }
                                }else{
                                    $skipme=1;
                                }
        
        
                                if( $skipme ){
                                    continue;
                                }
                                
                                $emptybrand=0;
            
                                $out='';
                                $out.='<div class="searchresult_part';
                                if( $n%2 ){
                                    $out.= ' odd';
                                }
                                $out.='">';
                    
                                $out.='<a href="/'.$path.'"><span>';
                                $out.=$catbrand;
                                $out.='</span></a>';
                        
                                $out.='<div class="clear">&nbsp;</div></div>';
                                $outs[]=$out;
                                $n++;
                                
                            }
                        }
    
    
    
                    }



        $emptybrand=1;
    if( isset($addon4) and is_array($addon4) and count($addon4) ){
        
        //подразделы
        
        $n=0;

        $res=db_query('SELECT DISTINCT d.tid, d.name, fp.field_part_tid FROM {taxonomy_term_data} as d inner join {field_data_field_subpart} as b on (d.tid=b.field_subpart_tid and b.entity_type=\'node\') inner join {node} as n on b.entity_id=n.nid inner join {field_data_field_part} as fp on (d.tid=fp.entity_id and fp.entity_type=\'taxonomy_term\') WHERE n.status=1 and n.type=\'item\' and d.vid = 1 AND ( '.implode(' AND ', $addon4).' ) order by d.weight asc', $combo);
        while( $re=$res->fetchAssoc() ){
            $already[$re['tid']]=1;
            $emptybrand=0;
        
            $out='';
            $out.='<div class="searchresult_part';
            if( $n%2 ){
                $out.= ' odd';
            }
            $out.='">';

            $out.='<a href="/catalog/'.$re['field_part_tid'].'/'.$re['tid'].'"><span>';
            $out.=$re['name'];
            $out.='</span></a>';
    
            $out.='<div class="clear">&nbsp;</div></div>';
            $outs[]=$out;
            $n++;
        }
    }
        

                    if( $emptybrand ){
                        //обратный поиск по подразделам

                        $res=db_query('SELECT DISTINCT d.tid, d.name, fp.field_part_tid FROM {taxonomy_term_data} as d inner join {field_data_field_subpart} as b on (d.tid=b.field_subpart_tid and b.entity_type=\'node\') inner join {node} as n on b.entity_id=n.nid inner join {field_data_field_part} as fp on (d.tid=fp.entity_id and fp.entity_type=\'taxonomy_term\') WHERE n.status=1 and n.type=\'item\' and d.vid = 1 order by d.weight asc');

                        while( $re=$res->fetchAssoc() ){
                            $terms2['catalog/'.$re['field_part_tid'].'/'.$re['tid']]=$re['name'];
                        }         
                        
                        if( isset($terms2) and is_array($terms2) and count($terms2) ){
                            foreach( $terms2 as $path => $catbrand ){
                                $skipme=0;
                                
                                $addon3=mb_strtolower(implode(' ',$addon2));
                                
                                $req2=explode(' ',mb_strtolower($catbrand));    
                                if( isset($req2) and is_array($req2) and count($req2) ){
                                    foreach( $req2 as $re ){
                                        $re=trim(filter_xss(strip_tags($re)));
                                        if( !strlen($re) or mb_strpos($addon3, $re)===false ){
                                            $skipme=1;
                                            break;
                                        }
                                    }
                                }else{
                                    $skipme=1;
                                }
        
        
                                if( $skipme ){
                                    continue;
                                }
                                
                                $emptybrand=0;
            
                                $out='';
                                $out.='<div class="searchresult_part';
                                if( $n%2 ){
                                    $out.= ' odd';
                                }
                                $out.='">';
                    
                                $out.='<a href="/'.$path.'"><span>';
                                $out.=$catbrand;
                                $out.='</span></a>';
                        
                                $out.='<div class="clear">&nbsp;</div></div>';
                                $outs[]=$out;
                                $n++;
                                
                            }
                        }
    
    
    
                    }


    $already=array();
    $icount=0;

    $combo=array();
    $addon=array();
    $icombo=1;
    $skip=0;
    if( isset($req) and is_array($req) and count($req) ){
        foreach( $req as $re ){
            $re=trim($re);
            if( strlen($re) ){
                $addon[]='n.title LIKE :combo'.$icombo;
                $combo[':combo'.$icombo]='%'.$re.'%';
                $icombo++;
            }
        }
    }


    if( !$skip and isset($addon) and is_array($addon) and count($addon) ){
        //товары, поиск по заголовку      
        
        $n=0;
//        echo '<pre>'.print_r($combo, true). '</pre>';
//        echo 'SELECT DISTINCT n.nid FROM {node} as n INNER JOIN {field_data_field_search} as s ON n.nid = s.entity_id AND s.entity_type = \'node\' WHERE n.status = 1 AND ( '.implode(' AND ', $addon).' ) LIMIT 0, 10';
        $res=db_query('SELECT DISTINCT n.nid FROM {node} as n WHERE n.status = 1 and n.type=\'item\' AND ( '.implode(' AND ', $addon).' ) LIMIT 0, 10', $combo);
        while( $re=$res->fetchAssoc() ){
            $already[$re['nid']]=1;
            $icount++;
            $out='';
            $re=node_load($re['nid']);
            if( isset($re->title) and strlen($re->title) ){
                
                $path=path_load('node/'.$re->nid); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$re->nid;
                
                $out.='<div class="searchresult searchresult_'.$icount;
                if( $n%2 ){
                    $out.= ' odd';
                }
                $out.='">';
                if( isset($re->field_image['und'][0]['uri']) and strlen($re->field_image['und'][0]['uri'] )){
                    $uri = image_style_url('inlinesearch', $re->field_image['und'][0]['uri']);
                    if( isset($uri) and strlen($uri) ){
                        $out .= '<div class="image"><img src="'.$uri.'" /></div>';
                    }
                }

            $out .= '<div class="inline_right">';
            $out .= '<div class="title"><a href="/'.$path.'">'.$re->title.'</a></div>';
            if( isset($re->field_rating['und'][0]['average']) and is_numeric($re->field_rating['und'][0]['average']) and $re->field_rating['und'][0]['average']>0 ){
                $out .= '<div class="rat';
                if( $re->field_rating['und'][0]['average']>90 ){
                    $out .= ' rat5';
                }elseif( $re->field_rating['und'][0]['average']>75 ){
                    $out .= ' rat4';
                }elseif( $re->field_rating['und'][0]['average']>55 ){
                    $out .= ' rat3';
                }elseif( $re->field_rating['und'][0]['average']>35 ){
                    $out .= ' rat2';
                }elseif( $re->field_rating['und'][0]['average']>15 ){
                    $out .= ' rat1';
                }
                $out .= '">&nbsp;</div>';
            }
            $out .= '<p class="product_descr">';
            
            $line='';
            if( isset($line) and strlen($line) ){
                $out .= $line;
            }
            
            $out .= ' &nbsp; <a href="/'.$path.'">Подробнее</a>';
            $out .= '</p>';
            $out .= '<p class="price">';
/*
            if( $pdxcurcourse>0 and isset($re->field_sort_price_min['und'][0]['value']) and is_numeric($re->field_sort_price_min['und'][0]['value']) and isset($re->field_sort_price_max['und'][0]['value']) and is_numeric($re->field_sort_price_max['und'][0]['value']) ){
                if( $re->field_sort_price_min['und'][0]['value']==$re->field_sort_price_max['und'][0]['value'] ){
                    $price=ceil($re->field_sort_price_min['und'][0]['value']/$pdxcurcourse);
                    $out .= number_format($price,0,'',' ').'&nbsp;у.е.';
                }else{
                    $price=ceil($re->field_sort_price_min['und'][0]['value']/$pdxcurcourse);
                    $out .= number_format($price,0,'',' ').'&nbsp;у.е.';
                    $out .= ' - ';
                    $price=ceil($re->field_sort_price_max['und'][0]['value']/$pdxcurcourse);
                    $out .= number_format($price,0,'',' ').'&nbsp;у.е.';
                    
                }
            }
*/


                if( isset($re->attributes) and is_array($re->attributes) and count($re->attributes) ){

                    if( isset($re->field_sort_price_min['und'][0]['value']) and is_numeric($re->field_sort_price_min['und'][0]['value']) and isset($re->field_sort_price_max['und'][0]['value']) and is_numeric($re->field_sort_price_max['und'][0]['value']) ){
                        if( $re->field_sort_price_min['und'][0]['value']==$re->field_sort_price_max['und'][0]['value'] ){
                            $out .= number_format($re->field_sort_price_min['und'][0]['value'], 0, '.',' ').'&nbsp;руб.';
                        }else{
                            $out .= number_format($re->field_sort_price_min['und'][0]['value'], 0, '.',' ');
                            $out .= '&nbsp;-&nbsp;';
                            $out .= number_format($re->field_sort_price_max['und'][0]['value'], 0, '.',' ').'&nbsp;руб.';
                            
                            
                        }
                    }
                    
                }else{

                    if( isset($re->sell_price) ){
                        $out .= number_format($re->sell_price, 0, '.',' ').'&nbsp;руб.';
                    }                    
                    
                }



            $out .= '</p></div>';

                $out.='<div class="clear">&nbsp;</div></div>';
                $outs[]=$out;
                $n++;
            }
        }
//    }else{
//        echo '<div class="searchresult lowcharcount">Ничего не найдено</div>';
    }








    $combo=array();
    $addon=array();
    $icombo=1;
    $skip=0;
    if( isset($req) and is_array($req) and count($req) ){
        foreach( $req as $re ){
            $re=trim($re);
            if( strlen($re) ){
                $addon[]='s.field_search_value LIKE :combo'.$icombo;
                $combo[':combo'.$icombo]='%'.$re.'%';
                $icombo++;
            }
        }
    }


    if( !$skip and $icount<10 and isset($addon) and is_array($addon) and count($addon) ){
        //товары, поиск по строке для поиска
        
        $n=0;
//        echo '<pre>'.print_r($combo, true). '</pre>';
//        echo 'SELECT DISTINCT n.nid FROM {node} as n INNER JOIN {field_data_field_search} as s ON n.nid = s.entity_id AND s.entity_type = \'node\' WHERE n.status = 1 AND ( '.implode(' AND ', $addon).' ) LIMIT 0, 10';
        $res=db_query('SELECT DISTINCT n.nid FROM {node} as n INNER JOIN {field_data_field_search} as s ON n.nid = s.entity_id AND s.entity_type = \'node\' WHERE n.status = 1 AND ( '.implode(' AND ', $addon).' )', $combo);
        while( $re=$res->fetchAssoc() ){
            if( isset($already[$re['nid']]) ){
                continue;
            }
            if(++$icount>10){
                break;
            }


            $out='';
            $re=node_load($re['nid']);
            if( isset($re->title) and strlen($re->title) ){
                
                $path=path_load('node/'.$re->nid); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$re->nid;
                
                $out.='<div class="searchresult searchresult_'.$icount;
                if( $n%2 ){
                    $out.= ' odd';
                }
                $out.='">';
                if( isset($re->field_image['und'][0]['uri']) and strlen($re->field_image['und'][0]['uri'] )){
                    $uri = image_style_url('inlinesearch', $re->field_image['und'][0]['uri']);
                    if( isset($uri) and strlen($uri) ){
                        $out .= '<div class="image"><img src="'.$uri.'" /></div>';
                    }
                }

            $out .= '<div class="inline_right">';
            $out .= '<div class="title"><a href="/'.$path.'">'.$re->title.'</a></div>';
            if( isset($re->field_rating['und'][0]['average']) and is_numeric($re->field_rating['und'][0]['average']) and $re->field_rating['und'][0]['average']>0 ){
                $out .= '<div class="rat';
                if( $re->field_rating['und'][0]['average']>90 ){
                    $out .= ' rat5';
                }elseif( $re->field_rating['und'][0]['average']>75 ){
                    $out .= ' rat4';
                }elseif( $re->field_rating['und'][0]['average']>55 ){
                    $out .= ' rat3';
                }elseif( $re->field_rating['und'][0]['average']>35 ){
                    $out .= ' rat2';
                }elseif( $re->field_rating['und'][0]['average']>15 ){
                    $out .= ' rat1';
                }
                $out .= '">&nbsp;</div>';
            }
            $out .= '<p class="product_descr">';
            
            $line='';

            if( isset($line) and strlen($line) ){
                $out .= $line;
            }
            
            $out .= ' &nbsp; <a href="/'.$path.'">Подробнее</a>';
            $out .= '</p>';
            $out .= '<p class="price">';
/*
            if( $pdxcurcourse>0 and isset($re->field_sort_price_min['und'][0]['value']) and is_numeric($re->field_sort_price_min['und'][0]['value']) and isset($re->field_sort_price_max['und'][0]['value']) and is_numeric($re->field_sort_price_max['und'][0]['value']) ){
                if( $re->field_sort_price_min['und'][0]['value']==$re->field_sort_price_max['und'][0]['value'] ){
                    $price=ceil($re->field_sort_price_min['und'][0]['value']/$pdxcurcourse);
                    $out .= number_format($price,0,'',' ').'&nbsp;у.е.';
                }else{
                    $price=ceil($re->field_sort_price_min['und'][0]['value']/$pdxcurcourse);
                    $out .= number_format($price,0,'',' ').'&nbsp;у.е.';
                    $out .= ' - ';
                    $price=ceil($re->field_sort_price_max['und'][0]['value']/$pdxcurcourse);
                    $out .= number_format($price,0,'',' ').'&nbsp;у.е.';
                    
                }
            }
*/
                

            $out .= '</p></div>';

                $out.='<div class="clear">&nbsp;</div></div>';
                $outs[]=$out;
                $n++;
            }
        }
//    }else{
//        echo '<div class="searchresult lowcharcount">Ничего не найдено</div>';
    }

        echo '<div class="searchresult lowcharcount">';
        if( isset($outs) and is_array($outs) and count($outs) ){
            echo implode('', $outs);
        }else{
            echo 'Ничего не найдено';
        }
        echo '</div>';

    
}else{
    echo '<div class="searchresult lowcharcount">Запрос пуст</div>';
}
?>
<script type="text/javascript">
    jQuery('#inline_ajax_search_results_pre').show(277);
    if( findsrc!='' ){
        jQuery('#inline_ajax_search_container .custom-search-button').attr('src', findsrc);
        findsrc='';
    }
    
</script>