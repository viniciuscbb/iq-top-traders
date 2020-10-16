<?php

function conection(){

  $host="localhost"; 
  $username="root"; 
  $password=""; 
  $db_name="iqtt";

	$conection = new mysqli($host, $username, $password, $db_name);
	mysqli_set_charset($conection, "utf8");
	if (!$conection) {
		die("Não foi possível conectar ao banco de dados" . mysqli_connect_error());
	} else {
		return $conection;
  }
  closeConection($conection);
}

function closeConection($connect){
	@mysqli_close($connect) or die(mysqli_error($connect));
}
