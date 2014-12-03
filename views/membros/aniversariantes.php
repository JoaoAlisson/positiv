<?php 
//print_r($dados['itens']);

  $mes = ["", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junio", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];


$paginacao = "";
 if(!isset($dados['filtro'])){
  $filtrosValores = "";
  foreach ($dados['filtrosValores'] as $campo => $valor) {
      $filtrosValores .= "/$campo:$valor";
  }
  echo "<script type='text/javascript'>setOrdemPag('".$dados['pagina']."','".$dados['ordem']."', '".$dados['ordenacao']."'); FILTROS = '$filtrosValores';</script>"; ?>
<div style="width:100%; text-align:center; text-transform: uppercase;"><h2>
  <i class="list icon"></i>Membros</h2>
</div>    
<?php if(isset($dados['filtros'])){ ?>
  <form class="formulario">
    <div class="ui column center aligned grid">
       <div class="column" style="width: auto;">
    <div class="ui <?php echo $dados['cor']; ?> segment" style="width: auto;">


<div class="field" id="campo_consagracao" style="margin: 2px; float:left; text-align:left;">
     <label>Mês </label><div class="ui left labeled icon input"><div class="ui search dropdown selection" id="select_consagracao" tabindex="2" onkeypress="enterSubmit(event);" onmouseover="registraSelect('select_consagracao');">
            <input type="hidden" name="mes" id="input_consagracao" class="" onchange=";" style="max-width:450px; min-width:300px;">
            <i class="triangle down icon disabled"></i>
            <div class="text" data-value="" style="max-width:450px; min-width:100px;"></div>
              <div class="menu ui ">
                <div class="item" data-value=""></div>
                <div class="item" data-value="1">Janeiro</div>
                <div class="item" data-value="2">Fevereiro</div>
                <div class="item" data-value="3">Março</div>
                <div class="item" data-value="4">Abril</div>
                <div class="item" data-value="5">Maio</div>
                <div class="item" data-value="6">Junio</div>
                <div class="item" data-value="7">Julho</div>
                <div class="item" data-value="8">Agosto</div>
                <div class="item" data-value="9">Setembro</div>
                <div class="item" data-value="10">Outubro</div>
                <div class="item" data-value="11">Novembro</div>
                <div class="item" data-value="12">Dezembro</div>
              </div>                                     
            </div></div>
</div>

  <div style="width:auto; float: left; text-align:left;">
     <br>
    <div class="ui <?php echo $dados['cor']; ?> vertical labeled circular icon submit button submeterForm" style="" onClick="filtrar('membros', 'aniversariantes')">
      <i class=" search icon"></i>Filtrar
    </div>
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



<div style="width:100%; text-align:center; text-transform: uppercase;">
  <div class="ui teal vertical labeled icon submit small button" style="margin-top:2px;" onclick="navegacaoSub('membros/','aniversariantes', '');">
    <i class="time icon"></i>HOJE
  </div>
  <h3><i class="gift icon"></i>Aniversariantes de <span id="outramsg"><?php if($dados['mes'] == "") echo "Hoje"; else echo $mes[$dados['mes']]; ?></span></h3>
</div> 

<table class="ui table segment" id="tabelaListagem" style="width:auto;">
	<thead>
		<tr>
		<?php foreach ($dados['campos'] as $campo => $nome) {
      if($nome == "facebook")
        echo "<th><i class=\"facebook sign big purple icon\" style='margin-left: 6px;'></i></th>";
      else
			 echo "<th><a class='small ui button' onclick=\"paginacao('".$dados['controller']."/', '','$campo', 'aniversariantes');\"><i class='sort icon'></i>$nome</a></th>";
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
            if($campo != 'id'){
              if($dados['tipos'][$campo] == "facebook" && $valor != "")
                $listagem .= "<td><a href=\"http://facebook.com/$valor\" TARGET=\"_blank\"><img class=\"rounded ui image\" src=\"http://graph.facebook.com/$valor/picture\"/></a></td>";
              else
      		      $listagem .= "<td>$valor</td>";
            }
      	}
      $listagem .= "<td>
                      <div class=\"tiny ui icon button balao\" data-content='Visualizar' onClick='verBt(".$campos['id'].")'><i class=\"unhide icon\"></i></div>
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
    
    if(in_array("facebook", $dados['tipos'])){
      $campo = array_search("facebook", $dados['tipos']);
      foreach ($dados['itens'] as $chave => $campos) {
        if($dados['itens'][$chave][$campo] != "")
          $dados['itens'][$chave][$campo] = "<a href=\"http://facebook.com/".$campos[$campo]."\" TARGET=\"_blank\"><img class=\"rounded ui image\" src=\"http://graph.facebook.com/".$campos[$campo]."/picture\"/></a>";
      } 
    }

    $retorna["outramsg"] = "HOJE";
    if($dados['mes'] != 0)
      $retorna['outramsg'] = $mes[$dados['mes']];

    $retorna["listagem"] = $dados['itens'];
    $retorna["paginacao"] = $paginacao;
    $retorna['total'] = $dados['qtd'];
    echo json_encode($retorna);
  }
?>
