<!doctype html>
<html lang="pt-br">
<head>
	<title>Positiv</title>
  <link rel="shortcut icon" href="<?php echo URL; ?>public/images/icons/favicon2.ico" />
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery1-11.js"></script>
  <!-- <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery-ui.min.js"></script> -->
	<script type="text/javascript" src="<?php echo URL; ?>public/js/default.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/chart.min.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/graficos.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.mask.min.js"></script> 

  <script type="text/javascript" src="<?php echo URL; ?>public/js/picker.js"></script> 
  <script type="text/javascript" src="<?php echo URL; ?>public/js/picker.date.js"></script> 

	<script type="text/javascript" src="<?php echo URL; ?>public/js/semantic.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/semantic.min.css"/>

  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/default.css"/>
  <!-- <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/jquery-ui.min.css"/> -->

  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/classic.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/classic.date.css"/>


  <?php
    if(MODO_DESENVOLVIMENTO)
      echo "<script type=\"text/javascript\" src=\"" . URL . "desenvolvimento/js\"></script>";
  ?>

	
	<script type="text/javascript">
    var URL = <?php echo "'".URL ."'";?>;
    var PAGINACAMINHO = <?php echo "'http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]'";?>;
    var CONTROLLER_GLOBAl = "<?php echo CONTROLLER.'/';?>";

		$(document).ready(function(){
      $('.balao').popup();
      $('.escondeMenu').click(function(){
         $('.ui.sidebar').sidebar("hide");
      });
      
/*
    $( document ).tooltip({
      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        }
      }
    }); 
*/
        $('.ui.sidebar').sidebar('attach events', '.menuLateral');
		});

    window.addEventListener("popstate", function(e) {
    if($(location).attr('href') != PAGINACAMINHO)
       $(window.document.location).attr('href', $(location).attr('href'));
    });
	</script>
	<?php
		if(isset($this->js)){
			foreach ($this->js as $js)
				echo "<script type=\"text/javascript\" src=\"".URL."views/{$js}.js\"></script>";
		}			
	?>
</head>

<body>
<div id="wrap">
<div class="ui large vertical corlateral inverted labeled icon sidebar menu" id="menu">

    <a class="item" onclick="navegacao('','', 'Home');">
      <i class="inverted circular red awesome download home icon"></i> <b>Home</b>
    </a>
    <?php $this->html->menuLateral(); ?>
  </div>

<div class="ui fixed cortopo transparent inverted main menu">
    <div class="container">
      <a class="launch item menuLateral"><i class="icon list layout"></i> Menu</a>
      <div class="title item">
        <b><i class="cloud icon"></i>Positiv </b>| Framework
      </div>
      
       
      <div class="right menu">
        <?php if(MODO_DESENVOLVIMENTO) { ?>  
        <a class="balao icon item esconder" data-content="Atualizar o banco de dados" onclick="atualizarBanco();">     
         <span id="iconeDesenvolvimento">Atualizar Banco <i class="icon refresh"></i></span>
        </a>
        <?php }?>
        <a class="balao icon item" data-content="Deslogar" onclick="deslogar();">
         <div class="esconder" style="float:left;"> <?php echo Sessao::pegar("usuario");?> </div>
          <i class="icon sign out"></i>
        </a>
        <a class="balao icon item esconder" data-content="Imprimir" href="#">
          <i class="icon print"></i>
        </a>      
      </div>
    </div>
  </div>  

<div class="ui center celled aligned grid">
  <div class="ui icon menu">
<?php include 'menuPrincipal.php';?>
  </div>
</div>

<br>

<div class="column mensagem_ok" style="margin:5px;display:none;">
  <div class="ui success message">
    <i class="close icon"></i>
    <div class="header" id="mensagem_ok">
      Produto Cadastrado Com Sucesso!
    </div>
  </div>
</div> 

<div class="column mensagem_erro" style="margin:5px;display:none;">
  <div class="ui error message">
    <i class="close icon"></i>
    <div class="header" id="mensagem_erro">
      O produto não foi cadastrado!
    </div>
  </div>
</div>  

<div id="conteudoConteiner">
<div class="ui segment">
<div id="conteudo">  