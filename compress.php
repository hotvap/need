<?php

if( !isset($_SERVER['HTTP_HOST']) or !strlen($_SERVER['HTTP_HOST']) ){
    exit();
}
$mysite='needto';


        $adir = "sites/all/themes/pdxneedto/min";
        $arrcss1=array('./sites/all/themes/pdxneedto/css/other.css', './sites/all/libraries/img/ignis.css');
        $arrcss2=array('./sites/all/themes/pdxneedto/tb/css/adm.css');
        $arrjs1=array('./sites/all/themes/pdxneedto/tb/js/jquery.zoom.min.js', './modules/locale/locale.datepicker.js', './sites/all/themes/pdxneedto/js/jquery.maskedinput.min.js', './sites/all/themes/pdxneedto/tb/js/accounting.min.js', './sites/all/themes/pdxneedto/tb/js/jquery.lazyload.min.js', './sites/all/themes/pdxneedto/js/autoNumeric-min.js',
        './js/'.$_SERVER['HTTP_HOST'].'/ball.js', './sites/all/libraries/img/ignis.js', './sites/all/themes/pdxneedto/js/addons.js');
        $arrjs2=array('./sites/all/themes/pdxneedto/tb/js/adm.js');

$unix_timestamp = time();		//get unix time stamp added to all file names
//require_once('jsmin.php');		//load javascript minifier	
//require_once('cssmin.php');		//load css minifier	

//		Create target directory and write Apache htaccess file when no directory found 
if (!file_exists($adir))
	{
	mkdir($adir) && chmod($adir, 0777);
	$htaccess='Options All -Indexes'.PHP_EOL;
	$htaccess.='AddType text/css cssgz'.PHP_EOL;
	$htaccess.='AddType text/javascript jsgz'.PHP_EOL;
	$htaccess.='AddEncoding x-gzip .cssgz .jsgz'.PHP_EOL;
	$htaccess.='# for all files in min directory'.PHP_EOL;
	$htaccess.='FileETag None'.PHP_EOL;
	$htaccess.='# Cache for a week, attempt to always use local copy'.PHP_EOL; 
	$htaccess.='<IfModule mod_expires.c>'.PHP_EOL;
	$htaccess.='  ExpiresActive On'.PHP_EOL;
	$htaccess.='  ExpiresDefault A604800'.PHP_EOL;
	$htaccess.='</IfModule>'.PHP_EOL;
	$htaccess.='<IfModule mod_headers.c>'.PHP_EOL;
	$htaccess.='  Header unset ETag'.PHP_EOL;
	$htaccess.='  Header set Cache-Control "max-age=604800, public"'.PHP_EOL;
	$htaccess.='</IfModule>'.PHP_EOL;
	file_put_contents($adir.'/.htaccess',$htaccess);			//write initial htaccess file
	}

//		prepare combined, minfied and combined, minified and gzipped css and js files
file_compress($mysite.'.css',$arrcss1);
file_compress('adm.css',$arrcss2);
file_compress('other.js', $arrjs1);
file_compress('adm.js',$arrjs2);
//, './sites/all/themes/pdxneedto/tb/js/swiper.min.js'

//		write new timestamp file compress_timestamp.php for php execution code
$infofile='<?php'.PHP_EOL;
$infofile.='$compress_stamp='.$unix_timestamp.';'.PHP_EOL;
$infofile.='?>'.PHP_EOL;
file_put_contents ('pdxcache/compress_'.$mysite.'_timestamp.php',$infofile,LOCK_EX);		//file loaded by ThreeColTemplate1.php to get unique stamp id

//		5 second delay should take care of 99.9% of inflight requests
//			except on super overloaded servers or slow connections
sleep(5);	

//		delete older files in library
$unix_timestamp.='';		//make a string
if (file_exists($adir) && $dh = opendir($adir)) 
	{
	while($fn = readdir($dh)) if ($fn[0] != "."){
		if( filectime($adir.'/'.$fn) < time()-1296777 ){
//		if (strpos($fn,$unix_timestamp)===false){
			unlink($adir.'/'.$fn);
			echo $fn,' deleted<br>';
		}
	}
	closedir($dh);
	}
else
	echo 'failed to open ',$adir,' ',date("r"),PHP_EOL;

function file_compress($file_name,$file_input) {
	global $unix_timestamp,$adir,$mysite;
	$pos=strrpos($file_name,'.');				//get last . in file name
	if ($pos==false)
		die ('illogical response from strrpos');
//	$fn=substr($file_name,0,$pos).'_'.$unix_timestamp.substr($file_name,$pos);	//put timestamp into file name
	$fn=substr($file_name,0,$pos).substr($file_name,$pos);	//put timestamp into file name

	$fl=null;						//clear file data variable
	foreach($file_input as $value)				//merge files in the group
		$fl.= file_get_contents($value).' ';
	$len_orig=strlen($fl);		
	if (strtolower(substr($file_name,$pos+1,2)) == 'js'){	
		$fl = preg_replace('/\t| {2,}/', '', $fl);
//		$fl = JSMin::minify($fl);			//minify js	
	}else{
        if( isset($_SERVER['HTTP_HOST']) and strlen( $_SERVER['HTTP_HOST'] ) and strpos($_SERVER['HTTP_HOST'], 'site')===false and strpos($_SERVER['HTTP_HOST'], 'localhost')===false and strpos($_SERVER['HTTP_HOST'], '.loc')===false ){

            $fl = str_replace('url(../', 'url(http://d15mt731vu6ndj.cloudfront.net/', $fl);
            $fl = str_replace('url(\'../', 'url(\'http://d15mt731vu6ndj.cloudfront.net/', $fl);
            $fl = str_replace("url('/sites/all/libraries/img/", "url('http://d15mt731vu6ndj.cloudfront.net/static/img/", $fl);
        }
		$fl = preg_replace('/\n\r|\r\n|\n|\r|\t| {2,}/', '', $fl);
//		$fl = CssMin::minify($fl);			//minify css
    }

	$len_minify=strlen($fl);
    	$gzdata=gzencode ($fl,9);				//gzip 

//		write files no need to lock, filename is unique and not yet in use
 	file_put_contents ($adir.'/'.$fn,$fl);			 	//put out minified, non gzipped version
   	file_put_contents ($adir.'/'.$fn.'gz',gzencode ($fl,9));	//put out gzipped version
	echo $adir.'/'.$fn, ' created length: ', $len_minify, ' ', $len_orig,'<br />'; 
	echo $adir.'/'.$fn.'gz', ' created length: ', strlen($gzdata), ' ', $len_minify, ' ', $len_orig,'<br />'; 
}
?>