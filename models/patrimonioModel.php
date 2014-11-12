<?php 
class patrimonioModel extends Model{
 
	public $tipos = array( "codigo" => "texto",
						   "nome"	=> "nome",
						   "descricao" => "descricao",
						   "ministerio"=> array("relacao" => "muitosParaUm", "model" => "ministerios", "campo" => "nome"),
						   "aquisicao" => "data",
						   "valor"     => "moeda",
						   "quantidade"=> "inteiro",
						   "descricao" => "textoLongo",
						   "situacao"  => array("Disponível", "Em Manutenção", "Doado", "Com Defeito"),
						   "obs" => "textoLongo");

	public $obrigatorios = array("nome");

}
?>