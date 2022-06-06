<!DOCTYPE html>
<html>
<head>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<script>
	function GetKey($btn) {
		if ($btn.id == 'F') {
			document.getElementById("key").value = '';
		} else {
			document.getElementById("key").value = document.getElementById("key").value + $btn.id;
		}
	}
</script>

<?php
	// Gerador de GUID
	function guidv4($data) {
    assert(strlen($data) == 16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	// Recupera valores enviados pelo FrontEnd
	$guid = @$_REQUEST['guid'];
	$base = @$_REQUEST['base'];
	$key = @$_REQUEST['key'];

	echo 'GUID: Key: ' . $key . ' | base: ' . $base . ' | Numeros: ';

	// Trata/Decodifica valores recebidos pelo FrontEnd
	$arrComb = array();
	for ($i = 0; $i <= 5; ++$i) {
		$arrNumb = array();
		if (substr($key, $i, 1) == 'A') {
			$arrNumb[] = substr($base, 0, 1);
			$arrNumb[] = substr($base, 1, 1);
		} else if (substr($key, $i, 1) == 'B') {
			$arrNumb[] = substr($base, 2, 1);
			$arrNumb[] = substr($base, 3, 1);
		} else if (substr($key, $i, 1) == 'C') {
			$arrNumb[] = substr($base, 4, 1);
			$arrNumb[] = substr($base, 5, 1);
		} else if (substr($key, $i, 1) == 'D') {
			$arrNumb[] = substr($base, 6, 1);
			$arrNumb[] = substr($base, 7, 1);
		} else if (substr($key, $i, 1) == 'E') {
			$arrNumb[] = substr($base, 8, 1);
			$arrNumb[] = substr($base, 9, 1);
		}
		$arrComb[] = $arrNumb;
	}
	// Debug - Exibe na tela 
	foreach ($arrComb as $nr) {
		echo @$nr[0] . @$nr[1];
	}
	echo '<hr>';

	// Prepara matriz de combinaçoes
  $max = (1 << strlen($key));
  $arrAux = array();
  for ($i = 0; $i < $max; $i++) {
  	$arrAux[] = str_pad(decbin($i), strlen($key), '0', STR_PAD_LEFT);
  }
	
	echo '<hr>';
	
	// Gera combinaçoes de senhas para a cadeia recebida (2^6 para 6 posicoes)
	$combs = array();
  foreach ($arrAux as $vals) {
  	$nr = '';
  	for ($posDig = 0; $posDig <= count($arrComb) - 1 ; $posDig++) {
			$nr .= @$arrComb[$posDig][$vals[$posDig]];
		}
  	$combs[] = $nr;
  }

    // Realiza a validacao do hash recebido 
		$lista = '';
		$auth = false;
    foreach ($combs as $value) {
			if ($value != '') {
    		if (md5($value) == '4badaee57fed5610012a296273158f5f') {
      		$auth = true;
      	}
				$lista .= $value . ' - ' . md5($value) . '<br>';
  		}
		}
	
		if ($auth) {
			echo '<h3 id="login-sucess" style="color:green" >Login realizado com sucesso!</h3>';
		} else if ($lista != '') {
			echo '<h3 id="login-error" style="color:red">Usuário e senha inválidos!</h3>';
		}

// Geração da semente
$a = array();
while (count($a) < 10) {
  // $vlr = random_int(0, 9);
	$vlr = rand(0, 9);
	$add = true;
	for ($i = 0; $i < count($a); ++$i) {
		if ($a[$i] == $vlr) {
			$add = false;
		}
	}
	if ($add) {
		$a[] = $vlr;
	}
}

$base = '';
for ($i = 0; $i < count($a); ++$i) {
	$base .= $a[$i];
}
echo '<h3 class="text-center text-info">Login</h3>';
echo '<form id="frm" name="frm" action="virtualkey.php" method="post">';
echo '<input type="text" class="form-control" name="guid" id="guid" value="' . guidv4(openssl_random_pseudo_bytes(16)) . '">';
echo '<input type="text" class="form-control" name="key" id="key" value="">';
echo '<input type="text" class="form-control" name="base" id="base" value="' . $base . '">';
echo '<table>';
echo '	<tr>';
echo '		<td><input type="button" class="btn btn-info btn-md" id="A" onclick="GetKey(this)" value="[' . $a[0] . '/'. $a[1] . ']"/></td>';
echo '		<td><input type="button" class="btn btn-info btn-md" id="B" onclick="GetKey(this)" value="[' . $a[2] . '/'. $a[3] . ']"/></td>';
echo '		<td><input type="button" class="btn btn-info btn-md" id="C" onclick="GetKey(this)" value="[' . $a[4] . '/'. $a[5] . ']"/></td>';
echo '	</tr>';
echo '	<tr>';
echo '		<td><input type="button" class="btn btn-info btn-md" id="D" onclick="GetKey(this)" value="[' . $a[6] . '/'. $a[7] . ']"/></td>';
echo '		<td><input type="button" class="btn btn-info btn-md" id="E" onclick="GetKey(this)" value="[' . $a[8] . '/'. $a[9] . ']"/></td>';
echo '		<td><input type="button" class="btn btn-info btn-md" id="F" onclick="GetKey(this)" value="[<<]"/></td>';
echo '	</tr>';
echo '</table>';
echo '<input type="submit" class="btn btn-info btn-md" value="Enviar" />';
echo '</form>';

echo '<br>';

echo 'Combinações:<hr>' . $lista . '<hr>';

?>

</body>
</html>