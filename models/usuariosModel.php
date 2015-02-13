<?php 
class usuariosModel extends Model{
 
	public $tipos = array( "nome"  => "nome",
						   "login" => "login",
						   "senha" => "senha",
						   "dono"  => "inteiro");

	public $obrigatorios = array("nome", "login", "senha");

	public $validacoes = array("login"  => "loginValidar");

	public function loginValidar($login){
		$id = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : 0;
		if($this->loginUnico($login, $id))
			return "ok";
		else
			return "Este login já está em uso, informe outro login.";
	}

	public function antesDeCadastrar(&$dados){

		if(isset($dados['senha'])){
			$senha = $dados['senha'];
			$senha = Hash::criar($senha, CHAVE_LOGIN);
			$dados['senha'] = $senha;
		}
	}

	public function antesDeEditar($id, &$dados){
		$this->antesDeCadastrar($dados);
	}

	public function depoisDeEditar($id, &$dados){
		if(!isset($_POST['editar1'])) {
			$this->depoisDeDeletar($id);
			$this->addPermissoes($id);
		}
	}	

	public function depoisDeCadastrar(&$dados){
		$id = $this->lastInsertId();
		$this->addPermissoes($id);

	}

	private function addPermissoes($id){
		$campos = array("igreja", "usuarios", "funcionarios", "programacao", "patrimonio", "financas", "relatorios", "documentos");

		$tabela = PREFIXO."usuariostipos";
		$sql = "INSERT INTO $tabela (id_usuario, tipo) VALUES";
		$primeiro = true;
		foreach ($campos as $key => $campo) {
			if(isset($_POST[$campo])){
				if($primeiro){
					$sql .= " ('$id', '$campo')";
					$primeiro = false;
				}else{
					$sql .= ", ('$id', '$campo')";
				}
			}
		}
		$sth = $this->prepare($sql);
		$sth->execute();
	}

	public function depoisDeDeletar($id){
		$id = (int)$id;
		$tabela = PREFIXO."usuariostipos";			
		$stmt = $this->prepare("DELETE FROM $tabela WHERE id_usuario = '$id'");
		$stmt->execute();

	}

	private function loginUnico($login, $id = 0){
		$tabela = PREFIXO."usuarios";
		mysql_connect(DB_HOST, DB_USER, DB_PASS);
		$login = mysql_real_escape_string($login);
		$id = (int)$id;

		$where = ($id != 0) ? " AND id != '$id'" : "";

		$sql = "SELECT COUNT(*) FROM $tabela WHERE login = '$login' $where";
		$resposta =  $this->query($sql);

		$qtd = $resposta->fetchColumn();

		if($qtd == 0)
			return true;
		else
			return false;
	}

	public function validaSenhaAntiga($id, $senha){
		$senha = Hash::criar($senha, CHAVE_LOGIN);
		$senhaAntiga = $this->pegarSenha($id);
		if($senha == $senhaAntiga)
			return true;
		else
			return false;
	}

	private function pegarSenha($id){

		$tabela = PREFIXO."usuarios";
		$consulta = $this->prepare("SELECT senha FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		return $resultado['senha'];
	}

	private function pegarEmUsuarios($id, $campo){
		$id = (int)$id;
		$tabela = PREFIXO."usuarios";
		$consulta = $this->prepare("SELECT $campo FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		return $resultado[$campo];
	}

	public function pegarDono($id){
		return $this->pegarEmUsuarios($id, "dono");		
	}

	public function pegarODono(){

		$tabela = PREFIXO."usuarios";
		$consulta = $this->prepare("SELECT id FROM $tabela WHERE dono = '1'");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$resultado = $resultado[0];
		return $resultado['id'];
	}

	public function pegarModulos($id){
		$tabela = PREFIXO."usuariostipos";
		$consulta = $this->prepare("SELECT tipo FROM $tabela WHERE id_usuario = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$tipos = array();
		foreach ($resultado as $key => $campo)
			array_push($tipos, $campo['tipo']);

		return $tipos;		
	}
}
?>