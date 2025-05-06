<?php

define('FILE_PATH', 'words.json');

function dodavanjeJsona($filepath) {
  $containsJson = file_get_contents($filepath);
  $rijeciIzJsona = json_decode($containsJson);
  return $rijeciIzJsona;
}

function izracunajBrojSlova($word) {
  $izbrojanaSlova = strlen($word);
  return $izbrojanaSlova;
}

function izracunajBrojSamoglasnika($word) {
  $sveUMalaSlova = strtolower($word);
  $samoglasnici = ['a', 'e', 'i', 'o', 'u'];
  $pretvorenaRijecUNiz = preg_split('//u', $sveUMalaSlova);

  $brojac = 0;
  foreach($pretvorenaRijecUNiz as $slovo) {
    if(in_array($slovo, $samoglasnici)) {
      $brojac++;
    }
  }
  return $brojac;
}

function izracunajBrojSuglasnika($word) {
  return izracunajBrojSlova($word) - izracunajBrojSamoglasnika($word);
}

function dodavanjeRijeci($lokalnoWord, $nizRijeci) {
  $r = htmlspecialchars($lokalnoWord);
  $trimmed = trim($r);
  $trimmed = preg_replace('/\s+/', '', $lokalnoWord);
  if (strlen($trimmed) === 0) return;


$rezultatBrojaSlova = izracunajBrojSlova($trimmed);

$rezultatBrojaSamoglasnika = izracunajBrojSamoglasnika($trimmed);

$rezultatBrojaSuglasnika = izracunajBrojSuglasnika($trimmed);

$nizRijeci[] = $trimmed;
$rijeciNazadUJson = json_encode($nizRijeci);
file_put_contents(FILE_PATH, $rijeciNazadUJson); 
header('Location:' . $_SERVER['PHP_SELF']);
}

$rijeci = dodavanjeJsona(FILE_PATH);

if($_SERVER['REQUEST_METHOD']=== 'POST') {

  echo "Ovo je POST metoda" . "<br>";
  
  $upisanaRijec = $_POST['rijec'];
  $trimmedUpisanaRijec = trim($upisanaRijec);
  

  dodavanjeRijeci($trimmedUpisanaRijec, $rijeci);


}

if($_SERVER['REQUEST_METHOD'] === 'GET')
  echo "Ovo je GET metoda";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parcijalni ispit</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Parcijalni ispit</h1>
  <hr>
  <main class="main">
    <form action="" method="POST">
      <label>Upišite riječ:
        <br>
        <input type="text" name="rijec" required>
      </label>
      <br><br>
      <input type="submit" value="pošalji">
    </form>

    <table border="1">
      <tr>
        <th>Riječ</th>
        <th>Broj slova</th>
        <th>Broj suglasnika</th>
        <th>Broj samoglasnika</th>
      </tr>
      <?php
      foreach($rijeci as $vrijednosti) {
        echo "<tr>";
          echo "<td>" . $vrijednosti . "</td>";
          echo "<td>" . izracunajBrojSlova($vrijednosti) . "</td>";
          echo "<td>" . izracunajBrojSuglasnika($vrijednosti) . "</td>";
          echo "<td>" . izracunajBrojSamoglasnika($vrijednosti) . "</td>";
        echo "</tr>";
      }
      ?>
    </table>
  </main>  

</body>
</html>