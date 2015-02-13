<?php 
class ministeriosModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome", 
						   "lider"		=>  array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
 						   "foto"   	=> "imagem",
 						   "qtd"		=> "inteiro",
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

	public $limiteDeLinhas = 1000;


	public function antesDeDeletar($id){

		$tabela = PREFIXO."integrantes";			
		$stmt = $this->prepare("DELETE FROM $tabela WHERE ministerio = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
		$stmt->execute();		
	}

	public function pegaIntegrantes($id){
		$id = (int)$id;
		$tabelaIntegrantes = PREFIXO."integrantes";
		$tabelaMembros = PREFIXO."membros";
		$tabelaFuncoes = PREFIXO."funcoes";

		$consulta = $this->prepare("SELECT `$tabelaMembros`.`nome` AS `membro`, `$tabelaMembros`.`id` AS `idMembro`, `$tabelaFuncoes`.`id` AS `idFuncao`, `$tabelaFuncoes`.`nome` AS `funcao` FROM `$tabelaIntegrantes` LEFT JOIN `$tabelaMembros` ON `$tabelaIntegrantes`.`membro` = `$tabelaMembros`.`id` LEFT JOIN `$tabelaFuncoes` ON `$tabelaIntegrantes`.`funcao` = `$tabelaFuncoes`.`id` WHERE `$tabelaIntegrantes`.`ministerio` = {$id}");
		$consulta->execute();

		return $consulta->fetchAll(PDO::FETCH_ASSOC);	
	}
}
?>