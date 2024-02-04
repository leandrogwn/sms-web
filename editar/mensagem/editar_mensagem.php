﻿﻿<?php
session_start();
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <head>
        <title>Editar Mensagem</title>
        <link rel = "stylesheet" href = "../../web_tools/css/reset.css">
        <link rel = "stylesheet" href = "../../web_tools/css/style.css">
        <script>
            function contarCaracteres(box, valor, campospan) {
                var conta = valor - box.length;
                document.getElementById(campospan).innerHTML = "Você ainda pode digitar " + conta + " caracteres";
                if (box.length >= valor) {
                    document.getElementById(campospan).innerHTML = "Opss.. você não pode mais digitar..";
                    document.getElementById("msgn").value = document.getElementById("msgn").value.substr(0, valor);
                }
            }
        </script>
    </head>
    <body>
        <div id="tamanho" align="center"><br><br>
            <?php
            include("../../db_tools/conecta_db.php");
            $getCod = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            $editar = $getCod['editar'];

            $busca = mysql_query("SELECT * FROM mensagem WHERE id_mensagem = '$editar' ") or die("Não foi possivel localizar a mensagem. " . mysql_error());
            $lista = mysql_fetch_assoc($busca);
            ?>
            <form name="inc_msgm" method="post" action="editar_mensagem_bd.php?editar=<?php echo $editar; ?>">
                <div id="send_grupo">
                    <table align="center" id="inc_mensagem" style="border-collapse:collapse;" border="0">
                        <tr>
                            <td>
                                <div id="tit_send_grupo"><br>Editar Mensagem</div>
                            </td>
                        </tr>
                        <tr>
                            <td id="lbl">
                                <fieldset>
                                    <label>Titulo da Mensagem:</label><br>
                                    <input type="text" name="titulo_msgn" id="titulo_msgn" value="<?php echo $lista['titulo']; ?>">
                                    <br>
                                    <label>Texto da Mensagem:</label><br>
                                    <textarea id="msgn" name="msgn"  cols="45" rows="5" onkeyup="contarCaracteres(this.value, 170, 'sprestante')"><?php echo $lista['mensagem']; ?></textarea><br>
                                    <span id="sprestante" style="font-family:Georgia;"></span>
                                </fieldset>
                                <div id="btn_env_sms_grupo">
                                    <input type="submit" id="btn_cad_msg" value="Salvar Edição">
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