<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$curt=time();
if(isset($_GET['d']) and is_numeric($_GET['d']) and isset($_GET['m']) and is_numeric($_GET['m'])){
    $curd1=mktime(0,0,0,$_GET['m'],$_GET['d'],date('Y',time()) );
    $day1=date('d',$curd1);
    $month1=date('n',$curd1);
    $year1=date('Y',$curd1);
    if(isset($_GET['d2']) and is_numeric($_GET['d2']) and isset($_GET['m2']) and is_numeric($_GET['m2'])){
        $curd2=mktime(0,0,0,$_GET['m2'],$_GET['d2'],date('Y',$curd1) );
        $day2=date('d',$curd2+86400);
        $month2=date('n',$curd2+86400);
        $year2=date('Y',$curd2+86400);
    }else{
        $day2=date('d',$curd1+86400);
        $month2=date('n',$curd1+86400);
        $year2=date('Y',$curd1+86400);
    }
}else{
    $day1=date('d',($curt-2764800));
    $month1=date('n',($curt-2764800));
    $year1=date('Y',($curt-2764800));
    $day2=date('d',$curt+86400);
    $month2=date('n',$curt+86400);
    $year2=date('Y',$curt+86400);
    $curd1=mktime(0,0,0,$month1,$day1,$year1);
}
$curd2=mktime(0,0,0,$month2,$day2,$year2);


//$products=db_query('select order_id from {uc_orders} where created>'.$curd1.' and created<'.$curd2.' order by order_id desc');
$products=db_query('select order_id from {uc_orders} order by order_id desc');

$out='';
while($product=$products->fetchAssoc()){
    $product=uc_order_load($product['order_id']);
    if( isset($product->products) and is_array($product->products) and count($product->products) ){
        foreach($product->products as $pr){
            if( isset($pr->nid) ){
                $out.= $product->order_id.'^'; //Номер заказа
                $out.= $pr->model.'^'; //Код товара
                $out.= round($pr->price,0).'^'; //Цена
                $out.= $pr->qty.'
'; //Количество
            }
        }
    }
    
}
if( isset($out) and strlen($out) ){
//header('Content-Encoding: UTF-8');
//header('Content-type: text/csv; charset=UTF-8');
header('Content-type: text/plain; charset=Windows-1251');
header("Content-Disposition: attachment; filename=txtorderdetails.txt");    
//header("Pragma: public");
//header("Expires: 0");
//echo "\xEF\xBB\xBF"; // UTF-8 BOM
    echo $out;
}




function string_moisklad($str){
//    $str=str_replace('"','""',$str);
    if(mb_detect_encoding($str)!='Windows-1251'){
        $str=mb_convert_encoding($str, 'Windows-1251');
    }
    return $str;
    return '"'.$str.'"';
}

?>