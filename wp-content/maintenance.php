<?php

//  ATTENTION!
//
//  DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
//  SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.
//  IF YOU REQUIRE TO APPLY CUSTOM MODIFICATIONS, PERFORM THEM IN THE FOLLOWING FILE:
//  /var/www/vhosts/agenziaviaggiltc.it/httpdocs/wp-content/maintenance/template.phtml


$protocol = $_SERVER['SERVER_PROTOCOL'];
if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol) {
    $protocol = 'HTTP/1.0';
}

header("{$protocol} 503 Service Unavailable", true, 503);
header('Content-Type: text/html; charset=utf-8');
header('Retry-After: 600');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="https://www.agenziaviaggiltc.it/wp-content/uploads/2017/09/cropped-cropped-testatasito2-32x32.png">
    <link rel="stylesheet" href="https://www.agenziaviaggiltc.it/wp-content/maintenance/assets/styles.css">
    <script src="https://www.agenziaviaggiltc.it/wp-content/maintenance/assets/timer.js"></script>
    <title>Manutenzione pianificata</title>
</head>

<body>

    <div class="container">

    <header class="header">
        <h1>Nel sito web è in corso la manutenzione pianificata.</h1>
        <h2>Ci scusiamo per l'inconveniente. Torna tra poco e saremo pronti!</h2>
    </header>

    <!--START_TIMER_BLOCK-->
        <!--END_TIMER_BLOCK-->

    <!--START_SOCIAL_LINKS_BLOCK-->
    <section class="social-links">
                    <a class="social-links__link" href="https://www.facebook.com/Plesk" target="_blank" title="Facebook">
                <span class="icon"><img src="https://www.agenziaviaggiltc.it/wp-content/maintenance/assets/images/facebook.svg" alt="Facebook"></span>
            </a>
                    <a class="social-links__link" href="https://twitter.com/Plesk" target="_blank" title="Twitter">
                <span class="icon"><img src="https://www.agenziaviaggiltc.it/wp-content/maintenance/assets/images/twitter.svg" alt="Twitter"></span>
            </a>
            </section>
    <!--END_SOCIAL_LINKS_BLOCK-->

</div>

<footer class="footer">
    <div class="footer__content">
        A cura di <a href="https://www.plesk.com/" target="_blank"><img class="logo" src="https://www.agenziaviaggiltc.it/wp-content/maintenance/assets/images/plesk-logo.png" alt="Plesk"></a>
    </div>
</footer>

</body>
</html>
