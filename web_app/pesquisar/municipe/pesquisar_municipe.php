<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    <script type="text/javascript">
        function bloq_nome() {
            document.getElementById('grupo').disabled = false;
            document.getElementById('pesquisar').disabled = true;
        }
        function bloq_grupo() {
            document.getElementById('pesquisar').disabled = false;
            document.getElementById('grupo').disabled = true;
        }
    </script>
    <style>
        #pesquisar{
            width:375px;
            font-size:16px;
            margin:5px 0;
            border-radius:5px;
            border:1px solid #999;
            padding:5px;
        }
        #grupo{
            font-size:16px;
            margin:5px 0;
            border-radius:5px;
            border:1px solid #999;
            padding:5px;
        }
        #btn{
            width:150px;
            font-size:16px;
            margin-left:30px;
            height:40px;
        }
        #filtro_agenda{
            border-style:groove;
            border-radius:8px;
        }
        #lbl{
            font-size:17px;
            font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;
        }
    </style>

    <div align="left" id="filtro_agenda">
        <?php
        include("../../db_tools/conecta_db.php");
        $banco = mysql_query("SELECT * FROM grupo ORDER BY grupo ASC") or die("Não foi possivel carregar grupo" . mysql_error());
        ?>
        <form action="pesquisar_municipe_bd.php" method="post">
            <legend id="lbl">Especifique o nome ou selecione o grupo para realizar a consulta</legend>
            <br>
            <input type="radio" name="filtro" id="proc" value="nome" class="radio" checked onClick="bloq_grupo();"><label for="nome" id="lbl">Nome</label>
            <input type="text"  name="pesquisar" required id="pesquisar" placeholder="Pesquisar" ><br />
            <input type="radio" name="filtro" id="proc" value="grupo" class="radio"  onClick="bloq_nome();"><label for="grupo" id="lbl">Grupo</label>
            <select name="grupo" id="grupo" required style="text-transform:uppercase;" disabled="disabled">

                <option value="f" id="pesquisar">FEMININO</option>
                <option value="m" id="pesquisar">MASCULINO</option>
                <?php
                while ($lista = mysql_fetch_assoc($banco)) {
                    if ($lista['situacao'] == 0) {
                        $cod_gupo = $lista['id_grupo'];
                        $busca_cod_grupo = mysql_query("SELECT * FROM grupo_assoc_municipe WHERE id_grupo = '$cod_gupo'; ") or die("Não foi possivel consultar tabela grupo_assoc_municipe. " . mysql_error());
                        $reg_busca_cod_grupo = mysql_num_rows($busca_cod_grupo);
                        if ($reg_busca_cod_grupo != 0) {
                            ?>
                            <option style="color: #999; text-decoration: line-through;" value="<?php echo $lista['id_grupo'] ?>"><?php echo $lista['grupo'] ?></option>
                            <?php
                        }
                    } else {
                        ?>
                        <option value="<?php echo $lista['id_grupo'] ?>"><?php echo $lista['grupo'] ?></option>
                        <?php
                    }
                }
                ?>
            </select>

            <input type="submit" value="Filtrar" id="btn" >
        </form>
    </div>
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