<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Cadastrar Mensagem</title>
            <link rel="stylesheet" href="../../web_tools/css/reset.css">
            <link rel="stylesheet" href="../../web_tools/css/style.css">
            <script>
                function contarCaracteres(box, valor, campospan) {
                    var conta = valor - box.length;
                    document.getElementById(campospan).innerHTML = "Você ainda pode digitar " + conta + " caracteres";
                    if (box.length >= valor) {
                        document.getElementById(campospan).innerHTML = "Opss.. você não pode mais digitar..";
                        document.getElementById("msgn").value = document.getElementById("msgn").value.substr(0, valor);
                    }
                }
                function focus_tit() {
                    document.getElementById('titulo_msgn').focus();
                }
            </script>
        </head>
        <body onLoad="focus_tit();">
            <div id="tamanho" align="center"><br><br>
                <form name="inc_msgm" method="post" action="cad_mensagem_bd.php">
                    <div id="send_grupo">
                        <table style="border-collapse:collapse;" border="0">
                            <tr>
                                <td>
                                    <div id="tit_send_grupo"><br>Cadastro de Mensagens</div>
                                </td>
                            </tr>
                            <tr>
                                <td id="lbl">
                                    <fieldset>
                                        <label>Título da Mensagem:</label><br>
                                        <input type="text" name="titulo_msgn" id="titulo_msgn" required>
                                        <br>
                                        <label>Texto da Mensagem:</label><br>
                                        <textarea id="msgn" name="msgn" rows="5" cols="45" required onkeyup="contarCaracteres(this.value, 170, 'sprestante')">
                                        </textarea><br>
                                        <span id="sprestante" style="font-family:Georgia;"></span>
                                        <br>
                                    </fieldset>
                                    <div id="btn_env_sms_grupo">
                                        <input type="submit" id="btn_cad_msg" value="Cadastrar">
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
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