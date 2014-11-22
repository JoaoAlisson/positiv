<?php 
class saidasModel extends Model{
 
	public $tipos = array( "saida"     => "texto",
						   "nota"      => "texto",
						   "categoria" => array("relacao" => "muitosParaUm", "model" => "categorias", "campo" => "nome"),
						   "valor"     => "moeda",
						   "vencimento"=> "data",
						   "pago"	   => array("Efetuado", "Não Efetuado"),
						   "descricao" => "textoLongo");

	public $obrigatorios = array("nome", "categoria", "vencimento", "pago");

}
?>