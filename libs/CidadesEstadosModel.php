<?php 
class CidadesEstadosModel extends Model{

	public function pegarCidades($estado = null){
		$tabela = "cidades";
		$retorna = "";
		if($estado != null)	
			$retorna = $this->pegarOndeGenerico("estado = $estado", array('id', 'cidade'), $tabela);
		else
			$retorna = $this->pegarTodosGenerico($tabela);
				
		return $retorna;
	}

	public function pegarEstados(){
		$tabela = "estados";
		$retorna = $this->pegarTodosGenerico($tabela);
		return $retorna;
	}	
}
?>