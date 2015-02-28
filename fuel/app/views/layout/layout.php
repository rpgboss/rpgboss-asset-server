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
    <script>
        var username = "<?php print \Auth\Auth::get('displayed_name') ?>",
            sessionid = "<?php print \Auth\Auth::get('login_hash') ?>";
    </script>
    <meta charset="utf-8">
    </head>
    <body>
    <div class="header">
        <div class="backgroundimage">
            <div class="layer"></div>
            <div class="layer2">
                <div class="container">
                    <div class="col-md-3 logo">
                        <a href="http://rpgboss.com">
                            <img src="/assets/img/logo2.png" alt="">
                            <span>rpgboss</span>
                        </a>
                    </div>
                    <div class="col-md-9">
                        <ul>
                            <li><a href="https://github.com/rpgboss/rpgboss">Get the Source</a></li>
                            <li><a href="https://github.com/rpgboss/rpgboss/issues">BugTracker</a></li>
                            <li><a href="http://rpgboss.forumatic.com/">Community Forum</a></li>
                            <li><a class="active" href="http://assets.rpgboss.com/">Assets &amp; Games</a></li>
                            <li><a href="https://github.com/rpgboss/rpgboss/wiki">Tutorials</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="userheader">
    <div class="container">
    <div class="col-md-8">
    <?php if($isAuthed): ?>
        <a class="<?php print $userpanel==-1 ? 'active' : '' ?>" href="/"><span class="icon-home"></span>Home</a>
    <a class="<?php print $userpanel==1 ? 'active' : '' ?>" href="/packagemanagement"><span class="icon-package"></span>Packagemanagement</a>
        <a class="<?php print $userpanel==4 ? 'active' : '' ?>" href="/projectmanagement"><span class="icon-game"></span>Projectmanagement</a>
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
    <div class="container <?php print $contentCustomClass; ?>">
        <?php print $leftcol ?>
        <?php print $view ?>
    </div>
    <footer class="footer">
    <div class="container">
    2014 © rpgboss.com
    </div>
    </footer>
    <?php if($isAuthed): ?>
        <audio id="ccsound" src="<?php print \Fuel\Core\Uri::create('assets/sound/cc-sound.wav') ?>" preload="auto"></audio>
    <div class="chat row">
        <div class="headline row">
            <h1>Community Chat</h1>
        </div>
        <div class="body row">
            <div class="users col-xs-4">
                <span class="online"></span>
                <ul id="userlist"></ul>
            </div>
            <div class="messages col-xs-8"></div>
        </div>
        <div class="inputmessage row">
            <input type="text" placeholder="Write a message..."/>
            <a class="send" href="#">Send</a>
        </div>
    </div>
        <script src="<?php print \Fuel\Core\Uri::create('assets/js/chat.js') ?>"></script>
        <script src="<?php print \Fuel\Core\Uri::create('assets/js/commander.js') ?>"></script>
    <?php endif; ?>
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