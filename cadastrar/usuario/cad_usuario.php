<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Novo usuário</title>
        <link rel="stylesheet" href="../../web_tools/css/reset.css">
        <link rel="stylesheet" href="../../web_tools/css/style.css">
        <link rel="stylesheet" href="../../web_tools/css/menu.css">
        <script type="text/javascript" src="../../web_tools/js/jquery-1.4.2.min.js"></script>
        <script language="JavaScript" src="../../web_tools/js/script_global.js"></script>
        <script type="text/javascript" src="../../web_tools/js/validamascara.js"></script>
        <script language="javascript" type="text/javascript" src="../../web_tools/js/mascara.js"></script>
        <script language="javascript" type="text/javascript" src="../../web_tools/js/jquery.js"></script>
        <script language="javascript" type="text/javascript" src="../../web_tools/js/data.js"></script>
        <style>
            input{
                text-transform:none!important;
            }
            #nome{
                width:225px;
                text-transform:capitalize!important;
            }
        </style>
    </head>
    <body>
        <div id="tamanho" align="center">
            <div>
                <br><br>
                <form action="cad_usuario_bd.php" method="post">
                    <div id="inc_agenda">
                        <table>
                            <tr>
                                <td>
                                    <div id="tit_send_grupo"><br>Cadastrar novo usuário</div>
                                </td>
                            </tr>
                            <tr>
                                <td id="lbl">
                                    <label>Primeiro nome:</label><br>
                                    <input type="text" name="nome" required id="nome">
                                    <br>
                                    <label>Telefone:</label><br>
                                    <input type="text" name="fone" required id="fone">
                                    <br>
                                    <label>Login:</label><br>
                                    <input type="text" name="login" required id="login">
                                    <br>
                                    <label>Senha:</label><br>
                                    <input type="text" name="senha" required id="senha"><br>
                                    <label>Permissões</label><br>
                                    <fieldset>
                                        <input type="checkbox" class="check" name="ic">
                                        Incluir Contato<br>
                                        <input type="checkbox" class="check" name="ig">Incluir Grupo<br><input type="checkbox" class="check" name="im">Incluir Mensagem<br><input type="checkbox" class="check" name="c">Configurações<br><input type="checkbox" class="check" name="es">Enviar SMS<br><input type="checkbox" class="check" name="um">Usuário Master
                                        <br>
                                    </fieldset>
                                    <div id="btn_env_sms_grupo">
                                        <input type="submit" value="Incluir Usuário" id="btngrupo"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div> 
        <script language="Javascript" type="text/javascript">
            parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 40; //40: Margem Superior e Inferior, somadas
        </script> 
    </body>
    </html>
    <?php
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        window.open('../../index.php', '_top');
    </script>
    <?php
}
?>