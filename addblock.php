<?php
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset( $_REQUEST['id'] ) and is_numeric($_REQUEST['id']) ){
    
    echo '<div class="inlinebodydiv"><img class="closeimg" onclick=" jQuery(this).parent().hide(); " alt="x" src="/sites/all/themes/pdxneedto/img/ico_close.png" />';

    switch($_REQUEST['id']){
    case 1:
    case 11:
//        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

        echo '<div class="inlinetitle"><div class="inlinetitlein">';
        echo '<a target="blank" class="smhel" href="http://'.PDX_URL_HELP.'/standartnyy-sposob-vhoda.html" title="Открыть справку в новом окне">&nbsp;</a>';
        echo 'Вход на сайт</div></div>';
        echo '<div class="fieldset-wrapper">';
        echo '<div class="cnt">';
        module_load_include('inc', 'user', 'user.pages');
        $out=drupal_get_form('user_login');
        print drupal_render($out);
        echo '</div>';
        echo '</div>'; 
        break;
    case 4:
    case 14:
//        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

        echo '<div class="inlinetitle"><div class="inlinetitlein">';
        echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/standartnyy-sposob-vhoda.html" title="Открыть справку в новом окне">&nbsp;</a>';
        echo 'Забыли пароль?</div></div>';
        echo '<div class="fieldset-wrapper">';
        echo '<div class="cnt">';
        module_load_include('inc', 'user', 'user.pages');
        $out=drupal_get_form('user_pass');
        print drupal_render($out);
        echo '</div>';
        echo '</div>'; 
        break;
    case 2:
    case 12:
//        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

        echo '<div class="inlinetitle"><div class="inlinetitlein">';
        echo '<a target="blank" class="smhelp" href="http://'.PDX_URL_HELP.'/standartnyy-sposob-registracii.html" title="Открыть справку в новом окне">&nbsp;</a>';
        echo 'Регистрация на сайте <a class="ttlsm" href="javascript:void(0);" onclick="onbasic(this, 1);"><span class="thr" style="display: none;"></span>Войти на сайт</a></div></div>';
        echo '<div class="fieldset-wrapper">';
        echo '<div class="cnt">';
        $out=drupal_get_form('user_register_form');
        print drupal_render($out); 
        echo '</div>';
        echo '</div>';
        break;
    case 3:
//        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

        echo '<div class="inlinetitle"><div class="inlinetitlein">';
        echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/vybor-goroda-s-kotorym-vy-budete-rabotat.html" title="Открыть справку в новом окне">&nbsp;</a>';
        echo 'Выберите свой город</div></div>';
        echo '<div class="fieldset-wrapper">';
        echo '<div class="cnt">';
        echo '<p>На данный момент сайт поддерживает работу только с одним городом – столицей республики Беларусь, городом Минск. Все объявления и все функции сайта относятся только к этому городу.</p>';
        echo '<p>Но совсем скоро планируется расширение сайта на другие города Беларуси, а также России и Украины. Не пропустите!</p>';
        echo '<p>Если у вас есть желание развивать сайт в собственном городе, предлагаем воспользоваться возможностью <a href="http://'.PDX_URL_HELP.'/franshiza-na-gorod.html">покупки франшизы</a> на определенный город.</p>';
        echo '<script type="text/javascript"> 
        jQuery(\'#city_current\').removeClass(\'ajaxproc\');
         </script>';
        echo '</div>';
        echo '</div>';
        break;
    }

    echo '</div>';
    echo '<script type="text/javascript"> jQuery(\'.thr\').hide(); </script>';

    if( isset($_REQUEST['op']) and strlen($_REQUEST['op']) ){

        drupal_goto('node');

    }

}



?>