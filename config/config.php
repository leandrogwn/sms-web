<!DOCTYPE html>
<head  lang="pt">
    <?php
    header("Content-Type: text/html; charset=utf-8", true);
    Header('Cache-Control: no-cache');
    Header('Pragma: no-cache');
    ?>
    <meta charset="utf-8">
    <title>Configurações</title>
    <link rel="stylesheet" href="../web_tools/css/reset.css">
    <link rel="stylesheet" href="../web_tools/css/style.css">
    <link rel="stylesheet" href="../web_tools/css/menu.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="../web_tools/js/validamascara.js"></script>
    <script type="text/javascript" src="../web_tools/js/inputMask.js"></script>
    <script type="text/javascript" src="../web_tools/js/ip.js"></script>
    <script type="text/javascript" src="../web_tools/js/inputMaskBase.js" ></script>
    <script type="text/javascript" src="../web_tools/js/inputMaskExtension.js"></script>

    <script type="text/javascript">
        //Valida hora
        $(document).ready(function () {
            $("#hora_envio").inputmask("h:s", {"placeholder": "hh/mm"});
        });
        //Valida segundos
        function valida_segundo(edit) {
            if (event.keyCode < 48 || event.keyCode > 57) {
                event.returnValue = false;
            }
            if (edit.value.length == 0) {
                if (event.keyCode < 48 || event.keyCode > 53) {
                    event.returnValue = false;
                }
            }
            if (edit.value.length == 1) {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.returnValue = false;
                }
            }
        }

        //mascaras telefones
        jQuery(function ($) {
            $('#telefoneinfo').mask('(99) 9999-9999');
            $('.ip_address').mask('099.099.099.099');
            $('#porta').mask('9999');
            $('#registrospagina').mask('99');
            
        });
    </script>
</head>
<body>
    <div id="tamanho" align="center"><br><br>
        <div>
            <?php
            include("../db_tools/conecta_db.php");
            //Mensagem configurada na ultima alteração de configuração
            $busca_config_mensagem = mysql_query("SELECT id_mensagem, titulo FROM mensagem where id_mensagem IN(SELECT mensagem_aniversario FROM config);") or die("Não foi possível carregar a configurações salva. " . mysql_error());
            $reg_config_mensagem = mysql_fetch_assoc($busca_config_mensagem);
            $codMsgmAtual = $reg_config_mensagem['id_mensagem'];

            //dados da ultima configuração
            $buscaConfig = mysql_query("SELECT * FROM config;") or die("Não encontrou configurações. " . mysql_error());
            $regConfig = mysql_fetch_assoc($buscaConfig);
            ?>
            <br><br>
            <?php
            ?>
            <div id="inc_agenda">
                <table>
                    <tr>
                        <td>
                            <div id="tit_send_grupo"><br>Configurações do Sistema de Envio de Mensagem</div>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl">
                            <form action="alterar_config_bd.php" method="post">
                                <fieldset>
                                    <legend>Mensagem destinada a aniversáriantes:</legend><br>
                                    <select id="mensagem_selecionada" name="mensagem_selecionada">
                                        <option value="<?php echo $reg_config_mensagem['id_mensagem']; ?>" selected><?php echo $reg_config_mensagem['titulo']; ?></option>
                                        <?php
                                        $busca_msgn = mysql_query("SELECT id_mensagem, titulo FROM mensagem WHERE id_mensagem <> '$codMsgmAtual' AND situacao = 1 ORDER BY titulo ASC") or die("Não foi possivel carregar as mensagens cadastradas. " . mysql_error());
                                        while ($lista_msgn = mysql_fetch_assoc($busca_msgn)) {
                                            ?>
                                            <option value="<?php echo $lista_msgn['id_mensagem']; ?>"><?php echo $lista_msgn['titulo']; ?></option> 
                                            <?php
                                        }
                                        ?>     
                                    </select><br><br>
                                    <label>Enviar SMS para aniversariantes as</label>
                                    <input name="hora_envio" type="text" required id="hora_envio" size="4" maxlength="5" value="<?php echo $regConfig['hora_envio_aniversario']; ?>">
                                    <label>horas.</label>
                                </fieldset>
                                <br>
                                <fieldset>
                                    <legend>Tempo de atualização</legend>
                                    <label>Verificar mensagens agendadas a cada</label> <input name="segundos_atualiza" type="text" required id="segundos_atualiza" size="2" maxlength="2"  onkeypress="valida_segundo(this)" value="<?php echo $regConfig['tempo_atualizacao']; ?>"> <label>segundos.</label>
                                </fieldset>
                                <br>
                                <fieldset>
                                    <legend>Informar sobre aniversáriante do dia</legend>
                                    <label>Enviar SMS com o nome dos aniversáriantes para o número </label> <input type="text" name="telefone_info" required id="telefoneinfo" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4}" value="<?php echo $regConfig['telefone_info']; ?>">
                                </fieldset>
                                <br>
                                <fieldset>
                                    <legend>Conexão com o Modem</legend>
                                    <label>Endereço de IP:</label> <input type="text" name="enderecoip" id="enderecoip" class="ip_address" required placeholder="xxx.xxx.xxx.xxx" value="<?php echo $regConfig['endereco_ip']; ?>"> <label>Porta:</label><input type="text" name="porta" id="porta" required placeholder="xxxx" value="<?php echo $regConfig['porta']; ?>">
                                </fieldset>
                                <br>
                                <fieldset>
                                    <legend>Filtragem de dados</legend>
                                    <label>Quantidade de registros por página:</label> <input name="registrospagina" type="text" id="registrospagina" required value="<?php echo $regConfig['registro_pagina']; ?>">
                                </fieldset>
                                <div id="btn_env_sms_grupo">
                                    <input type="submit" id="btn_config" value="Salvar Configurações">
                                </div>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div> 
    <script language="Javascript" type="text/javascript">
        parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 40; //40: Margem Superior e Inferior, somadas
    </script> 
    <?php
    mysql_free_result($buscaConfig);
    mysql_free_result($busca_config_mensagem);
    mysql_free_result($busca_msgn);
    mysql_close($con);
    ?>
</body>
</html>
