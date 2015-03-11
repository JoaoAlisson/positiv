<?php
Class Inss extends PDO 
{
	function __construct(){
		parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_ADM, DB_USER, DB_PASS);
	}

	public function pegarInss() 
	{
		$tabela = PREFIXO_ADM . 'inss';
		
		$sql = "SELECT id, inicio, fim, taxa FROM $tabela";

		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;	
	}
}
?>