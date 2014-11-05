<?php 
class cargosModel extends Model{
 
	public $tipos = array( "nome"     	=> "nome", 
						   "salario"	=> "moeda",
						   "descricao"  => "textoLongo");

	public $obrigatorios = array("nome");

}
?>