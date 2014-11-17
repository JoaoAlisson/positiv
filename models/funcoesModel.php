<?php 
class funcoesModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome",
						   "qtd"		=> "inteiro",
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

}
?>