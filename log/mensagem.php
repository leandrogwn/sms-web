<?php
session_start();

error_reporting(0);

function validaData($data) {
    $data_v = explode("/", $data);
    $dataOk = $data_v[2] . "-" . $data_v[1] . "-" . $data_v[0];
    return $dataOk;
}

function formataData($data) {
    $str = explode('-', $data);
    $dataFormatada = $str[2] . "/" . $str[1] . "/" . $str[0];
    return $dataFormatada;
}

$sessao = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $sessao) {
    include("../db_tools/conecta_db.php");
    ?>
    <!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="../web_tools/css/reset.css">
        <link rel="stylesheet" href="../web_tools/css/style_log.css">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../web_tools/js/language_datepickerPT.js"></script>
        <script>
            function carrega() {
                document.getElementById("buscar").click();
            }
        </script>
    </head>
    <body <?php
    $load = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    if (isset($load['load']) == 1) {
        ?>
            onload="carrega()"
            <?php
        }
        ?>>
        <script>
            $(function () {
                $("#filtro_data_inicial").datepicker({
                    // Consistent format with the HTML5 picker
                    dateFormat: 'dd/mm/yy'
                },
                // Localization
                        $.datepicker.regional['pt']
                        );
            });
            $(function () {
                $("#filtro_data_final").datepicker({
                    // Consistent format with the HTML5 picker
                    dateFormat: 'dd/mm/yy'
                },
                // Localization
                        $.datepicker.regional['pt']
                        );
            });
        </script>

        <div id="tamanho">
            <br>
            <table align="center" width="81%">
                <tr>
                    <td>
                        <form id="div_buscar" action="mensagem.php" method="get">
                            <?php
                            $busca_data_min = mysql_query("SELECT MIN(data_envio) as data_envio FROM envio_prog;") or die("Não foi possivel localizar a menor data. " . mysql_error());
                            $regDataMin = mysql_fetch_assoc($busca_data_min);
                            $busca_data_max = mysql_query("SELECT MAX(data_envio) as data_envio FROM envio_prog;") or die("Não foi possivel localizar a menor data. " . mysql_error());
                            $regDataMax = mysql_fetch_assoc($busca_data_max);
                            ?>
                            <label id="label_periodo">Período</label>
                            <br>
                            <input type="text" name="filtro_data_inicial" id="filtro_data_inicial" value="<?php echo formataData($regDataMin["data_envio"]); ?>">
                            -
                            <input type="text" name="filtro_data_final" id="filtro_data_final" value="<?php echo formataData($regDataMax["data_envio"]); ?>">
                            <?php
                            $busca_usuario = mysql_query("SELECT id_admin, nome FROM admin order by nome;") or die("Não foi possivel localizar os usuários. " . mysql_error());
                            ?>

                            <select id="filtro_usuario" name="filtro_usuario">
                                <option value="all" selected>Selecione o usuário...</option>
                                <?php
                                while ($registro_usuarios = mysql_fetch_assoc($busca_usuario)) {
                                    ?>
                                    <option value="<?php echo $registro_usuarios['id_admin']; ?>"><?php echo $registro_usuarios['nome']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <select id="filtro_situacao" name="filtro_situacao">
                                <option value="all" selected>Selecione a situação...</option>
                                <option value="1">Agendado</option>
                                <option value="0">Cancelado</option>
                                <option value="2">Enviado</option>
                            </select>
                            <div id="btn_buscar">
                                <input type="submit" id="buscar" value="Buscar">
                            </div>
                        </form>

                    </td>
                </tr>
            </table>

            <table align="center">
                <tr id="tr-title">
                    <td id="col_data_hora">Data e Hora de Envio</td>
                    <td id="col_destino">Destino</td>
                    <td id="col_mensagem">Mensagem</td>
                    <td id="col_usuario">Usuário</td>
                    <td id="col_situacao">Situação</td>
                    <td id="col_acoes">Ações</td>
                </tr>
                <?php

                function limitaTexto($texto, $tamanho) {
                    return strlen($texto) > $tamanho ? substr($texto, 0, $tamanho) : $texto;
                }

                $dadosFiltro = filter_input_array(INPUT_GET, FILTER_DEFAULT);

                $buscaConfig = mysql_query("SELECT * FROM config") or die("Não foi possivel acessar as configurações. " . mysql_error());
                $regConfig = mysql_fetch_assoc($buscaConfig);

                $limite = $regConfig['registro_pagina']; // limite de registros por pagina
                $pag = 0; // valor padrao se nao for enviado nenhum valor via metodo GET
                $go_pag = $dadosFiltro['pag_atual']; // recebe o valor enviado pelo metodo GET
                $mul_go_pag = $limite * $go_pag;

                $periodo_inicial = validaData($dadosFiltro['filtro_data_inicial']);
                $periodo_final = validaData($dadosFiltro['filtro_data_final']);
                $usuario = $dadosFiltro['filtro_usuario'];
                $situacao = $dadosFiltro['filtro_situacao'];

                if (strtotime($periodo_final) < strtotime($periodo_inicial)) {
                    $periodo_temp = $periodo_inicial;
                    $periodo_inicial = $periodo_final;
                    $periodo_final = $periodo_temp;
                }

                if ($periodo_inicial == "") {
                    $periodo_inicial = date("Y-m-d");
                }
                if ($periodo_final == "") {
                    $periodo_final = date("Y-m-d");
                }
                if ($usuario == "") {
                    $usuario = "all";
                }
                if ($situacao == "") {
                    $situacao = "all";
                }

                //recebe o total que esta sendo exibido e repete os resultados    
                $buscaEnvioProg = mysql_query("SELECT * FROM envio_prog WHERE data_envio BETWEEN '$periodo_inicial' AND '$periodo_final' AND
                    if
			('$usuario' = 'all', id_admin >= 0, id_admin = '$usuario')
                    and
                    if
			('$situacao' = 'all', situacao >= 0, situacao = '$situacao') 
		ORDER BY id_envio_prog desc LIMIT $limite OFFSET $mul_go_pag") or die("Não foi possível carregar os dados do envio programado. " . mysql_error());

                $resultado = mysql_num_rows($buscaEnvioProg);

                //contar o total de registros sem limite
                //recebe o total que esta sendo exibido e repete os resultados
                $buscaEnvioProg2 = mysql_query("SELECT * FROM envio_prog WHERE data_envio BETWEEN '$periodo_inicial' AND '$periodo_final' AND
                    if
			('$usuario' = 'all', id_admin > 0, id_admin = '$usuario')
                    and
                    if
			('$situacao' = 'all', situacao >= 0, situacao = '$situacao')") or die("Não foi possível carregar os dados do envio programado. " . mysql_error());

                $resultado2 = mysql_num_rows($buscaEnvioProg2);

                //paginação
                $qtd_pagina = intval($resultado2 / $limite + 1);
                ?>
                <br>           
                <div class="paginacao">
                    <?php
                    if ($go_pag != 0) {
                        ?>
                        <a href="mensagem.php?pag_atual=0&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#03C;font-size:12px;">Primeira</a>
                        <?php
                    }
                    for ($i = 1; $i <= $qtd_pagina; $i++) {
                        $pg = $i - 1;
                        if ($go_pag == $pg) {
                            ?>
                            <a href="mensagem.php?pag_atual=<?php echo $pg; ?>&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#03C;font-size:15px;"><?php echo $i; ?></a>
                            <?php
                        } else {
                            ?>
                            <a href="mensagem.php?pag_atual=<?php echo $pg; ?>&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#666767;"><?php echo $i; ?></a>
                            <?php
                        }
                    }

                    if ($go_pag != $qtd_pagina - 1) {
                        ?>
                        <a href="mensagem.php?pag_atual=<?php echo $qtd_pagina - 1; ?>&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#03C;font-size:12px;">Última</a>
                        <?php
                    }
                    ?>
                </div>
                <br>
                <?php
                $cor = 0;

                while ($registroEnvioProg = mysql_fetch_assoc($buscaEnvioProg)) {

                    if ($cor == 0) {
                        ?>	
                        <tr bgcolor="#F3F3F3">
                            <?php
                            $cor = 1;
                        } else {
                            ?>
                        <tr bgcolor="#fff">
                            <?php
                            $cor = 0;
                        }
                        ?>
                        <td>
                            <?php echo formataData($registroEnvioProg['data_envio']); ?>
                            &nbsp;&nbsp;
                            <?php echo $registroEnvioProg['hora_envio']; ?>
                        </td>
                        <td style="text-transform: uppercase; width: 160px;">
                            <?php
                            $idEnvioProgramado = $registroEnvioProg['id_envio_prog'];
                            if ($registroEnvioProg['tipo_destino'] == 1) {
                                //municipe
                                $buscaEspMunicipe = mysql_query("SELECT id_municipe FROM esp_ep_municipe WHERE id_envio_prog = '$idEnvioProgramado'")or die("Erro busca id Municipe Programado") . mysql_error();
                                $regIdMunicipe = mysql_fetch_assoc($buscaEspMunicipe);
                                $idMunicipe = $regIdMunicipe['id_municipe'];
                                $buscaMunicipe = mysql_query("SELECT nome FROM municipe where id_municipe = '$idMunicipe'")or die("Erro busca municipe") . mysql_error();
                                $regDadosMunicipe = mysql_fetch_assoc($buscaMunicipe);
                                echo $regDadosMunicipe['nome'];
                            } else {
                                //grupo
                                $buscaEspGrupo = mysql_query("SELECT id_grupo FROM esp_ep_grupo WHERE id_envio_prog = '$idEnvioProgramado'")or die("Erro busca id Grupo") . mysql_error();
                                $regIdGrupo = mysql_fetch_assoc($buscaEspGrupo);
                                $idGrupo = $regIdGrupo['id_grupo'];
                                $buscaGrupo = mysql_query("SELECT grupo FROM grupo where id_grupo = '$idGrupo'")or die("Erro busca grupo") . mysql_error();
                                $regDadosGrupo = mysql_fetch_assoc($buscaGrupo);
                                echo 'Grupo ' . $regDadosGrupo['grupo'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($registroEnvioProg['tipo_msgm'] == 1) {
                                //predefinida
                                $buscaEspMsgPred = mysql_query("SELECT id_mensagem FROM esp_ep_msgm_pred WHERE id_envio_prog = '$idEnvioProgramado'") or die("Erro busca  id Mensagem Predefinida") . mysql_error();
                                $regbuscaEspMsgPred = mysql_fetch_assoc($buscaEspMsgPred);
                                $idMensagem = $regbuscaEspMsgPred['id_mensagem'];
                                $buscaMsgPredefinida = mysql_query("SELECT mensagem FROM mensagem WHERE id_mensagem = '$idMensagem'") or die("Erro busca  Mensagem Predefinida") . mysql_error();
                                $regbuscaMsgPredefinida = mysql_fetch_assoc($buscaMsgPredefinida);
                                echo limitaTexto($regbuscaMsgPredefinida['mensagem'], 50) . '...';
                            } else {
                                //escrita
                                $buscaEspMsgNPred = mysql_query("SELECT mensagem FROM esp_ep_msgm_n_pred WHERE id_envio_prog = '$idEnvioProgramado'") or die("Erro busca  id Mensagem Não Predefinida") . mysql_error();
                                $regbuscaEspMsgNPred = mysql_fetch_assoc($buscaEspMsgNPred);
                                echo limitaTexto($regbuscaEspMsgNPred['mensagem'], 50) . '...';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $_SESSION['nome'];
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            if ($registroEnvioProg['situacao'] == 1) {
                                echo 'Agendado';
                            } else if ($registroEnvioProg['situacao'] == 0) {
                                echo 'Cancelado';
                            } else if ($registroEnvioProg['situacao'] == 2) {
                                echo 'Enviado';
                            }
                            ?>
                        </td>
                        <td align="center">
                            <a href="../enviar/reenviar_sms_programado.php?sms=<?php echo $idEnvioProgramado; ?>" target="meio"><img src="../web_tools/img/reenviar.png" title="Reenviar Mensagem"></a>
                            <?php
                            if ($registroEnvioProg['situacao'] == 1) {
                                ?>
                                <a href="../enviar/cancela_envio.php?cancelar=<?php echo $idEnvioProgramado; ?>" onClick="javascript:return confirm('Atenção! Deseja CANCELAR o agendamento?')"><img src="../web_tools/img/inativar.png" title="Cancelar Agendamento"></a>
                                <?php
                            }
                            ?>

                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br>           
            <div class="paginacao">
                <?php
                if ($go_pag != 0) {
                    ?>
                    <a href="mensagem.php?pag_atual=0&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#03C;font-size:12px;">Primeira</a>
                    <?php
                }
                for ($i = 1; $i <= $qtd_pagina; $i++) {
                    $pg = $i - 1;
                    if ($go_pag == $pg) {
                        ?>
                        <a href="mensagem.php?pag_atual=<?php echo $pg; ?>&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#03C;font-size:15px;"><?php echo $i; ?></a>
                        <?php
                    } else {
                        ?>
                        <a href="mensagem.php?pag_atual=<?php echo $pg; ?>&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#666767;"><?php echo $i; ?></a>
                        <?php
                    }
                }
                if ($go_pag != $qtd_pagina - 1) {
                    ?>
                    <a href="mensagem.php?pag_atual=<?php echo $qtd_pagina - 1; ?>&filtro_data_inicial=<?php echo formataData($periodo_inicial); ?>&filtro_data_final=<?php echo formataData($periodo_final); ?>&filtro_usuario=<?php echo $usuario; ?>&filtro_situacao=<?php echo $situacao; ?>" target="meio" style="color:#03C;font-size:12px;">Última</a>
                    <?php
                }
                ?>
            </div>
            <br>
        </div>
        <script language="Javascript" type="text/javascript">
            parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 40; //40: Margem Superior e Inferior, somadas
        </script>
    </body>
    </html>
    <?php
    if (isset($busca_usuario)) {
        mysql_free_result($busca_usuario);
    }
    if (isset($busca_data)) {
        mysql_free_result($busca_data);
    }
    if (isset($buscaMunicipe)) {
        mysql_free_result($buscaMunicipe);
    }
    if (isset($buscaMsgPredefinida)) {
        mysql_free_result($buscaMsgPredefinida);
    }
    if (isset($buscaGrupo)) {
        mysql_free_result($buscaGrupo);
    }
    if (isset($buscaEspMunicipe)) {
        mysql_free_result($buscaEspMunicipe);
    }
    if (isset($buscaEspMsgPred)) {
        mysql_free_result($buscaEspMsgPred);
    }
    if (isset($buscaEspMsgNPred)) {
        mysql_free_result($buscaEspMsgNPred);
    }
    if (isset($buscaEspGrupo)) {
        mysql_free_result($buscaEspGrupo);
    }
    if (isset($buscaEnvioProg2)) {
        mysql_free_result($buscaEnvioProg2);
    }
    if (isset($buscaEnvioProg)) {
        mysql_free_result($buscaEnvioProg);
    }
    if (isset($buscaConfig)) {
        mysql_free_result($buscaConfig);
    }
    mysql_close($con);
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        window.open('../../index.php', '_top');
    </script>
    <?php
}
?>

<!--SELECT envio_prog.id_envio_prog, envio_prog.data_envio, envio_prog.hora_envio, esp_ep_municipe.id_municipe, esp_ep_grupo.id_grupo, municipe.nome, grupo.grupo from envio_prog
INNER JOIN esp_ep_municipe ON(envio_prog.id_envio_prog = esp_ep_municipe.id_envio_prog)
INNER JOIN municipe ON(esp_ep_municipe.id_municipe = municipe.id_municipe)
INNER JOIN esp_ep_grupo on(envio_prog.id_envio_prog = esp_ep_grupo.id_envio_prog)
INNER JOIN grupo ON(esp_ep_grupo.id_grupo = grupo.id_grupo) ORDER BY envio_prog.id_envio_prog DESC;-->
