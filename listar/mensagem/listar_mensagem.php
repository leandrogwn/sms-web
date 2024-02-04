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
            alert('<?php echo $_SESSION['nome']; ?>, você não tem permisão para modificar esse Grupo. Caso necessite de alguma alteração, entre em contato com o(a) usuário(a) ' + nome);
        }
    </script>
    <?php
    include("../../db_tools/conecta_db.php");

    $busca = mysql_query("SELECT * FROM mensagem WHERE situacao = 1 ORDER BY titulo ASC") or die("Não foi possivel realizar a busca de mensagens no banco. " . mysql_error());
    $registro = mysql_num_rows($busca);
    if ($registro != 0) {
        ?>
        <title>Listar mensagens</title>
        <div id="tamanho" align="center"><br><br>
            <div id="send_grupo">
                <table align="center" cellpadding="4px">
                    <tr>
                        <td colspan="2">
                            <div id="tit_send_grupo"><br>Lista de Mensagens</div>
                        </td>
                    </tr>
                    <tr id="topo_tabela">
                        <td>Título</td>
                        <td width="77" align="center">Ações</td>
                    </tr>

                    <?php
                    $cor = 0;
                    while ($lista = mysql_fetch_assoc($busca)) {
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

                            <td style="text-transform:uppercase;"><?php echo $lista['titulo'] ?></td>
                            <td align="center">
                                <?php
                                $idadmin = $lista['id_admin'];
                                if ($_SESSION['idUser'] == $idadmin) {
                                    ?>
                                        <!--<a href="../../editar/mensagem/editar_mensagem.php?editar=<?php //echo $lista['id_mensagem']      ?>" target="meio"><img src="../../web_tools/img/edit.png" /></a>&nbsp;&nbsp;-->
                                    <a href="../../inativar/mensagem/inativar_mensagem_bd.php?inativar=<?php echo $lista['id_mensagem'] ?>" onclick="javascript:return confirm('Atenção! Deseja INATIVAR esta mensagem?')">
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
                                        <img src="../../web_tools/img/inativar.png" /></a></td>

                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <div id="btn_env_sms_grupo">

                </div>
            </div>
        </div>
        <p>
            <?php
        } else {
            ?>
            <script>
                alert("O banco de dados não possui mensagens cadastradas!");
                window.replace("listar_mensagem.php");
            </script>
            <?php
        }
        mysql_free_result($busca);
        mysql_close($con);
        ?>

    </p>
    <p>&nbsp; </p>
    <script language="Javascript" type="text/javascript">
        parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 100; //40: Margem Superior e Inferior, somadas
    </script>
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