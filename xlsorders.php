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
$aSheet->setTitle('Заказы');


//$products=db_query('select order_id from {uc_orders} where created>'.$curd1.' and created<'.$curd2.' order by order_id desc');
$products=db_query('select order_id from {uc_orders} order by order_id desc');
$aSheet->setCellValue('A1','Кол-во');
$aSheet->setCellValue('B1','Цена, р');
$aSheet->setCellValue('C1','Стоимость, р');
$aSheet->setCellValue('D1','Доставка, р');
$aSheet->setCellValue('E1','Email');
$aSheet->setCellValue('F1','Имя');
$aSheet->setCellValue('G1','Фамилия');
$aSheet->setCellValue('H1','Телефон');
$aSheet->setCellValue('I1','Улица');
$aSheet->setCellValue('J1','Город');
$aSheet->setCellValue('K1','Регион');
$aSheet->setCellValue('L1','Индекс');
$aSheet->setCellValue('M1','Страна');
$aSheet->setCellValue('N1','Оплата');
$aSheet->setCellValue('O1','Создан');
$aSheet->setCellValue('P1','IP');
$aSheet->setCellValue('Q1','UID пользователя');
$aSheet->setCellValue('R1','Статус заказа');
$aSheet->setCellValue('S1','№ заказа');
$aSheet->setCellValue('T1','Номер отправления');
$aSheet->setCellValue('U1','Дата отправки');
//$aSheet->setCellValue('V1','Вес');

$num=1;
while($product=$products->fetchAssoc()){
    $num++;
    $product=uc_order_load($product['order_id']);

    if( isset($product->product_count) and strlen($product->product_count) ){
        $aSheet->setCellValue('A'.$num, $product->product_count );
    }
    if( isset($product->products) and is_array($product->products) and count($product->products) ){
        $cost=0;
        $price=0;
        foreach($product->products as $pr){
            if( isset($pr->cost) ){
                $cost+=$pr->qty*$pr->cost;
                $price+=$pr->qty*$pr->price;
            }
        }
        $aSheet->setCellValue('B'.$num, $price );
        $aSheet->setCellValue('C'.$num, $cost );
    }
    if( isset($product->quote['rate']) and is_numeric($product->quote['rate']) ){
        $aSheet->setCellValue('D'.$num, $product->quote['rate'] );
    }
    if( isset($product->primary_email) and strlen($product->primary_email) ){
        $aSheet->setCellValue('E'.$num, $product->primary_email );
    }
    if( isset($product->delivery_first_name) and strlen($product->delivery_first_name) ){
        $aSheet->setCellValue('F'.$num, $product->delivery_first_name );
    }
    if( isset($product->delivery_last_name) and strlen($product->delivery_last_name) ){
        $aSheet->setCellValue('G'.$num, $product->delivery_last_name );
    }
    if( isset($product->delivery_phone) and strlen($product->delivery_phone) ){
        $aSheet->setCellValue('H'.$num, $product->delivery_phone );
    }
    if( isset($product->delivery_street1) and strlen($product->delivery_street1) ){
        $aSheet->setCellValue('I'.$num, $product->delivery_street1 );
    }
    if( isset($product->delivery_city) and strlen($product->delivery_city) ){
        $aSheet->setCellValue('J'.$num, $product->delivery_city );
    }
    if( isset($product->delivery_zone) and strlen($product->delivery_zone) ){
        $zonename=db_query("SELECT zone_name FROM {uc_zones} WHERE zone_id = ".$product->delivery_zone);
        $zonename=$zonename->fetchAssoc();
        if(isset($zonename['zone_name'])){
            $aSheet->setCellValue('K'.$num, $zonename['zone_name'] );
        }
    }
    if( isset($product->delivery_postal_code) and strlen($product->delivery_postal_code) ){
        $aSheet->setCellValue('L'.$num, $product->delivery_postal_code );
    }
    if( isset($product->delivery_country) and strlen($product->delivery_country) ){
        $aSheet->setCellValue('M'.$num, $product->delivery_country );
    }
    if( isset($product->payment_method) and strlen($product->payment_method) ){
        $aSheet->setCellValue('N'.$num, $product->payment_method );
    }
    if( isset($product->created) and strlen($product->created) ){
        $aSheet->setCellValue('O'.$num, date('d.m.Y',$product->created) );
    }
    if( isset($product->host) and strlen($product->host) ){
        $aSheet->setCellValue('P'.$num, $product->host );
    }
    if( isset($product->uid) and strlen($product->uid) ){
        $aSheet->setCellValue('Q'.$num, $product->uid );
    }
    if( isset($product->order_status) and strlen($product->order_status) ){
        $name=db_query('select title from {uc_order_statuses} where order_status_id=\''.$product->order_status.'\'');
        $name=$name->fetchAssoc();
        if( isset($name['title']) ){
            $product->order_status=$name['title'].' ('.$product->order_status.')';
        }
        $aSheet->setCellValue('R'.$num, $product->order_status );
    }
    if( isset($product->order_id) and strlen($product->order_id) ){
        $aSheet->setCellValue('S'.$num, $product->order_id );
    }

    $ship=db_query('SELECT * FROM {uc_shipments} where order_id='.$product->order_id);
    $ship=$ship->fetchAssoc();
    if( isset($ship['tracking_number']) and strlen($ship['tracking_number']) ){
        $aSheet->setCellValue('T'.$num, '"'.$ship['tracking_number'].'"' );
    }
    if( isset($ship['ship_date']) and is_numeric($ship['ship_date']) ){
        $aSheet->setCellValue('U'.$num, date('d.n.Y', $ship['ship_date']) );
    }
    

    continue;
}


$aSheet->getColumnDimension('A')->setWidth(11);
$aSheet->getColumnDimension('B')->setWidth(9);
$aSheet->getColumnDimension('C')->setWidth(9);
$aSheet->getColumnDimension('D')->setWidth(9);
$aSheet->getColumnDimension('E')->setWidth(14);
$aSheet->getColumnDimension('F')->setWidth(14);
$aSheet->getColumnDimension('G')->setWidth(14);
$aSheet->getColumnDimension('H')->setWidth(14);
$aSheet->getColumnDimension('I')->setWidth(14);
$aSheet->getColumnDimension('J')->setWidth(14);
$aSheet->getColumnDimension('K')->setWidth(14);
$aSheet->getColumnDimension('L')->setWidth(14);
$aSheet->getColumnDimension('M')->setWidth(7);
$aSheet->getColumnDimension('N')->setWidth(7);
$aSheet->getColumnDimension('O')->setWidth(14);
$aSheet->getColumnDimension('P')->setWidth(14);
$aSheet->getColumnDimension('Q')->setWidth(7);
$aSheet->getColumnDimension('R')->setWidth(7);
$aSheet->getColumnDimension('S')->setWidth(17);
$aSheet->getColumnDimension('T')->setWidth(7);
$aSheet->getColumnDimension('U')->setWidth(7);
//$aSheet->getColumnDimension('V')->setWidth(7);
//отдаем пользователю в браузер
include(DRUPAL_ROOT . "/sites/all/libraries/php/Classes/PHPExcel/Writer/Excel5.php");
//$objWriter = new PHPExcel_Writer_Excel5($pExcel);
//$objWriter->save('price.xls');


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="rate.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

?>