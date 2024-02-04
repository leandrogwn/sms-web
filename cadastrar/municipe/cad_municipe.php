<?php
session_start();
error_reporting(0);
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <head lang="pt">
        <?php
        header("Content-Type: text/html; charset=utf-8", true);
        ?>
        <title>Incluir</title>
        <link rel="stylesheet" href="../../web_tools/css/reset.css">
        <link rel="stylesheet" href="../../web_tools/css/style.css">
        <link rel="stylesheet" href="../../web_tools/css/menu.css">
        <script type="text/javascript" src="../../web_tools/js/jquery-1.4.2.min.js"></script>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../../web_tools/js/language_datepickerPT.js"></script>

        <script language="JavaScript" src="../../web_tools/js/script_global.js"></script>
        <script type="text/javascript" src="../../web_tools/js/validamascara.js"></script>
        <script language="javascript" type="text/javascript" src="../../web_tools/js/mascara.js"></script>
        <script language="javascript" type="text/javascript" src="../../web_tools/js/data.js"></script>
        <?php
        if ($_SESSION['reload'] == 1) {
            $_SESSION['reload'] = 2;
            ?>
            <script> window.location.reload(true);</script>
        <?php } ?> 
        <script language="javascript" type="text/javascript" src="../../web_tools/js/sessionStorangeMunicipe.js"></script>
    </head>
    <body bgcolor="#FFFFFF" onload="carregaSessao();" onpageshow="zeraSessao();">
        <script>
            $(function () {
                $("#data").datepicker({
                    dateFormat: 'dd/mm/yy'
                },
                        $.datepicker.regional['pt']
                        );
            });
        </script>
        <div id="tamanho" align="center"><br>
            <?php
            include("../../db_tools/conecta_db.php");
            ?>
            <div id="pop">
                <div align="right">
                    <a href="#" onclick="document.getElementById('pop').style.display = 'none';" style="text-align:right;"><img src="../../web_tools/img/fechar.png"></a></div>
                <br />
                <div align="center">
                    <div id="tit_cad_grupo">Digite o nome do Grupo que deseja incluir</div>
                    <form action="../grupo/cad_grupo_bd.php" method="post">
                        <input type="hidden" id="tela" name="tela" value="municipe">
                        <input type="text" name="inc_grupo" required id="inc_grupo"><br>
                        <input type="submit" value="Incluir Grupo" id="btngrupo">
                    </form>
                </div>
            </div>
            <div id="inc_agenda">
                <table align="center" style="text-align:center;" bgcolor="#FFFFFF">
                    <tr>
                        <td>
                            <div id="tit_send_grupo"><br>Cadastrar novo munícipe</div>
                        </td>
                    </tr>               
                    <tr align="left">
                        <td>
                            <form action="cad_municipe_bd.php" method="post">
                                <label id="lbl_nome">Nome Completo:</label> <br>
                                <input type="text" placeholder="Nome Completo" name="nome" required id="nome" autofocus><br>
                                <label id="lbl_data_nasc">Data de Nascimento:</label><br>
                                <input type="text" placeholder="EX: 01/01/1999"  name="data_n" id="data" required>&nbsp;
                                <label id="lbl_email">E-mail:</label><br>
                                <input type="email" placeholder="E-mail" name="email" id="email" style="text-transform:lowercase!important;">
                                <label id="lbl_bairro">Bairro:</label><br>
                                <select name="bairro" id="bairro" required>
                                    <?php
                                    $buscar_bairro = mysql_query("SELECT * FROM bairro WHERE situacao = 1;") or die("Nâo foi possível carregar os dados da tabela de Bairro" . mysql_error());
                                    while ($registro_bairro = mysql_fetch_assoc($buscar_bairro)) {
                                        ?>
                                        <option value="<?php echo $registro_bairro['id_bairro']; ?>"><?php echo $registro_bairro['bairro']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <label id="lbl_endereco">Endereço:</label>
                                <input name="rua" type="text" required id="rua" placeholder="Rua" >
                                <input name="numero" type="text" required id="numero" placeholder="Número" >
                                <br>
                                <fieldset>
                                    <legend>Selecione os grupos do Contato</legend>
                                    <div id="checkboxes">
                                        <?php
                                        $listaGrupo = mysql_query("SELECT * FROM grupo WHERE situacao = 1 AND id_grupo <> 1 ORDER BY grupo ASC") or die("Não foi possivel carregar os dados da tabela" . mysql_error());
                                        while ($regGrupos = mysql_fetch_array($listaGrupo)) {
                                            ?>
                                            <input type="checkbox" class="check_grupo" value="<?php echo $regGrupos['id_grupo']; ?>" name="box[]" /><div id="div-grupo"><?php echo $regGrupos['grupo']; ?></div><br>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </fieldset>
                                <a href="#" onclick="salvaDados()">Adicionar Grupo <img src="../../web_tools/img/add_grupo.png" title="Adicionar novo Grupo"></a>
                                <br><br>
                                <fieldset id="f_sexo">
                                    <legend>Sexo</legend>
                                    <input type="radio" name="sexo" id="1" value="m" class="radio" checked><label for="1">Masculino</label><input type="radio" name="sexo" id="0" value="f" class="radio"><label for="0">Feminino</label>
                                </fieldset>
                                <fieldset>
                                    <legend>Telefones</legend>
                                    <input type="text" placeholder="Fone SMS" name="fone_sms" id="fonesms" required>
                                    <input type="text" placeholder="Fone Fixo" name="fone_fixo" id="fone1">
                                    <input type="text" placeholder="Fone Recado" name="fone_recado" id="fone2">
                                </fieldset>
                                <div id="btn_env_sms_grupo">
                                    <input type="submit" value="Cadastrar" id="btnpublicar" onMouseOver="verifica_data();">
                                </div>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
            mysql_free_result($buscar_bairro);
            mysql_free_result($listaGrupo);
            mysql_close($con);
            ?>
        </div>
        <script language="Javascript" type="text/javascript">
            parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 60; //40: Margem Superior e Inferior, somadas
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