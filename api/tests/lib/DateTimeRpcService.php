<?php
$mycookie='';



        function keylegal( $key ) {
            if( file_exists(getcwd().'/pdxcache/api/keys/'.urlencode($key)) ){
                $data=file_get_contents(getcwd().'/pdxcache/api/keys/'.urlencode($key));
                if( !isset($data) or !is_numeric($data) ){
                    $data=0;
                }
                $data++;
                $fp = fopen(getcwd().'/pdxcache/api/keys/'.urlencode($key), 'w'); fwrite($fp, $data); fclose($fp);
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

                $row=db_query('select d11.field_url_vk_value, d12.field_url_fb_value, d9.field_youtube_input, d10.field_url_value, n.nid, n.uid, d1.body_value, d2.field_name_value, d3.field_company_name_value, d7.field_url_site_value, d8.field_skype_value from {node} as n inner join {field_data_field_name} as d2 on (n.uid=d2.entity_id and d2.entity_type=\'user\') left join {field_data_field_company_name} as d3 on (n.uid=d3.entity_id and d3.entity_type=\'user\') left join {field_data_field_url_site} as d7 on (n.uid=d7.entity_id and d7.entity_type=\'user\' and d7.delta=0) left join {field_data_field_url_vk} as d11 on (n.uid=d11.entity_id and d11.entity_type=\'user\' and d11.delta=0) left join {field_data_field_url_fb} as d12 on (n.uid=d12.entity_id and d12.entity_type=\'user\' and d12.delta=0) left join {field_data_field_skype} as d8 on (n.uid=d8.entity_id and d8.entity_type=\'user\' and d8.delta=0) left join {field_data_body} as d1 on (n.nid=d1.entity_id and d1.entity_type=\'node\') left join {field_data_field_youtube} as d9 on (n.nid=d9.entity_id and d9.entity_type=\'node\') left join {field_data_field_url} as d10 on (n.nid=d10.entity_id and d10.entity_type=\'node\') left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type = \'item\' and n.nid='.$nid.' and n.status=1 and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) limit 0,1');
                $row=$row->fetchAssoc();
                if( isset($row['nid']) and is_numeric($row['nid']) ){
                    if( isset($row['body_value']) and strlen($row['body_value']) ){
                        $ares['out']['desc']=$row['body_value'];
                    }
//                    $ares['out']['itemlink']='item/'.$row['nid'];
//                    $ares['out']['userlink']='user/'.$row['uid'];
                    $ares['out']['username']=$row['field_name_value'];
                    if( isset($row['field_company_name_value']) and strlen($row['field_company_name_value']) ){
                        $ares['out']['username']=$row['field_company_name_value'].' ('.$ares['out']['username'].')';
                    }
                    if( isset($row['field_youtube_input']) and strlen($row['field_youtube_input']) ){
                        $ares['out']['moreyoutube']=$row['field_youtube_input'];
                    }
                    if( isset($row['field_url_value']) and strlen($row['field_url_value']) ){
                        $ares['out']['moreurl']=$row['field_url_value'];
                    }
                    
                    $ares['out']['addr']=array();
                    $isaddrs=db_query('select f1.delta, f1.field_address_value, f2.field_longitude_value, f3.field_latitude_value from {field_data_field_address} as f1 inner join {field_data_field_longitude} as f2 on (f1.entity_id=f2.entity_id and f1.entity_type=f2.entity_type and f1.delta=f2.delta) inner join {field_data_field_latitude} as f3 on (f1.entity_id=f3.entity_id and f1.entity_type=f3.entity_type and f1.delta=f3.delta) where f1.entity_type=\'user\' and f1.entity_id='.$row['uid']);
                    while( $isaddr=$isaddrs->fetchAssoc() ){
                        if( $isaddr['delta']==0 ){
                            $ares['out']['address']=$isaddr['field_address_value'];
                            $ares['out']['longitude']=$isaddr['field_longitude_value'];
                            $ares['out']['latitude']=$isaddr['field_latitude_value'];
                        }
                        $ares['out']['addr'][]=array('address'=>$isaddr['field_address_value'], 'longitude'=>$isaddr['field_longitude_value'], 'latitude'=>$isaddr['field_latitude_value']);
                    }
                    
                    if( isset($row['field_url_site_value']) and strlen($row['field_url_site_value']) ){
                        $ares['out']['site']=$row['field_url_site_value'];
                    }
                    if( isset($row['field_url_vk_value']) and strlen($row['field_url_vk_value']) ){
                        $ares['out']['vk']=$row['field_url_vk_value'];
                    }
                    if( isset($row['field_url_fb_value']) and strlen($row['field_url_fb_value']) ){
                        $ares['out']['fb']=$row['field_url_fb_value'];
                    }
                    if( isset($row['field_skype_value']) and strlen($row['field_skype_value']) ){
                        $ares['out']['skype']=$row['field_skype_value'];
                    }
                    $ares['out']['phone']=array();
                    $nums=db_query('select field_phones_value from {field_data_field_phones} where entity_type=\'user\' and entity_id='.$row['uid']);
                    while( $num=$nums->fetchAssoc() ){
                        $ares['out']['phone'][]=$num['field_phones_value'];
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