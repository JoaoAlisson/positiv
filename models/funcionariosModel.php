<?php 
class funcionariosModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome", 
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

}
?>