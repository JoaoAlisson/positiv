<?php
class integrantesModel extends Model{
	public $tipos = array( "ministerio"	=> array("relacao" => "muitosParaUm", "model" => "ministerios", "campo" => "nome"),
						   "membro"	    => array("relacao" => "muitosParaUm", "model" => "membros", "campo" => "nome"),
						   "funcao"		=> array("relacao" => "muitosParaUm", "model" => "funcoes", "campo" => "nome"));

	public $obrigatorios = array("ministerio", "membro");

	public $validacoes = array("membro"  => "validarMembro");

	public function validarMembro($valor){
		$valor = (int)$valor;
		$idMinisterio = $this->dados['ministerio'];
		$tabela = PREFIXO."integrantes";

		$consulta = $this->prepare("SELECT COUNT(*) AS quantidade FROM $tabela WHERE membro = {$valor} AND ministerio = {$idMinisterio}");
		$consulta->execute();
		$quantidade = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$quantidade = $quantidade[0]['quantidade'];

		if($quantidade == 0)
			return "ok";
		else
			return "Este membro já pertence ao ministério";
	}

	public function informacoesMinisterio($idMinisterio){

		$idMinisterio = (int)$idMinisterio;
		$tabelaMinisterios = PREFIXO."ministerios";
		$tabelaMembros = PREFIXO."membros";

		$consulta = $this->prepare("SELECT `$tabelaMembros`.`id` AS `idLider`, `$tabelaMembros`.`nome` AS `lider`, `$tabelaMinisterios`.`nome`, `$tabelaMinisterios`.`foto`, `$tabelaMinisterios`.`qtd` FROM `$tabelaMinisterios` LEFT JOIN `$tabelaMembros` ON `$tabelaMinisterios`.`lider` = `$tabelaMembros`.`id` WHERE `$tabelaMinisterios`.`id` = {$idMinisterio}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		return $resultado[0];
	}

	public function informacoesIntegrante($id){
		$id = (int)$id;
		$tabelaIntegrantes = PREFIXO."integrantes";
		$tabelaMinisterios = PREFIXO."ministerios";
		$tabelaMembros = PREFIXO."membros";
		$tabelaFuncoes = PREFIXO."funcoes";

		$consulta = $this->prepare("SELECT `$tabelaIntegrantes`.`id`, `$tabelaMembros`.`id` AS `idMembro`, `$tabelaMembros`.`nome` AS `nomeMembro`, `$tabelaMinisterios`.`nome` AS `nomeMinisterio`, `$tabelaMinisterios`.`id` AS `idMinisterio`, `$tabelaFuncoes`.`id` AS `idFuncao`, `$tabelaFuncoes`.`nome` AS `nomeFuncao` FROM `$tabelaIntegrantes` LEFT JOIN `$tabelaMembros` ON `$tabelaIntegrantes`.`membro` = `$tabelaMembros`.`id` LEFT JOIN `$tabelaMinisterios` ON `$tabelaIntegrantes`.`ministerio` = `$tabelaMinisterios`.`id` LEFT JOIN `$tabelaFuncoes` ON `$tabelaIntegrantes`.`funcao` = `$tabelaFuncoes`.`id` WHERE `$tabelaIntegrantes`.`id` = {$id}");
		$consulta->execute();

		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		return $resultado[0];
	}

	public function depoisDeCadastrar($dados){
		$id = $dados['ministerio'];
		if($id != "" && $id != "0"){
			$tabela = PREFIXO."ministerios";
			$sth = $this->prepare("UPDATE $tabela SET qtd = qtd + 1 WHERE id = $id");
			$sth->execute();
		}
	}

	public function antesDeDeletar($id){

		$tabela = PREFIXO."integrantes";
		$consulta = $this->prepare("SELECT ministerio FROM $tabela WHERE id = {$id}");
		$consulta->execute();

		$idMinisterio = $consulta->fetchAll(PDO::FETCH_ASSOC);
		$idMinisterio = $idMinisterio[0];
		$idMinisterio = $idMinisterio['ministerio'];

		$tabela = PREFIXO."ministerios";
		$sth = $this->prepare("UPDATE $tabela SET qtd = qtd - 1 WHERE id = $idMinisterio");
		$sth->execute();		
	}
}?>