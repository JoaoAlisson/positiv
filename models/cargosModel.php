<?php 
class cargosModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome",
						   "qtd"		=> "inteiro",
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

	public $limiteDeLinhas = 10000;
}
?>