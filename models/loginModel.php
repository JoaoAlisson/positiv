<?php 
class loginModel extends Model {

	public $tipos = array('login' => 'login',
						  'senha' => 'senha');	

	public $obrigatorios = array('login', 'senha');

	public function verificaLogin($login = '', $senha = '') {

		$dados = array('login' => $login, 'senha' => $senha);

		$validar;
		
		if(!$this->novoConecta())
			$validar[0] = 'erro';
		else
			$validar = $this->validar($dados);

		if($validar[0] == 'ok'){
			$tabela = PREFIXO . 'usuarios';
			$senha = Hash::criar($senha, CHAVE_LOGIN);
			$sth = $this->prepare("SELECT id, login, nome, dono FROM $tabela WHERE login = :login AND senha = :senha");

			$sth->execute(array(
					':login' => $login,
					':senha' => $senha
				));

			$retorno = $sth->fetchAll();
			if(empty($retorno)){
				$validar[0] = 'erro';
				$validar[1] = 'inexistente';
			}else{
				$validar[0] = 'ok';
				$validar[1] = $retorno[0];
			}
		}

		return $validar;
	}	

	private function novoConecta() {

		$retorna;
		mysql_connect(DB_HOST, DB_USER, DB_PASS);
		$coneccao = mysql_select_db(DB_NAME);
		if($coneccao)
			$retorna = true;
		else
			$retorno = false;
				
		return $retorna;
	}


	public function ativo() {
		$sql = 'SELECT ativo FROM ' . PREFIXO . 'informacoes WHERE id = 1';
		$sth = $this->prepare($sql);
		$sth->execute();
		$retorna = $sth->fetchAll();
		return $retorna[0]['ativo'];
	}
}
?>