<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once DRUPAL_ROOT . '/sites/all/libraries/php/Classes/PHPExcel.php';

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
    $day1=date('d',$curt);
    $month1=date('n',$curt);
    $year1=date('Y',$curt);
    $day2=date('d',$curt+86400);
    $month2=date('n',$curt+86400);
    $year2=date('Y',$curt+86400);
    $curd1=mktime(0,0,0,$month1,$day1,$year1);
}
$curd2=mktime(0,0,0,$month2,$day2,$year2);


$pExcel = new PHPExcel();
$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();
$aSheet->setTitle('Купленные товары');


//$products=db_query('select order_id from {uc_orders} where created>'.$curd1.' and created<'.$curd2.' order by order_id desc');
$products=db_query('select order_id from {uc_orders} order by order_id desc');
$aSheet->setCellValue('A1','URL');
$aSheet->setCellValue('B1','Наименование');
$aSheet->setCellValue('C1','Кол-во');


$p=array();
while($product=$products->fetchAssoc()){
    $product=uc_order_load($product['order_id']);
    
    if( isset($product->products) and is_array($product->products) and count($product->products) ){
        foreach($product->products as $pr){
            if( isset($pr->nid) ){
                $p[$pr->nid]['title']=$pr->title.' ('.$pr->model.')';
                if( isset($p[$pr->nid]['count']) and is_numeric($p[$pr->nid]['count']) ){
                    $p[$pr->nid]['count']+=$pr->qty;
                }else{
                    $p[$pr->nid]['count']=$pr->qty;
                }
            }
        }
    }
    
}

if( isset($p) and is_array($p) and count($p) ){
    $num=1;
    foreach($p as $nid => $data){
        $num++;
        $aSheet->setCellValue('A'.$num, '/node/'.$nid );
        $aSheet->setCellValue('B'.$num, $data['title'] );
        $aSheet->setCellValue('C'.$num, $data['count'] );
        
    }
}

$aSheet->getColumnDimension('A')->setWidth(15);
$aSheet->getColumnDimension('B')->setWidth(23);
$aSheet->getColumnDimension('C')->setWidth(7);
//отдаем пользователю в браузер
include(DRUPAL_ROOT . "/sites/all/libraries/php/Classes/PHPExcel/Writer/Excel5.php");
//$objWriter = new PHPExcel_Writer_Excel5($pExcel);
//$objWriter->save('price.xls');


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="products.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

?>