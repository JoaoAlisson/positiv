<?php 
class dizimos_ofertasModel extends Model{
 
	public $tipos = array("tipo"   => array("Dízimo", "Oferta"),
						  "nome"   => "nome", 
						  "membro" => array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
						  "data"   => "data",
						  "valor"  => "moeda",					   
						  "obs"    => "textoLongo");

	public $obrigatorios = array("tipo");
}
?>