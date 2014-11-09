<?php
	$arquivo = $dados['img'];
	$nome = $arquivo;
	$caminho = PASTA_ARQUIVOS.SEPARADOR."imagens".SEPARADOR.CONTROLLER.SEPARADOR;
	$caminhoCompleto = $caminho.$arquivo;

	$savename = (basename($caminhoCompleto));

	if(file_exists($caminhoCompleto)){

		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename='.$savename);
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($caminhoCompleto));
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		ob_clean();
		flush();

		readfile($caminhoCompleto);
	}
?>