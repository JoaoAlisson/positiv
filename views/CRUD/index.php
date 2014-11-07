<?php 
//print_r($dados['itens']);
$paginacao = "";
 if(!isset($dados['filtro'])){
  $filtrosValores = "";
  foreach ($dados['filtrosValores'] as $campo => $valor) {
      $filtrosValores .= "/$campo:$valor";
  }
  echo "<script type='text/javascript'>setOrdemPag('".$dados['pagina']."','".$dados['ordem']."', '".$dados['ordenacao']."'); FILTROS = '$filtrosValores';</script>"; ?>
<div style="width:100%; text-align:center; text-transform: uppercase;"><h2><?php if($dados['icone'] != "") echo "<i class=\"".$dados['icone']." icon\"></i>"; ?><?php echo $dados['nome'][1];?></h2>
  <?php 
    $nomeSingular = "";
    if(isset($dados['nome'][0]))
      $nomeSingular = $dados['nome'][0];
    else
      $nomeSingular = $dados['nomeController'];
  ?>
  <div class="ui <?php echo $dados['cor']; ?> inverted vertical labeled icon submit small button" style="margin-top:-10px;" onclick="navegacaoSub('<?php echo CONTROLLER;?>/','cadastrar', '');">
    <i class="plus icon"></i>Cadastrar <?php echo $nomeSingular;?>
  </div>
</div>    
<?php if(isset($dados['filtros'])){ ?>
  <form class="formulario">
    <div class="ui column center aligned grid">
       <div class="column" style="width: auto;">
    <div class="ui <?php echo $dados['cor']; ?> segment" style="width: auto;">

  <?php
      foreach ($dados['filtros'] as $campo => $nome) {
        echo "<div style='width:auto; float: left; margin-right: 20px;'>";
          $valorFiltros = isset($dados['filtrosValores'][$campo]) ? $dados['filtrosValores'][$campo] : null;
          $this->html->campo($campo, false, $valorFiltros);
        echo "</div>";
      }
  ?>
    <div class="ui inverted <?php echo $dados['cor']; ?> vertical labeled circular icon submit button submeterForm" style="margin-top:0px;" onClick="filtrar('<?php echo $dados['controller'];?>')">
      <i class=" search icon"></i>Filtrar
    </div>

  </div>
    <div class="ui <?php echo $dados['cor']; ?> circular label" style="float:left;">
      <strong id="totalBusc">Total: <?php echo $dados['qtd']; ?></strong>
    </div>
  </div></div>
  </form>
<?php }?>
    <div class="ui column center aligned grid">
       <div class="column" style="width: auto;">
        <?php if(!isset($dados['filtros'])) { ?>
    <div class="ui <?php echo $dados['cor']; ?> circular label" style="float:left;">
      <strong id="totalBusc">Total: <?php echo $dados['qtd']; ?></strong>
    </div>
    <br>
<?php } ?>
<table class="ui table segment" id="tabelaListagem" style="width:auto;">
	<thead>
		<tr>
		<?php foreach ($dados['campos'] as $campo => $nome) {
      if($nome == "facebook")
        echo "<th><i class=\"facebook sign big purple icon\" style='margin-left: 6px;'></i></th>";
      else
			 echo "<th><a class='small ui button' onclick=\"paginacao('".$dados['controller']."/', null ,'$campo');\"><i class='sort icon'></i>$nome</a></th>";
		}?>
    <th></th>
		</tr>
	</thead>
  <tbody id="listagem">
  <div>
  <?php //} ?>
  	<?php 
      $listagem = "";
      foreach ($dados['itens'] as $iten => $campos) { 

      $listagem .= "<tr>";
      	foreach ($campos as $campo => $valor) {
            if($campo != 'id')
      		    $listagem .= "<td>$valor</td>";
      	}
      $listagem .= "<td>
                      <div class=\"tiny ui icon button balao\" data-content='Editar' onClick='editarBt(".$campos['id'].")'><i class=\"pencil icon\"></i></div>
                      <div class=\"tiny ui red icon button balao\" data-content='Deletar' onClick='deletarBt(".$campos['id'].")'><i class=\"trash icon\"></i></div>
              </td></tr>";
    	} 
      //if(!isset($dados['filtro']))
          echo $listagem;
    ?>
 <?php //if(!isset($dados['filtro'])){ ?>  
 </div>  
  </tbody>
</table>

<?php } ?>
<?php if($dados['qtdPaginas'] > 1) { ?>
<?php if(!isset($dados['filtro'])) echo "<div class=\"ui pagination menu\" id=\"paginacao\" style='float:left;'>"; ?> 
  <?php  
    $qtdPgV = ceil($dados['qtdPaginas']/10);
    $pgV = ceil($dados['pagina']/10);
    if($qtdPgV > 1){
      $dsbltd = ($dados['pagina'] == 1) ? "desabled" : "";
      $onclick = ($dsbltd == "") ? "onclick=\"paginacao('".$dados['controller']."/', '1');\"" : "";
      $paginacao .= "<a class='$dsbltd icon item' $onclick><i class='double angle left icon'></i></a>";
    }
    $dsbltd = ($dados['pagina'] == 1) ? "disabled" : "";
    $anterior = $dados['pagina'] - 1;
    $onclick = ($dsbltd == "") ? "onclick =\"paginacao('".$dados['controller']."/', '$anterior');\"" : "";
    $paginacao .= "<a class='$dsbltd icon item' $onclick><i class='left arrow icon'></i></a>";

    if($pgV > 1)
       $paginacao .= "<a class='disabled icon item'><strong>...</strong></a>";
  $inicio = ($pgV - 1)*10 + 1;
  if($pgV == $qtdPgV)
    $fin = $dados['qtdPaginas'] - $inicio + 1;
  else
    $fin = $inicio + 9;
  $fin = ($inicio > $fin) ? $inicio : $fin;
  for($i=$inicio; $i<=$fin; $i++){ 
     $ativo = ($i == $dados['pagina']) ? "active" : "";
     $onclick = ($ativo == "") ? "onclick =\"paginacao('".$dados['controller']."/','$i');\"" : "";
     $paginacao .= "<a class='$ativo item' $onclick>".$i."</a>"; 
  }
  
    if($pgV < $qtdPgV)
       $paginacao .= "<a class='disabled icon item'><strong>...</strong></a>";

    $dsbltd = ($dados['pagina'] == $dados['qtdPaginas']) ? "disabled" : "";
    $proxima = $dados['pagina'] + 1;
    $onclick = ($dsbltd == "") ? "onclick =\"paginacao('".$dados['controller']."/','$proxima');\"" : "";
    $paginacao .= "<a class='$dsbltd icon item' $onclick><i class='right arrow icon'></i></a>";

    if($qtdPgV > 1){
      $dsbltd = ($dados['pagina'] == $dados['qtdPaginas']) ? "desabled" : "";
      $onclick = ($dsbltd == "") ? "onclick=\"paginacao('".$dados['controller']."/','".$dados['qtdPaginas']."');\"" : "";
     $paginacao .= "<a class='$dsbltd icon item' $onclick><i class='double angle right icon'></i></a>";
    }
    if(!isset($dados['filtro']))
      echo $paginacao."</div>";
  ?>

<?php } ?>
<?php  if(!isset($dados['filtro'])) echo "</div></div>"; ?>

<?php 

  //print_r($dados['itens']);
  if(isset($dados['filtro'])){
    $retorna["listagem"] = $dados['itens'];
    $retorna["paginacao"] = $paginacao;
    $retorna['total'] = $dados['qtd'];
    echo json_encode($retorna);
  }
?>
