<?php 
class folhasMd extends classePdo{

	public function pegarFolhas($ano){

		require(RAIZ . SEPARADOR . 'models' . SEPARADOR . 'folhasModel.php');
		$model = new folhasModel();

		$folhas   = $this->folhasAno($ano, $model);
		$folhasRt = array();

		foreach ($folhas as $folha)
		{	
			$dados = $model->informacoesFolha($folha['id']);
			$dados['mes'] = $folha['mes'];
			array_push($folhasRt, $dados);
		}
		return $folhasRt;
	}

	private function folhasAno($ano, &$model)
	{	
		$tabela	= PREFIXO."folhas";
		$sql = "SELECT `$tabela`.`id`, `$tabela`.`mes` FROM `$tabela` WHERE `$tabela`.`ano` = '$ano' ORDER BY `$tabela`.`mes` ASC";

		$query = $model->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);		
	}
}
?>