<?php
$mycookie='';



        function keylegal( $key ) {
            if( file_exists(getcwd().'/pdxcache/api/pickto/keys/'.urlencode($key)) ){
                $data=file_get_contents(getcwd().'/pdxcache/api/pickto/keys/'.urlencode($key));
                if( !isset($data) or !is_numeric($data) ){
                    $data=0;
                }
                $data++;
                $fp = fopen(getcwd().'/pdxcache/api/pickto/keys/'.urlencode($key), 'w'); fwrite($fp, $data); fclose($fp);
//                if($data>77){
//                    return 0;
//                }
                return 1;
            }
            return 0;
        }


    /**
     * Simple Date Time RPC Service
     */
    class DateTimeRpcService extends BaseJsonRpcServer {

        /**
         * Получить список проектов пользователя
         * @param string $key
         */
        public function GetInfo( $key, $nid ) {
            $ares=array('error'=>array('code'=>0, 'text'=>''), 'out'=>array() );
            if( !is_numeric($nid) or $nid<1 ){
                $ares['error']['code']=2;
                $ares['error']['text']='Входные данные неверны';
                return $ares;
            }
            $key=str_replace("'", '', str_replace('"', '', strip_tags($key)));
            $uid=keylegal($key);
            if( $uid ){

                $row=db_query('select n.nid, n.uid, d2.field_name_value, d13.field_nameloc_value, d1.field_fam_value, d3.field_youtube_input, d4.field_urli_value, d5.field_fam_value, d6.field_sex_value, d7.field_kurenie_value, d8.field_alko_value, d9.field_religia_value, d10.field_char_value, d11.body_value, d12.field_telo_value from {node} as n left join {field_data_field_name} as d2 on (n.uid=d2.entity_id and d2.entity_type=\'user\') left join {field_data_field_nameloc} as d13 on (n.nid=d13.entity_id and d13.entity_type=\'node\') left join {field_data_field_fam} as d1 on (n.nid=d1.entity_id and d1.entity_type=\'node\') left join {field_data_field_youtube} as d3 on (n.nid=d3.entity_id and d3.entity_type=\'node\') left join {field_data_field_urli} as d4 on (n.nid=d4.entity_id and d4.entity_type=\'node\') left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') left join {field_data_field_fam} as d5 on (n.nid=d5.entity_id and d5.entity_type=\'node\') left join {field_data_field_sex} as d6 on (n.nid=d6.entity_id and d6.entity_type=\'node\') left join {field_data_field_kurenie} as d7 on (n.nid=d7.entity_id and d7.entity_type=\'node\') left join {field_data_field_alko} as d8 on (n.nid=d8.entity_id and d8.entity_type=\'node\') left join {field_data_field_religia} as d9 on (n.nid=d9.entity_id and d9.entity_type=\'node\') left join {field_data_field_char} as d10 on (n.nid=d10.entity_id and d10.entity_type=\'node\') left join {field_data_body} as d11 on (n.nid=d11.entity_id and d11.entity_type=\'node\') left join {field_data_field_telo} as d12 on (n.nid=d12.entity_id and d12.entity_type=\'node\') where n.type = \'item\' and n.nid='.$nid.' and n.status=1 and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) limit 0,1');
                $row=$row->fetchAssoc();
                if( isset($row['nid']) and is_numeric($row['nid']) ){
//                    $ares['out']['itemlink']='item/'.$row['nid'];
//                    $ares['out']['userlink']='user/'.$row['uid'];
                    $ares['out']['username']='Аноним';
                    if( $row['uid']>0 ){
                        if( isset($row['field_name_value']) and strlen($row['field_name_value']) ){
                            $ares['out']['username']=$row['field_name_value'];
                        }else{
                            $ares['out']['username']='Неизвестно';
                        }
                    }else{
                        if( isset($row['field_nameloc_value']) and strlen($row['field_nameloc_value']) ){
                            $ares['out']['username']='Аноним ('.$row['field_nameloc_value'].')';
                        }
                    }
                    
                    if( isset($row['body_value']) and strlen($row['body_value']) ){
                        $ares['out']['body']=$row['body_value'];
                    }
                    if( isset($row['field_telo_value']) and strlen($row['field_telo_value']) ){
                        switch( $row['field_telo_value'] ){
                            case 1:
                                $ares['out']['telo']='худощавое';
                                break;
                            case 2:
                                $ares['out']['telo']='стройное';
                                break;
                            case 3:
                                $ares['out']['telo']='атлетическое';
                                break;
                            case 4:
                                $ares['out']['telo']='плотное';
                                break;
                            case 5:
                                $ares['out']['telo']='полное';
                                break;
                        }                        
                    }
                    if( isset($row['field_youtube_input']) and strlen($row['field_youtube_input']) ){
                        $ares['out']['moreyoutube']=$row['field_youtube_input'];
                    }
                    if( isset($row['field_urli_value']) and strlen($row['field_urli_value']) ){
                        $ares['out']['moreurl']=$row['field_urli_value'];
                    }
                    if( isset($row['field_fam_value']) and is_numeric($row['field_fam_value']) ){
                        switch( $row['field_fam_value'] ){
                            case 1:
                                if( isset($row['field_sex_value']) and $row['field_sex_value']==1 ){
                                    $ares['out']['fam']='не замужем';
                                }else{
                                    $ares['out']['fam']='не женат';
                                }
                                break;
                            case 2:
                                $ares['out']['fam']='встречаюсь';
                                break;
                            case 3:
                                if( isset($row['field_sex_value']) and $row['field_sex_value']==1 ){
                                    $ares['out']['fam']='помолвлена';
                                }else{
                                    $ares['out']['fam']='помолвлен';
                                }
                                break;
                            case 4:
                                if( isset($row['field_sex_value']) and $row['field_sex_value']==1 ){
                                    $ares['out']['fam']='замужем';
                                }else{
                                    $ares['out']['fam']='женат';
                                }
                                break;
                            case 5:
                                if( isset($row['field_sex_value']) and $row['field_sex_value']==1 ){
                                    $ares['out']['fam']='влюблена';
                                }else{
                                    $ares['out']['fam']='влюблен';
                                }
                                break;
                            case 6:
                                $ares['out']['fam']='все сложно';
                                break;
                            case 7:
                                $ares['out']['fam']='в активном поиске';
                                break;                                
                        }
                    }
                    if( isset($row['field_kurenie_value']) and is_numeric($row['field_kurenie_value']) ){
                        switch( $row['field_kurenie_value'] ){
                            case 1:
                                $ares['out']['kurenie']='резко негативное';
                                break;
                            case 2:
                                $ares['out']['kurenie']='негативное';
                                break;
                            case 3:
                                $ares['out']['kurenie']='компромиссное';
                                break;
                            case 4:
                                $ares['out']['kurenie']='нейтральное';
                                break;
                            case 5:
                                $ares['out']['kurenie']='положительное';
                                break;                            
                        }
                    }
                    if( isset($row['field_alko_value']) and is_numeric($row['field_alko_value']) ){
                        switch( $row['field_alko_value'] ){
                            case 1:
                                $ares['out']['alko']='резко негативное';
                                break;
                            case 2:
                                $ares['out']['alko']='негативное';
                                break;
                            case 3:
                                $ares['out']['alko']='компромиссное';
                                break;
                            case 4:
                                $ares['out']['alko']='нейтральное';
                                break;
                            case 5:
                                $ares['out']['alko']='положительное';
                                break;                            
                        }
                    }
                    if( isset($row['field_religia_value']) and is_numeric($row['field_religia_value']) ){
                        switch( $row['field_religia_value'] ){
                            case 1:
                                $ares['out']['religia']='иудаизм';
                                break;
                            case 2:
                                $ares['out']['religia']='православие';
                                break;
                            case 3:
                                $ares['out']['religia']='католицизм';
                                break;
                            case 4:
                                $ares['out']['religia']='протестантизм';
                                break;
                            case 5:
                                $ares['out']['religia']='ислам';
                                break;
                            case 6:
                                $ares['out']['religia']='буддизм';
                                break;
                            case 7:
                                $ares['out']['religia']='конфуцианство';
                                break;
                            case 8:
                                $ares['out']['religia']='светский гуманизм';
                                break;
                            case 9:
                                $ares['out']['religia']='пастафарианство';
                                break;                                
                        }
                    }
                    if( isset($row['field_char_value']) and is_numeric($row['field_char_value']) ){
                        switch( $row['field_char_value'] ){
                            case 0:
                                $ares['out']['char']='спокойный, уравновешенный';
                                break;
                            case 1:
                                $ares['out']['char']='веселый, с чувством юмора';
                                break;
                            case 2:
                                $ares['out']['char']='общительный';
                                break;
                            case 3:
                                $ares['out']['char']='пробивной, боевой';
                                break;
                            case 4:
                                $ares['out']['char']='непоседливый, ищущий';
                                break;
                            case 5:
                                $ares['out']['char']='неуравновешенный, истеричный';
                                break;                          
                        }
                    }                    
                    
                }else{
                    $ares['error']['code']=3;
                    $ares['error']['text']='Объявление не найдено.';
                }
            }else{
                $ares['error']['code']=1;
                $ares['error']['text']='Ключ API неверен. Обновите Ваше приложение для получения нового API-ключа.';
            }

            return $ares;
        }

        /**
         * Returns associative array containing dst, offset and the timezone name
         * @return array
         */
        public function GetTimeZones() {
            return DateTimeZone::listAbbreviations();
        }


        /**
         * Get Relative time
         * @param string $text    a date/time string
         * @param string $timezone
         * @param string $format
         * @return string
         */
        public function GetRelativeTime( $text, $timezone = 'UTC', $format = 'c' ) {
            $result = new DateTime( $text, new DateTimeZone( $timezone ) );
            return $result->format( $format );
        }


        /**
         * Implode Function
         * @param string $glue
         * @param array $pieces
         * @return string string
         */
        public function Implode( $glue, $pieces = array( "1", "2", "3" ) ) {
            return implode( $glue, $pieces );
        }

    }

?>