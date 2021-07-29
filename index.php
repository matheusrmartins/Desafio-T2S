<?php
  $con = mysqli_connect('localhost','root','', 'db_t2s_dev');
  
  if ($con -> connect_errno) {
    echo "Failed to connect to MySQL: " . $con -> connect_error;
    exit();
  }
  
$nome_cliente = "";
$tipo_movimentacao = "";

?>

<html>
<head>
	<title>Relatórios</title>
</head>
<body>
<h3>Movimentações</h3>

<form method="post" action="">
<table class="filtros">
  <tr>
    <td>Nome do cliente
	  <select name="filtro_cliente">
	  <?php

        $sql = "SELECT nome_cliente FROM tb_container group by nome_cliente";
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
			  echo "<option>" . $row['nome_cliente'] . "</option>";
		  }
		}
		
      ?>
	  </select>
	</td>
	<td>Tipo de movimentação
	  <select name="filtro_movimentacao">
	  <?php

      // Perform query
        $sql = "SELECT tipo FROM tb_movimentacoes group by tipo";
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
			echo "<option>" . $row['tipo'] . "</option>";
		  }
		}
		
      ?>
	  </select>
	</td>
	<td>
	  <input type="submit" name="submit" />
	</td>
  </tr>
</table>
</form>

<?php

if (isset($_POST["submit"])){
  $nome_cliente = $_POST['filtro_cliente'];
  $tipo_movimentacao = $_POST['filtro_movimentacao'];
  $sql = "SELECT a.id, a.numero, a.nome_cliente, a.tipo, b.tipo tipo_mov, b.dt_inicio, b.dt_fim, a.status, a.categoria FROM tb_container a, tb_movimentacoes b where a.numero = b.container and a.nome_cliente = '$nome_cliente' and b.tipo = '$tipo_movimentacao'";
  $result = $con->query($sql);
  
  if ($result->num_rows > 0 and $nome_cliente != "" and $tipo_movimentacao != "") {
?>
<table border="1">
<tr class="cabecalho">
  <td>ID</td><td>Numero do Container</td>
  <td>Nome do cliente</td>
  <td>Tipo do container</td>
  <td>Tipo da movimentação</td>
  <td>Data Início</td>
  <td>Data Fim</td>
  <td>Status</td>
  <td>Categoria</td>
</tr>
<?php
    while($row = $result->fetch_assoc()) {
		?>
		
		<tr>
		<td><?php echo $row["id"]; ?> </td> 
		<td> <?php echo $row["numero"]; ?> </td> 
		<td> <?php echo $row["nome_cliente"]; ?> </td>
		<td> <?php echo $row["tipo"]; ?> </td>
		<td> <?php echo $row["tipo_mov"]; ?> </td>
		<td> <?php echo $row["dt_inicio"]; ?> </td>
		<td> <?php echo $row["dt_fim"]; ?> </td>
		<td> <?php echo $row["status"]; ?> </td>
		<td> <?php echo $row["categoria"]; ?> </td>
	  </tr>
	  <?php
    }
  } else {
    echo "Sem resultados";
  }


?>
</tr>
</table>

<h3>Sumário importacao/exportação</h3>
<?php
        $sql = "SELECT count(*) count FROM tb_container a, tb_movimentacoes b Where a.numero = b.container and a.nome_cliente = '$nome_cliente' and b.tipo = '$tipo_movimentacao' and a.categoria = 'Importacao' group by a.nome_cliente";
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
			echo  "Importacao: " . $row['count'] ;
		  }
		} else {
			echo  "Importacao: 0";
		}
		?>
		<br/>
		<?php
		$sql = "SELECT count(*) count FROM tb_container a, tb_movimentacoes b Where a.numero = b.container and a.nome_cliente = '$nome_cliente' and b.tipo = '$tipo_movimentacao' and a.categoria = 'Exportacao' group by a.nome_cliente";
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
			echo  "Exportacao: " . $row['count'] ;
		  }
		} else {
			echo  "Exportacao: 0";
		}
		
}
    // Free result set
    mysqli_free_result($result);
  

mysqli_close($con);
?>

</body>
</html>




