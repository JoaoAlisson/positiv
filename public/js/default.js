var PAGINA = 0;
var CAMPOORDEM = "";
var ORDENACAO = "ASC";
var FILTROS = "";
function carregando(){
	load = "  <div class=\"ui inverted active dimmer\">"+
  				  "<div class=\"ui text loader\">Carregando...</div>"+
  		   "</div>";
  	$("#conteudo").append(load);	   
}

function graficos(){
	if($('#canvas')){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, { responsive : true	});

		var ctx2 = document.getElementById("chart-area").getContext("2d");
		window.myDoughnut = new Chart(ctx2).Doughnut(doughnutData, {responsive : true});

		var ctx3 = document.getElementById("canvas3").getContext("2d");
		window.myLine = new Chart(ctx3).Line(lineChartData, {responsive: true});

		var ctx4 = document.getElementById("chart-area4").getContext("2d");
		window.myPie = new Chart(ctx4).Pie(pieData);
	}
}

function navegacao(controller, action, menuPincipal){
	//$( "#conteudo" ).load( "http://localhost/mvcPHP/"+controller+"/"+action, { ajaxPg: true }, function() {});
	carregando();
	menuPincipal = menuPincipal || "";

	$(".menuprin.active").removeClass("active");
	$("#menu_"+menuPincipal).addClass("active");

	controller = controller || "";
	action = action || ""; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$( "#conteudo" ).load(caminho, { ajaxPg: true}, function() {
		graficos();
	});
	
	window.history.pushState('Object', 'Positv', caminho);

    //history.pushState(null, null, url);
    PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
}

function setOrdemPag(pagina, campo, ordem){
    PAGINA = pagina;
	CAMPOORDEM = campo;
	ORDENACAO = ordem;
}

function navegacaoSub(controller, action){
	controller = controller || "";
	action = action || ""; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$( "#subconteudo" ).load(caminho, { ajaxPg: true, subClick: true });


	window.history.pushState('Object', 'Teste', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
}

function navPaginacao(controller, action){
	controller = controller || "";
	action = action || ""; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$.post(caminho, { ajaxPg: true, subClick: true, filtro: true }, function(retorno){

		retorno = jQuery.parseJSON(retorno);

		lista = "";
		$.each(retorno.listagem, function(item, campos){
			lista = lista + "<tr>";
			$.each(campos, function(chave, valor){
				lista = lista + "<td>" + valor + "</td>";
			});
			lista = lista + "</tr>";
		});
		$("#totalBusc").html("Total: "+retorno.total);
		$("#listagem").html(lista);
		$("#paginacao").html(retorno.paginacao);
	});


	window.history.pushState('Object', 'Teste', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
}

function paginacao(controller, pag, campo){
	CAMPOORDEM = campo || CAMPOORDEM;
	PAGINA = pag || PAGINA;

	salvCampo = CAMPOORDEM;
	salvPag = PAGINA;

	action = "index";

	ordem = ORDENACAO;
	if(campo == CAMPOORDEM){
		if(ordem == "ASC")
			ordem = "DESC";
		else
			ordem = "ASC";
	}

	actionPassar = action+"/pag:"+PAGINA+"/ordem:"+CAMPOORDEM+"/ordenacao:"+ordem+FILTROS;
	navPaginacao(controller, actionPassar);

	CAMPOORDEM = salvCampo;
	PAGINA = salvPag;
	ORDENACAO = ordem;
}

function filtrar(controller){
	var data = $(".formulario").serializeArray();
	filtrs = "";
	$.each(data, function(chave, valor){
		filtrs = filtrs + "/" + valor.name + ":" + valor.value;
	});
	FILTROS = filtrs;
	paginacao(controller+"/", '1');
}

function submeter(controller, action){

	controller = controller || "";
	action = action || "";

	caminho = URL+controller+action;

	valido = validacao();
	if(valido == true){
		$('.formulario').submit(function(event){

	 		var data = $(".formulario").serialize();
	  		data = data+"&ajaxPg=true";

		    $.post(caminho, data)
		        .done(function(result){
		        	
		        	classeMostrar = "";
		        	imagem = "";
					//alert(result);
		        	retorno = jQuery.parseJSON(result);
		        	
		        	if(retorno.valido == "ok"){
		            	navegacao(controller, action);
		            	classeMostrar = "mensagem_ok";
		            	imagem = "<i class='checkmark sign icon'></i>";
		            }else{
						$(".mensagemErro").remove();
						$(".field.error").removeClass("error");
		            	$.each(retorno.erros, function(campo, erros){
		          			erroValidacaoAoSubmeter(campo, erros);
		          		});

		            	classeMostrar = "mensagem_erro";
		            	imagem = "<i class='warning sign icon'></i>";
		            }
		            $("#"+classeMostrar).html(imagem+retorno.mensagem);
		            $("."+classeMostrar).slideDown();
					setTimeout(function(){
					 	$("."+classeMostrar).slideUp();
					}, 2000);
		    })
		    return false;
		});	

	    $('.formulario').submit();
	}    
}


function validacao(){

	retornar = true;
	$('form > * :input').each(function(){
		campo = $(this).attr('id');
		valido = true;
		if(campo != null)
			valido = validar(campo);

		if(valido == false)
			retornar = false;
	});
	return retornar;
}

function erroValidacaoAoSubmeter(campo, mensagens){
	texto = "";

	$.each(mensagens, function(chave, mensagem){
		if(texto =="")
			texto = "<i class='attention icon'></i>"+mensagem;
		else
			texto = texto + "<br><i class='attention icon'></i>"+mensagem;
	});

	$("#campo_"+campo).addClass("error");
	mensagemErro = "<div class='ui red pointing prompt label transition mensagemErro' id='erro_"+ campo +"' style='display: inline-block;'>"+texto+"</div>";
	$("#campo_"+campo).append(mensagemErro);	

}

function validar(idCampo){

	idCampo = idCampo.split('input_');
	if(idCampo[0] == "")
		idCampo = idCampo[1];
	else
		idCampo = idCampo[0];

	$("#erro_"+idCampo).remove();
	$("#campo_"+idCampo).removeClass("error");

	classe = $('#input_'+idCampo).attr('class');
	if(classe != ""){
		classes = classe.split(' ');

		mensagem = "";

		$.each(classes, function(chave, tipoValidacao){
			var texto =  eval(tipoValidacao+"('"+idCampo+"')");
			if(texto != "") texto = "<i class='attention icon'></i>"+texto; 
			if(mensagem != "")
				mensagem = mensagem + "<br>" + texto;
			else	
				mensagem = mensagem + texto;
		});


		if(mensagem != ""){
			$("#campo_"+idCampo).addClass("error");
			mensagemErro = "<div class='ui red pointing prompt label transition mensagemErro' id='erro_"+ idCampo +"' style='display: inline-block;'>"+mensagem+"</div>";
			$("#campo_"+idCampo).append(mensagemErro);
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}	
}

// -- FUNÇÕES DE VALIDAÇÃO -- //

function calendario(id){
	$("#"+id).mask("00/00/0000", {placeholder: "__/__/____"});
    $("#"+id).datepicker({
		showButtonPanel:true,
		changeMonth: true,
		changeYear: true,
		closeText: 'Fechar',
		prevText: 'Anterior',
		nextText: 'Seguinte',
		showAnim: 'drop',
		currentText: 'Hoje',		
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
   });
    $("#"+id).datepicker("show");
}

function cpfMask(id){
	$('#'+id).mask('000.000.000-00', {reverse: true});
}

function moedaMask(id){
	$('#'+id).mask('000.000.000.000.000,00', {reverse: true});
}

function telefoneMask(id){
	telefone = $("#"+id).val();
	if(telefone.length < 15)
		$('#'+id).mask('(00) 0000-00000');
	else
		$('#'+id).unmask().mask('(00) 00000-0000');
}

function inteiroMask(id){
	valor = $("#"+id).val().replace(/[^0-9]+/g,'');
	$("#"+id).val(valor);
}

function numeroMask(id){
	valor = $("#"+id).val().replace(/[^, 0-9]+/g,'');
	$("#"+id).val(valor);
}

function validarObrigatorio(id){
	if($("#input_"+id).val() == "")
		return "Campo obrigatório.";
	else
		return "";
}

function hasDatepicker(id){
	return "";
}

function validarTeste(id){
	return "";
}

function data(id){
	return "";
}

function textoLongo(id){
	return "";
}