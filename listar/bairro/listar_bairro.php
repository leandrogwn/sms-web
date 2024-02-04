<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <link rel="stylesheet" href="../../web_tools/css/style_filtro.css" />
    <script>
        function userAdmin(nome) {
            alert('<?php echo $_SESSION['nome']; ?>, você não tem permisão para modificar esse Bairro. Caso necessite de alguma alteração, entre em contato com o(a) usuário(a) ' + nome);
        }
    </script>
    <?php
    include("../../db_tools/conecta_db.php");

    $busca = mysql_query("SELECT * FROM bairro WHERE situacao = 1 ORDER BY bairro ASC") or die("Não foi possivel realizar a busca de bairros no banco de dados" . mysql_error());
    $registro = mysql_num_rows($busca);
    if ($registro != 0) {
        ?>
        <div id="tamanho" align="center"><br><br>
            <div id="send_grupo">
                <table align="center" cellpadding="4px">
                    <tr>
                        <td colspan="2">
                            <div id="tit_send_grupo"><br>Lista de Bairro(s)</div>
                        </td>
                    </tr>
                    <tr id="topo_tabela">
                        <td>Nome do Bairro</td>
                        <td width="77" align="center">Ações</td>
                    </tr>

                    <?php
                    $cor = 0;
                    while ($lista = mysql_fetch_assoc($busca)) {
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
                            <td style="text-transform:uppercase;"><?php echo $lista['bairro'] ?></td>
                            <td  align="center">
                                <?php
                                $idadmin = $lista['id_admin'];
                                if ($_SESSION['idUser'] == $idadmin) {
                                    ?>
                                    <a href="../../editar/bairro/editar_bairro.php?editar=<?php echo $lista['id_bairro'] ?>" target="meio">
                                        <?php
                                    } else {
                                        $buscaUser = mysql_query("select * from admin where id_admin = '$idadmin'")
                                                or die("Erro ao realizar a busca do usuário. " . mysql_error());
                                        $res_bu = mysql_fetch_assoc($buscaUser);
                                        $nome_admin = $res_bu['nome'];
                                        ?>
                                        <a href="#" onClick="userAdmin('<?php echo $nome_admin; ?>')">
                                            <?php
                                        }
                                        ?>
                                            <img src="../../web_tools/img/edit.png" title="Editar" /></a>&nbsp;&nbsp;
                                    <?php
                                    if ($_SESSION['idUser'] == $idadmin) {
                                        ?>
                                        <a href="../../inativar/bairro/inativar_bairro_bd.php?inativar=<?php echo $lista['id_bairro'] ?>" onclick="javascript:return confirm('Atenção!  Deseja INATIVAR este bairro?')">
                                            <?php
                                        } else {
                                            $buscaUser = mysql_query("select * from admin where id_admin = '$idadmin'")
                                                    or die("Erro ao realizar a busca do usuário. " . mysql_error());
                                            $res_bu = mysql_fetch_assoc($buscaUser);
                                            $nome_admin = $res_bu['nome'];
                                            ?>
                                            <a href="#" onClick="userAdmin('<?php echo $nome_admin; ?>')">
                                                <?php
                                            }
                                            ?>
                                                <img src="../../web_tools/img/inativar.png" title="Inativar"/></a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </table>
                                    </div>
                                    </div>
                                    <script language="Javascript" type="text/javascript">
                                        parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 80; //40: Margem Superior e Inferior, somadas
                                    </script>
                                    <?php
                                } else {
                                    ?>
                                    <script>
                                        alert("O banco de dados não possui Bairros cadastrados!");
                                        window.history.back();
                                    </script>
                                    <?php
                                }
                                mysql_free_result($busca);
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