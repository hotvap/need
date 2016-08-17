<?php
if( isset($_POST['reason']) and strlen($_POST['reason']) and isset($_POST['more']) and strlen($_POST['more']) and isset($_POST['mail']) and strlen($_POST['mail']) and isset($_POST['id']) and is_numeric($_POST['id']) and isset($_POST['city']) and is_numeric($_POST['city']) and isset($_POST['type']) and is_numeric($_POST['type']) ){
    $_POST['reason']=strip_tags(urldecode($_POST['reason']));
    $_POST['more']=strip_tags(urldecode($_POST['more']));
    $_POST['mail']=strip_tags(urldecode($_POST['mail']));

    $sitemail='needtome@yandex.ru';
    
    $msg='<p>Подробнее о жалобе:</p>';
    $msg.='<p><strong>Сайт:</strong></p><p>'.$_SERVER['HTTP_HOST'].'</p>';
    $msg.='<p><strong>Суть:</strong></p><p>'.$_POST['reason'].'</p>';
    $msg.='<p><strong>Подробнее:</strong></p><p>'.$_POST['more'].'</p>';
    $msg.='<p><strong>Email:</strong></p><p>'.$_POST['mail'].'</p>';
    $msg.='<p><strong>Город:</strong></p><p>'.$_POST['city'].'</p>';
    $msg.='<p>Жалоба на ';
    switch($_POST['type']){
    case 1:
        $msg.='участника '.$_POST['id'];
        break;
    case 2:
        $msg.='объявление '.$_POST['id'];
        break;
    }
    $msg.='</p>';
    
    $mailttl='Жалоба на сайте =(';
    switch($_POST['type']){
        case 5:
            $mailttl='Заказ билета';
            
            $msg='<p>Подробнее о заказе:</p>';
            $msg.='<p><strong>Сайт:</strong> '.$_SERVER['HTTP_HOST'].'</p>';
            $msg.='<p><strong>Телефон:</strong> '.$_POST['reason'].'</p>';
            $msg.='<p><strong>Имя:</strong> '.$_POST['more'].'</p>';
            $msg.='<p><strong>Кол-во мест:</strong> '.$_POST['mail'].'</p>';
            $msg.='<p><strong>Город:</strong> '.$_POST['city'].'</p>';
            $msg.='<p><strong>Мероприятие:</strong> '.$_POST['id'].'</p>';
            
            break;
    }
    
    $header="Content-type: text/html; charset=\"utf-8\"";
    $header.="From: \"".$_SERVER['HTTP_HOST']."\" <".$sitemail.">";
    $header.='Subject: "'.$theme.'"';
    $header.="Content-type: text/html; charset=\"utf-8\"";
    mail($sitemail, $mailttl, $msg, $header, '-f'.$sitemail);


    switch($_POST['type']){
        case 3:
        case 4:
            echo '<div class="abuse_ok">Спасибо, ваше сообщение отправлено модератору. Мы ознакомимся с ним, и обязательно примем меры.</div>';
            break;
        case 5:
            echo '<div class="abuse_ok">Спасибо, ваш заказ получен. Оператор свяжется с вами в ближайшее время.</div>';
            break;
        default:
            echo '<div class="abuse_ok">Спасибо, ваша жалоба отправлена модератору. Мы ознакомимся с ней, и обязательно примем меры.</div>';
    }
    
    echo ' <script type="text/javascript"> jQuery(\'.abuseform\').remove(); </script> ';
    exit();
}
echo '<div class="abuse_error">Что-то произошло не так =( Попробуйте ещё раз.</div>';


?>