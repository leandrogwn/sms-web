﻿<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <head>
        <meta charset = "utf-8">
        <title>Editar Grupo</title>
        <link rel = "stylesheet" href = "../../web_tools/css/reset.css">
        <link rel = "stylesheet" href = "../../web_tools/css/style.css">
        <link rel = "stylesheet" href = "../../web_tools/css/menu.css">
        <script>
            function focus_grupo() {
                document.getElementById('edita_grupo').focus();
            }
        </script>
    </head>
    <body onLoad="focus_grupo()">
        <div id="tamanho" align="center"><br>
            <?php
            include("../../db_tools/conecta_db.php");
            $getEditar = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            $editar = $getEditar['editar'];

            $busca = mysql_query("SELECT * FROM bairro WHERE id_bairro = '$editar' ") or die("Não foi possivel localizar o bairro. " . mysql_error());
            while ($lista = mysql_fetch_assoc($busca)) {
                ?>
                <div align="center">

                    <div id="inc_agenda">
                        <table>
                            <tr>
                                <td>
                                    <div id="tit_send_grupo"><br>Digite um novo nome para o bairro</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form action="editar_bairro_bd.php?cod=<?php echo $editar; ?>" method="post">
                                        <input type="text" name="edita_bairro" required id="edita_bairro" value="<?php echo $lista['bairro'] ?>">
                                        <div id="btn_env_sms_grupo">
                                            <input type="submit" value="Salvar Edição" id="btn_bairro">
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
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