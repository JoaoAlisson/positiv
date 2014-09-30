<?php
	$token = Hash::criar(time(), CHAVE_GERAL);
	Sessao::inserir("token", $token);
?>
<?php if(!isset($_POST['ajaxPg'])) { ?>
<!doctype html>
<html>
<head>
  <title>Positiv</title>
  <link rel="shortcut icon" href="<?php echo URL; ?>public/images/icons/favicon2.ico" />
  <meta charset="utf-8" />
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery1-11.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.mask.min.js"></script>  
  <script type="text/javascript" src="<?php echo URL; ?>public/js/default.js"></script> 

	<script type="text/javascript" src="<?php echo URL; ?>public/js/semantic.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/semantic.min.css"/>

 <body>	

<?php if(isset($dados)) { ?>
	<script type="text/javascript">
		setTimeout(function(){
			$(".mensagem_erro").slideUp();
		}, 2000);
	</script>
	<div class="column mensagem_erro" style="margin:5px;">
	  <div class="ui error message">
	    <i class="close icon"></i>
	    <div class="header" id="mensagem_erro">
	      Verifique seus dados e tente novamente
	    </div>
	  </div>
	</div> 
<?php } ?>

<?php  } ?>
	<form class="ui form formulario" action="<?php echo URL;?>login" method="POST">
	<input name="token" value="<?php echo $token;?>" hidden/>
	<div class="ui three column center aligned grid">
	    <div class="column">
	    	<h1>Login</h1>
			<div class="ui form segment">
			<?php $this->html->campo("login");?>
			<?php $this->html->campo("senha");?>
			  <div class="ui error message">
			    <div class="header">We noticed some issues</div>
			  </div>
			  <div class="ui green vertical labeled icon submit button" onclick="logar();">
			  	<i class="sign in icon"></i>
			  	Logar</div>
			</div>
		</div>
	</div>		
	</form>
<?php if(!isset($_POST['ajaxPg'])) { ?>	
</body>
</html>
<?php  } ?>