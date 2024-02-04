<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<html>
    <head>
        <link rel="stylesheet" href="../../web_tools/css/style_filtro.css">
        <script>
            function master() {
                alert('Função habilitada somente para usuário autorizado!');
            }
            function userAdmin(nome, fone) {
                alert('<?php echo $_SESSION['nome']; ?>, você tem permissão para modificar somente os grupos deste municípe. Caso necessite de outra alteração, entre em contato com o(a) usuário(a) ' + nome + ' pelo telefone ' + fone + '.');
            }
        </script>
        <style>
            #tamanho{
                border-style:groove;
                border-radius:8px;
            }
            #res{
                font-size:14px;
                font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;
            }
        </style>
    </head>
    <?php
    include("../../db_tools/conecta_db.php");
    $dadosFiltro = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $pesq = $dadosFiltro["filtro"];

    function validata($data) {
        $str = explode('-', $data);
        $datavalidada = $str[2] . '/' . $str[1] . '/' . $str[0];
        return $datavalidada;
    }

    if ($pesq == "nome") {
        $procura = $dadosFiltro["pesquisar"];
        $busca = mysql_query("SELECT * FROM municipe WHERE nome LIKE '%$procura%' AND situacao = 1 ") or die("Não foi possivel realizar a busca no banco" . mysql_error());
    } else {
        $procura = $dadosFiltro["grupo"];
        if ($procura == "f" || $procura == "m") {
            $busca = mysql_query("SELECT * FROM municipe WHERE sexo LIKE '$procura' AND situacao = 1 ORDER BY nome ASC") or die("Não foi possivel realizar a busca no banco para a entidade sexo" . mysql_error());
        } else if ($procura == 1) {
            $busca = mysql_query("SELECT * FROM municipe WHERE situacao = 1 ORDER BY nome ASC") or die("Não foi possivel realizar a busca de todos os munícipes cadastrados. Erro: " . mysql_error());
        } else {
            $busca = mysql_query("SELECT * FROM municipe WHERE id_municipe in(select id_municipe from grupo_assoc_municipe  WHERE id_grupo LIKE '$procura') AND situacao = 1 ORDER BY nome ASC") or die("Não foi possivel realizar a busca no banco" . mysql_error());
        }
    }
    $registro = mysql_num_rows($busca);
    if ($registro != 0) {
        include("pesquisar_municipe.php");
        ?>
        <body>
            <div id="tamanho">
                <?php
                if ($registro == 1) {
                    ?>
                    <div id="res">&nbsp;Foi encontrado 1 registro para a pesquisa atual.</div><br>
                    <?php
                } else {
                    ?>
                    <div id="res">&nbsp;Foram encontrados <?php echo $registro; ?> registros para a pesquisa atual.</div><br>
                    <?php
                }
                ?>

                <table align="center" cellpadding="4">
                    <tr id="topo_tabela">
                        <td>Nome</td>
                        <td>Data Nasc.</td>
                        <td>E-mail</td>
                        <td>Endereço</td>
                        <td>Fone SMS</td>
                        <td>Fone Fixo</td>
                        <td>Fone Recado</td>
                        <td align="center">Ações</td>
                    </tr>
                    <?php
                    $cor = 0;
                    while ($lista = mysql_fetch_assoc($busca)) {

                        $id_municipe = $lista['id_municipe'];
                        $busca_endereco = mysql_query("SELECT * FROM endereco WHERE id_municipe = '$id_municipe'") or die("Não foi possivel carregar o endereço do munícipe. " . mysql_error());
                        $registroEndereco = mysql_fetch_assoc($busca_endereco);

                        $busca_fone = mysql_query("SELECT * FROM telefone WHERE id_municipe = '$id_municipe'") or die("Não foi possivel carregar o telefone do munícipe. " . mysql_error());
                        $registroTelefone = mysql_fetch_assoc($busca_fone);
                        ?>
                        <?php
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

                            <td id="cols_pesq"><?php echo $lista['nome'] ?></td>
                            <td id="cols_pesq"><?php echo validata($lista['data_nasc']) ?></td>
                            <td id="cols_pesq" style="text-transform: lowercase;"><?php echo $lista['email'] ?></td>
                            <td id="cols_pesq"><?php echo $registroEndereco['rua'] . ', ' . $registroEndereco['numero']; ?></td>
                            <td id="cols_pesq"><?php echo $registroTelefone['fone_sms'] ?></td>
                            <td id="cols_pesq"><?php echo $registroTelefone['fone_fixo'] ?></td>
                            <td id="cols_pesq"><?php echo $registroTelefone['fone_recado'] ?></td>
                            <td>
                                <?php $idadmin = $lista['id_admin']; ?>
                                <?php
                                if ($_SESSION['ic'] == TRUE) {
                                    if ($_SESSION['idUser'] == $idadmin || $_SESSION['idUser'] != $idadmin) {
                                        ?><a href="../../editar/municipe/editar_municipe.php?editar=<?php echo $lista['id_municipe']; ?>"><?php
                                        } else {
                                            $buscaUser = mysql_query("select * from admin where id_admin = '$idadmin'")
                                                    or die("Erro busca Admin. " . mysql_error());
                                            $res_bu = mysql_fetch_assoc($buscaUser);
                                            $nome_admin = $res_bu['nome'];
                                            $fone_admin = $res_bu['fone'];
                                            ?>
                                            <a href="../../editar/municipe/editar_municipe.php?editar=<?php echo $lista ['id_municipe']; ?>" onClick="userAdmin('<?php echo $nome_admin; ?>', '<?php echo $fone_admin; ?>')">
                                                <?php
                                            }
                                        } else {
                                            echo '<a href="#" onClick="master()">';
                                        }
                                        ?><img src="../../web_tools/img/edit.png" title="Editar"></a>&nbsp;&nbsp;
                                    <?php
                                    if ($_SESSION['ic'] == TRUE) {
                                        if ($_SESSION['idUser'] == $idadmin || $_SESSION['idUser'] != $idadmin) {
                                            ?><a href="../../inativar/municipe/inativar_municipe_bd.php?inativar=<?php echo $lista['id_municipe'] ?>" onClick="javascript:return confirm('Atenção! Deseja INATIVAR o munícipe?')">
                                                    <?php
                                                } else {
                                                    $buscaUser = mysql_query("select * from admin where id_admin = '$idadmin'")
                                                            or die("Erro busca Admin. " . mysql_error());
                                                    $res_bu = mysql_fetch_assoc($buscaUser);
                                                    $nome_admin = $res_bu['nome'];
                                                    ?>
                                                <a href="#" onClick="userAdmin('<?php echo $nome_admin; ?>')">
                                                    <?php
                                                }
                                            } else {
                                                echo '<a href="#" onClick="master()">';
                                            }
                                            ?><img src="../../web_tools/img/inativar.png" title="Inativar"></a>&nbsp;&nbsp;
                                        <?php
                                        if ($registroTelefone['fone_sms'] != "") {
                                            if ($_SESSION['es'] == TRUE) {
                                                ?><a href="../../enviar/sms_individual_lista.php?numero=<?php echo $registroTelefone['fone_sms']; ?>&nome=<?php echo $lista['nome']; ?>"><?php
                                                    } else {
                                                        echo '<a href="#" onClick="master()">';
                                                    }
                                                    ?><img src="../../web_tools/img/sms.png" title="Enviar SMS"></a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </table>
                                    <?php
                                } else {
                                    ?>
                                    <script>
                                        alert("Não foi encontrado nenhum registro para o filtro escolhido.");
                                        location.replace("pesquisar_municipe.php");
                                    </script>
                                    <?php
                                }

                                mysql_free_result($busca);
                                mysql_free_result($busca_endereco);
                                mysql_free_result($busca_fone);

                                mysql_close($con);
                                ?>
                                </div>
                                <script language="Javascript" type="text/javascript">
                                    parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 300; //40: Margem Superior e Inferior, somadas
                                </script>
                                </body>
                                </html>