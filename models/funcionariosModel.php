<?php 
class funcionariosModel extends Model{
 
	public $tipos = array( "nome"      => "nome",
						   "membro"    => array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
						   "cargo"     => array("relacao" => "muitosParaUm", "model" => "cargos", "campo" => "nome"),
						   "salario"   => "moeda",
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "cargo");

}
?>