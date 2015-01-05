<?php
Class Grafico
{	
	private $arquivo = "";

	public function criar($entradas, $saidas)
	{
		header('Content-type: png');
		 
		$data = array(
		             array('Janeiro' , $entradas[0] , $saidas[0]), 
		             array('Fevereiro' ,$entradas[1] , $saidas[1]), 
		             array(utf8_decode('Março') , $entradas[2] , $saidas[2]), 
		             array('Abril' , $entradas[3] , $saidas[3]), 
		             array('Maio' , $entradas[4] , $saidas[4]), 
		             array('Junho' , $entradas[5] , $saidas[5]), 
		             array('Julho' , $entradas[6] , $saidas[6]), 
		             array('Agosto' , $entradas[7] , $saidas[7]), 
		             array('Setembro' , $entradas[8] , $saidas[8]),
		             array('Outubro' , $entradas[9] , $saidas[9]),
		             array('Novembro' , $entradas[10] , $saidas[10]),
		             array('Dezembro' , $entradas[11] , $saidas[11])
		        );     
         
		$plot = new PHPlot(750 , 300);     

		$plot->SetPrecisionY(2);

		$plot->SetPlotType("bars");

		$plot->SetDataType("text-data");
		# Adiciona ao gráfico os valores do array
		$plot->SetDataValues($data);
		  
		$plot->SetXTickPos('none');
		# Texto abaixo do eixo X

		$plot->SetXLabelFontSize(2);
		$plot->SetAxisFontSize(5);

		$plot->SetNumberFormat(',', '.');

		$plot->SetDefaultTTFont("pChart/fonts/verdana.ttf");
		$plot->SetDataColors(array('green', 'SkyBlue'));

		$plot->SetYDataLabelPos('plotin');

		$nome = session_id() . '_r' . rand(0, 300);
		$plot->SetIsInline(true);
		$caminho = PASTA_ARQUIVOS . 'imagens' . SEPARADOR . 'graficos'. SEPARADOR . $nome . '.png';
		$plot->SetOutputFile($caminho);

		$plot->DrawGraph();

		$this->arquivo = $caminho;
		return $caminho;
	}

	public function deletarImg()
	{
		unlink($this->arquivo);
	}
}
?>