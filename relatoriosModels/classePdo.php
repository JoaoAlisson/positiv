<?php 
class classePdo extends PDO
{

	private $conectado = false;
	function __construct(){	}

	private function conecta()
	{
		if(!$this->conectado)
		{
			parent::__construct(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);	
			$this->conectado = true;
		}
	}

	public function query($sql)
	{
		$this->conecta();
		return parent::query($sql);
	}

	public function prepare($sql)
	{
		$this->conecta();
		return parent::prepare($sql);
	}

	public function pegar($id = 0, $campos = null, $tabela = null)
	{

		if($campos == null)
			$campos = "*";
		elseif($campos != "*")
			$campos = implode(", ", $campos);

		$tabela = PREFIXO."$tabela";
		$where = '';
		if($id != 0) {
			$id = (int)$id;
			$where = ($id != 0) ? "WHERE id = '".$id."'" : "";
		}
		
		$sql = "SELECT $campos FROM $tabela $where";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);

	}

	public function nomeIgreja()
	{
		$igreja = $this->pegar(1,  array('nome'), 'informacoes');
		return $igreja[0]['nome'];
	}

	public function infIgreja()
	{
		$igreja = $this->pegar(1,  array('nome', 'email', 'telefone'), 'informacoes');
		return $igreja[0];
	}	
}
?>