<div style="width:100%; text-align:center; text-transform: uppercase;"><h2><?php if($dados['icone'] != "") echo "<i class=\"".$dados['icone']." icon\"></i>"; ?><?php echo $dados['nome'][0];?></h2>
  <?php 
    $nomeSingular = "";
    if(isset($dados['nome'][0]))
      $nomeSingular = $dados['nome'][0];
    else
      $nomeSingular = $dados['nomeController'];
  ?>
  <div class="ui <?php echo $dados['cor']; ?> inverted vertical labeled icon submit small button" style="margin-top:-10px;" onclick="editarBt('<?php echo $dados['id']; ?>');">
    <i class="pencil icon"></i>Editar <?php echo $nomeSingular;?>
  </div>
</div>  

<div class="ui column center aligned grid">
	<div class="column" style="width: auto;">
		<div class="ui <?php echo $dados['cor']; ?> segment" style="width: auto; max-width:900px;">
				<?php 
					$temImagem = false;
					$altura = "40";
					$maginTop = "10";
					if(in_array('imagem', $dados['tipos'])) { 
					$campo = array_search('imagem', $dados['tipos']);
						if($dados[CONTROLLER][$campo] != ""){
							$temImagem = true;
							$altura = "106";
							$maginTop = "60";
						}
					}

					$valorFace = "";
					if(in_array('facebook', $dados['tipos'])){
						$campoFace = array_search('facebook', $dados['tipos']);
						$valorFace = $dados[CONTROLLER][$campoFace];
						if($valorFace != "")
							$altura = "106";
					}
						
				?>			
			<div style="height:<?php echo $altura; ?>px;">

				<?php 
					if($temImagem) { 					
					?> 
					<div class="ui rounded image" style="float:left; margin-right:10px;">
						<a class="ui left <?php echo $dados['cor']; ?> corner label">
   							<i class="photo icon"></i>
 						</a>
						<img style="max-height:115px;" src="<?php echo URL.CONTROLLER."/imagens/img:".$dados[CONTROLLER][$campo];?>">
					</div>
				<?php unset($dados['campos'][$campo]); 
				}?>

				<?php if(in_array('facebook', $dados['tipos']) && $temImagem == false) { 
					if($valorFace != ""){
				?>
					<div class="ui rounded image" style="float:left; margin-right:10px;">
						<a class="ui left <?php echo $dados['cor']; ?> corner label">
   							<i class="facebook icon"></i>
 						</a>
						<a href="http://facebook.com/<?php echo $valorFace?>" TARGET="_blank">
							<img style="max-height:150px;" class="rounded ui image" src="http://graph.facebook.com/<?php echo $valorFace?>/picture?height=115"/>
						</a>
					</div>
				<?php
					} 
				}?>

				<?php if(in_array('nome', $dados['tipos'])) { 
					$campo = array_search('nome', $dados['tipos']);
				?>
					<div style="float:left; text-transform: uppercase; margin-top:10px;"><h3><?php echo $dados[CONTROLLER][$campo]; unset($dados['campos'][$campo]); ?></h3></div>
					<br>
					
				<?php } ?>
			</div>
			<div class="ui divider"></div>
			<?php foreach ($dados['campos'] as $campo => $campoNome) { 
				if($dados[CONTROLLER][$campo] != "" && $dados[CONTROLLER][$campo] != '0'){
					if($dados['tipos'][$campo] == "facebook"){
						$dados[CONTROLLER][$campo] = "<a href=\"http://facebook.com/".$dados[CONTROLLER][$campo]."\" TARGET=\"_blank\">http://facebook.com/".$dados[CONTROLLER][$campo]."</a>";
						$campoNome = "Facebook";
					}
			?>
				<div class="" style="float: left; margin:6px; width: auto; min-width:100px; color: #706d6d; border-color: #c6c2c2 !important; border-radius: 5px; border-bottom: 2px solid;">
				  <h4><?php echo $campoNome; ?></h4><p><?php echo $dados[CONTROLLER][$campo]; ?></p>
				</div>
			<?php 
				} 
			}
			?>

		</div>
	</div>
</div>