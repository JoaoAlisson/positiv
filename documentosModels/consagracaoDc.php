<?php
class consagracaoDc extends classePdo {

	public function membros() {
		return $this->pegar( null, array('id', 'nome'), 'membros');
	}

	public function consagracoes() {
		return $this->pegar( null, array('id', 'nome'), 'consagracoes');
	}

	public function membro($id) {
		$id = (int)$id;
		$retorna = $this->pegar($id, array('nome', 'consagracao', 'sexo'), 'membros');

		return $retorna[0];
	}

	public function consagracao($id) {
		if($id == 0 || $id == '')
			return '';
		$consagracao = $this->pegar($id, array('nome'), 'consagracoes');
		return $consagracao[0]['nome'];
	}
}
?>