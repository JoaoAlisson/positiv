<?php
	$caminho = RAIZ . SEPARADOR . 'phpqrcode' . SEPARADOR . 'qrlib.php';
	require($caminho);

	class imagensCodigos {

		private $codBarraNome = '';
		private $qrNome  = '';
		private $cod  	 = '';

		public function gerarImagens($cod) {
			
			$this->gerarCod($cod);
			$this->gerarQr($cod);

			$retornar['codNome'] = $this->codBarraNome;
			$retornar['qrNome']  = $this->qrNome;

			return $retornar;
		}

		private function gerarCod($cod) {
			$cod = str_pad($cod, 10, "0", STR_PAD_LEFT);

			$caminho = PASTA_ARQUIVOS . 'imagens' . SEPARADOR . 'patrimonio' . SEPARADOR;
			$nome = $caminho. 'cod' . $this->gerarNome() . '.gif';
			$this->codBarraNome = $nome;
			new codigo_de_barras($cod, 1, $nome, 100, 40);
		}

		private function gerarQr($cod) {
			$url  = URL . 'patrimonio/visualizar/cod:' . $cod;

			$caminho = PASTA_ARQUIVOS . 'imagens' . SEPARADOR . 'patrimonio' . SEPARADOR;
			$nome = $caminho . 'QR' . $this->gerarNome() . '.png';
			$this->qrNome = $nome;
			QRcode::png($url, $nome, QR_ECLEVEL_L);
		}

		public function deletarImagens() {
			$this->deletar($this->codBarraNome);
			$this->deletar($this->qrNome);
		}

		private function gerarNome() {
			$nome = $this->cod . session_id() . '_r' . rand(0, 300);
			return $nome;
		}

		private function deletar($arquivo) {
			unlink($arquivo);
		}	
	}

?>