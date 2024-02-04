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
        <title>Incluir Grupo</title>
        <link rel="stylesheet" href="../../web_tools/css/reset.css">
        <link rel="stylesheet" href="../../web_tools/css/style.css">
        <link rel="stylesheet" href="../../web_tools/css/menu.css">
        <script>
            function set_focus() {
                document.getElementById('inc_grupo').focus();
            }
        </script>
    </head>
    <body onLoad="set_focus();">
        <div id="tamanho" align="center"><br><br>
            <div id="send_grupo">
                <table style="border-collapse:collapse;" border="0">
                    <tr>
                        <td>
                            <div id="tit_send_grupo"><br>Inclusão de Grupos de SMS<br></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form action="cad_grupo_bd.php?tela=grupo" method="post">
                                <fieldset>
                                    <div id="lbl">Informe o nome do grupo no campo abaixo para incluir</div><br>
                                    <input type="text" name="inc_grupo" required id="inc_grupo">
                                </fieldset>
                                <br>
                                <div id="btn_env_sms_grupo"><input type="submit" value="Incluir Grupo" id="btngrupo"></div>
                            </form>
                        </td>
                    </tr>
                </table>
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
    </script>';
    <?php
}
?>