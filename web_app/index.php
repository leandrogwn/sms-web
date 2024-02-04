<!DOCTYPE html>
<head>
    <link rel="manifest" href="/manifest.json">
    <!-- Add to home screen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="smsWeb">
    <link rel="apple-touch-icon" href="icons/icon-152x152.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="icons/icon-192x192.png">
    <meta name="theme-color" content="#F77F00">

    <!--Tile icon form window -->
    <meta name="msapplication-TileImage" content="images/icons/icon-144x144.png">
    <meta name="msapplication-TileColor" content="#2F3BA2">
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <meta charset="utf-8" />
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="web_tools/css/style_login.css">
    <?php
    Header('Cache-Control: no-cache');
    Header('Pragma: no-cache');
    ?>
    
    <style>
        body{
            background:url(icons/background.png);
            text-align:center;
        }
        #form-login{
            background-color:#FFF;
            text-align:center;
            border-radius:8px;
            margin-top:50px;
            margin-left:5px;
            margin-right:5px;
        }
        #logo-sms{
            margin-top:30px;	
        }
    </style>
</head>
<body>
    <nav class="light-blue lighten-1" role="navigation">
        <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo"><img src="web_tools/img/smsystem-logo.png"></a>
        </div>
    </nav>
    <div class="section no-pad-bot" id="index-banner" align="center">
        <img id="logo-sms" src="icons/logomain.png">
        <form action="RecebeDadosLogin.php" method="post" id="form-login">
            <img src="web_tools/img/LOGI.png">
            <div class="input-field col s6">

                <input type="text" id="login_digitado" name="login_digitado" required>
                <label for="login_digitado">Login</label>
            </div>
            <div class="input-field col s6">
                <input type="password" id="senha_digitada" name="senha_digitada" required>
                <label for="senha_digitada">Senha</label>
            </div>
            <div id="caixa_btn"><br>
                <button class="btn waves-effect waves-light" type="submit" name="action">Login
                    <i class="material-icons right">vpn_key</i>
                </button>
                              <!--<input type="submit" id="btn_login" value="Login">-->
                <br>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script src="js/spa.js"></script>
</body>
</html>