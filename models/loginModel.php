<?php 
class loginModel extends Model{

	public $tipos = array("login" => "login",
						  "senha" => "senha");	

	public $obrigatorios = array("login", "senha");

	public function verificaLogin($login = "", $senha = ""){

		$dados = array("login" => $login, "senha" => $senha);

		$validar = $this->validar($dados);

		if($validar[0] == "ok"){
			$tabela = PREFIXO."usuarios";
			$senha = Hash::criar($senha, CHAVE_LOGIN);
			$sth = $this->prepare("SELECT id, login, nome FROM $tabela WHERE login = :login AND senha = :senha");

			$sth->execute(array(
					':login' => $login,
					':senha' => $senha
				));

			$retorno = $sth->fetchAll();
			if(empty($retorno)){
				$validar[0] = "erro";
				$validar[1] = "inexistente";
			}else{
				$validar[0] = "ok";
				$validar[1] = $retorno[0];
			}
		}

		return $validar;
	}	
}
?>