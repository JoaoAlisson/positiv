<?php
	include(RAIZ . "/pChart/class/pDraw.class.php");
	include(RAIZ . "/pChart/class/pData.class.php");
	include(RAIZ . "/pChart/class/pImage.class.php");

Class Grafico
{
	public function criar()
	{
		header('Content-type: png');
		 
		$data = new pData();  
		$data->addPoints( array( 200 , 1500 , 900 , 2030 , 800 , 790 , 980 , 1436 , 210.5 , 2500 ) , "Valores A" );
		$data->addPoints( array( "Janeiro" , "Fevereiro" , "Março" , "Abril" , "Maio" , "Junho" , "Julho" , "Agosto" , "Setembro" , "Outubro" ) , "Meses" ); 
		  
		$data->setAbscissa("Meses");
		$data->setAxisName(0,"Hits");
		 
		$myPicture = new pImage( 700 , 400 , $data );
		$myPicture->drawRectangle( 0 , 0 , 699 , 229 , array( "R"=>0 , "G"=>0 , "B"=>0 ) );
		 
		$myPicture->setFontProperties( array( "FontName"=>"pChart/fonts/verdana.ttf" , "FontSize"=>10 ) );
		 
		$myPicture->setGraphArea(60,40,650,200);
		 
		$scaleSettings = array( "GridR"=>200 , "GridG"=>200 , "GridB"=>200 , "CycleBackground"=>TRUE ); 
		$myPicture->drawScale($scaleSettings);
		 
		$myPicture->drawLegend( 580 , 12 , array( "Style"=>LEGEND_NOBORDER , "Mode"=>LEGEND_HORIZONTAL ) );
		 
		$myPicture->drawBarChart();
		 
		$caminho = PASTA_ARQUIVOS . 'imagens' . SEPARADOR . 'graficos'. SEPARADOR . 'grafico.png';
		$myPicture->render($caminho);

		return $caminho;
	}
}
?>