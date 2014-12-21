<!DOCTYPE html>

<html>
<head>
    <title><?php print $title; ?></title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
    <link type='text/css' rel="stylesheet" media="screen" href="<?php print Uri::create("assets/css/bootstrap.min.css") ?>">
    <link type='text/css' rel="stylesheet" media="screen" href="<?php print Uri::create("assets/css/bootstrap-theme.min.css") ?>">
    <link type='text/css' rel="stylesheet" media="screen" href="<?php print Uri::create("assets/css/main.css") ?>">
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">
    <script src="/assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-ui.min.js" type="text/javascript"></script>
    <meta charset="utf-8">
    </head>
    <body>
    <div class="header">
    <div class="container">
    <div class="col-md-3 logo">
    <a href="http://rpgboss.com">
    <img src="/assets/img/rpgboss-logo.png" alt="">
    rpgboss
    </a>
    </div>
    <div class="col-md-9">
    <ul>
    <li><a href="https://github.com/rpgboss/rpgboss">Get the Source</a></li>
    <li><a href="https://github.com/rpgboss/rpgboss/issues">BugTracker</a></li>
    <li><a href="http://rpgboss.forumatic.com/">Community Forum</a></li>
    <li><a class="active" href="http://assets.rpgboss.com/">Asset Store</a></li>
    <li><a href="https://github.com/rpgboss/rpgboss/wiki">Tutorials</a></li>
    </ul>
    </div>
    </div>
    </div>
    <div class="userheader">
    <div class="container">
    <div class="col-md-8">
    <?php if($isAuthed): ?>
        <a class="<?php print $userpanel==-1 ? 'active' : '' ?>" href="/"><span class="icon-home"></span>Home</a>
    <a class="<?php print $userpanel==1 ? 'active' : '' ?>" href="/packagemanagement"><span class="icon-package"></span>Packagemanagement</a>

        <a class="<?php print $userpanel==2 ? 'active' : '' ?>" href="/profile"><span class="icon-profile"></span>Profile</a>
        <?php if(\Auth\Auth::get("group")==1): ?>
            <a class="<?php print $userpanel==3 ? 'active' : '' ?>" href="/adminpanel/unapproved"><span class="icon-settings"></span>Admin</a>
        <?php endif; ?>

    <?php endif; ?>
    </div>
    <div class="col-md-4 hello-col">
        <?php if($isAuthed): ?>
            <strong>Hello, <?php print \Auth\Auth::get("displayed_name") ?></strong>&nbsp;&nbsp;&nbsp;<a href="/logout" class="button">Logout</a>
        <?php else: ?>
            <strong>Hello, Guest</strong>&nbsp;&nbsp;&nbsp;<a href="/login" class="button">Login</a>
        <?php endif; ?>
    </div>
    </div>
    </div>
    <div class="container">
        <?php print $leftcol ?>
        <?php print $view ?>
    </div>
    <footer class="footer">
    <div class="container">
    2014 © rpgboss.com
    </div>
    </footer>
    <script type="text/javascript">
    $(document).ready(function() {
        var bodyHeight = $("body").height();
        var vwptHeight = $(window).height();
        if (vwptHeight > bodyHeight) {
            $("footer").css("position","absolute").css("bottom",0);
        }
    });
    </script>
    </body>
</html>