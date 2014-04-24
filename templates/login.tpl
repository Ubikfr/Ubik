<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="fr"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="fr"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="fr"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="fr"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>Ubik ~ {$titre}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="{block name=description}pas de description{/block}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- , user-scalable=no -->
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory 
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
    <link rel="shortcut icon" href="/favicon.png"> -->
    <!--[if IE]> <link rel="shortcut icon" href="/favicon.ico"> <![endif]-->

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/normalize.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/styles.css" type="text/css">
{if isset($extra_css) }{foreach $extra_css as $css}
    <link rel="stylesheet" href="/assets/css/{$css}" type="text/css">
{/foreach}{/if}
    <!--[if lt IE 9]>
        <link rel="stylesheet" href="/assets/css/ie.css">
    <![endif]-->

    <!-- Modernizr -->
    <script type="text/javascript" src="/assets/js/vendor/modernizr.min.js"></script>
</head>
<body>
<!--[if lt IE 9]>
    <span class="browsehappy">Vous utilisez un navigateur totalement <strong>dépassé</strong>. Merci de le <a href="http://browsehappy.com/">mettre à jour</a> afin de visualiser ce site correctement. <a class="close" href="#"></a></span>
<![endif]-->
<section class="login">
    <div class="inner">
{if isset($loggedinUser)}
    <form class="loginform">
        <span>Vous êtes déjà connecté en tant que {$loggedinUser.nom}</span>
        <button id='logout' class="button">Déconnexion</button>
    </form>
{else}
        {$content}
{/if}
    </div>
</section>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/assets/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
{if isset($extra_js) }{foreach $extra_js as $js}
<script type="text/javascript" src="{$js}"></script>
{/foreach}{/if}
</body>
</html>
