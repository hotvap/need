<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<TITLE>Отправить ошибку</TITLE>
<style type="text/css">
body {
margin: 23px 28px 0 28px;
font-size:14px;
font-family:Helvetica, Sans-serif, Arial;
line-height:2em;
}
form {margin: 0;}
.text {
font-weight: bold;
font-size:12px;
color:#292635;
display: block;
    line-height: 1em;
    padding: 21px 0 5px 0;
}
.copyright {
font-size:11px;
color:#292635;
}
.mclose a{
font:bold 16px Verdana;
color:#9f4b76;
position:absolute;
right:12px;
top:9px;
display:block;
text-decoration:none;
}
.mclose a:hover{
color: #000;
}
    .btnpan input{
        color: #fff;
        cursor: pointer;
        border: 0px none transparent;
    }
#m{
border: 1px solid silver;
padding: 3px;
width: 294px;
border-radius:4px;
font-size: 13px;
background-color: #fff;
}
#m strong{
color:red;
}
    .btnpan{
        margin-top: 17px;
    }
    .btnpan input{
        -moz-border-radius: 21px;
        -khtml-border-radius:21px;
        -webkit-border-radius:21px;
        border-radius:21px;
        position: relative; left: +0px;
        margin: 0 7px;
        height: 25px;
        padding-right: 11px;
        padding-left: 11px;
    }
</style>

<script language="JavaScript"> 
var p=top;
function readtxt()
{ if(p!=null)document.forms.mistake.url.value=p.loc
 if(p!=null)document.forms.mistake.mis.value=p.mis
}
function hide()
{ var win=p.document.getElementById('mistake');
win.parentNode.removeChild(win);
}
</script>

<?php 
if($_POST['submit']) { 
$title = 'Сообщение об ошибке на сайте';
$ip = getenv('REMOTE_ADDR');
$url = (trim($_POST['url']));
$mis =  (trim($_POST['mis']));
$comment =  substr(htmlspecialchars(trim($_POST['comment'])), 0, 100000);
                       
                $mess = '
                <html>
                <head>
                <title>Ошибка на сайте</title>
                </head>
                <body>
                <strong>Адрес страницы:</strong> <a href="'.$url.'">'.$url.'</a>
                <br/>
                <strong>Ошибка:</strong> '.$mis.'
                <br/>
                <strong>Комментарий:</strong> '.$comment.'
                <br/>                               
                <strong>IP:</strong> '.$ip.'
                '.$_POST['mess'].'
                </body>
                </html>
                ';
$to = 'needtome@yandex.ru';
$mymail='needtome@yandex.ru';
               
    $header="Content-type: text/html; charset=\"utf-8\"";
    $header.="From: \"Ignis Media\" <".$mymail.">";
    $header.='Subject: "Найдена ошибка на сайте"';
    $header.="Content-type: text/html; charset=\"utf-8\"";
    mail($to, $title, $mess, $header, '-f'.$mymail);

    
echo '<div class="mclose"><a href="javascript:void(0)" onclick="hide()" title="Закрыть">&times;</a></div><br><br><br><center>Спасибо!<br>Ваше сообщение отправлено.<br><br><br><input onclick="hide()" type="button" value="Закрыть" id="close" name="close"><center>'; 
exit();
}  
?>

</head>
<body onload=readtxt()>
<div class="mclose"><a href="javascript:void(0)" onclick="hide()" title="Закрыть">&times;</a></div>
<span class="text">
Адрес страницы:
 </span>
<form name="mistake" action="" method=post>
<input id="m" type="text" name="url" size="35" readonly="readonly">
              <span class="text">
Ошибка:
              </span>
              <div id="m" style="line-height:normal;height: 75px;width: 287px;">
<script language="JavaScript">
	document.write(p.mis); 
</script>
              </div>
              <input type="hidden" id="m" rows="4" name="mis" readonly="readonly"></textarea>
              <span class="text">
Комментарий:
              </span>
              
              <textarea id="m" rows="3" name="comment" cols="30"></textarea> 
              <div class="btnpan"><input type="submit" value="Отправить" name="submit" style="background: #504c5e; " >
              <input onclick="hide()" type="button" value="Отмена" id="close" name="close" style="background: #9f4b76; "> 
              </div>
</form> 

</body></html>
