var PAGINA = 0;
var CAMPOORDEM = "";
var ORDENACAO = "ASC";
var FILTROS = "";
var files = {};

function carregando(){
	load = "  <div class=\"ui inverted active dimmer\">"+
  				  "<div class=\"ui text loader\">Carregando...</div>"+
  		   "</div>";
  	$("#conteudo").append(load);
   
}

function evento(event){
	event.preventDefault();
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

function navegacao(controller, action, menuPincipal, id, urlCompleta){

	carregando();
	menuPincipal = menuPincipal || "";
	urlCompleta = urlCompleta || "";
	id = id || "";

	if(menuPincipal != ""){
		$(".menuprin.active").removeClass("active");
		$("#menu_"+menuPincipal).addClass("active");
	}

	controller = controller || "";
	action = action || ""; 

	if(urlCompleta == "")
		caminho = URL+controller+action;
	else
		caminho = urlCompleta;
	PAGINACAMINHO = caminho; 

	$.post(caminho, { ajaxPg: true, idSet: id}, function(retorno) {
		$( "#conteudo" ).html(retorno);
		$('.balao').popup({ on: 'hover' });
		graficos();
	});	

/*
	$( "#conteudo" ).load(caminho, { ajaxPg: true}, function() {
		graficos();
	});
*/
	window.history.pushState('Object', 'Positv', caminho);

    //history.pushState(null, null, url);
    PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
	CONTROLLER_GLOBAl = controller;
	
}

function setOrdemPag(pagina, campo, ordem){
    PAGINA = pagina;
	CAMPOORDEM = campo;
	ORDENACAO = ordem;
}

function navegacaoSub(controller, action, id){

	if(id != ""){
		$(".submenu.active").removeClass("active");
		$("#subMenu_"+id).addClass("active");
	}
	
	files = {};
	controller = controller || "";
	action = action || ""; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 

	$.post(caminho, { ajaxPg: true, subClick: true }, function(retorno){
		$( "#subconteudo" ).html(retorno);
		$('.balao').popup({ on: 'hover' });	
	});
	//$( "#subconteudo" ).load(caminho, { ajaxPg: true, subClick: true });

	window.history.pushState('Object', 'Teste', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
	CONTROLLER_GLOBAl = controller;
	
}

function navPaginacao(controller, action){
	files = {};
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

		$("#listagem").html("");

		lista = "";
		$.each(retorno.listagem, function(item, campos){
			lista = lista + "<tr>";
			$.each(campos, function(chave, valor){
				if(chave != "id")
					lista = lista + "<td>" + valor + "</td>";
			});
			lista = lista + "<td> " +
					  "<div class=\"tiny ui icon button balao \" id=\"btEditar\" data-content='Editar' onClick='verBt("+ campos.id +")'><i class=\"unhide icon\"></i></div>" +
                      "<div class=\"tiny ui icon button balao \" id=\"btEditar\" data-content='Editar' onClick='editarBt("+ campos.id +")' style='margin-left:4px;'><i class=\"pencil icon\"></i></div>" +
                      "<div class=\"tiny ui red icon button balao btDeletar\" data-content='Deletar' onClick='deletarBt("+ campos.id +")' style='margin-left:4px;'><i class=\"trash icon\"></i></div>" +
              "</td></tr>";
		});

		$("#totalBusc").html("Total: "+retorno.total);
		$("#listagem").html(lista);
		$("#paginacao").html(retorno.paginacao);

		$('.balao').popup({ on: 'hover' });
	});


	window.history.pushState('Object', 'Teste', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
}

function editarBt(id, control){

	$('.balao').popup('hide');
	controller = control || CONTROLLER_GLOBAl;
	action = "editar"; 

	caminho = URL+controller+action+"/cod:"+id;
	PAGINACAMINHO = caminho; 

	$.post(caminho, { ajaxPg: true, subClick: true , idSet: id}, function(retorno) {
		$( "#subconteudo" ).html(retorno);
		$('.balao').popup({ on: 'hover' });
		//graficos();
	});

	window.history.pushState('Object', 'Postiv', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";

}

function verBt(id){
	$('.balao').popup('hide');
	controller = CONTROLLER_GLOBAl;
	action = "visualizar"; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$( "#subconteudo" ).load(caminho, { ajaxPg: true, subClick: true , idSet: id});


	window.history.pushState('Object', 'Postiv', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";	
}

function deletarBt(idd, controller, action){
	$('.balao').popup('hide');
	mensagemConfirmacao("Deseja realmente deletar esse camarada?");
	$('.small.modal.canfirmar').modal('setting', {
    closable  : false,
    onApprove : function() {
     	deletar(idd, controller, action);
    }
  }).modal('show');
}

function deletar(idd, controller, action){
    	$('.small.modal.canfirmar').modal("hide");
    	controller = controller || CONTROLLER_GLOBAl;
    	action = action || "deletar";
    	caminho = URL+controller+action;

		$.post(caminho, { model : controller, id : idd }, function(retorno){

			try{
				retorno = jQuery.parseJSON(retorno);
			}
			catch(e){
				$(window.document.location).attr('href', URL);
			}

        	if(retorno.flag == "ok"){
            	//navegacao(controller, action);

				mensagemAlerta(retorno.mensagem);
				setTimeout(function(){
					$('.small.modal.sucesso').modal('show');
				}, 500);            
				caminho = window.location.href;
				navegacao(controller, "", null, null, caminho);
            }else{

				mensagemAlertaErro(retorno.mensagem); 
				setTimeout(function(){
					$('.small.modal.erro').modal('show');
				}, 500);   
            }
			setTimeout(function(){
			 	$('.small.modal.sucesso').modal('hide');
			 	$('.small.modal.erro').modal('hide');
			}, 2500);            			

		});
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

function submeter(controller, action, id, mudarPg){

	controller = controller || "";
	action = action || "";
	id = id || "";

	mudarPg = mudarPg || "";

	caminho = URL+controller+action;

	valido = validacao();
	if(valido == true){

		$('.formulario').submit(function(event){

			event.preventDefault();

			var formularioData = new FormData(this);

			//adiciona as imagems
			$.each(files, function(key, campo){
				if(campo != "" && campo != null)
					formularioData.append(key, campo);
			});

		    $.ajax({
				url: caminho,
				type: 'POST',
				data: formularioData,
				mimeType:"multipart/form-data",
				async: false,
				cache: false,
				contentType: false,
				processData: false,
    			success: function (result) {
		        	
		        	classeMostrar = "";
		        	imagemm = "";
					//alert(result);
					//$(".textoLongo").val(result);
					var retorno;
					try{
		        		retorno = jQuery.parseJSON(result);
		        	}
		        	catch(e){
						$(window.document.location).attr('href', URL);
		        	}
		        	
		        	if(retorno.valido == "ok"){
		        		if(mudarPg == "")
		            		navegacao(controller, action, "", id);
		            	else
		            		list(controller, "listagem", id);
		            	files = {};

						mensagemAlerta("Os dados foram salvos com sucesso!"); 
						$('.small.modal.sucesso').modal('show');

		            }else{
		            
						$(".mensagemErro").remove();
						$(".field.error").removeClass("error");
		            	$.each(retorno.erros, function(campo, erros){
		          			erroValidacaoAoSubmeter(campo, erros);
		          		});

						mensagemAlertaErro("Os dados não foram salvos!"); 
						$('.small.modal.erro').modal('show');
		            }
					setTimeout(function(){
					 	$('.small.modal.sucesso').modal('hide');
					 	$('.small.modal.erro').modal('hide');
					}, 2500);
				}	
		    });
			event.unbind();
		    return false;		    
		});	

	    $('.formulario').submit();
	    files.empty();
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

	campo = idCampo;
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
			var funcao = tipoValidacao+"(\'"+idCampo+"\')";
			var funcaoCompara = "(\'"+ idCampo +"\')";
			if(funcao != funcaoCompara){
				var texto = eval(funcao);
				textoMantem = texto;
				if(texto != "") texto = "<i class='attention icon'></i>"+texto; 
				if(mensagem != ""){
					if(textoMantem != campo)
						mensagem = mensagem + "<br>" + texto;
				}else{	
					if(textoMantem != campo)
						mensagem = mensagem + texto;
				}
			}
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

function cpfMask(id){
	$('#'+id).mask('000.000.000-00', {reverse: true});
}

function moedaMask(id){
	$('#'+id).mask('000.000.000.000.000,00', {reverse: true});
}

function telefoneMask(id){
	telefoneM = $("#"+id).val();
	if(telefoneM.length < 15)
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

function picker__input(id){
	return "";
}

function cpf(id){
	cpff = $("#input_"+id).val();
	cpff = cpff.replace(/[.,-]+/g, "");

	if(funcaoValidarCPF(cpff) || cpff == "")
		return "";
	else
		return "CPF inválido!.";
}

function nome(id){
	if($("#input_"+id).val().length <= 2)
		return "O nome deve ter no mínimo 3 caracteres.";
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

function mensagemAlerta(mensagem){
	$("#msgAlerta").html(mensagem);
}

function mensagemAlertaErro(mensagem){
	$("#msgAlertaErro").html(mensagem);
}

function deslogar(){
	mensagemConfirmacao("Deseja realmente sair do sistema?");
	logout = URL + "login/deslogar"; 
	$('.small.modal.canfirmar').modal('setting', {
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

function prepareUpload(id){

	if($("#input_"+id).val() != null && $("#input_"+id).val() != ""){
		nnome = ($("#input_"+id))[0].files[0].name;
		if(verificarImagem(nnome) && tamanhoImagemOk(id)) //se o arquivo for válido, adiciona as imagens
			files[id] = nnome;
	}else{
		files[id] = "";
	}
}

function verificarImagem(nnome){
	var Extensao = nnome.substring(nnome.lastIndexOf('.') + 1);
	Extensao = Extensao.toLowerCase();
	if(Extensao == "jpg" || Extensao == "jpeg" || Extensao == "png")
		return true;
	else
		return false;
}

function tamanhoImagemOk(id){
	tamanho = ($("#input_"+id))[0].files[0].size;
	if(tamanho <= 3000000)
		return true;
	else
		return false;
}

function imagem(id){
	retorno = "";
	if($("#input_"+id).val() != null && $("#input_"+id).val() != "") {
		nnome = ($("#input_"+id))[0].files[0].name;
		if(!verificarImagem(nnome))
			retorno = "O arquivo selecionado não é uma imagem!";
		if(!tamanhoImagemOk(id))
			retorno = "A imagem pode ter no máximo 3 megas!";
	}
	return retorno;
}

function trocaImgSexo(id){
	if($("#input_"+id).val() == 1){
		$("#masculino_"+id).show();
		$("#feminino_"+id).hide();
	}else{
		if($("#input_"+id).val() == 2){
			$("#feminino_"+id).show();
			$("#masculino_"+id).hide();
		}else{
			$("#feminino_"+id).show();
			$("#masculino_"+id).show();
		}
	}
}

function datapick(id){

	$("#"+id).pickadate({
		selectYears: true,
   		selectMonths: true,
   		format: 'd/mm/yyyy',
   		formatSubmit: 'd/mm/yyyy',
   		labelMonthNext: 'Próximo Mês',
		labelMonthPrev: 'Mês Anterior',
   		monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
   		weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
    	today: 'Hoje',
    	clear: 'Limpar',
    	close: 'Cancelar'
	});
}
/**
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
*/

function funcaoValidarCPF(cpf){
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
          return false;
    for (i = 0; i < cpf.length - 1; i++)
          if (cpf.charAt(i) != cpf.charAt(i + 1))
                {
                digitos_iguais = 0;
                break;
                }
    if (!digitos_iguais)
          {
          numeros = cpf.substring(0,9);
          digitos = cpf.substring(9);
          soma = 0;
          for (i = 10; i > 1; i--)
                soma += numeros.charAt(10 - i) * i;
          resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          if (resultado != digitos.charAt(0))
                return false;
          numeros = cpf.substring(0,10);
          soma = 0;
          for (i = 11; i > 1; i--)
                soma += numeros.charAt(11 - i) * i;
          resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          if (resultado != digitos.charAt(1))
                return false;
          return true;
          }
    else
        return false;		
}

function mudarCidade(campo){
	estadoo = $('#input_'+campo).val();
	caminho = URL+"/estadoscidades/cidades";
	if(estadoo != ""){
		$('#cidadeInput').html("<div class=\"default text\" data-value=\"0\" style=\'min-width:50px;'>&nbsp&nbsp&nbsp...</div>");
		$.post(caminho, { estado : estadoo}, function(retorno){

			try{
				retorno = jQuery.parseJSON(retorno);
			}
			catch(e){
				$(window.document.location).attr('href', URL);
			}

			todasCidades = "<div class=\"item\" data-value=\"\"></div>";
			$.each(retorno, function(id, cidadeVal){
				todasCidades = todasCidades + "<div class=\"item\" data-value=\""+ cidadeVal.id +"\" style=\'min-width:50px;'>"+ cidadeVal.cidade +"</div>";
			});

			$('#cidadeInput').html("");
			$('#cidadeInput').html(todasCidades);
		});
	}else{
		$('#cidadeInput').html("");
	}
}

function enterSubmit(e){
	if(e.which == 13 || e.keyCode == 13)
		$(".submeterForm").click();
}

function registraSelect(id){
	$("#"+id).dropdown();
}

function redir(controller, id){

	action = "/visualizar"; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$( "#subconteudo" ).load(caminho, { ajaxPg: true, subClick: true , idSet: id});

	window.history.pushState('Object', 'Postiv', caminho);

	PAGINA = 1;
	CAMPOORDEM = "";
	ORDENACAO = "ASC";
	CONTROLLER_GLOBAl = controller;
}

function facebook(face){

	facebk = $("#input_"+face).val();
	if(facebk == "" || facebk == null)
		return "";
	urlFace = "https://graph.facebook.com/" + facebk;
	
	result = null;
    $.ajax({
        url: urlFace,
        type: 'get',
        dataType: 'json',
        async: false,
        success: function(data) {
            result = "ok";
        },
  		error: function(XMLHttpRequest, textStatus, errorThrown){
        	result = "erro";
  		}
     });

	if(result == "ok")
		return "";
	else
		return "Esta conta não existe";	
}

function list(controller, action, id){

	controller = controller || "";
	action = action || ""; 

	caminho = URL+controller+action;
	PAGINACAMINHO = caminho; 
	$.post(caminho, { idSet: id }, function(retorno){

		var retorno;
		try{
			retorno = jQuery.parseJSON(retorno);
		}catch(e){
			//$(window.document.location).attr('href', URL);
			alert(retorno);
		}

		$("#listagem").html("");
		$("#qtd").html(retorno.qtd);
		lista = "";
		$.each(retorno.listagem, function(item, campos){
			lista = lista + "<tr>";
			$.each(campos, function(chave, valor){
				if(chave != "id")
					lista = lista + "<td>" + valor + "</td>";
			});
			lista = lista + "<td> " +
					  "<div class=\"tiny ui icon button balao \" id=\"btEditar\" data-content='Editar' onClick='editarBt("+ campos.id +")' style='margin-left:4px;'><i class=\"pencil icon\"></i></div>" +
                      "<div class=\"tiny ui red icon button balao btDeletar\" data-content='Deletar' onClick='deletarBt("+ campos.id +")' style='margin-left:4px;'><i class=\"trash icon\"></i></div>" +
              "</td></tr>";
		});

		//$("#totalBusc").html("Total: "+retorno.total);
		$("#listagem").html(lista);
	});
}

function addItem(controller, id){
	valido = validacao();
	if(valido == true){
		submeter(controller, "cadastrar", id, "naum"); 
			
			
	}
}