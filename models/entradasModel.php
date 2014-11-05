<?php 
class entradasModel extends Model{
 
	public $tipos = array( "entrada" => "texto",
						   "categoria" => array("relacao" => "muitosParaUm", "model" => "categorias", "campo" => "nome"),
						   "valor"     => "moeda",
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "categoria", "valor");

}
?>