<?php
class visitasModel extends Model{
	public $tipos = array("visitante" => array("relacao" => "muitosParaUm", "model" => "visitantes", "campo" => "nome"),
						  "data"	  => "data");

	public $obrigatorios = array("visitante", "data");

	//public $validacoes = array("membro"  => "validarMembro");

	public function informacoesVisitante($idVisitante){

		$idVisitante = (int)$idVisitante;
		$tabelaVisitantes = PREFIXO."visitantes";

		$consulta = $this->prepare("SELECT `$tabelaVisitantes`.`nome` FROM `$tabelaVisitantes` WHERE `$tabelaVisitantes`.`id` = {$idVisitante}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		return $resultado[0];
	}	

	public $limiteDeLinhas = 50000;
}?>