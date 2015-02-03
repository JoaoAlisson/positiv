<?php
class batismoDc extends classePdo {

	public function membros() {

		$tabela = PREFIXO . "membros";

		$sql = "SELECT id, nome FROM $tabela";

		$query = $this->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function membro($id) {
		$id = (int)$id;
		$retorna = $this->pegar($id, array('nome', 'dataBatismo', 'sexo'), 'membros');
		return $retorna[0];
	}
}
?>