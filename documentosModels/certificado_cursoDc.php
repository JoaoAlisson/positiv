<?php
class certificado_cursoDc extends classePdo {

	public function membros() {

		$tabela = PREFIXO . "membros";

		$sql = "SELECT id, nome FROM $tabela";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function membro($id) {
		$id = (int)$id;
		$retorna = $this->pegar($id, array('nome'), 'membros');
		return $retorna[0]['nome'];
	}

	public function responsavel($id) {
		$id = (int)$id;
		$responsavel = $this->pegar($id, array('nome', 'consagracao'), 'membros');
		$nome  = $responsavel[0]['nome'];
	
		if($responsavel[0]['consagracao'] != 0) {
			$cargo = $this->pegar($responsavel[0]['consagracao'], array('nome'), 'consagracoes');
			$nome  = ucfirst($cargo[0]['nome']) . ' ' . $nome;
		}

		return $nome;
	}
}
?>