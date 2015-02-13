<?php 
class programacaoModel extends Model{
 
	public $tipos = array("nome"     	=> "nome", 
						   "inicio"		=> "data",
						   "fim"		=> "data",	
 						   "telefone"   => "telefone",
 						   "local"		=> "texto",
						   "descricao"	=> "textoLongo");

	public $obrigatorios = array("nome");

	public $limiteDeLinhas = 50000;
}
?>