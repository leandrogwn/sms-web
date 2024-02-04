<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    function validaData($data) {
        $data_v = explode("-", $data);
        $dataOk = $data_v[2] . "/" . $data_v[1] . "/" . $data_v[0];
        return $dataOk;
    }

    //conecta ao banco
    include("../db_tools/conecta_db.php");
    $dadosAgendamento = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    $idEnvio = $dadosAgendamento['sms'];

    $dadosEnvio = mysql_query("SELECT * from envio_prog WHERE id_envio_prog = '$idEnvio'") or die("Não carregou dados para reenviar." . mysql_error());
    $regDadosEnvio = mysql_fetch_assoc($dadosEnvio);
    ?>
    <!DOCTYPE html>
    <head>
        <title>Reenvio de SMS</title>
        <link rel="stylesheet" href="../web_tools/css/style.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../web_tools/js/language_datepickerPT.js"></script>

        <link rel="stylesheet" href="../web_tools/css/style.css">
        <script type="text/javascript" src="../web_tools/js/inputMask.js"></script>
        <script type="text/javascript" src="../web_tools/js/inputMaskBase.js" ></script>
        <script type="text/javascript" src="../web_tools/js/inputMaskExtension.js"></script>
        <script>
            function disposicao() {
    <?php
    if ($regDadosEnvio['tipo_destino'] == 1) {
        $buscaIdMunicipe = mysql_query("select id_municipe from esp_ep_municipe where id_envio_prog = '$idEnvio'") or die("Não foi possivel carregar a id do municipe. " . mysql_error());
        $regIdMunicie = mysql_fetch_assoc($buscaIdMunicipe);
        ?>
                    radioMunicipe();
        <?php
    } else {
        $buscaIdGrupo = mysql_query("select id_grupo from esp_ep_grupo where id_envio_prog = '$idEnvio'") or die("Não foi possivel carregar a id do grupo. " . mysql_error());
        $regIdGrupo = mysql_fetch_assoc($buscaIdGrupo);
        ?>
                    radioGrupo();
        <?php
    }

    if ($regDadosEnvio['tipo_msgm'] == 1) {
        $buscaIdMensagemPredefinida = mysql_query("select id_mensagem from esp_ep_msgm_pred where id_envio_prog = '$idEnvio'") or die("Não foi possivel carregar a id do mensagem predefinida. " . mysql_error());
        $regIdMensagemPredefinida = mysql_fetch_assoc($buscaIdMensagemPredefinida);
        ?>
                    radioMsgmPredefinida();
        <?php
    } else {
        $buscarMensagem = mysql_query("select mensagem from esp_ep_msgm_n_pred where id_envio_prog = '$idEnvio'") or die("Não carregou a id da mensagem não predefinida. " . mysql_error());
        $regMensagemNPredefinida = mysql_fetch_assoc($buscarMensagem);
        ?>
                    radioMsgmNPredefinida();
        <?php
    }
    ?>
            }
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
            function radioMsgmPredefinida() {
                document.getElementById('txtMENSAGEM').style.display = 'none';
                document.getElementById('mensagem_selecionada').style.display = 'inline';
            }
            function radioMsgmNPredefinida() {
                document.getElementById('mensagem_selecionada').style.display = 'none';
                document.getElementById('txtMENSAGEM').style.display = 'inline';
            }
            function radioMunicipe() {
                document.getElementById('envia_grupo').style.display = 'none';
                document.getElementById('txtTELEFONE').style.display = 'inline';
            }
            function radioGrupo() {
                document.getElementById('txtTELEFONE').style.display = 'none';
                document.getElementById('envia_grupo').style.display = 'inline';
            }

            function validaDataAtrasso() {
                var data = document.getElementById('data_envio').value;
                var objDate = new Date();
                objDate.setYear(data.split("/")[2]);
                objDate.setMonth(data.split("/")[1] - 1);//- 1 pq em js é de 0 a 11 os meses
                objDate.setDate(data.split("/")[0]);

                if (objDate.getTime() < new Date().getTime()) {
                    alert("O dia informado é menor que a data atual..");
                    document.getElementById('data_envio').focus();
                }
            }
        </script>
    </head>
    <body onload="disposicao()">
        <script>
            $(function () {
                $("#data_envio").datepicker({
                    // Consistent format with the HTML5 picker
                    dateFormat: 'dd/mm/yy'
                },
                // Localization
                        $.datepicker.regional['pt']
                        );
            });
        </script>
        <div id="tamanho" align="center"><br><br>
            <form method="post" action="agenda_envio_bd.php">
                <div id="send_grupo">
                    <table style="border-collapse:collapse;" border="0">
                        <tr>
                            <td>
                                <div id="tit_send_grupo"><br>Reenvio de SMS Programado<br></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" id="lbl">
                                <fieldset>
                                    <legend>Destinatário</legend>
                                    <input type="radio" name="tipo_envio" id="tipo_envio" class="tipo_envio" value="1" <?php if ($regDadosEnvio['tipo_destino'] == 1) { ?> checked="checked" <?php } ?> onclick="radioMunicipe()">
                                    <label for="municipe">Munícipe</label>
                                    <input type="radio" name="tipo_envio" id="tipo_envio" class="tipo_envio" value="2" <?php if ($regDadosEnvio['tipo_destino'] == 2) { ?> checked="checked" onshow="radioGrupo()" <?php } ?> onclick="radioGrupo()">
                                    <label for="grupo">Grupo</label><br>
                                    <?php
                                    //busca os grupos no banco de dados
                                    $busca_grupo = mysql_query("SELECT id_grupo, grupo FROM grupo WHERE situacao = 1 ORDER BY grupo") or die("Não foi possivel carregar os grupos. " . mysql_error());
                                    $busca_municipe = mysql_query("SELECT id_municipe, nome FROM municipe WHERE situacao = 1 ORDER BY nome;") or die("Não foi possivel buscar os dados." . mysql_error());
                                    ?>
                                    <select name="txtTELEFONE" id="txtTELEFONE">
                                        <?php
                                        while ($lista = mysql_fetch_assoc($busca_municipe)) {
                                            ?>
                                            <option value="<?php echo $lista['id_municipe'] ?>" <?php if (isset($regIdMunicie['id_municipe']) == $lista['id_municipe']) { ?> selected="selected" <?php } ?>><?php echo $lista['nome'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <select id="envia_grupo" name="envia_grupo">
                                        <?php
                                        //lista com os dados encontrados
                                        while ($lista = mysql_fetch_assoc($busca_grupo)) {
                                            ?>
                                            <option value="<?php echo $lista['id_grupo'] ?>" <?php if (isset($regIdGrupo['id_grupo']) == $lista['id_grupo']) { ?> selected="selected" <?php } ?>><?php echo $lista['grupo'] ?></option>        
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <?php
                                $busca_msgn = mysql_query("SELECT id_mensagem, titulo FROM mensagem WHERE situacao = 1 ORDER BY titulo ASC") or die("Não foi possivel carregar as mensagens. " . mysql_error());
                                ?>
                                <fieldset>
                                    <legend>Mensagem</legend>
                                    <input type="radio" name="tipo_msgn" id="tipo_msgn" class="tipo_msgn" value="1" <?php if ($regDadosEnvio['tipo_msgm'] == 1) { ?> checked="checked" <?php } ?> onClick="radioMsgmPredefinida()">
                                    <label for="cadastrada">Mensagem Cadastrada</label>
                                    <input type="radio" name="tipo_msgn" id="tipo_msgn" class="tipo_msgn" value="2" onMouseMove="focus_textarea();" <?php if ($regDadosEnvio['tipo_msgm'] == 2) { ?> checked="checked" <?php } ?> onClick="radioMsgmNPredefinida()">
                                    <label for="cadastrada">Escrever Mensagem</label>
                                    <select id="mensagem_selecionada" name="mensagem_selecionada">
                                        <?php
                                        while ($lista_msgn = mysql_fetch_assoc($busca_msgn)) {
                                            ?>
                                            <option value="<?php echo $lista_msgn['id_mensagem']; ?>" <?php if (isset($regIdMensagemPredefinida['id_mensagem']) == $lista_msgn['id_mensagem']) { ?> selected="selected" <?php } ?>><?php echo $lista_msgn['titulo']; ?></option> 
                                            <?php
                                        }
                                        ?>     
                                    </select>
                                    <?php
                                    if (isset($regMensagemNPredefinida['mensagem'])) {
                                        $mensagem = $regMensagemNPredefinida['mensagem'];
                                    } else {
                                        $mensagem = "";
                                    }
                                    ?>
                                    <textarea name="txtMENSAGEM" rows="2" id="txtMENSAGEM" onkeyup="contarCaracteres(this.value, 165, 'sprestante')"><?php echo $mensagem; ?></textarea><br>
                                    <span id="sprestante" style="font-family:Georgia;"></span>
                                </fieldset>
                                <fieldset>
                                    <legend>Programar</legend>
                                    <label>Enviar no dia</label>
                                    <input type="text" placeholder="Ex: 01/01/2014" name="data_envio" id="data_envio" value="<?php echo validaData($regDadosEnvio['data_envio']); ?>">
                                    <label id="hora_envio_lbl"> as </label>
                                    <input type="text" name="hora_envio" id="hora_envio" value="<?php echo $regDadosEnvio['hora_envio']; ?>">
                                    <label>horas.</label>

                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><div id="btn_env_sms_grupo"><input type="submit" id="btn_enviar_grupo" name="Submit" onmouseover="validaDataAtrasso();" value="Agendar Envio"></div></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <script language="Javascript" type="text/javascript">
            parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 40; //40: Margem Superior e Inferior, somadas
        </script>
        <?php
        if (isset($buscaIdGrupo)) {
            mysql_free_result($buscaIdGrupo);
        }
        if (isset($buscaIdMensagemPredefinida)) {
            mysql_free_result($buscaIdMensagemPredefinida);
        }
        if (isset($buscaIdMunicipe)) {
            mysql_free_result($buscaIdMunicipe);
        }
        if (isset($busca_grupo)) {
            mysql_free_result($busca_grupo);
        }
        if (isset($busca_msgn)) {
            mysql_free_result($busca_msgn);
        }
        if (isset($busca_municipe)) {
            mysql_free_result($busca_municipe);
        }
        if (isset($buscarMensagem)) {
            mysql_free_result($buscarMensagem);
        }
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