<!DOCTYPE html>
<html>
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

$base = @$_REQUEST['base'];
$key = @$_REQUEST['key'];

echo 'Key: ' . $key . ' | base: ' . $base . ' | Numeros: ';
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

foreach ($arrComb as $nr) {
	echo @$nr[0] . @$nr[1];
}
echo '<hr>';

  $max = (1 << strlen($key));
  $arrAux = array();
  for ($i = 0; $i < $max; $i++) {
  	$arrAux[] = str_pad(decbin($i), strlen($key), '0', STR_PAD_LEFT);
  }
	
	echo '<hr>';
	
	$combs = array();
  foreach ($arrAux as $vals) {
  	$nr = '';
  	for ($posDig = 0; $posDig <= count($arrComb) - 1 ; $posDig++) {
			$nr .= @$arrComb[$posDig][$vals[$posDig]];
		}
  	$combs[] = $nr;
  }

    //verificando as combinações possíveis
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
			echo "Autenticado!";
		} else if ($lista != '') {
			echo "Usuario ou senha inválido!";
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

echo '<form id="frm" name="frm" action="virtualkey.php" method="post">';
echo '<input type="text" name="key" id="key" value="">';
echo '<input type="text" name="base" id="base" value="' . $base . '">';
echo '<table>';
echo '	<tr>';
echo '		<td><input type="button" id="A" onclick="GetKey(this)" value="[' . $a[0] . '/'. $a[1] . ']"/></td>';
echo '		<td><input type="button" id="B" onclick="GetKey(this)" value="[' . $a[2] . '/'. $a[3] . ']"/></td>';
echo '		<td><input type="button" id="C" onclick="GetKey(this)" value="[' . $a[4] . '/'. $a[5] . ']"/></td>';
echo '	</tr>';
echo '	<tr>';
echo '		<td><input type="button" id="D" onclick="GetKey(this)" value="[' . $a[6] . '/'. $a[7] . ']"/></td>';
echo '		<td><input type="button" id="E" onclick="GetKey(this)" value="[' . $a[8] . '/'. $a[9] . ']"/></td>';
echo '		<td><input type="button" id="F" onclick="GetKey(this)" value="[<<]"/></td>';
echo '	</tr>';
echo '</table>';
echo '<input type="submit" value="Enviar" />';
echo '</form>';

// var_dump($a);
#for($i = 0; count($a); ++$i) {
#	echo $a[$i] . '-';
#}



echo '<br>';


echo $lista;
// If you want to fetch multiple values you can try this:
// print_r( array_intersect_key( $a, array_flip( array_rand( $a, $n ) ) ) );
// echo '<br>';
// // If you want to re-index keys wrap the call in 'array_values':
// print_r( array_values( array_intersect_key( $a, array_flip( array_rand( $a, $n ) ) ) ) );

function guidv4($data)
{
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

echo "GID: " . guidv4(openssl_random_pseudo_bytes(16));



?>

</body>
</html>