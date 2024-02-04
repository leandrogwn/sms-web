<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Alterar Senha</title>
        <link rel="stylesheet" href="../../web_tools/css/reset.css">
        <link rel="stylesheet" href="../../web_tools/css/style.css">
        <link rel="stylesheet" href="../../web_tools/css/menu.css">
        <style type="text/css">
            #tamanho div div h1 {
                font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
            }
            #senha_atual, #nova_senha, #repete_senha{
                text-transform:none!important;
            }
        </style>
        <script>
            function focus_senha() {
                document.getElementById('senha_atual').focus();
            }
        </script>
    </head>
    <body onLoad="focus_senha();">
        <div id="tamanho" align="center"><br><br>
            <script>
                function confere_senha() {
                    var sn = document.getElementById('nova_senha').value;
                    var rs = document.getElementById('repete_senha').value;
                    if (sn != rs) {
                        alert("O campo Nova senha deve ser igual ao campo Repita a nova senha. Digite novamente!");
                        document.getElementById('nova_senha').value = "";
                        document.getElementById('repete_senha').value = "";
                        document.getElementById('nova_senha').focus();
                    }
                }
            </script>
            <div>
                <form action="alterar_senha_bd.php" method="post">
                    <div id="inc_agenda">
                        <table>
                            <tr>
                                <td>
                                    <div id="tit_send_grupo"><br>Alteração de Senha</div>
                                </td>
                            </tr>
                            <tr>
                                <td id="lbl">
                                    Digite a senha atual<br>
                                    <input type="password" name="senha_atual" required id="senha_atual" >
                                    <br>
                                    Digite sua nova senha<br>
                                    <input type="password" name="nova_senha" required id="nova_senha">
                                    <br>
                                    Repita a nova senha<br>
                                    <input type="password" name="repete_senha" required id="repete_senha">
                                    <br>
                                    <div align="center">
                                        <div id="btn_env_sms_grupo">
                                            <input type="submit" value="Salvar Alterações" id="btngrupo_senha" onMouseOver="confere_senha()"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
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