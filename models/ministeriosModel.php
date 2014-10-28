<?php 
class ministeriosModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome", 
						   "lider"		=> "texto",
						   "integrantes"=> "texto",
 						   "foto"   	=> "imagem",
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

}
?>