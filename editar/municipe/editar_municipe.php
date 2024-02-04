<?php
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    function validaData($data) {
        $data_v = explode("-", $data);
        $dataOk = $data_v[2] . "/" . $data_v[1] . "/" . $data_v[0];
        return $dataOk;
    }
    ?>
    <!DOCTYPE html>
    <head lang="pt">
        <?php
//        header("Content-Type: text/html; charset=utf-8", true);
//        Header('Cache-Control: no-cache');
//        Header('Pragma: no-cache');
        ?>
        <title>Atualizar</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../../web_tools/js/language_datepickerPT.js"></script>

        <link rel="stylesheet" href="../../web_tools/css/reset.css">
        <link rel="stylesheet" href="../../web_tools/css/style.css">
        <link rel="stylesheet" href="../../web_tools/css/menu.css">
        <script type="text/javascript" src="../../web_tools/js/validamascara.js"></script>
        <script type="text/javascript">
            function addLoadEvent(func) {
                var oldonload = window.onload;
                if (typeof window.onload != 'function') {
                    window.onload = func;
                } else {
                    window.onload = function () {
                        if (oldonload) {
                            oldonload();
                        }
                        func();
                    }
                }
            }

            //mascaras telefones
            jQuery(function ($) {
                $('#fonesms').mask('(99) 9999-9999?9');
                $('#fone1').mask('(99) 9999-9999?9');
                $('#fone2').mask('(99) 9999-9999?9');
            });

            function zera_grupo() {
                document.getElementById('inc_grupo').value = "";
            }

            function disableCheck(check) {
                alert('Atenção! Esse grupo esta inativo e após editado não podera ser vincúlado a este municípe novamente.');
                document.getElementById(check).disabled = true;
            }
        </script>
        <script>
            $(function () {
                $("#data").datepicker({
                    // Consistent format with the HTML5 picker
                    dateFormat: 'dd/mm/yy'
                },
                // Localization
                        $.datepicker.regional['pt']
                        );
            });
        </script>
    </head>
    <body>
        <div id="tamanho" align="center">
            <?php
            include("../../db_tools/conecta_db.php");
            $dadosMunicipe = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            //codigo do cliente a ser editado
            $cod = $dadosMunicipe['editar'];
            $_SESSION['codMunicipe'] = $cod;
            //faz busca na tabela municipe, endereco, bairro, fone e grupo
            $buscaMunicipe = mysql_query("SELECT * FROM municipe WHERE id_municipe = '$cod' ") or die("Não foi possível localizar o munícipe no banco de dados. " . mysql_error());
            $buscaEndereco = mysql_query("SELECT * FROM endereco WHERE id_municipe = '$cod' ") or die("Não foi possível localizar o endereco no banco de dados. " . mysql_error());
            $buscaBairro = mysql_query("SELECT * FROM bairro WHERE id_bairro IN (SELECT id_bairro FROM endereco WHERE id_municipe = '$cod') ") or die("Não foi possível localizar o bairro no banco de dados. " . mysql_error());
            $buscaFone = mysql_query("SELECT * FROM telefone WHERE id_municipe = '$cod' ") or die("Não foi possível localizar o fone no banco de dados. " . mysql_error());
            $buscaGrupo = mysql_query("SELECT * FROM grupo_assoc_municipe WHERE id_municipe = '$cod' ") or die("Não foi possível localizar o grupo no banco de dados. " . mysql_error());

            //recebe informações encontradas
            $listaMunicipe = mysql_fetch_assoc($buscaMunicipe);
            $listaEndereco = mysql_fetch_assoc($buscaEndereco);
            $listaBairro = mysql_fetch_assoc($buscaBairro);
            $listaFone = mysql_fetch_assoc($buscaFone);
            ?>
            <br><br>
            <div id="pop">
                <div align="right">
                    <a href="#" onclick="document.getElementById('pop').style.display = 'none';" style="text-align:right;"><img src="../../web_tools/img/fechar.png"></a></div>
                <div align="center">
                    <div id="tit_cad_grupo">Digite o nome do Grupo que deseja incluir</div>
                    <form action="../../cadastrar/grupo/cad_grupo_bd.php" method="post">
                        <input type="hidden" name="tela" id="tela" value="edicao">
                        <input type="text" name="inc_grupo" required id="inc_grupo"><br>
                        <input type="submit" value="Incluir Grupo" id="btngrupo">
                    </form>
                </div>
            </div>
            <div id="inc_agenda">  
                <table align="center" style="text-align:center;">
                    <tr>
                        <td>
                            <div id="tit_send_grupo"><br>Atualizar informações do munícipe</div>
                        </td>
                    </tr>
                    <tr align="left">
                        <td> 
                            <?php
                            $lista_user = $listaMunicipe['id_admin'];
                            $id_user_sessao = $_SESSION['idUser'];

                            if ($lista_user == $id_user_sessao || $lista_user != $id_user_sessao) {
                                ?>           
                                <form action="editar_municipe_bd.php" method="post"> 
                                    <?php
                                } else {
                                    ?>
                                    <form action="editar_municipe_bd_grupo.php" method="post">
                                        <?php
                                    }
                                    ?>
                                    <script>
                                        $(function () {
                                            $("#data").datepicker({
                                                // Consistent format with the HTML5 picker
                                                dateFormat: 'dd/mm/yy'
                                            },
                                            // Localization
                                                    $.datepicker.regional['pt']
                                                    );
                                        });
                                    </script>
                                    <label id="lbl_nome">Nome Completo:</label> <br>
                                    <input name="nome" type="text" required id="nome" value="<?php echo $listaMunicipe['nome'] ?>"><br>
                                    <label id="lbl_data_nasc">Data de Nascimento:</label><br>
                                    <input type="text" name="data_n" id="data" required value="<?php echo validaData($listaMunicipe['data_nasc']); ?>">&nbsp;
                                    <label id="lbl_email">E-mail:</label><br>
                                    <input type="email" placeholder="E-mail" name="email" id="email" style="text-transform:lowercase!important;" value="<?php echo $listaMunicipe['email']; ?>">
                                    <label id="lbl_bairro">Bairro:</label><br>
                                    <select name="bairro" id="bairro" required>
                                        <option value="<?php echo $listaBairro['id_bairro']; ?>" selected><?php echo $listaBairro['bairro']; ?></option>
                                        <?php
                                        $bairro_atual = $listaBairro['bairro'];
                                        $buscar_bairros = mysql_query("SELECT * FROM bairro WHERE situacao = 1 and bairro <> '$bairro_atual';") or die("Não foi possível carregar os dados da tabela de Bairro" . mysql_error());
                                        while ($registros_bairro = mysql_fetch_assoc($buscar_bairros)) {
                                            ?>
                                            <option value="<?php echo $registros_bairro['id_bairro']; ?>"><?php echo $registros_bairro['bairro']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <label id="lbl_endereco">Endereço:</label>
                                    <input name="rua" type="text" required id="rua" placeholder="Rua" value="<?php echo $listaEndereco['rua']; ?>">
                                    <input name="numero" type="text" required id="numero" placeholder="Número" value="<?php echo $listaEndereco['numero']; ?>">
                                    <br>

                                    <fieldset>
                                        <legend>Selecione os grupos do Contato</legend>
                                        <div id="checkboxes">
                                            <?php
                                            $listaGrupo = mysql_query("SELECT * FROM grupo WHERE id_grupo <> 1 ORDER BY grupo ASC") or die("Não foi possivel carregar os dados da tabela" . mysql_error());
                                            while ($regGrupos = mysql_fetch_assoc($listaGrupo)) {
                                                $idRegGrupo = $regGrupos['id_grupo'];
                                                $buscaAssocGrupo = mysql_query("SELECT * FROM grupo_assoc_municipe WHERE id_municipe = '$cod' AND id_grupo = '$idRegGrupo'") or die("Não foi possível consultar a tabela grupo_assoc_municipe. " . mysql_error());
                                                $qtdRegAssoc = mysql_num_rows($buscaAssocGrupo);
                                                if ($qtdRegAssoc != 0) {
                                                    if ($regGrupos['situacao'] == 0) {
                                                        ?>
                                                        <input type="checkbox" class="check_grupo" id="<?php echo $regGrupos['id_grupo']; ?>" onchange="disableCheck(<?php echo $regGrupos['id_grupo']; ?>)" checked="true" value="<?php echo $regGrupos['id_grupo']; ?>" name="box[]" /><div id="div-grupo" style="color: #999; text-decoration: line-through;"><?php echo $regGrupos['grupo']; ?></div><br>

                                                        <?php
                                                    } else {
                                                        ?>   
                                                        <input type="checkbox" class="check_grupo" id="<?php echo $regGrupos['id_grupo']; ?>"  checked="true" value="<?php echo $regGrupos['id_grupo']; ?>" name="box[]" /><div id="div-grupo"><?php echo $regGrupos['grupo']; ?></div><br>
                                                        <?php
                                                    }
                                                } else {
                                                    if ($regGrupos['situacao'] == 1) {
                                                        ?>  
                                                        <input type="checkbox" class="check_grupo" id="<?php echo $regGrupos['id_grupo']; ?>"  value="<?php echo $regGrupos['id_grupo']; ?>" name="box[]" /><div id="div-grupo"><?php echo $regGrupos['grupo']; ?></div><br>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </fieldset>

                                    <a href="#" onclick="document.getElementById('pop').style.display = 'block';" onMouseDown="zera_grupo();">Adicionar Grupo <img src="../../web_tools/img/add_grupo.png" title="Adicionar novo Grupo"></a>
                                    <br><br>
                                    <fieldset id="f_sexo">
                                        <legend>Sexo</legend>
                                        <?php
                                        if ($listaMunicipe['sexo'] == "m") {
                                            ?>
                                            <input type="radio" name="sexo" id="1" value="m" class="radio" checked><label for="1">Masculino</label><input type="radio" name="sexo" id="0" value="f" class="radio"><label for="0">Femenino</label>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="radio" name="sexo" id="1" value="m" class="radio"><label for="1">Masculino</label><input type="radio" name="sexo" id="0" value="f" class="radio" checked><label for="0">Feminino</label>
                                            <?php
                                        }
                                        ?>
                                    </fieldset><br>
                                    <fieldset>
                                        <legend>Telefones</legend>
                                        <input type="text" placeholder="Fone SMS" name="fone_sms" required id="fonesms" placeholder="Fone SMS" value="<?php echo $listaFone['fone_sms'] ?>">
                                        <input type="text" placeholder="Fone Fixo" name="fone_fixo" id="fone1" value="<?php echo $listaFone['fone_fixo'] ?>" >&nbsp;
                                        <input type="text" placeholder="Fone Recado" name="fone_recado" id="fone2" value="<?php echo $listaFone['fone_recado'] ?>" >
                                    </fieldset>
                                    <div id="btn_env_sms_grupo">
                                        <input type="submit" value="Atualizar" id="btnpublicar">
                                    </div>
                                </form>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
            mysql_free_result($buscaBairro);
            mysql_free_result($buscaEndereco);
            mysql_free_result($buscaFone);
            mysql_free_result($buscaGrupo);
            mysql_free_result($buscaMunicipe);
            mysql_free_result($buscar_bairros);
            mysql_close($con);
            ?>
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