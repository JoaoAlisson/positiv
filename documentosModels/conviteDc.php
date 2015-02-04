<?php
class conviteDc extends classePdo {

	public function eventos() {

		$tabela = PREFIXO . "programacao";

		$sql = "SELECT id, nome FROM $tabela ORDER BY id DESC";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function evento($id) {
		$id = (int)$id;
		$retorna = $this->pegar($id, array('nome', 'inicio', 'fim', 'local', 'descricao'), 'programacao');
		return $retorna[0];
	}
}
?>