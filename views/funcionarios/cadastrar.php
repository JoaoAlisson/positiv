<?php 
/**
  function tiposModel(){
      $tipos = array("nome"  => "nome",
              "face"         => "facebook",
              "cpf"          => "cpf",
              "rg"           => "texto",
              "sexo"         => "sexo",
              "foto"         => "imagem",
              "estadocivil"  => array("Solteiro", "Casado", "Divorciado"),
              "conjuge"      => "texto",
              "nascimento"   => "data",
              "telefone"     => "telefone",
              "celular"      => "telefone",
              "email"        => "email",
              "estado"       => "estado",
              "cidade"       => "cidade",
              "bairro"       => "texto",
              "rua"          => "texto",
              "numero"       => "texto",
              "observacoes"  => "textoLongo");

      return $tipos;
  }
*/

  function tiposController(){
      $tipos = array("nome"  => "Nome",
               "face"        => "Facebook (http://facebook.com/exemplo)",
               "cpf"         => "CPF",
               "rg"          => "RG",
               "nascimento"  => "Data de Nascimento",
               "sexo"        => "Sexo",
               "foto"        => "Foto",
               "estadocivil" => "Estado Civil",
               "conjuge"     => "Conjugue",
               "telefone"    => "Telefone",
               "celular"     => "Celular",
               "email"       => "Email",
               "estado"      => "Estado",
               "cidade"      => "Cidade",
               "bairro"      => "Bairro",
               "rua"         => "Rua",
               "numero"      => "Numero");    

        return $tipos;   
  }  


  if(isset($dados['retorno'])){
      $retornoJson['valido'] = $dados['retorno'][0];
      $retornoJson['mensagem'] = $dados['retorno'][1];
      $retornoJson['erros'] = isset($dados['retorno'][2]) ? $dados['retorno'][2] : "";

      $retornoJson = json_encode($retornoJson);
      echo $retornoJson;

  }else{
?>

<div style="width:100%; text-align:center; text-transform: uppercase;">
  <h2><i class="male icon"></i>Cadastrar Funcionário</h2>
</div>
<br>

<div class="ui center aligned grid">
  <div class="ui center aligned grid">
    <div class="column left aligned" style="width:auto;">


        <div id="context1">
          <div class="ui tab segment active" data-tab="first">
            <div class="ui top attached tabular menu">
              <a class="item <?php if($dados['aba'] == 1) echo "active"; ?>" data-tab="first/a">Membro</a>
              <a class="item <?php if($dados['aba'] == 2) echo "active"; ?>" data-tab="first/b">Não Membro</a>
            </div>
            <div class="ui bottom attached <?php if($dados['aba'] == 1) echo "active"; ?> tab segment" data-tab="first/a">

<?php
function inicio(){
  echo "<div class=\"column divForm\" style=\"width:auto; max-width: 450px; float: left; margin: 10px;\">";
}

function fim(){
  echo "</div>";
}
?>

<style>
@media (min-width: 460px) {
  .divForm {
    width:450px !important;
  }
}
</style>
<?php
  $this->html->formulario("membro");
  echo "<input hidden name='subMembro'>
        <div class=\"ui column center aligned grid\">";

  echo "<div class=\"column divForm\" style=\"width:auto; max-width: 450px;\">
        <div class=\"ui left ".$dados['cor']." aligned segment\" style=\"\">";
  
  $this->html->campo("membro");
  $this->html->campo("cargo");
  $this->html->campo("salario");
  echo "<script type=\"text/javascript\">$(document).ready(function(){ $('.ui.checkbox').checkbox(); });</script>
       <div class='ui checkbox'>
        <input name='inss' type='checkbox'>
        <label><strong>Calcular INSS</strong></label>
       </div><br>";
  $this->html->campo("descricao");
  
  echo "<div style='clear: both;'>";
  $this->html->submeter(null , "cadastrar", null, null, null, "membro"); 
  echo "</div>";
  fim();
  echo "</div>";
    $this->html->formularioFim(); 
?>

</div>
      </div>
      <div class="ui bottom attached tab <?php if($dados['aba'] == 2) echo "active"; ?> segment" data-tab="first/b">

<?php

  $informacoes['tipos']  = $dados['campos'];
  $informacoes['campos'] = tiposController();
  $informacoes['obrigatorios'] = array("nome", "sexo");

  $html2 = new HTML($informacoes);

  require RAIZ . SEPARADOR . "views". SEPARADOR . "func_nao_membro" . SEPARADOR . "cadastrar.php";
?>

            </div>
          </div>
        </div>

<script type="text/javascript">
  $('#context1 .menu .item')
    .tab({
      context: $('#context1')
    });
</script>

</div>
</div>
</div>

<?php } ?>