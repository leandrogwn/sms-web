<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <head>
        <title>Envio de SMS</title>
        <link rel="stylesheet" href="../web_tools/css/style.css">
        <script>
            function contarCaracteres(box, valor, campospan) {
                var conta = valor - box.length;
                document.getElementById(campospan).innerHTML = "Você ainda pode digitar " + conta + " caracteres";
                if (box.length >= valor) {
                    document.getElementById(campospan).innerHTML = "Opss.. você não pode mais digitar..";
                    document.getElementById("txtMENSAGEM").value = document.getElementById("txtMENSAGEM").value.substr(0, valor);
                }
            }
            function focus_textarea() {
                document.getElementById('txtMENSAGEM').focus();
            }
        </script>
    </head>
    <body>
        <div id="tamanho" align="center"><br><br>
            <form method="post" action="enviar_sms_individual.php">
                <input type="hidden" name="tipo_envio" value="Individual">
                <div id="send_grupo">
                    <table style="border-collapse:collapse;" border="0">
                        <tr>
                            <td>
                                <div id="tit_send_grupo"><br>Envio de SMS individual</div>
                            </td>
                        </tr>
                        <tr>
                            <?php
                            include("../db_tools/conecta_db.php");

                            $busca = mysql_query("SELECT municipe.nome AS nome, telefone.fone_sms as fone from municipe INNER JOIN telefone on(municipe.id_municipe = telefone.id_municipe) WHERE situacao = 1 ORDER BY municipe.nome;") or die("Não foi possivel buscar os dados." . mysql_error());
                            ?>
                            <td id="lbl">Nome:<br><select name="txtTELEFONE" id="txtTELEFONE">
                                    <?php
                                    while ($lista = mysql_fetch_assoc($busca)) {
                                        ?>
                                        <option value="<?php echo $lista['fone'] ?>"><?php echo $lista['nome'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                        $busca_msgn = mysql_query("SELECT id_mensagem, titulo FROM mensagem WHERE situacao = 1 ORDER BY titulo;") or die("Não foi possivel carregar as mensagens. " . mysql_error());
                        ?>
                        <tr>
                            <td id="lbl">
                                <fieldset>
                                    Mensagem:<br>
                                    <input type="radio" name="tipo_msgn" id="tipo_msgn" class="tipo_msgn" value="cadastrada" checked onClick="document.getElementById('txtMENSAGEM').style.display = 'none';document.getElementById('mensagem_selecionada').style.display = 'inline';document.getElementById('sprestante').style.display = 'none';">
                                    <label for="cadastrada">Mensagem Cadastrada</label>
                                    <input type="radio" name="tipo_msgn" id="tipo_msgn" class="tipo_msgn" value="escrever" onMouseMove="focus_textarea();" onClick="document.getElementById('mensagem_selecionada').style.display = 'none'; document.getElementById('txtMENSAGEM').style.display = 'inline';document.getElementById('sprestante').style.display = 'inline';">
                                    <label for="escrever">Escrever Mensagem</label>
                                    <br>
                                    <select id="mensagem_selecionada" name="mensagem_selecionada">
                                        <?php
                                        while ($lista_msgn = mysql_fetch_assoc($busca_msgn)) {
                                            ?>
                                            <option value="<?php echo $lista_msgn['id_mensagem']; ?>"><?php echo $lista_msgn['titulo']; ?></option> 
                                            <?php
                                        }
                                        ?>     
                                    </select>
                                    <textarea name="txtMENSAGEM" cols="45" rows="2" id="txtMENSAGEM" style="display:none;" onkeyup="contarCaracteres(this.value, 170, 'sprestante')"></textarea><br>
                                    <span id="sprestante" style="font-family:Georgia;"></span>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="btn_env_sms_grupo">
                                    <input type="submit" name="Submit" id="btn_enviar_individual" value="Enviar SMS">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <script language="Javascript" type="text/javascript">
            parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 200; //40: Margem Superior e Inferior, somadas
        </script>
        <?php
        mysql_free_result($busca);
        mysql_free_result($busca_msgn);
        mysql_close($con);
        ?>
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