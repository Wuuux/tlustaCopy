
<!DOCTYPE html>

<html lang="en-US"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>Edit Program</title>
	<style type="text/css">
body {
	margin: 2em 5em;
	font-family:Georgia, "Times New Roman", Times, serif;
}
h1, legend {
	font-family:Arial, Helvetica, sans-serif;
}
label, input, select {
	display:block;
}
input, select {
	margin-bottom: 1em;
}
fieldset {
	margin-bottom: 2em;
	padding: 1em;
}
fieldset fieldset {
	margin-top: 1em;
	margin-bottom: 1em;
}
input[type="checkbox"] {
	display:inline;
}
.range {
	margin-bottom:1em;
}
.card-type input, .card-type label {
	display:inline-block;
}
	</style>
</head>
<body>
		<form id="newsletter" action="../php/programSave.php" method="post">
		<h1>Program Tłustej Langusty</h1>
		  <fieldset>
		    <legend>Dodaj wydarzenie</legend>

				<!-- Tytuł-->
		    <div>
		        <label>Tytuł
		        	<input id="tytul" name="tytul" type="text" placeholder="Tytul" required="" autofocus="">
						</label>
		    </div>
				<!-- Autor-->
		    <div>
		        <label>Autor: teatr, osoba, grupa
		        	<input id="kto" name="kto" type="text" placeholder="Kto" required="" autofocus="">
						</label>
		    </div>
				<!-- Data -->
		    <div>
		    		<label>Data
		        	<input id="dzien" name="dzien" type="date" placeholder="" required="">
						</label>
		    </div>
				<!-- Godzina-->
		    <div>
		    		<label>Godzina
		        	<input id="godzina" name="godzina" type="text" placeholder="19:00" required="">
						</label>
		    </div>
				<!-- Ilość miejsc -->
				<div>
						<label>Ilość miejsc
							<input id="iloscMiejsc" name="iloscMiejsc" type="number" placeholder="20" value="20" required="">
						</label>
				</div>
				<!-- Opis -->
				<div>
						<label>Opis<br>
							<textarea id="opis" name="opis" required="" rows="4" cols="50">nslfnasd</textarea>
						</label>
				</div>
				<!-- Rodzaj -->
				<div>
						<label>Rodzaj
							<input id="rodzaj" name="rodzaj" type="text" placeholder="teatr" value="teatr" required="">
						</label>
				</div>
				<!-- Plik -->
				<div>
						<label>Nazwa pliku
							<input id="plik" name="plik" type="text" placeholder="*.jpg" required="">
						</label>
				</div>
				<!-- Archiwum?-->
				<div>
						<label>Czy do archiwum
							<select id="archiwum" name="archiwum" required="">
								<option value="true">TAK</option>
								<option value="false">NIE</option>
							</select>
						</label>
				</div>
				<!-- Bilety??? -->
				<div>
					<label>Czy biletowany?
						<select id="bilety" name="bilety" required="">
							<option value="true">TAK</option>
							<option value="false">NIE</option>
						</select>
					</label>
				</div>
				<!-- Link do biletów-->
				<div>
						<label>Link do biletów
							<input id="biletyURL" name="biletyURL" type="text" placeholder="" required="">
						</label>
				</div>
				<!-- Rezerwacje??? -->
				<div>
					<label>Czy będzie możliwość rezerwacji?
						<select id="rezerwacje" name="rezerwacje" required="">
							<option value="true">TAK</option>
							<option value="false">NIE</option>
						</select>
					</label>
				</div>
				<!-- Link do rezerwacji-->
				<div>
						<label>Link do rezerwacji
							<input id="rezerwacjeURL" name="rezerwacjeURL" type="text" placeholder="" required="">
						</label>
				</div>


				<input type="submit" value="DODAJ NOWE WYDARZENIE">

		  </fieldset>

		</form>

</body></html>




<?php
echo "<h2>EDYTUJ ZAPISANE WYDARZENIA:</h2>";
if ($_SERVER['REQUEST_METHOD']==="POST") {
      if ( isset($_GET['result']) && isset($_GET['info']) ) {


      };
}
else {
	//łaczymy się z bazą danych
	require 'link.php';
	if (!$link) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			exit;
	}
	// wysyłamy zapytanie o program posortowany wg daty i godziny
	$query = "SELECT * FROM program ORDER BY dzien DESC, godzina DESC";

	if ($result = mysqli_query($link, $query)) {

			while ($row = mysqli_fetch_assoc($result)) {

					echo "<form action='updateprogram.php' method='POST' data-id=".$row['id']."><fieldset>";
					echo "ID :           <input type='text' value='".$row['id']."' name='id'>";
					echo "Tytuł :        <input type='text' value='".$row['tytul']."' name='tytul'>";
					echo "Teatr :        <input type='text' value='".$row['kto']."' name='kto'>";
					echo "Dzień :        <input type='text' value='".$row['dzien']."' name='dzien'>";
					echo "Godzina :      <input type='text' value='".$row['godzina']."' name='godzina'>";
					echo "Ilość miejsc : <input type='text' value='".$row['iloscmiejsc']."' name='iloscmiejsc'>";
					echo "Opis :         <br><textarea rows='15' cols='120'  name='opis'>".$row['opis']."</textarea><br>";
					echo "Rodzaj :       <input type='text' value='".$row['rodzaj']."' name='rodzaj'>";
					echo "Plik :         <input type='text' value='".$row['plik']."' name='plik'>";
					echo "<img width = '600' src='../images/".$row['plik']."'><br>";
					echo "Archiwum :     <input type='text' value='".$row['archiwum']."' name='archiwum'>";
					echo "Bilety :       <input type='text' value='".$row['bilety']."' name='bilety'>";
					echo "Bilety URL :   <input type='text' value='".$row['biletyURL']."' name='biletyURL'>";
					echo "Rezerwacje :   <input type='text' value='".$row['rezerwacje']."' name='rezerwacje'>";
					echo "Rezerwacje URL : <input type='text' value='".$row['rezerwacjeURL']."' name='rezerwacjeURL'>";
					echo "<input type='submit' value='UPDATE'>";
					echo "</fieldset></form>";

			};

			mysqli_free_result($result);
	};
	mysqli_close($link);
};

 ?>
