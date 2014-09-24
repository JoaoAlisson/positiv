<!doctype html>
<html>
<head>
  <title>Positiv</title>
  <link rel="shortcut icon" href="<?php echo URL; ?>public/images/icons/favicon2.ico" />
  <meta charset="utf-8" />
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery1-11.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.mask.min.js"></script>  

	<script type="text/javascript" src="<?php echo URL; ?>public/js/semantic.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/semantic.min.css"/>

 <body>	
	<form action="<?php echo URL;?>login" class="ui form formularioLogin" method="POST">	
	<div class="ui three column center aligned grid">
	    <div class="column">
	    	<h1>Login</h1>
			<div class="ui form segment">
			  <div class="field" style="text-align: left;">
			    <label>Usuário</label>
			    <div class="ui left labeled icon input">

			      <input type="text" class="validarObrigatorio" placeholder="Nome de usuário" name="login" />
			      <i class="user icon"></i>
			      <div class="ui corner label">
			        <i class="icon asterisk"></i>
			      </div>
			    </div>
			  </div>
			  <div class="field" style="text-align: left;">
			    <label>Senha</label>
			    <div class="ui left labeled icon input">
			      <input type="password" class="validarObrigatorio" name="senha" placeholder="********" />
			      <i class="lock icon"></i>
			      <div class="ui corner label">
			        <i class="icon asterisk"></i>
			      </div>
			    </div>
			  </div>
			  <div class="ui error message">
			    <div class="header">We noticed some issues</div>
			  </div>
			  <div class="ui green vertical labeled icon submit button" onclick="$('.formularioLogin').submit();">
			  	<i class="sign in icon"></i>
			  	Logar</div>
			</div>
		</div>
	</div>		
	</form>
<?php // echo md5("joao");?>
</body>
</html>