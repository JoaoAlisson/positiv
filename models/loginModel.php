<?php 
class LoginModel extends Model{
	
	public function verificaLogin($login = "", $senha = ""){

		$tabela = PREFIXO."usuarios";
		$sth = $this->prepare("SELECT id, login FROM $tabela WHERE login = :login AND senha = MD5(:senha)");

		$sth->execute(array(
				':login' => $login,
				':senha' => $senha
			));

		$retorno = $sth->fetchAll();
		if(empty($retorno))
			return 0;
		else
			return $retorno[0];
	}	
}
?>