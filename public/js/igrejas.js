var GERANDOFOLHA = false;
function filtrarFolha(){
	ano = $('#input_ano').val();
	action = '/index/ano:' + ano,
	navegacaoSub('folhas/', action, null);
}

function selecionarFunc(){
	id = $("#input_funcionario").val();
	idFolha = $("#idFolha").val();
	controller = 'folhas/';
	action = 'editar/cod:'+ idFolha +'/fun:'+id;

	if(id != "")
		navegacaoSub(controller, action);
}

function atualizar(mes, folha){

	if(GERANDOFOLHA == false){
		GERANDOFOLHA = true;

		$('.balao').popup('hide');
		mensagemConfirmacao("Ao atualizar a folha, o valor total pode ser alterado.<br> Tem certeza que deseja atualizar?");
		$('.small.modal.canfirmar').modal('setting', {
	    closable  : false,
	    onApprove : function() {
	    	colocaAtualizando(mes);
			gerarOk(folha, 'atualizar');
		}
		}).modal('show');

		GERANDOFOLHA = false;
	}
}

function gerar(mes){

	ano = $('#ano').val();
	anoAtual = $('#anoAtual').val();
	mesAtual = $('#mesAtual').val();

	if(GERANDOFOLHA == false){
		if(ano == anoAtual && mes == mesAtual){
			colocaGerando(mes);
			gerarOk(mes);
		}else{
			
			$('.balao').popup('hide');
			mensagemConfirmacao("Você está tentando gerar a folha de um mês que não é o atual.<br> Tem certeza que deseja continuar?");
			$('.small.modal.canfirmar').modal('setting', {
		    closable  : false,
		    onApprove : function() {
		    	colocaGerando(mes);
				gerarOk(mes);
			}
			}).modal('show');
		}
	}	
	//GERANDOFOLHA = false;
}

function gerarOk(mes, action){
		ano = $('#ano').val();
    	$('.small.modal.canfirmar').modal("hide");
    	controller = 'folhas/';
    	action = action || 'gerar';
    	caminho = URL+controller+action;

		$.post(caminho, { mesSet : mes , anoSet : ano }, function(retorno){
			
			try{
				retorno = jQuery.parseJSON(retorno);
			}
			catch(e){
				$(window.document.location).attr('href', URL);
			}

        	if(retorno.flag == "ok"){
				mensagemAlerta(retorno.mensagem);
				setTimeout(function(){
					$('.small.modal.sucesso').modal('show');
				}, 600);            
				caminho = window.location.href;
				navegacao(controller, "", null, null, caminho);
            }else{

				mensagemAlertaErro(retorno.mensagem); 
				setTimeout(function(){
					$('.small.modal.erro').modal('show');
				}, 600);   
            }
			setTimeout(function(){
			 	$('.small.modal.sucesso').modal('hide');
			 	$('.small.modal.erro').modal('hide');
			}, 2500);            			
			GERANDOFOLHA = false;
		});
}

function colocaGerando(id){
	GERANDOFOLHA = true;
	$("#gerar_"+id).html("Gerando Folha... <i class='spinner loading icon'></i>");
}

function colocaAtualizando(id){
	GERANDOFOLHA = true;
	$("#gerar_"+id).html("Atualizando ... <i class='spinner loading icon'></i>");	
}

function mostrarDtDemissao(id){
	input = '';
	if(id == 1)
		input = '#input_situacao';
	else
		input = '#input_situacao2';
	if($(input).val() == "Demitido")
		$("#demissaoId"+id).slideDown();
	else
		$("#demissaoId"+id).slideUp();
}

function recibo(){
	id = $("#input_funcionario").val();
	idFolha = $("#idFolha").val();
	controller = 'folhas/';
	if(id != "-1")
		action = 'recibo/cod:'+ idFolha +'/func:'+id;
	else
		action = 'recibo/cod:'+ idFolha;

	link = URL + controller + action;
	$("#reciboLink").attr("href", link);

	jQuery('#reciboLink')[0].click();
	action = 'visualizar/cod:'+idFolha;
	navegacaoSub('folhas/', action);
}

function permicaoTodos(){

	if($("#campoTodos").prop("checked"))
		$(".modulos").prop("checked", false);
	else
		$(".modulos").prop("checked", true);
}

function moduloClick(){
	$("#campoTodos").prop("checked", false);
}

function submtRelatorio(){
	$("#formRelatorio").submit();
}

function submtRelatPagamFunc(){
	if($("#input_funcionario").val() != "")
		$("#formRelatorio").submit();
}

function submtRelatSaidEntCateg(){
	if($("#input_categoria").val() != "")
		$("#formRelatorio").submit();
}

function subDizmPmemb(){
	if($("#input_membro").val() != "")
		$("#formRelatorio").submit();	
}