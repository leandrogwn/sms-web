<?php
error_reporting(0);
session_start();

class VerificarLogin {

    private $usuario;
    private $senha;
    private $registro;
    private $sessao;

    public function __construct() {
        
    }

    public function verifiLogin($usuario, $senha) {

        $this->usuario = $usuario;
        $this->senha = $senha;

        if ($this->usuario === "" || $this->senha === "") {

            echo "<br><br><center><h2>Os campos login e senha não podem ter valores nulos</2></center>";
            echo "<br><br><center><a href=\"principal.php\">Clique aqui para tentar novamente</a></center>";
        } else {

            include '../db_tools/conecta_db.php';

            $validaLogin = mysql_query("SELECT * FROM admin WHERE login = '$this->usuario'")
                    or die("<br>Não foi possivel realizar a busca. Erro: " . mysql_error());

            while ($this->registro = mysql_fetch_assoc($validaLogin)) {

                $login_db = $this->registro["login"];
                $senha_db = $this->registro["senha"];
                $this->sessao = md5(date("d/m/Y"));
                

                if ($login_db === $this->usuario && $senha_db === $this->senha || $_SESSION['logado'] === $this->sessao) {

                    $this->habilitarSessao();
                }
            }
        }
    }

    private function habilitarSessao() {
        $_SESSION['idUser'] = $this->registro["id_admin"];
        $sessaoAtual = $this->registro["id_admin"];
        $_SESSION['nome'] = $this->registro["nome"];

        $buscaLogin = mysql_query("SELECT * FROM permissao WHERE id_permissao = '$sessaoAtual'")
                or die("<br>Não foi possível realizar a busca de sessão do usuário. Erro: " . mysql_errno());

        $permissao = mysql_fetch_assoc($buscaLogin);

        $_SESSION['ic'] = $permissao["incluir_contato"];
        $_SESSION['ig'] = $permissao["incluir_grupo"];
        $_SESSION['im'] = $permissao["incluir_sms"];
        $_SESSION['c'] = $permissao["alterar_config"];
        $_SESSION['es'] = $permissao["enviar_sms"];
        $_SESSION['master'] = $permissao["master"];

        $_SESSION['logado'] = md5(date("d/m/Y"));
    }

}
