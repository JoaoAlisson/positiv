<?php
	$caminho = RAIZ . SEPARADOR . 'fpdf' . SEPARADOR . 'fpdf.php';
	require($caminho);

	class pdf_recibo_class extends FPDF{
		private $posicaoY = 0;

		public function setPosicaoY($y){
			$this->posicaoY = $y;
		}

		public function SetXY($x, $y){
			$y = $y + $this->posicaoY;
			parent::SetXY($x, $y);
		}
	} 
?>