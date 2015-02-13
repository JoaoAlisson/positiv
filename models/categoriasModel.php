<?php 
class categoriasModel extends Model{
 
	public $tipos = array( "nome" => "nome", "tipo" => array("Entrada", "Saída", "Entrada/Saída"));

	public $obrigatorios = array("nome");

	public $limiteDeLinhas = 10000;
}
?>