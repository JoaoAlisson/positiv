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
	CONTROLLER_GLOBAl = controller;
	$('.balao').popup();
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

		var retorno;
		try{
			retorno = jQuery.parseJSON(retorno);
		}
		catch(e){
			$(window.document.location).attr('href', URL);
		}


		lista = "";
		$.each(retorno.listagem, function(item, campos){
			lista = lista + "<tr>";
			$.each(campos, function(chave, valor){
				if(chave != "id")
					lista = lista + "<td>" + valor + "</td>";
			});
			lista = lista + "<td> " +
                      "<div class=\"tiny ui icon button balao\" data-content='Editar' onClick='editarBt("+ campos.id +")'><i class=\"pencil icon\"></i></div>" +
                      "<div class=\"tiny ui red icon button balao\" data-content='Deletar' onClick='deletarBt("+ campos.id +")' style='margin-left:4px;'><i class=\"trash icon\"></i></div>" +
              "</td></tr>";
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

function editarBt(id){
	
	controller = CONTROLLER_GLOBAl;
	action = "editar"; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$( "#subconteudo" ).load(caminho, { ajaxPg: true, subClick: true , idSet: id});


	window.history.pushState('Object', 'Postiv', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";

}

function deletarBt(id){
	mensagemConfirmacao("Deseja realmente deletar esse camarada?");
	$('.small.modal').modal('setting', {
    closable  : false,
    onApprove : function() {
      window.alert('Approved!');
      //aqui vai a parte da deleção
    }
  }).modal('show');
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
					var retorno;
					try{
		        		retorno = jQuery.parseJSON(result);
		        	}
		        	catch(e){
						$(window.document.location).attr('href', URL);
		        	}
		        	
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

function nome(id){
	if($("#input_"+id).val().length <= 2)
		return "O nome deve ter no mínimo 3 caracteres."
	else
		return "";
}

function login(id){
	if($("#input_"+id).val().length <= 2)
		return "O login deve ter no mínimo 3 caracteres."
	else
		return "";
}

function soLetras(id){
	texto = $("#input_"+id).val();
	texto = texto.replace(/[^a-zA-ZãÃáÁàÀêÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçÇºª' ']+/g,'');

	if(texto.length != $("#input_"+id).val().length)
		return "O login só pode conter letras";
	else
		return "";
}

function senha(id){
	if($("#input_"+id).val().length <= 3)
		return "A senha deve ter no mínimo 4 caracteres."
	else
		return "";
}

function mensagemConfirmacao(mensagem){
	$("#msgConfirmacao").html(mensagem);
}

function deslogar(){
	mensagemConfirmacao("Deseja realmente sair do sistema?");
	logout = URL + "login/deslogar"; 
	$('.small.modal').modal('setting', {
		closable  : false,
		onApprove : function() {
		  $(window.document.location).attr('href',logout);
		}
	}).modal('show');	 
}

function limpaNome(id){

	texto = $("#input_"+id).val();
	if(texto.substr(0,1) == " ")
		texto = texto.substring(0, texto.length-1);

	texto = texto.replace(/[^a-zA-ZãÃáÁàÀêÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçÇºª' ']+/g,'');
	texto = texto.replace(/\s{2,}/g, ' ');

	$("#input_"+id).val(texto);
}

function logar(){
	valido = validacao();
	if(valido == true){
		$('.formulario').submit();
	}
}