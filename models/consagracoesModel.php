<?php 
class consagracoesModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome", 
						   "qtd"		=> "inteiro",
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

	public $limiteDeLinhas = 1000;
}
?>