<?php  
	if(MODO_DESENVOLVIMENTO) { 
		header('Content-Type: application/javascript');
?>

var ATUALIZANDOBANCO = false;
function atualizarBanco(){
	if(ATUALIZANDOBANCO == false){
		colocaLoad();
		$.get("<?php echo URL; ?>desenvolvimento/atualizar", function(retorno){
			tiraLoad();
			if(retorno !== "ok")
				alert("Ocorreu um erro durante a atualização");
			else
				alert("O banco foi atualizado com sucesso!");	
		});
	}
}

function colocaLoad(){
	ATUALIZANDOBANCO = true;
	$("#iconeDesenvolvimento").html("Atualizando... <i class='icon loading'></i>");
}

function tiraLoad(){
	ATUALIZANDOBANCO = false;
	$("#iconeDesenvolvimento").html("Atualizar Banco <i class='icon refresh'></i>");
}
<?php } ?>