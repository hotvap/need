<?php

if( isset( $_REQUEST['op'] ) and is_numeric($_REQUEST['op']) ){

        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        
    $acols=array(
        'colpos1'=>'Артикул',
        'colpos2'=>'Название',
        'colpos3'=>'Раздел',
        'colpos4'=>'Подраздел',
        'colpos5'=>'Бренд',
        'colpos6'=>'Цена/час',
        'colpos7'=>'Цена/день',
        'colpos8'=>'Цена/неделю',
        'colpos9'=>'Цена/месяц',
        'colpos10'=>'Ссылка',
        'colpos11'=>'Картинка',
    );
    $acolsno=$acols;
    $asizes=array(
        'colpos1'=>'11',
        'colpos2'=>'33',
        'colpos3'=>'15',
        'colpos4'=>'15',
        'colpos5'=>'15',
        'colpos6'=>'11',
        'colpos7'=>'11',
        'colpos8'=>'11',
        'colpos9'=>'11',
        'colpos10'=>'17',
        'colpos11'=>'11',
    );
    $asymbol=array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                
        global $user;
        if(  $user->uid ){
            switch($_REQUEST['op']){
                case 1: //экспорт в doc
    if( isset( $_POST['brands'] ) and is_array($_POST['brands']) and count($_POST['brands']) and isset( $_POST['cats'] ) and is_array($_POST['cats']) and count($_POST['cats']) and isset( $_POST['cols'] ) and strlen($_POST['cols']) ){



        $allbrand=0;
        foreach( $_POST['brands'] as $ind => $br ){
            if( !is_numeric($br) ){
                unset( $_POST['brands'][$ind] );
                continue;
            }
            if( $br==0 ){
                $allbrand=1;
            }
        }

        $allcat=0;
        foreach( $_POST['cats'] as $ind => $br ){
            if( !is_numeric($br) ){
                unset( $_POST['cats'][$ind] );
                continue;
            }
            if( $br==0 ){
                $allcat=1;
            }
        }
        
        $style= image_style_load('inlinesearch');
        $exportcols=explode('|', $_POST['cols']);
        if( isset($exportcols) and is_array($exportcols) and count($exportcols)>1 ){
            foreach( $exportcols as $ind => $exportcol ){
                if( isset( $acols[$exportcol] ) and strlen( $acols[$exportcol] ) ){}else{
                    unset( $exportcols[$ind] );
                }
            }
            if( isset($exportcols) and is_array($exportcols) and count($exportcols)>1 ){
                $num=1;
                $counter=1;
                $source_content.=  '<div class="export_results">';

                require_once getcwd() . '/sites/all/libraries/word/PHPWord.php';

                $word = new PHPWord();
                $word->setDefaultFontName('Times New Roman');
                $word->setDefaultFontSize(14);
                $meta = $word->getProperties();
                $meta->setCreator($_SERVER['HTTP_HOST']); 
                $meta->setCompany($_SERVER['HTTP_HOST']);
                $meta->setTitle('Экспорт объявлений '.$_SERVER['HTTP_HOST']);
                $meta->setDescription('Экспорт объявлений '.$_SERVER['HTTP_HOST']); 
                $meta->setCategory('Отчет');
                $meta->setLastModifiedBy('http://'.$_SERVER['HTTP_HOST']);
                $meta->setCreated( time() );
                $meta->setModified( time() );
                $meta->setSubject('Экспорт объявлений'); 
                $meta->setKeywords('отчет, экспорт, объявления');
                $sectionStyle = array('orientation' => 'landscape',
                               'marginLeft' => parad0x_changes_m2t(15),
                               'marginRight' => parad0x_changes_m2t(15),
                               'marginTop' => parad0x_changes_m2t(15),
                               'borderTopColor' => 'C0C0C0'
                         );
                $section = $word->createSection($sectionStyle);
                $fontStyle = array('color'=>'000000', 'size'=>18, 'bold'=>true);
                $fontStyle2 = array('color'=>'000000', 'size'=>12);
                $fontCellStyle = array('align'=>'center');
    
                $paragraphStyle = array('align'=>'center');
                $paragraphStyle2 = array('align'=>'left');
                $word->addParagraphStyle('myParagraphStyle', $paragraphStyle);
                $word->addParagraphStyle('myParagraphStyle2', $paragraphStyle2);
    
                $section->addTextBreak(7);
                $section->addText('Экспорт1 объявлений '.$_SERVER['HTTP_HOST'], $fontStyle, 'myParagraphStyle');
                $section->addText('Дата: '.date('j', time()).' '.mb_strtolower(pdxneedto_month_declination_ru(format_date(time(),'custom','F'),date('n',time()))).' '.date('Y', time()).' года', $fontStyle2, 'myParagraphStyle2');
                $section->addText('Профиль пользователя: '.$GLOBALS['base_url'].'/user/'.$user->uid, $fontStyle2, 'myParagraphStyle2');

                if( !$allbrand and count($_POST['brands']) ){
                    $isnm=array();
                    $names=db_query('select name from {taxonomy_term_data} where tid IN ('.implode(', ', $_POST['brands']).')');
                    while( $name=$names->fetchAssoc() ){
                        $isnm[]=$name['name'];
                    }
                    if( isset($isnm) and is_array($isnm) and count($isnm) ){
                        $section->addText('Бренды: '.implode(', ', $isnm), $fontStyle2, 'myParagraphStyle2');
                    }
                }
                if( !$allcat and count($_POST['cats']) ){
                    $isnm=array();
                    $names=db_query('select name from {taxonomy_term_data} where tid IN ('.implode(', ', $_POST['cats']).')');
                    while( $name=$names->fetchAssoc() ){
                        $isnm[]=$name['name'];
                    }
                    if( isset($isnm) and is_array($isnm) and count($isnm) ){
                        $section->addText('Разделы: '.implode(', ', $isnm), $fontStyle2, 'myParagraphStyle2');
                    }
                }
                $section->addPageBreak();


                $tableStyle = array('cellMarginTop'=>parad0x_changes_m2t(1), 'cellMarginBottom'=>parad0x_changes_m2t(1), 'cellMarginRight'=>parad0x_changes_m2t(1), 'cellMarginLeft'=>parad0x_changes_m2t(1));
                $thStyle = array('borderTopColor' => 'ffffff', 'borderTopSize'=>10, 'borderBottomColor' => 'ffffff', 'borderBottomSize'=>10, 'borderRightColor' => 'ffffff', 'borderRightSize'=>10, 'borderLeftColor' => 'ffffff', 'borderLeftSize'=>10, 'bgColor'=>'fabf21');
                $tdStyle = array('borderTopColor' => '000000', 'borderTopSize'=>10, 'borderBottomColor' => '000000', 'borderBottomSize'=>10, 'borderRightColor' => '000000', 'borderRightSize'=>10, 'borderLeftColor' => '000000', 'borderLeftSize'=>10);
                $tdStyle2 = array('borderTopColor' => '000000', 'borderTopSize'=>10, 'borderBottomColor' => '000000', 'borderBottomSize'=>10, 'borderRightColor' => '000000', 'borderRightSize'=>10, 'borderLeftColor' => '000000', 'borderLeftSize'=>10, 'bgColor'=>'ececec');

                $table = $section->addTable($tableStyle);
                $table->addRow();
                $table->addCell(parad0x_changes_m2t(10), $thStyle)->addText("№");
                foreach( $exportcols as $ind => $exportcol ){
                    $table->addCell(parad0x_changes_m2t(10), $thStyle)->addText($acols[$exportcol]);
                }

        $arows=array();
        
                $inner=$where='';
                if( !$allbrand and count($_POST['brands']) ){
                    $where.=' and b.field_brand_tid IN ('.implode(', ', $_POST['brands']).')';
                    $inner.=' inner join {field_data_field_brand} as b on (n.nid=b.entity_id and b.entity_type=\'node\')';
                }
                if( !$allcat and count($_POST['cats']) ){
                    $where.=' and t.taxonomy_catalog_tid IN ('.implode(', ', $_POST['cats']).')';
                    $inner.=' inner join {field_data_taxonomy_catalog} as t on (n.nid=t.entity_id and t.entity_type=\'node\')';
                }
                $sql='select n.nid from {node} as n'.$inner.' left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type = \'item\' and n.uid='.$user->uid.' and n.status=1'.$where.' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) order by n.created desc';
                $rows=db_query($sql);
                while( $row=$rows->fetchAssoc() ){
                    $arows[$row['nid']]=1;
                }
                
                if( isset($arows) and is_array($arows) and count($arows) ){
                foreach( $arows as $nid=>$row ){
                    $nd=node_load($nid);
                    if( !isset($nd->nid) ){
                        continue;
                    }

   $out='';
   
   
                    $table->addRow();
                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2) )->addText($counter++);
   
                    foreach( $exportcols as $ind => $exportcol ){
                        switch($exportcol){
                        case 'colpos1': //Артикул
                            $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2) )->addText('N1'.$nd->nid);
                            break;
                        case 'colpos2': //Название
                            $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText($nd->title);
                            break;
                        case 'colpos3': //Раздел
                            if( isset($nd->taxonomy_catalog['und'][0]['tid']) and is_numeric($nd->taxonomy_catalog['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->taxonomy_catalog['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText($name['name']);
                                }else{
                                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                                }
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos4': //Подраздел
                            if( isset($nd->field_subpart['und'][0]['tid']) and is_numeric($nd->field_subpart['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->field_subpart['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText($name['name']);
                                }else{
                                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                                }
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos5': //Бренд
                            if( isset($nd->field_brand['und'][0]['tid']) and is_numeric($nd->field_brand['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->field_brand['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText($name['name']);
                                }else{
                                    $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                                }
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos6': //Цена/час
                            if( isset($nd->field_price_hour['und'][0]['value']) and is_numeric($nd->field_price_hour['und'][0]['value']) ){
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(str_replace('.00', '', number_format($nd->field_price_hour['und'][0]['value'], 2, '.', ' ')));
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos7': //Цена/день
                            if( isset($nd->field_price_day['und'][0]['value']) and is_numeric($nd->field_price_day['und'][0]['value']) ){
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(str_replace('.00', '', number_format($nd->field_price_day['und'][0]['value'], 2, '.', ' ')));
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos8': //Цена/неделю
                            if( isset($nd->field_price_week['und'][0]['value']) and is_numeric($nd->field_price_week['und'][0]['value']) ){
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(str_replace('.00', '', number_format($nd->field_price_week['und'][0]['value'], 2, '.', ' ')));
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos9': //Цена/месяц
                            if( isset($nd->field_price_month['und'][0]['value']) and is_numeric($nd->field_price_month['und'][0]['value']) ){
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(str_replace('.00', '', number_format($nd->field_price_month['und'][0]['value'], 2, '.', ' ')));
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        case 'colpos10': //Ссылка
                            $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addLink(url('node/'.$nd->nid, array('absolute'=>TRUE)), url('node/'.$nd->nid, array('absolute'=>TRUE)));
                            break;
                        case 'colpos11': //Картинка
                            $img='';

/*    
                            if( isset( $nd->field_image['und'][0]['fid'] ) and is_numeric( $nd->field_image['und'][0]['fid'] ) ){
                                $file = file_load($nd->field_image['und'][0]['fid']);
                                if( isset( $file->uri ) and strlen( $file->uri ) ){
                                    $real = drupal_realpath($file->uri);
                                    $img=image_style_path('inlinesearch', $file->uri);
                                    $img = drupal_realpath($img);
                                    if( !file_exists($img) ){
                                        image_style_create_derivative($style, $real, $img);
                                    }
                                }
                            }
*/
                            if( isset( $nd->field_image['und'][0]['fid'] ) and is_numeric( $nd->field_image['und'][0]['fid'] ) ){
                                $file = file_load($nd->field_image['und'][0]['fid']);
                                if( isset( $file->uri ) and strlen( $file->uri ) ){
                                    $real = drupal_realpath($file->uri);
                                    $img=image_style_url('inlinesearch', $file->uri);
                                    if( !file_exists($img) ){
                                        image_style_create_derivative($style, $real, $img);
                                        $img=image_style_url('inlinesearch', $file->uri);
                                    }
                                }
                            }
                $img=str_replace('http://'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                $img=str_replace('https://'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                $img=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                $img=str_replace('https://www.'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img); $img=str_replace('http://d15mt731vu6ndj.cloudfront.net/static/needtome/'.PDX_CITY_ID.'/', DRUPAL_ROOT.'/sites/default/files/styles/', $img);
                if( mb_strpos($img, '?')===false ){}else{
                    $img=mb_substr($img, 0, mb_strpos($img, '?') );
                }

                            if( isset($img) and strlen($img) and file_exists($img) ){
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addImage($img, array('width'=>57, 'height'=>57, 'align'=>'center'));
                            }else{
                                $table->addCell(parad0x_changes_m2t(10), ($num%2)?($tdStyle):($tdStyle2))->addText(' ');
                            }
                            break;
                        }
                     
                    }

   $num++;

                }
                }
                


                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Disposition: attachment;filename="export_'.date('Y.m.d', time()).'.docx"');
                header('Cache-Control: max-age=0');
                $writer = PHPWord_IOFactory::createWriter($word, 'Word2007');
                $writer->save('php://output');
                
            }
        }



/* ------------ конец экспорта -------------- */
        
    }
                    break;
                case 2: //сохранить как Excel
    if( isset( $_POST['brands'] ) and is_array($_POST['brands']) and count($_POST['brands']) and isset( $_POST['cats'] ) and is_array($_POST['cats']) and count($_POST['cats']) and isset( $_POST['cols'] ) and strlen($_POST['cols']) ){



        $allbrand=0;
        foreach( $_POST['brands'] as $ind => $br ){
            if( !is_numeric($br) ){
                unset( $_POST['brands'][$ind] );
                continue;
            }
            if( $br==0 ){
                $allbrand=1;
            }
        }

        $allcat=0;
        foreach( $_POST['cats'] as $ind => $br ){
            if( !is_numeric($br) ){
                unset( $_POST['cats'][$ind] );
                continue;
            }
            if( $br==0 ){
                $allcat=1;
            }
        }
        
        $style= image_style_load('inlinesearch');
        $exportcols=explode('|', $_POST['cols']);
        if( isset($exportcols) and is_array($exportcols) and count($exportcols)>1 ){
            foreach( $exportcols as $ind => $exportcol ){
                if( isset( $acols[$exportcol] ) and strlen( $acols[$exportcol] ) ){}else{
                    unset( $exportcols[$ind] );
                }
            }
            if( isset($exportcols) and is_array($exportcols) and count($exportcols)>1 ){
                $num=1;
                $counter=1;
                $source_content.=  '<div class="export_results">';

                require_once getcwd() . '/sites/all/libraries/php/Classes/PHPExcel.php';
                
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Экспорт объявлений '.$_SERVER['HTTP_HOST']);

                $aSheet->setCellValue('A'.$num, 'Дата: '.date('j', time()).' '.mb_strtolower(pdxneedto_month_declination_ru(format_date(time(),'custom','F'),date('n',time()))).' '.date('Y', time()).' года' );
                $aSheet->getStyle('A'.$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $aSheet->getStyle('A'.$num)->getFill()->getStartColor()->setRGB('EEEEEE');
                $aSheet->mergeCells('A'.$num.':C'.$num);
                $num++;

                $aSheet->setCellValue('A'.$num, 'Профиль пользователя: '.$GLOBALS['base_url'].'/user/'.$user->uid );
                $aSheet->getStyle('A'.$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $aSheet->getStyle('A'.$num)->getFill()->getStartColor()->setRGB('EEEEEE');
                $aSheet->mergeCells('A'.$num.':C'.$num);
                $num++;
                
                if( !$allbrand and count($_POST['brands']) ){
                    $isnm=array();
                    $names=db_query('select name from {taxonomy_term_data} where tid IN ('.implode(', ', $_POST['brands']).')');
                    while( $name=$names->fetchAssoc() ){
                        $isnm[]=$name['name'];
                    }
                    if( isset($isnm) and is_array($isnm) and count($isnm) ){
                        $aSheet->setCellValue('A'.$num, 'Бренды: '.implode(', ', $isnm) );
                        $aSheet->getStyle('A'.$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $aSheet->getStyle('A'.$num)->getFill()->getStartColor()->setRGB('EEEEEE');
                        $aSheet->mergeCells('A'.$num.':C'.$num);
                        $num++;
                    }
                }
                
                if( !$allcat and count($_POST['cats']) ){
                    $isnm=array();
                    $names=db_query('select name from {taxonomy_term_data} where tid IN ('.implode(', ', $_POST['cats']).')');
                    while( $name=$names->fetchAssoc() ){
                        $isnm[]=$name['name'];
                    }
                    if( isset($isnm) and is_array($isnm) and count($isnm) ){
                        $aSheet->setCellValue('A'.$num, 'Разделы: '.implode(', ', $isnm) );
                        $aSheet->getStyle('A'.$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $aSheet->getStyle('A'.$num)->getFill()->getStartColor()->setRGB('EEEEEE');
                        $aSheet->mergeCells('A'.$num.':C'.$num);
                        $num++;
                    }
                }
                
                $num++;
                
                $aSheet->setCellValue($asymbol[0].$num, '№' );
                $aSheet->getStyle($asymbol[0].$num)->getFont()->setBold(true);
                foreach( $exportcols as $ind => $exportcol ){
                    $aSheet->setCellValue($asymbol[($ind+1)].$num, $acols[$exportcol] );
                    $aSheet->getStyle($asymbol[($ind+1)].$num)->getFont()->setBold(true);
                }
                $num++;


        $arows=array();
        
                $inner=$where='';
                if( !$allbrand and count($_POST['brands']) ){
                    $where.=' and b.field_brand_tid IN ('.implode(', ', $_POST['brands']).')';
                    $inner.=' inner join {field_data_field_brand} as b on (n.nid=b.entity_id and b.entity_type=\'node\')';
                }
                if( !$allcat and count($_POST['cats']) ){
                    $where.=' and t.taxonomy_catalog_tid IN ('.implode(', ', $_POST['cats']).')';
                    $inner.=' inner join {field_data_taxonomy_catalog} as t on (n.nid=t.entity_id and t.entity_type=\'node\')';
                }
                $sql='select n.nid from {node} as n'.$inner.' left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type = \'item\' and n.uid='.$user->uid.' and n.status=1'.$where.' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) order by n.created desc';
                $rows=db_query($sql);
                while( $row=$rows->fetchAssoc() ){
                    $arows[$row['nid']]=1;
                }
                
                if( isset($arows) and is_array($arows) and count($arows) ){
                foreach( $arows as $nid=>$row ){
                    $nd=node_load($nid);
                    if( !isset($nd->nid) ){
                        continue;
                    }

   $out='';
   
   
   
                    $aSheet->setCellValue('A'.$num, $counter++ );
                    foreach( $exportcols as $ind => $exportcol ){
                        switch($exportcol){
                        case 'colpos1': //Артикул
                            $aSheet->setCellValue($asymbol[($ind+1)].$num, 'N1'.$nd->nid );
                            break;
                        case 'colpos2': //Название
                            $aSheet->setCellValue($asymbol[($ind+1)].$num, $nd->title );
                            $aSheet->getStyle($asymbol[($ind+1)].$num)->getAlignment()->setWrapText(true);
                            //$aSheet->getStyle($asymbol[($ind+2)].$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            break;
                        case 'colpos3': //Раздел
                            if( isset($nd->taxonomy_catalog['und'][0]['tid']) and is_numeric($nd->taxonomy_catalog['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->taxonomy_catalog['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $aSheet->setCellValue($asymbol[($ind+1)].$num, $name['name'] );
                                }else{
                                    $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                                }
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos4': //Подраздел
                            if( isset($nd->field_subpart['und'][0]['tid']) and is_numeric($nd->field_subpart['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->field_subpart['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $aSheet->setCellValue($asymbol[($ind+1)].$num, $name['name'] );
                                }else{
                                    $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                                }
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos5': //Бренд
                            if( isset($nd->field_brand['und'][0]['tid']) and is_numeric($nd->field_brand['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->field_brand['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $aSheet->setCellValue($asymbol[($ind+1)].$num, $name['name'] );
                                }else{
                                    $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                                }
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos6': //Цена/час
                            if( isset($nd->field_price_hour['und'][0]['value']) and is_numeric($nd->field_price_hour['und'][0]['value']) ){
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, str_replace('.00', '', number_format($nd->field_price_hour['und'][0]['value'], 2, '.', ' ')) );
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos7': //Цена/день
                            if( isset($nd->field_price_day['und'][0]['value']) and is_numeric($nd->field_price_day['und'][0]['value']) ){
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, str_replace('.00', '', number_format($nd->field_price_day['und'][0]['value'], 2, '.', ' ')) );
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos8': //Цена/неделю
                            if( isset($nd->field_price_week['und'][0]['value']) and is_numeric($nd->field_price_week['und'][0]['value']) ){
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, str_replace('.00', '', number_format($nd->field_price_week['und'][0]['value'], 2, '.', ' ')) );
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos9': //Цена/месяц
                            if( isset($nd->field_price_month['und'][0]['value']) and is_numeric($nd->field_price_month['und'][0]['value']) ){
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, str_replace('.00', '', number_format($nd->field_price_month['und'][0]['value'], 2, '.', ' ')) );
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        case 'colpos10': //Ссылка
                            $aSheet->setCellValue($asymbol[($ind+1)].$num, url('node/'.$nd->nid, array('absolute'=>TRUE)) );
                            break;
                        case 'colpos11': //Картинка
                            $img='';
                            if( isset( $nd->field_image['und'][0]['fid'] ) and is_numeric( $nd->field_image['und'][0]['fid'] ) ){
                                $file = file_load($nd->field_image['und'][0]['fid']);
                                if( isset( $file->uri ) and strlen( $file->uri ) ){
                                    $real = drupal_realpath($file->uri);
                                    $img=image_style_path('inlinesearch', $file->uri);
                                    $img = drupal_realpath($img);
                                    if( !file_exists($img) ){
                                        image_style_create_derivative($style, $real, $img);
                                    }
                                }
                            }
                            if( isset($img) and strlen($img) and file_exists($img) ){
                                $logo = new PHPExcel_Worksheet_Drawing();
                                $logo->setPath($img);
                                $logo->setCoordinates($asymbol[($ind+1)].$num);             
                                $logo->setOffsetX(0);
                                $logo->setOffsetY(0);    
                                $aSheet->getRowDimension($num)->setRowHeight(57);
                                $logo->setWorksheet($aSheet);
                            }else{
                                $aSheet->setCellValue($asymbol[($ind+1)].$num, '' );
                            }
                            break;
                        }
                     
                    }

                    $num++;

                }
                }
                

                $aSheet->getColumnDimension('A')->setWidth(5);
                foreach( $exportcols as $ind => $exportcol ){
                    $aSheet->getColumnDimension($asymbol[($ind+1)])->setWidth($asizes[$exportcol]);
                }

                include(getcwd() . "/sites/all/libraries/php/Classes/PHPExcel/Writer/Excel5.php");
                
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="export_'.date('Y.m.d', time()).'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');

            }
        }



/* ------------ конец экспорта -------------- */
        
    }
                    break;
                case 3: //экспорт в pdf

                    
    if( isset( $_POST['brands'] ) and is_array($_POST['brands']) and count($_POST['brands']) and isset( $_POST['cats'] ) and is_array($_POST['cats']) and count($_POST['cats']) and isset( $_POST['cols'] ) and strlen($_POST['cols']) ){



        $allbrand=0;
        foreach( $_POST['brands'] as $ind => $br ){
            if( !is_numeric($br) ){
                unset( $_POST['brands'][$ind] );
                continue;
            }
            if( $br==0 ){
                $allbrand=1;
            }
        }

        $allcat=0;
        foreach( $_POST['cats'] as $ind => $br ){
            if( !is_numeric($br) ){
                unset( $_POST['cats'][$ind] );
                continue;
            }
            if( $br==0 ){
                $allcat=1;
            }
        }
        
        $pdfhtml='';
        $style= image_style_load('inlinesearch');
        $exportcols=explode('|', $_POST['cols']);
        if( isset($exportcols) and is_array($exportcols) and count($exportcols)>1 ){
            foreach( $exportcols as $ind => $exportcol ){
                if( isset( $acols[$exportcol] ) and strlen( $acols[$exportcol] ) ){}else{
                    unset( $exportcols[$ind] );
                }
            }
            if( isset($exportcols) and is_array($exportcols) and count($exportcols)>1 ){
                $num=1;
                $counter=1;
                $source_content.=  '<div class="export_results">';

                require_once DRUPAL_ROOT . '/sites/all/libraries/mpdf/mpdf.php';
                $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10, 'L'); /*задаем формат, отступы и.т.д.*/
                $mpdf->charset_in = 'cp1251'; /*не забываем про русский*/
//                $stylesheet = file_get_contents('pdfstyle.css'); /*подключаем css*/
//                $mpdf->WriteHTML($stylesheet, 1);
                
                $pdfhtml.=iconv('UTF-8','cp1251','<h1>Экспорт объявлений '.$_SERVER['HTTP_HOST'].'</h1><p><strong>Дата:</strong> '.date('j', time()).' '.mb_strtolower(pdxneedto_month_declination_ru(format_date(time(),'custom','F'),date('n',time()))).' '.date('Y', time()).' года<br /><strong>Профиль пользователя:</strong> '.$GLOBALS['base_url'].'/user/'.$user->uid);
                if( !$allbrand and count($_POST['brands']) ){
                    $isnm=array();
                    $names=db_query('select name from {taxonomy_term_data} where tid IN ('.implode(', ', $_POST['brands']).')');
                    while( $name=$names->fetchAssoc() ){
                        $isnm[]=$name['name'];
                    }
                    if( isset($isnm) and is_array($isnm) and count($isnm) ){
                        $pdfhtml.=iconv('UTF-8','cp1251','<br /><strong>Бренды:</strong> '.implode(', ', $isnm));
                    }
                }
                if( !$allcat and count($_POST['cats']) ){
                    $isnm=array();
                    $names=db_query('select name from {taxonomy_term_data} where tid IN ('.implode(', ', $_POST['cats']).')');
                    while( $name=$names->fetchAssoc() ){
                        $isnm[]=$name['name'];
                    }
                    if( isset($isnm) and is_array($isnm) and count($isnm) ){
                        $pdfhtml.=iconv('UTF-8','cp1251','<br /><strong>Разделы:</strong> '.implode(', ', $isnm));
                    }
                }
                $pdfhtml.='</p><p>&nbsp;</p>';

                $pdfhtml.='<table style="border-collapse: collapse;">';
                $pdfhtml.='<tr>';
                $pdfhtml.='<th style="border: 1px solid #fff; background: #feeab5; text-align: left; padding: 7px 3px;">'.iconv('UTF-8','cp1251','№').'</th>';
                foreach( $exportcols as $ind => $exportcol ){
                    $pdfhtml.='<th style="border: 1px solid #fff; background: #feeab5; text-align: left; padding: 7px 3px;">'.iconv('UTF-8','cp1251',$acols[$exportcol]).'</th>';
                }
                $pdfhtml.='</tr>';


        $arows=array();
        
                $inner=$where='';
                if( !$allbrand and count($_POST['brands']) ){
                    $where.=' and b.field_brand_tid IN ('.implode(', ', $_POST['brands']).')';
                    $inner.=' inner join {field_data_field_brand} as b on (n.nid=b.entity_id and b.entity_type=\'node\')';
                }
                if( !$allcat and count($_POST['cats']) ){
                    $where.=' and t.taxonomy_catalog_tid IN ('.implode(', ', $_POST['cats']).')';
                    $inner.=' inner join {field_data_taxonomy_catalog} as t on (n.nid=t.entity_id and t.entity_type=\'node\')';
                }
                $sql='select n.nid from {node} as n'.$inner.' left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type = \'item\' and n.uid='.$user->uid.' and n.status=1'.$where.' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) order by n.created desc';
                $rows=db_query($sql);
                while( $row=$rows->fetchAssoc() ){
                    $arows[$row['nid']]=1;
                }
                
                if( isset($arows) and is_array($arows) and count($arows) ){
                foreach( $arows as $nid=>$row ){
                    $nd=node_load($nid);
                    if( !isset($nd->nid) ){
                        continue;
                    }
                    $pdfhtml.='<tr';
                    if( (++$num)%2 ){
                        $pdfhtml.=' style=" background: #ececec; "';
                    }
                    $pdfhtml.='>';

   $out='';
   
                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',$counter++).'</td>';
   
                    foreach( $exportcols as $ind => $exportcol ){
                        switch($exportcol){
                        case 'colpos1': //Артикул
                            $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251','N1'.$nd->nid).'</td>';
                            break;
                        case 'colpos2': //Название
                            $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',$nd->title).'</td>';
                            break;
                        case 'colpos3': //Раздел
                            if( isset($nd->taxonomy_catalog['und'][0]['tid']) and is_numeric($nd->taxonomy_catalog['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->taxonomy_catalog['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',$name['name']).'</td>';
                                }else{
                                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                                }
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos4': //Подраздел
                            if( isset($nd->field_subpart['und'][0]['tid']) and is_numeric($nd->field_subpart['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->field_subpart['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',$name['name']).'</td>';
                                }else{
                                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                                }
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos5': //Бренд
                            if( isset($nd->field_brand['und'][0]['tid']) and is_numeric($nd->field_brand['und'][0]['tid']) ){
                                $name=db_query('select name from {taxonomy_term_data} where tid='.$nd->field_brand['und'][0]['tid']);
                                $name=$name->fetchAssoc();
                                if( isset($name['name']) and strlen($name['name']) ){
                                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',$name['name']).'</td>';
                                }else{
                                    $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                                }
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos6': //Цена/час
                            if( isset($nd->field_price_hour['und'][0]['value']) and is_numeric($nd->field_price_hour['und'][0]['value']) ){
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',str_replace('.00', '', number_format($nd->field_price_hour['und'][0]['value'], 2, '.', ' '))).'</td>';
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos7': //Цена/день
                            if( isset($nd->field_price_day['und'][0]['value']) and is_numeric($nd->field_price_day['und'][0]['value']) ){
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',str_replace('.00', '', number_format($nd->field_price_day['und'][0]['value'], 2, '.', ' '))).'</td>';
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos8': //Цена/неделю
                            if( isset($nd->field_price_week['und'][0]['value']) and is_numeric($nd->field_price_week['und'][0]['value']) ){
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',str_replace('.00', '', number_format($nd->field_price_week['und'][0]['value'], 2, '.', ' '))).'</td>';
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos9': //Цена/месяц
                            if( isset($nd->field_price_month['und'][0]['value']) and is_numeric($nd->field_price_month['und'][0]['value']) ){
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',str_replace('.00', '', number_format($nd->field_price_month['und'][0]['value'], 2, '.', ' '))).'</td>';
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        case 'colpos10': //Ссылка
                            $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251', '<a href="'.url('node/'.$nd->nid, array('absolute'=>TRUE)).'">'.url('node/'.$nd->nid, array('absolute'=>TRUE)).'</a>' ).'</td>';
                            break;
                        case 'colpos11': //Картинка
                            $img='';
                            if( isset( $nd->field_image['und'][0]['fid'] ) and is_numeric( $nd->field_image['und'][0]['fid'] ) ){
                                $file = file_load($nd->field_image['und'][0]['fid']);
                                if( isset( $file->uri ) and strlen( $file->uri ) ){
                                    $img=image_style_url('inlinesearch', $file->uri);
                                }
                            }
                            if( isset($img) and strlen($img) ){
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;"><img alt="" src="'.$img.'" /></td>';
                            }else{
                                $pdfhtml.='<td style="border: 1px solid #ececec; padding: 7px 3px;">'.iconv('UTF-8','cp1251',' ').'</td>';
                            }
                            break;
                        }
                     
                    }

                    $pdfhtml.='</tr>';

                }
                }


                $pdfhtml.='</table>';
                
                $mpdf->list_indent_first_level = 0;
                $mpdf->WriteHTML($pdfhtml, 2); /*формируем pdf*/
                $mpdf->Output('export_'.date('Y.m.d', time()).'.pdf', 'D');
                

            }
        }



/* ------------ конец экспорта -------------- */
        
    }                    
                    
                    
                    
                    break;
                case 4: //очистить Избранное
                    global $user;
                    if( $user->uid){
                        if( is_dir('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST']) ){
                            $aflag=scandir('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST']);
                            if( isset($aflag) and is_array($aflag) and count($aflag) ){
                                foreach( $aflag as $file ){
                                    if( is_numeric($file) ){
                                        unlink('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST'].'/'.$file);
                                    }
                                }
                            }
                            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto') ){
                                unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto');
                            }
                        }
                    }
                    break;
                case 5: //очистить Избранных пользователей
                    global $user;
                    if( $user->uid){
                        if( is_dir('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST']) ){
                            $aflag=scandir('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST']);
                            if( isset($aflag) and is_array($aflag) and count($aflag) ){
                                foreach( $aflag as $file ){
                                    if( is_numeric($file) ){
                                        unlink('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST'].'/'.$file);
                                    }
                                }
                            }
                            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto') ){
                                unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto');
                            }
                        }
                    }
                    break;                    
            }
        }
    
    if( isset( $_REQUEST['nid'] ) and is_numeric($_REQUEST['nid']) ){
        switch($_REQUEST['op']){
            case 6: //сохранить в PDF  отдельное
                $node=node_load($_REQUEST['nid']);
                if( isset( $node->type ) and strlen( $node->type ) and $node->type=='item' ){
                        $pdfhtml='';
                        require_once DRUPAL_ROOT . '/sites/all/libraries/mpdf/mpdf.php';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10, 'L');
                        $mpdf->charset_in = 'cp1251';
                        
                        $usr=user_load($node->uid);

                    $companyname=$usr->field_name['und'][0]['value'];
                    if( isset($usr->field_who['und'][0]['value']) and $usr->field_who['und'][0]['value']==2 ){
                        if( isset($usr->field_company_name['und'][0]['value']) and strlen($usr->field_company_name['und'][0]['value']) ){
                            $companyname=$usr->field_company_name['und'][0]['value'];
                        }
                    }
                    if( isset($companyname) and strlen($companyname) ){
                        $username='<br /><strong>Автор:</strong> '.$companyname;
                    }else{
                        $username='';
                    }

                        $img='';
                        if( isset($usr->field_company_logo['und'][0]['fid']) and strlen($usr->field_company_logo['und'][0]['fid']) ){
                            $uri = file_load($usr->field_company_logo['und'][0]['fid'])->uri;
                            $uri = image_style_url('userbg', $uri);
                            if(isset($uri) and strlen($uri)){
                                $img=$uri;
                            }
                        }else{
                            if(isset($usr->picture->uri) and strlen($usr->picture->uri)){
                                $uri = image_style_url('userbg', $usr->picture->uri);
                                if(isset($uri) and strlen($uri)){
                                    $img=$uri;
                                }
                            }
                        }
                        if( isset($img) and strlen($img) ){
                            $img='<div><img width="109" height="109" alt="" src="'.$img.'" /></div>';
                        }
                        
//<strong>Дата:</strong> '.date('j', $node->created ).' '.mb_strtolower(pdxlogmy_month_declination_ru(format_date($node->created,'custom','F'),date('n',$node->created))).' '.date('Y', time()).' года'.$username.'<br /> 
                    
                    
                        $pdfhtml.=iconv('UTF-8','cp1251','<h1>'.$node->title.'</h1><p><strong>Артикул:</strong> N1'.$node->nid.'<br /><strong>Ссылка на объявление:</strong> '.url('node/'.$node->nid, array('absolute'=>true)).'</p><h3>Об авторе</h3>'.$img.'<p><strong>Профиль автора:</strong> '.url('user/'.$node->uid, array('absolute'=>true)).$username);
                        
                        if( isset( $usr->field_sex['und'][0]['value'] ) and is_numeric( $usr->field_sex['und'][0]['value'] ) ){
                            switch( $usr->field_sex['und'][0]['value'] ){
                                case 0:
                                    $pdfhtml.=iconv('UTF-8','cp1251', '<br /><strong>Пол:</strong> мужской');
                                    break;
                                case 1:
                                    $pdfhtml.=iconv('UTF-8','cp1251', '<br /><strong>Пол:</strong> женский');
                                    break;
                            }
                        }

                        if( isset( $usr->field_who['und'][0]['value'] ) and is_numeric( $usr->field_who['und'][0]['value'] ) ){
                            switch( $usr->field_who['und'][0]['value'] ){
                                case 1:
                                    $pdfhtml.=iconv('UTF-8','cp1251', '<br /><strong>Я представляю:</strong> частное лицо');
                                    break;
                                case 2:
                                    $pdfhtml.=iconv('UTF-8','cp1251', '<br /><strong>Я представляю:</strong> компанию или магазин');
                                    break;
                            }
                        }
                    
                        $pdfhtml.='</p><p>&nbsp;</p><hr /><p>&nbsp;</p>';

                        $img='';
                        if( isset( $node->field_image['und'][0]['fid'] ) and is_numeric( $node->field_image['und'][0]['fid'] ) ){
                            $file = file_load($node->field_image['und'][0]['fid']);
                            if( isset( $file->uri ) and strlen( $file->uri ) ){
                                $img=image_style_url('product_full', $file->uri);
                            }
                        }
                        if( isset($img) and strlen($img) ){
                            $pdfhtml.='<div style="text-align: center;"><img alt="" src="'.$img.'" /></div><p>&nbsp;</p>';
                        }
                    
                    
                        if( isset($node->body['und'][0]['value']) and strlen($node->body['und'][0]['value']) ){
                            $pdfhtml.=iconv('UTF-8','cp1251', '<div>'.$node->body['und'][0]['value'].'</div>' );
                        }                        
/*                        
                        if( isset($node->field_part['und'][0]['tid']) and is_numeric($node->field_part['und'][0]['tid']) ){
                            $name=db_query('select name from {taxonomy_term_data} where tid='.$node->field_part['und'][0]['tid']);
                            $name=$name->fetchAssoc();
                            if( isset($name['name']) and strlen($name['name']) ){
                                $pdfhtml.=iconv('UTF-8','cp1251', '<br /><strong>Раздел:</strong> '.$name['name'] );
                            }
                        }
*/
                    
        if(isset($node->field_state['und'][0]['value']) and is_numeric($node->field_state['und'][0]['value'])){
            $name='';
            switch($node->field_state['und'][0]['value']){
            case 1:
                $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>Состояние:</strong> отличное</div>');
                break;
            case 2:
                $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>Состояние:</strong> хорошее</div>');
                break;
            case 3:
                $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>Состояние:</strong> есть потертости</div>');
                break;
            case 4:
                $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>Состояние:</strong> плохое, но функцию выполняет</div>');
                break;
            }
        }
        if(isset($node->field_brand['und'][0]['tid']) and is_numeric($node->field_brand['und'][0]['tid']) and isset($node->taxonomy_catalog['und'][0]['tid']) and is_numeric($node->taxonomy_catalog['und'][0]['tid'])){
            $name=db_query('select name from {taxonomy_term_data} where tid='.$node->field_brand['und'][0]['tid']);
            $name=$name->fetchAssoc();
            if( isset($name['name']) and strlen($name['name']) ){
                $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>Бренд:</strong> '.$name['name'].'</div>');
            }
        }
                    
        if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) ){
            $suffix= 'часов';
            $tmpis=$node->field_price_min['und'][0]['value'];
            if( $tmpis>23 ){
                $tmpis=round($tmpis/24, 0);
                $suffix= 'дней';
                if( $tmpis==1 ){
                    $suffix= 'дня';
                }
            }
            if( $tmpis>29 ){
                $tmpis=round($tmpis/30, 0);
                $suffix= 'месяца';
            }
            $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>Срок аренды:</strong> от '.$tmpis.' '.$suffix.'</div>');
        }

                    
    $sign='';
    if( defined('PDX_CITY_CUR') ){
        $sign=PDX_CITY_CUR;
    }
    
    if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
        switch($node->field_cur['und'][0]['value']){
        case 2:
            echo ' nodeinval';
            $sign='$';
            break;
        default:
            echo ' nodeinval2';
            $sign='€';
            break;
        }
    }

    $pdfhtml.=iconv('UTF-8','cp1251', '<div>&nbsp;</div>');
                    
                    
    $price1=$price2=$price4=$price3=$yesprice=$pricelen=0;
    
    if( isset($node->field_price_month['und'][0]['value']) and is_numeric($node->field_price_month['und'][0]['value']) ){
        $price4=$node->field_price_month['und'][0]['value'];
        $price3=round($node->field_price_month['und'][0]['value']/4, 2);
        $price2=round($node->field_price_month['und'][0]['value']/30, 2);
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_week['und'][0]['value']) and is_numeric($node->field_price_week['und'][0]['value']) ){
        $price3=$node->field_price_week['und'][0]['value'];
        $price2=round($node->field_price_week['und'][0]['value']/7, 2);
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_day['und'][0]['value']) and is_numeric($node->field_price_day['und'][0]['value']) ){
        $price2=$node->field_price_day['und'][0]['value'];
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_hour['und'][0]['value']) and is_numeric($node->field_price_hour['und'][0]['value']) ){
        $price1=$node->field_price_hour['und'][0]['value'];
        if( !isset($price2) or !is_numeric($price2) or $price2<1 ){
            $price2=$price1*24;
        }
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $yesprice=1;
    }
    if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) ){
        if( $node->field_price_min['und'][0]['value']>23 ){
            $price1=0;
            if( $node->field_price_min['und'][0]['value']>167 ){
                $price2=0;
            }
        }
    }

                    
                    
    if( $yesprice ){
        $nodiscount=$isdisc=0;
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $tmponly=number_format($price1, 0, '', ' ');
            $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>'.$tmponly.'</strong> '.$sign.' / час</div>');
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*24;
        }
        if( isset($price2) and is_numeric($price2) and $price2>0 ){
            $tmponly=number_format($price2, 0, '', ' ');
            $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>'.$tmponly.'</strong> '.$sign.' / день (24 ч)</div>');
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*168;
        }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
            $nodiscount=$price2*7;
        }
        if( isset($price3) and is_numeric($price3) and $price3>0 ){
            $tmponly=number_format($price3, 0, '', ' ');
            $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>'.$tmponly.'</strong> '.$sign.' / неделю</div>');
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*672;
        }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
            $nodiscount=$price2*28;
        }elseif( isset($price3) and is_numeric($price3) and $price3>0 ){
            $nodiscount=$price2*4;
        }
        if( isset($price4) and is_numeric($price4) and $price4>0 ){
            $pdfhtml.=iconv('UTF-8','cp1251', '<div><strong>'.number_format($price4, 0, '', ' ').'</strong> '.$sign.' / месяц (30 дн)</div>');
        }
       
    }                    
                    
                    
                        
                        $mpdf->list_indent_first_level = 0;
                        $mpdf->WriteHTML($pdfhtml, 2);
                        $mpdf->Output('needto_'.$node->nid.'.pdf', 'D');

                    }
                    break;
                case 7: //сохранить как Word
                    $node=node_load($_REQUEST['nid']);
                    if( isset( $node->type ) and strlen( $node->type ) and $node->type=='item' ){

                require_once getcwd() . '/sites/all/libraries/word/PHPWord.php';
                
                $shortttl=$node->title;
                if( mb_strlen($shortttl)>16 ){
                    $shortttl=mb_substr($shortttl, 0, 13).'...';
                }
                $word = new PHPWord();
                $word->setDefaultFontName('Times New Roman');
                $word->setDefaultFontSize(14);
                $meta = $word->getProperties();
                $meta->setCreator($_SERVER['HTTP_HOST']); 
                $meta->setCompany($_SERVER['HTTP_HOST']);
                $meta->setTitle($shortttl);
                $meta->setDescription($node->title); 
                $meta->setCategory('Объявление');
                $meta->setLastModifiedBy('http://'.$_SERVER['HTTP_HOST']);
                $meta->setCreated( time() );
                $meta->setModified( time() );
                $meta->setSubject('Объявление'); 
                $meta->setKeywords('объявление');
                $sectionStyle = array('orientation' => 'landscape',
                               'marginLeft' => parad0x_changes_m2t(15),
                               'marginRight' => parad0x_changes_m2t(15),
                               'marginTop' => parad0x_changes_m2t(15),
                               'borderTopColor' => 'C0C0C0'
                         );
                $section = $word->createSection($sectionStyle);
                $fontStyle = array('color'=>'000000', 'size'=>33, 'bold'=>true);
                $fontStyle2 = array('color'=>'000000', 'size'=>15);
                $fontStyle3 = array('color'=>'000000', 'size'=>19, 'bold'=>true);
                $fontCellStyle = array('align'=>'center');
    
                $paragraphStyle = array('align'=>'center');
                $paragraphStyle2 = array('align'=>'left');
                $word->addParagraphStyle('myParagraphStyle', $paragraphStyle);
                $word->addParagraphStyle('myParagraphStyle2', $paragraphStyle2);
    
                $section->addText($node->title, $fontStyle, 'myParagraphStyle');
                $section->addTextBreak(1);
                        
                        
                        
                $usr=user_load($node->uid);

                $section->addLink(url('node/'.$node->nid, array('absolute'=>true)), 'Ссылка на объявление: '.url('node/'.$node->nid, array('absolute'=>true)), $fontStyle2, 'myParagraphStyle2');
                $section->addText('Артикул: N1'.$node->nid, $fontStyle2, 'myParagraphStyle2');
                $section->addTextBreak(3);
                        
                $section->addText('     Об авторе', $fontStyle3, 'myParagraphStyle2');
                $section->addTextBreak(1);

                        
                    $companyname=$usr->field_name['und'][0]['value'];
                    if( isset($usr->field_who['und'][0]['value']) and $usr->field_who['und'][0]['value']==2 ){
                        if( isset($usr->field_company_name['und'][0]['value']) and strlen($usr->field_company_name['und'][0]['value']) ){
                            $companyname=$usr->field_company_name['und'][0]['value'];
                        }
                    }
                    if( isset($companyname) and strlen($companyname) ){
                        $username=$companyname;
                    }else{
                        $username='';
                    }

/*                    
                        $img='';
                        if( isset($usr->field_company_logo['und'][0]['fid']) and strlen($usr->field_company_logo['und'][0]['fid']) ){
                            $uri = file_load($usr->field_company_logo['und'][0]['fid'])->uri;
                            $uri = image_style_url('userbg', $uri);
                            if(isset($uri) and strlen($uri)){
                                $img=$uri;
                            }
                        }else{
                            if(isset($usr->picture->uri) and strlen($usr->picture->uri)){
                                $uri = image_style_url('userbg', $usr->picture->uri);
                                if(isset($uri) and strlen($uri)){
                                    $img=$uri;
                                }
                            }
                        }
                $img=str_replace('http://'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                $img=str_replace('https://'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                $img=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                $img=str_replace('https://www.'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                if( mb_strpos($img, '?')===false ){}else{
                    $img=mb_substr($img, 0, mb_strpos($img, '?') );
                }
                if( isset($img) and strlen($img) and file_exists($img) ){
                    $section->addImage($img, array('width'=>125, 'height'=>125, 'align'=>'left'));
                    $section->addTextBreak(1);
                }                        
*/
                        
                        $section->addLink(url('user/'.$node->uid, array('absolute'=>true)), 'Профиль автора: '.url('user/'.$node->uid, array('absolute'=>true)), $fontStyle2, 'myParagraphStyle2');
                        if( isset($username) and strlen($username) ){
                            $section->addText('Автор: '.$username, $fontStyle2, 'myParagraphStyle2');
                        }
                        
                        
                        if( isset( $usr->field_sex['und'][0]['value'] ) and is_numeric( $usr->field_sex['und'][0]['value'] ) ){
                            switch( $usr->field_sex['und'][0]['value'] ){
                                case 0:
                                    $section->addText('Пол: мужской', $fontStyle2, 'myParagraphStyle2');
                                    break;
                                case 1:
                                    $section->addText('Пол: женский', $fontStyle2, 'myParagraphStyle2');
                                    break;
                            }
                        }

                        if( isset( $usr->field_who['und'][0]['value'] ) and is_numeric( $usr->field_who['und'][0]['value'] ) ){
                            switch( $usr->field_who['und'][0]['value'] ){
                                case 1:
                                    $section->addText('Я представляю: частное лицо', $fontStyle2, 'myParagraphStyle2');
                                    break;
                                case 2:
                                    $section->addText('Я представляю: компанию или магазин', $fontStyle2, 'myParagraphStyle2');
                                    break;
                            }
                        }
                    
                        
                        
                        
                $section->addPageBreak();

/*                        
                        $img='';
                        if( isset( $node->field_image['und'][0]['fid'] ) and is_numeric( $node->field_image['und'][0]['fid'] ) ){
                            $file = file_load($node->field_image['und'][0]['fid']);
                            if( isset( $file->uri ) and strlen( $file->uri ) ){
                                $img=image_style_url('product_full', $file->uri);
                            }
                        }
                        $img=str_replace('http://'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                        $img=str_replace('https://'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                        $img=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                        $img=str_replace('https://www.'.$_SERVER['HTTP_HOST'].'/', DRUPAL_ROOT.'/', $img);
                        if( mb_strpos($img, '?')===false ){}else{
                            $img=mb_substr($img, 0, mb_strpos($img, '?') );
                        }
                        if( isset($img) and strlen($img) and file_exists($img) ){
                            $section->addImage($img, array('width'=>350, 'height'=>350, 'align'=>'center'));
                            $section->addTextBreak(1);
                        }                        
*/
                        
                if( isset($node->body['und'][0]['value']) and strlen($node->body['und'][0]['value']) ){
                    $node->body['und'][0]['value']=str_replace('</div>', '|newln|</div>', $node->body['und'][0]['value']);
                    $node->body['und'][0]['value']=str_replace('</p>', '|newln|</p>', $node->body['und'][0]['value']);
                    $node->body['und'][0]['value']=html_entity_decode(strip_tags($node->body['und'][0]['value']));
                    
                    $node->body['und'][0]['value']=explode('|newln|', $node->body['und'][0]['value']);
                    if( isset($node->body['und'][0]['value']) and is_array($node->body['und'][0]['value']) and count($node->body['und'][0]['value']) ){
                        foreach( $node->body['und'][0]['value'] as $tmpid=>$tmpval ){
                            if( strlen($tmpval) ){
                                $section->addText(trim($tmpval), $fontStyle2, 'myParagraphStyle2');
                            }
                        }
                    }
                }                        
                $section->addTextBreak(1);

                        
        if(isset($node->field_state['und'][0]['value']) and is_numeric($node->field_state['und'][0]['value'])){
            $name='';
            switch($node->field_state['und'][0]['value']){
            case 1:
                $section->addText('Состояние: отличное', $fontStyle2, 'myParagraphStyle2');
                break;
            case 2:
                $section->addText('Состояние: хорошее', $fontStyle2, 'myParagraphStyle2');
                break;
            case 3:
                $section->addText('Состояние: есть потертости', $fontStyle2, 'myParagraphStyle2');
                break;
            case 4:
                $section->addText('Состояние: плохое, но функцию выполняет', $fontStyle2, 'myParagraphStyle2');
                break;
            }
        }
        if(isset($node->field_brand['und'][0]['tid']) and is_numeric($node->field_brand['und'][0]['tid']) and isset($node->taxonomy_catalog['und'][0]['tid']) and is_numeric($node->taxonomy_catalog['und'][0]['tid'])){
            $name=db_query('select name from {taxonomy_term_data} where tid='.$node->field_brand['und'][0]['tid']);
            $name=$name->fetchAssoc();
            if( isset($name['name']) and strlen($name['name']) ){
                $section->addText('Бренд: '.$name['name'], $fontStyle2, 'myParagraphStyle2');
            }
        }
                    
        if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) ){
            $suffix= 'часов';
            $tmpis=$node->field_price_min['und'][0]['value'];
            if( $tmpis>23 ){
                $tmpis=round($tmpis/24, 0);
                $suffix= 'дней';
                if( $tmpis==1 ){
                    $suffix= 'дня';
                }
            }
            if( $tmpis>29 ){
                $tmpis=round($tmpis/30, 0);
                $suffix= 'месяца';
            }
            $section->addText('Срок аренды: от '.$tmpis.' '.$suffix, $fontStyle2, 'myParagraphStyle2');
        }

                    
    $sign='';
    if( defined('PDX_CITY_CUR') ){
        $sign=PDX_CITY_CUR;
    }
    
    if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
        switch($node->field_cur['und'][0]['value']){
        case 2:
            echo ' nodeinval';
            $sign='$';
            break;
        default:
            echo ' nodeinval2';
            $sign='€';
            break;
        }
    }

                $section->addTextBreak(1);
                    
     
                    
    $price1=$price2=$price4=$price3=$yesprice=$pricelen=0;
    
    if( isset($node->field_price_month['und'][0]['value']) and is_numeric($node->field_price_month['und'][0]['value']) ){
        $price4=$node->field_price_month['und'][0]['value'];
        $price3=round($node->field_price_month['und'][0]['value']/4, 2);
        $price2=round($node->field_price_month['und'][0]['value']/30, 2);
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_week['und'][0]['value']) and is_numeric($node->field_price_week['und'][0]['value']) ){
        $price3=$node->field_price_week['und'][0]['value'];
        $price2=round($node->field_price_week['und'][0]['value']/7, 2);
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_day['und'][0]['value']) and is_numeric($node->field_price_day['und'][0]['value']) ){
        $price2=$node->field_price_day['und'][0]['value'];
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_hour['und'][0]['value']) and is_numeric($node->field_price_hour['und'][0]['value']) ){
        $price1=$node->field_price_hour['und'][0]['value'];
        if( !isset($price2) or !is_numeric($price2) or $price2<1 ){
            $price2=$price1*24;
        }
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $yesprice=1;
    }
    if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) ){
        if( $node->field_price_min['und'][0]['value']>23 ){
            $price1=0;
            if( $node->field_price_min['und'][0]['value']>167 ){
                $price2=0;
            }
        }
    }

                    
                    
    if( $yesprice ){
        $nodiscount=$isdisc=0;
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $tmponly=number_format($price1, 0, '', ' ');
            $section->addText($tmponly.' '.$sign.' / час', $fontStyle2, 'myParagraphStyle2');
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*24;
        }
        if( isset($price2) and is_numeric($price2) and $price2>0 ){
            $tmponly=number_format($price2, 0, '', ' ');
            $section->addText($tmponly.' '.$sign.' / день (24 ч)', $fontStyle2, 'myParagraphStyle2');
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*168;
        }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
            $nodiscount=$price2*7;
        }
        if( isset($price3) and is_numeric($price3) and $price3>0 ){
            $tmponly=number_format($price3, 0, '', ' ');
            $section->addText($tmponly.' '.$sign.' / неделю', $fontStyle2, 'myParagraphStyle2');
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*672;
        }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
            $nodiscount=$price2*28;
        }elseif( isset($price3) and is_numeric($price3) and $price3>0 ){
            $nodiscount=$price2*4;
        }
        if( isset($price4) and is_numeric($price4) and $price4>0 ){
            $section->addText(number_format($price4, 0, '', ' ').' '.$sign.' / месяц (30 дн)', $fontStyle2, 'myParagraphStyle2');
        }
       
    }                    
                    
                          
                        
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Disposition: attachment;filename="needto_'.$node->nid.'.docx"');
                header('Cache-Control: max-age=0');
                $writer = PHPWord_IOFactory::createWriter($word, 'Word2007');
                $writer->save('php://output');

                    
                    }
                    break;                
            
        }
    }
    
    //без необходимости авторизации
        switch($_REQUEST['op']){
            case 13: //выбор города
    $out='';
    $cur=0;
    $out.= '<div id="iscityblock">&nbsp;';
    $topath='';
    if($cur){
        if( arg(0)==PDXCAT_NAME ){
            $topath=implode('/', arg());
        }else{
            switch(arg(0)){
            case 'news':
            case 'a':
            case 'i':
            case 'apply':
            case 'rules':
                $topath=arg(0);
                break;
            case 'users':
                if( is_string(arg(1)) and arg(1)=='rents' ){
                    $topath=implode('/', arg());
                }
                break;
            case 'findmy':
            case 'admin':
                $topath=implode('/', arg());
                break;
            case 'node':
                if( isset($_SESSION['pdxpdx_node_tid']) and is_numeric($_SESSION['pdxpdx_node_tid']) ){
                    $topath=PDXCAT_NAME.'/'.$_SESSION['pdxpdx_node_tid'];
                }
                break;
            }
        }
    }
    $out.= '<div id="city_current" onclick=" stpr(event || window.event); "><div id="city_current_is" onclick="showcountrys();" title="Сменить город">';
    if( $cur ){
        $out.= PDX_CITY_NAME;
    }else{
        $out.= 'Ваш город';
    }
    $out.= '</div>';
    $out.= '<div style="display: none;" id="city_current_list"><a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/vybor-goroda-s-kotorym-vy-budete-rabotat.html" title="Открыть справку в новом окне">&nbsp;</a><noindex>';
    $out.= '<div class="countrydiv">';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://needto.me/index.html"';
    if( !$cur ){
        $out.= ' class="active"';
    }
    $out.= '>все города</a></div>';
    $out.= '</div>';
    $out.= '<div class="countrydiv">';
    $out.= '<div class="countrylbl">Россия</div>';
                
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://sp.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==26 ){
        $out.= ' class="active"';
    }
    $out.= '>Санкт-Петербург</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://ekb.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==1 ){
        $out.= ' class="active"';
    }
    $out.= '>Екатеринбург</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://msc.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==2 ){
        $out.= ' class="active"';
    }
    $out.= '>Москва</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://nsb.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==3 ){
        $out.= ' class="active"';
    }
    $out.= '>Новосибирск</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://smr.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==4 ){
        $out.= ' class="active"';
    }
    $out.= '>Самара</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://tmn.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==5 ){
        $out.= ' class="active"';
    }
    $out.= '>Тюмень</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://chlb.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==6 ){
        $out.= ' class="active"';
    }
    $out.= '>Челябинск</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://nn.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==7 ){
        $out.= ' class="active"';
    }
    $out.= '>Нижний Новгород</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://kzn.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==8 ){
        $out.= ' class="active"';
    }
    $out.= '>Казань</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://omsk.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==9 ){
        $out.= ' class="active"';
    }
    $out.= '>Омск</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://ufa.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==10 ){
        $out.= ' class="active"';
    }
    $out.= '>Уфа</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://krs.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==11 ){
        $out.= ' class="active"';
    }
    $out.= '>Курск</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://perm.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==12 ){
        $out.= ' class="active"';
    }
    $out.= '>Пермь</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://vlg.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==13 ){
        $out.= ' class="active"';
    }
    $out.= '>Волгоград</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://vnzh.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==14 ){
        $out.= ' class="active"';
    }
    $out.= '>Воронеж</a></div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://rnd.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==15 ){
        $out.= ' class="active"';
    }
    $out.= '>Ростов-на-Дону</a></div>';                
                
    $out.= '</div>';
    $out.= '<div class="countrydiv">';
    $out.= '<div class="countrylbl">Беларусь</div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://mn.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==28 ){
        $out.= ' class="active"';
    }
    $out.= '>Минск</a></div>';
    $out.= '</div>';
    $out.= '<div class="countrydiv">';
    $out.= '<div class="countrylbl">Украина</div>';
    $out.= '<div class="citylbl"><a rel="nofollow" href="http://od.needto.me/'.$topath.'"';
    if( $cur and PDX_CITY_ID==27 ){
        $out.= ' class="active"';
    }
    $out.= '>Одесса</a></div>';
    $out.= '</div>';
    $out.= '</noindex></div>';
    $out.= '</div>';
    $out.= '</div>';
    echo $out;            
                break;
            case 14: //получить страницы справки
                $outs=array();
                
                $anids=pdxgethelpmenu();
                $outs['anids']=$anids;
                $outs['apages']=array();
                $outs['apagestop']=array();
                $outs['aaddon']=array();
                
                if( isset($outs['anids']) and is_array($outs['anids']) and count($outs['anids']) ){
                    foreach($outs['anids'] as $isnid=>$tmpval ){

                        $link=db_query('select alias from {url_alias} where source=\'node/'.$isnid.'\'');
                        $link=$link->fetchAssoc();
                        if( isset($link['alias']) and strlen( $link['alias'] ) ){
                                $link=$link['alias'].'.html';
                                $link='%%'.$link;
                                $link=str_replace('%%help/', '', $link);
                                $link=str_replace('%%', '', $link);
                                $link='/'.$link;
                        }else{
                            $link='node/'.$isnid.'.html';
                        }
                        $title=db_query('select title from {node} where nid='.$isnid);
                        $title=$title->fetchAssoc();
                        if( isset($title['title']) and strlen( $title['title'] ) ){
                            $title=$title['title'];
                        }else{
                            $title='';
                        }
                        $outs['apages'][$isnid]['parent']=0;
                        $outs['apages'][$isnid]['link']=$link;
                        $outs['apages'][$isnid]['mlid']=$isnid;
                        $outs['apages'][$isnid]['title']=$title;
                        $outs['apagestop'][]='<a class="lnk'.$isnid.'" href="'.$link.'">'.$title.'</a>';

                        $body=db_query('select b.body_value, y.field_youtube_video_id from {field_data_body} as b left join {field_data_field_youtube} as y on (b.entity_id=y.entity_id and y.entity_type=\'node\') where b.entity_type=\'node\' and b.entity_id='.$isnid);
                        $body=$body->fetchAssoc();
                        if( isset($body['field_youtube_video_id']) and strlen($body['field_youtube_video_id']) ){
                            $outs['apages'][$isnid]['video']=$body['field_youtube_video_id'];
                        }
                        if( isset($body['body_value']) and strlen($body['body_value']) ){
                            $outs['apages'][$isnid]['body']=$body['body_value'];
                        }
                        
                        
                        if( isset($tmpval) and is_array($tmpval) and count($tmpval) ){
                            foreach($tmpval as $isnid2 ){
                                $link=db_query('select alias from {url_alias} where source=\'node/'.$isnid2.'\'');
                                $link=$link->fetchAssoc();
                                if( isset($link['alias']) and strlen( $link['alias'] ) ){
                                        $link=$link['alias'].'.html';
                                        $link='%%'.$link;
                                        $link=str_replace('%%help/', '', $link);
                                        $link=str_replace('%%', '', $link);
                                        $link='/'.$link;
                                }else{
                                    $link='node/'.$isnid2.'.html';
                                }
                                $title=db_query('select title from {node} where nid='.$isnid2);
                                $title=$title->fetchAssoc();
                                if( isset($title['title']) and strlen( $title['title'] ) ){
                                    $title=$title['title'];
                                }else{
                                    $title='';
                                }
                                $outs['apages'][$isnid2]['parent']=$isnid;
                                $outs['apages'][$isnid2]['link']=$link;
                                $outs['apages'][$isnid2]['mlid']=$isnid2;
                                $outs['apages'][$isnid2]['title']=$title;
                                $outs['aaddon'][]='<li><a href="'.$link.'">'.$title.'</a>';

                                $body=db_query('select b.body_value, y.field_youtube_video_id from {field_data_body} as b left join {field_data_field_youtube} as y on (b.entity_id=y.entity_id and y.entity_type=\'node\') where b.entity_type=\'node\' and b.entity_id='.$isnid2);
                                $body=$body->fetchAssoc();
                                if( isset($body['field_youtube_video_id']) and strlen($body['field_youtube_video_id']) ){
                                    $outs['apages'][$isnid2]['video']=$body['field_youtube_video_id'];
                                }
                                if( isset($body['body_value']) and strlen($body['body_value']) ){
                                    $outs['apages'][$isnid2]['body']=$body['body_value'];
                                }
                            }
                        }


                    }
                }                
                
                if( isset($outs) and is_array($outs) and count($outs) ){
                    echo serialize($outs);
                }
                
                break;
            case 12: //получить заголовки новостей
                $outs=array();
                $nums=db_query('select title from {node} where status=1 and type=\'news\' order by created desc limit 0,3');
                while( $num=$nums->fetchAssoc() ){
                    $outs[]=$num;
                }
                if( isset($outs) and is_array($outs) and count($outs) ){
                    echo serialize($outs);
                }
                
                break;
            case 11: //получить новости
                $outs=array();
                $nums=db_query('select n.title, b.body_value, i.field_image_fid from {node} as n inner join {field_data_body} as b on n.nid=b.entity_id left join {field_data_field_image} as i on (n.nid=i.entity_id and i.entity_type=\'node\') where n.status=1 and n.type=\'news\' order by n.created desc limit 0,77');
                while( $num=$nums->fetchAssoc() ){
                    if( isset( $num['field_image_fid'] ) and is_numeric( $num['field_image_fid'] ) ){
                        $file = file_load($num['field_image_fid']);
                        if( isset( $file->uri ) and strlen( $file->uri ) ){
                            $img=image_style_url('news_preview', $file->uri);
                        }
                        if( isset($img) and strlen($img) ){
                            $num['img']=$img;
                        }

                    }
                    $outs[]=$num;
                }
                if( isset($outs) and is_array($outs) and count($outs) ){
                    echo serialize($outs);
                }
                
                break;
            case 10: //показать на карте
                if( isset( $_REQUEST['data2'] ) and strlen($_REQUEST['data2']) ){
                    $nids=explode('|',$_REQUEST['data2']);
                    if( isset($nids) and is_array($nids) and count($nids) ){
                        foreach($nids as $id=>$nid ){
                            if( !is_numeric($nid) ){
                                unset($nids[$id]);
                            }
                        }
                    }
                    if( isset($nids) and is_array($nids) and count($nids) ){
                        $points=array();
                        $delta=0;
                        
                        foreach($nids as $nid ){
                            $usr=user_load($nid);
                            
                            $companyname='???';
                            if( isset($usr->field_name['und'][0]['value']) and strlen($usr->field_name['und'][0]['value']) ){
                                $companyname=$usr->field_name['und'][0]['value'];
                                if( isset($usr->field_who['und'][0]['value']) and $usr->field_who['und'][0]['value']==2 ){
                                    if( isset($usr->field_company_name) and isset($usr->field_company_name['und']) and isset($usr->field_company_name['und'][0]['value']) and strlen($usr->field_company_name['und'][0]['value']) and $usr->field_company_name['und'][0]['value']!=$companyname ){
                                        $companyname=$usr->field_company_name['und'][0]['value'].' ('.$companyname.')';
                                    }
                                }
                            }
                            $companyname=str_replace('"', '', $companyname);
                            
                            $txtout='';

                            if( isset($usr->picture->uri) and strlen($usr->picture->uri) ){
                                $uri = image_style_url('user', $usr->picture->uri);
                            }else{
                                if( isset($usr->field_company_logo['und'][0]['fid']) and strlen($usr->field_company_logo['und'][0]['fid']) ){
                                    $uri = file_load($usr->field_company_logo['und'][0]['fid'])->uri;
                                    $uri = image_style_url('logosm', $uri);
                                }else{
                                    $uri = PDX_IMGPATH.'/img/happy2.png';
                                }
                            }
                            if( isset($uri) and strlen($uri) ){
                                $txtout.='<img style=\'float: left; margin: 0 11px 11px 0; \' alt=\'\' src=\''.$uri.'\' />';
                            }

                            $txtout.='<div><a href=\''.url('user/'.$usr->uid, array('absolute'=>TRUE)).'\'>Компания <strong>'.$companyname.'</strong></a></div><div class="m_addr">{{STREET}}</div><div class=\'clear\'>&nbsp;</div>';

                            $isset=db_query('select uid from {pm_disable} where uid='.$usr->uid);
                            $isset=$isset->fetchAssoc();
                            if( !isset($isset['uid']) ){
                                $txtout.='<div class=\'sendpm\' style=\'display: none;\'><a href=\''.$GLOBALS['base_url'].'/messages/new?to='.$usr->uid.'\' onclick=\' sendpmgen(this); \'>Отправить сообщение</a></div>';
                            }


                            if( isset($usr->field_address['und'][0]['value']) and strlen($usr->field_address['und'][0]['value']) and isset($usr->field_longitude['und'][0]['value']) and is_numeric($usr->field_longitude['und'][0]['value']) and isset($usr->field_latitude['und'][0]['value']) and is_numeric($usr->field_latitude['und'][0]['value']) ){
                                foreach( $usr->field_longitude['und'] as $dlt => $longitude ){ 
                                    $points[$delta]['title']=str_replace('"', '', str_replace('{{STREET}}', $usr->field_address['und'][$dlt]['value'], $txtout));
                                    $points[$delta]['longitude']=$longitude['value'];
                                    $points[$delta]['latitude']=$usr->field_latitude['und'][$dlt]['value'];
                                    $points[$delta]['address']=$usr->field_address['und'][$dlt]['value'];
                                    $points[$delta]['companyname']=$companyname;
                                    $delta++;
                                }
                                
                            }
                            
                        }
                        
                        

    if(is_array($points) and count($points)){
        echo '<div id=\'map_canvas_user\' style=\'width: 100%; height: 433px;\'></div><script type="text/javascript">
       jQuery(document).ready(function($){ window.ymaps && ymaps.ready(init); });
       var myMap;
       function init () {
           myMap = new ymaps.Map("map_canvas_user", {
                center: ['.$points[0]['longitude'].', '.$points[0]['latitude'].'],
                behaviors: [\'default\', \'scrollZoom\'],
                zoom: ';
            if( count($points)>1 ){
                echo '12';
            }else{
                echo '14';
            }
            echo '}, {
                searchControlProvider: \'yandex#search\'
            });
            ';
    
        foreach($points as $num=> $point){
            echo 'myPlacemark'.$num.' = new ymaps.Placemark(['.$point['longitude'].','.$point['latitude'].'], {
            iconContent: "'.$point['companyname'].'", balloonContent: "'.$point['title'].'"
    	}, {
        preset: "islands#blueStretchyIcon",
        openEmptyBalloon: true
    });
    
    myMap.geoObjects.add(myPlacemark'.$num.');';
//            if( $num==0 ){
//                echo 'myPlacemark'.$num.'.balloon.open();';
//            }
        }
        echo 'myMap.behaviors.disable(\'scrollZoom\');';
                            echo '
       }
    </script>';
            

    }                        
                        
                        
                    }
                }
                break;
        }
    
    
}


?>