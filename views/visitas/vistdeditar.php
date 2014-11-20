<div style="width:100%; text-align:center; text-transform: uppercase;">
	<h2>Visitas</h2>
</div>
<div style="width:100%; text-align:center; text-transform: uppercase;">
	<h3>
		Visitante: <a onclick="redir('visitantes', '<?php echo $dados['id']; ?>')">
					<?php echo $dados['visitante']['nome']; ?>
			   </a>
	</h3>
</div>
<?php
	//print_r($dados);
?>
<form class="formulario">
	<div class="ui column center aligned grid">
	    <div class="column" style="width: auto;">
			<div class="ui teal segment" style="width: auto;">
				<form action="" class="ui form formulario" method="POST">
				<input name="visitante" value="<?php echo $dados['id']; ?>" hidden/>
				<div style='width:auto; float: left; text-align:left; margin-right: 20px; margin: 5px;'>
					<?php $this->html->campo("data");?>
				</div>
				<div class="ui green inverted vertical labeled icon submit button" style=" margin-right: 20px; margin: 5px; margin-top: 5px;" onclick="addItem('visitas/', '<?php echo $dados['id']; ?>');">
	   				<i class="plus icon"></i>Adicionar
	 			</div>	
	 			</form>							
			</div>
		</div>
	</div>
</form>	

<div class="ui column center aligned grid">
   <div class="column" style="width: auto;"> 
		<div class="ui teal circular label" style="float:center;">
			<strong id="totalBusc">Total: <span id="qtd"><?php echo $dados['qtd']; ?></span></strong>
		</div> 
		<table class="ui table segment" id="" style="width:auto;">
				<thead>
					<tr>
			        	<th style="text-align:center;">Data da Visita</th>
			   			<th></th>
					</tr>
				</thead>
				<tbody id="listagem">
				<?php foreach ($dados['visitas'] as $key => $visita) { ?>
				<tr>
					<td><?php echo $visita['data']; ?></td>
					<td>
	                    <div class="tiny ui red icon button balao" data-content='Deletar' onClick="deletarBt('<?php echo $visita['id']; ?>', 'visitas/vistdeditar/cod:<?php echo $dados['id']; ?>')"><i class="trash icon"></i></div>
					</td>
				</tr>	
				<?php } ?>
			</tbody>
		</table>	
	</div>
</div>