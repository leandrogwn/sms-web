<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="web_tools/css/style_login.css">
    <link rel="icon" sizes="192x192" href="icons/icon-192x192.png">
    <?php
    Header('Cache-Control: no-cache');
    Header('Pragma: no-cache');
    ?>
</head>
<body background="web_tools/img/grey_wash_wall.png">
    <table align="center" width="100%" border="0">
        <tr style="background:#FFFFFF;">
            <td align="left">
                <br>
                <img src="web_tools/img/smsystem10.png"><br>
            </td>
        </tr>
        <tr>
            <td align="center"> <form action="login/RecebeDadosLogin.php" method="post">
                    <br><br><br><br><br><br><div id="quadro" align="center">
                        <table align="center" id="campos" style="border-collapse:collapse;" border="0">
                            <tr>
                                <td colspan="2">
                                    <div id="titulo"><img src="web_tools/img/LOGI.png"></div>
                                </td>
                            </tr>

                            <tr>
                                <td width="163" id="lbl_login">      
                                    <label id="lbl_text">Digite  seu login:</label>      
                                </td>
                                <td width="333" id="lbl_login"><input type="text" id="login_digitado" name="login_digitado" autofocus></td>
                            </tr>
                            <tr>
                                <td id="lbl_login"><label id="lbl_text">Digite sua senha:</label></td>
                                <td id="lbl_login"><input type="password" id="senha_digitada" name="senha_digitada"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">
                                    <div id="caixa_btn"><br>
                                        <input type="submit" id="btn_login" value="Login">
                                        <br>
                                    </div>
                                </td>
                            </tr>
                        </table> 
                    </div>
                </form>
            </td>
        </tr>
    </table>
</body>
</html>