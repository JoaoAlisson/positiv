<?php 
$paginacao = "";
 if(!isset($dados['filtro'])){
  $filtrosValores = "";
  foreach ($dados['filtrosValores'] as $campo => $valor) {
      $filtrosValores .= "/$campo:$valor";
  }
  echo "<script type='text/javascript'>setOrdemPag('".$dados['pagina']."','".$dados['ordem']."', '".$dados['ordenacao']."'); FILTROS = '$filtrosValores';</script>"; ?>
<h2><?php echo $dados['nome'][1];?></h2>

<?php if(isset($dados['filtros'])){ ?>
  <form class="formulario">
  <?php  
      foreach ($dados['filtros'] as $campo => $nome) {
          $valorFiltros = isset($dados['filtrosValores'][$campo]) ? $dados['filtrosValores'][$campo] : null;
          $this->html->campo($campo, false, $valorFiltros);
          echo "<br>";
      }
  ?>
    <div class="ui inverted blue vertical labeled circular icon submit button" style="" onClick="filtrar('<?php echo $dados['controller'];?>')">
      <i class=" search icon"></i>Filtrar
    </div>
  </form>
<?php } ?>
  
<table class="ui table segment">
  <div class="ui green circular label"><strong id="totalBusc">Total: <?php echo $dados['qtd']; ?></strong></div>
	<thead>
		<tr>
		<?php foreach ($dados['campos'] as $campo => $nome) {
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
                      <div class=\"tiny ui icon button\" title='Editar' onClick='editarBt(".$campos['id'].")'><i class=\"pencil icon\"></i></div>
                      <div class=\"tiny ui red icon button\" title='Deletar' onClick='alert(".$campos['id'].")'><i class=\"trash icon\"></i></div>
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
<?php if(!isset($dados['filtro'])) echo "<div class=\"ui pagination menu\" id=\"paginacao\">"; ?> 
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
<?php 

  //print_r($dados['itens']);
  if(isset($dados['filtro'])){
    $retorna["listagem"] = $dados['itens'];
    $retorna["paginacao"] = $paginacao;
    $retorna['total'] = $dados['qtd'];
    echo json_encode($retorna);
  }
?>
