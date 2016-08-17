<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

drupal_add_http_header('Content-Type', "text/xml; charset=utf-8");

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">
<yml_catalog date=\"". date('Y-m-d h:i') ."\">
<shop>
<name>".variable_get('site_name', 'Drupal')."</name>
  <company>".variable_get('site_name', 'Drupal')."</company>
  <url>".$GLOBALS['base_url']."</url>

<currencies><currency id=\"RUR\" rate=\"1\"/></currencies>
<categories>
";

$tids = taxonomy_get_tree(2);
if($tids){
    foreach($tids as $term){
        echo "<category id=\"".$term->tid."\"";
        if($term->parents[0]!=0){
            echo " parentId=\"".$term->parents[0]."\"";
        }
        echo ">".yml_mysafe($term->name)."</category>
";
    }
}


echo "</categories>
<offers>
";

if (!is_dir('p')){
    mkdir('p');
}
if (!is_dir('p/foryml')){
    mkdir('p/foryml');
}
if (!is_dir('p/foryml/item_opt')){
    mkdir('p/foryml/item_opt');
}

$rows=db_query('select n.nid, n.title, u.sell_price, u.model, b.body_value from {node} as n inner join {uc_products} as u on n.nid=u.nid inner join {field_data_field_market} as m on n.nid=m.entity_id left join {field_data_body} as b on n.nid=b.entity_id where n.status=1 and m.field_market_value=1 order by n.created desc');
while ($row=$rows->fetchAssoc()) {

        if(isset($row['body_value']) and strlen($row['body_value'])){
            $row['body_value']=strip_tags($row['body_value']);
            if( mb_strlen($row['body_value'])>251 ){
                $row['body_value']=mb_substr($row['body_value'], 0, 251).'...';
            }
        }

    $isopt=0;
    $options=db_query('select a.oid, a.name, a.aid, o.price from {uc_product_options} as o inner join {uc_attribute_options} as a on o.oid=a.oid where o.nid='.$row['nid']);
    while($option=$options->fetchAssoc()){        


        $item_opt='';
        if(file_exists('p/foryml/item_opt/'.$row['nid'].'_'.$option['oid'])){
            $isopt++;
            $item_opt=file_get_contents('p/foryml/item_opt/'.$row['nid'].'_'.$option['oid']);
        }else{

            $issell=0;
            if( isset($row['sell_price']) and is_numeric($row['sell_price']) ){
                $issell=$row['sell_price'];
            }
            if( isset($option['price']) and is_numeric($option['price']) ){
                $issell+=$option['price'];
            }

            if(!isset($issell) or !intval($issell) or $issell<1 ) continue;
            
            $isopt++;
            $stock='true';
        
            $path=path_load('node/'.$row['nid']);
            if(strlen($path['alias'])) $path=$path['alias'].'/'; else $path='node/'.$row['nid'];
            
            $path.='?oid='.$option['oid'];

            
            $item_opt.='<offer id="'.$row['model'].'o'.$option['oid'].'" available="';
            $item_opt.=$stock;
            $item_opt.='">
            <url>'.$GLOBALS['base_url'].'/'.$path.'</url>
            <price>'.$issell.'</price>
            <currencyId>RUR</currencyId>';
            
            $term=db_query('select t.tid from {taxonomy_index} as t inner join {taxonomy_term_data} as d on t.tid=d.tid where d.vid=2 and t.nid='.$row['nid']);
            $term=$term->fetchAssoc();
            if(isset($term['tid'])){
                $item_opt.='
                <categoryId>'. $term['tid'].'</categoryId>
                ';
            }else{
                $item_opt.='
                <categoryId>1</categoryId>
                ';
            }
            
            $image=db_query('select fid from {file_usage} where type=\'node\' and id='.$row['nid']);
            $image=$image->fetchAssoc();
            if ($image and is_numeric($image['fid'])){
                $uri = file_load($image['fid'])->uri;
                $uri = file_create_url($uri);
                if(strlen($uri)){
            $item_opt.='<picture>'.$uri.'</picture>
            ';
                }
            }
            
            $item_opt.='<delivery>true</delivery>
            <name>'.yml_mysafe($row['title'].' '.$option['name']).'</name>';
            $desc='';
            if(isset($row['body_value']) and strlen($row['body_value'])){
                $desc=yml_mysafe($row['body_value']);
            }else{
                if(isset($row['title']) and strlen($row['title'])){
                    $desc=yml_mysafe($row['title']);
                }
            }
            $item.='<description>'.$desc.'</description>';
            $item_opt.='
            <sales_notes></sales_notes>
            </offer>
            ';
    
    
            if(strlen($item_opt)){
                $fp = fopen('p/foryml/item_opt/'.$row['nid'].'_'.$option['oid'], "w");
                fputs($fp, $item_opt);
                fclose($fp);
            }else{
                continue;
            }

            
        }
        
        if( isset($item_opt) and strlen($item_opt) ){
            echo $item_opt;
        }
        
        
    }

    if(!$isopt){
        
        $item_opt='';
        if(file_exists('p/foryml/item_opt/'.$row['nid'])){
            $item_opt=file_get_contents('p/foryml/item_opt/'.$row['nid']);
        }else{
    
    
    
            if(!isset($row['sell_price']) or !intval($row['sell_price']) or $row['sell_price']<1 ) continue;
            $row['sell_price']=intval($row['sell_price']);
        
            $stock='true';
        
        
        $path=path_load('node/'.$row['nid']);
        if(strlen($path['alias'])) $path=$path['alias'].'/'; else $path='node/'.$row['nid'];
        
        $item_opt.='<offer id="'.$row['model'].'" available="';
        $item_opt.=$stock;
        $item_opt.='">
        <url>'.$GLOBALS['base_url'].'/'.$path.'</url>
        <price>'.$row['sell_price'].'</price>
        <currencyId>RUB</currencyId>';
        
        $term=db_query('select t.tid from {taxonomy_index} as t inner join {taxonomy_term_data} as d on t.tid=d.tid where d.vid=2 and t.nid='.$row['nid']);
        $term=$term->fetchAssoc();
        if(isset($term['tid'])){
            $item_opt.='
            <categoryId>'. $term['tid'].'</categoryId>
            ';
        }else{
            $item_opt.='
            <categoryId>1</categoryId>
            ';
        }
        
        $image=db_query('select fid from {file_usage} where type=\'node\' and id='.$row['nid']);
        $image=$image->fetchAssoc();
        if ($image and is_numeric($image['fid'])){
            $uri = file_load($image['fid'])->uri;
            $uri = file_create_url($uri);
            if(strlen($uri)){
        $item_opt.='<picture>'.$uri.'</picture>
        ';
            }
        }
        
        $item_opt.='<delivery>true</delivery>
        <name>'.yml_mysafe($row['title']).'</name>';
        $desc='';
        if(isset($row['body_value']) and strlen($row['body_value'])){
            $desc=yml_mysafe($row['body_value']);
        }else{
            if(isset($row['title']) and strlen($row['title'])){
                $desc=yml_mysafe($row['title']);
            }
        }
        $item.='<description>'.$desc.'</description>';
        $item_opt.='
        <sales_notes></sales_notes>
        </offer>
        ';
    
    
            if(strlen($item_opt)){
                $fp = fopen('p/foryml/item_opt/'.$row['nid'], "w");
                fputs($fp, $item_opt);
                fclose($fp);
            }else{
                continue;
            }
    
    
        }
    
        if( isset($item_opt) and strlen($item_opt) ){
            echo $item_opt;
        }
    }



}



 
echo '</offers>

</shop>
</yml_catalog>';


function yml_mysafe($str) {
  $rep = array(
    '"' => '&quot;',
    '&' => '&amp;',
    '>' => '&gt;',
    '<' => '&lt;',
    "'" => '&apos;'
  );
  
  return strtr($str, $rep);
}

?>