<!doctype html>
<html lang="pt-br" Xmanifest="appcache.manifest">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>smsWeb</title>
        <meta name="description" content="Envio de mensagem SMS através da WEB">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/main.css">

        <link rel="manifest" href="manifest.json">

        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="icon" sizes="192x192" href="icons/icon-192x192.png">
        <meta name="theme-color" content="#0b5b8c">

        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="smsWeb">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon-precomposed" href="icons/icon-152x152.png">

        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="icons/icon-144x144.png">
        <meta name="msapplication-TileColor" content="#0b5b8c">

        <style>
            body{
                background-color: #052146;
                text-align:center;
            }
            #form-login{
                height: 100%;
                background-color:#FFF;
                text-align:center;
                border-radius:8px;
                margin-left:5px;
                margin-right:5px;
            }
            #logo-sms{
                margin-top:30px;	
            }
            #caixa_btn{
                margin-bottom: 15px;
            }button{
                width: 300px;
            }
        </style>
    </head>
    <body>
        <nav class="light-blue lighten-1" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo"><img src="icons/smsystem-logo.png"></a>
        </div>
    </nav>
        <div class="current-page" align="center">

            <main class="home">
                <div align="center">
                    <img id="logo-sms" src="icons/logomain.png">
                    <form action="RecebeDadosLogin.php" method="post" id="form-login">
                        <img src="icons/LOGI.png">
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

            </main>

        </div><!--/.current-page-->

        <script src="js/vendor/jquery.min.js"></script>
        <script src="js/vendor/materialize-0.97.0.min.js"></script>
        <script src="js/spa.js"></script>
        <script src="js/main.js"></script>
        <script src="js/install.js"></script>

    </body>
</html>

