<?php 
class CidadesEstadosModel extends Model{

	public function pegarCidades($estado = null){
		$tabela = "cidades";
		$retorna = "";
		if($estado != null || $estado != "")	
			$retorna = $this->pegarOndeGenerico("estado = $estado", array('id', 'cidade'), $tabela);
				
		return $retorna;
	}

	public function pegarEstados(){
		$tabela = "estados";
		$retorna = $this->pegarTodosGenerico($tabela);
		return $retorna;
	}	
}
?>