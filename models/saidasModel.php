<?php 
class saidasModel extends Model{
 
	public $tipos = array( "saida" => "texto",
						   "categoria" => array("relacao" => "muitosParaUm", "model" => "categorias", "campo" => "nome"),
						   "valor"     => "moeda",
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "categoria", "valor");

}
?>