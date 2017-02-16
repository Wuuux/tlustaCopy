
<?php

// if ($_SERVER['REQUEST_METHOD']==="GET")
// 		{
// 		      if ( isset($_GET['id']) ) {
//
// 		      };
// 		}
// else
		{

			require 'link.php';
			if (!$link) {
					echo "Error: Unable to connect to MySQL." . PHP_EOL;
					echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
					exit;
			};

			$query = "SELECT tytul, dzien, godzina, email, ilosc, valid, temp
								FROM program INNER JOIN rezerwacje ON
								program.id=rezerwacje.idwydarzenia
								WHERE (valid=1 OR temp = 1)
								ORDER BY dzien DESC";
								echo "REZERWACJE<br>";
			if ($result = mysqli_query($link, $query)) {
					echo "<style>td { border:solid 1px black;} </style><table >";
					echo "<tr>";
					echo "<td>Tytuł</td><td>Data</td><td>Godzina</td><td>E-mail</td><td>Ilość</td><td>Potwierdzona</td><td>Oczekująca</td>";
					echo "</tr>";
					while ($row = mysqli_fetch_assoc($result)) {
						echo "<tr>";
						echo "<td>" . $row['tytul'] ."</td><td>". $row['dzien'] ."</td><td>". $row['godzina'] ."</td><td>". $row['email'] ."</td><td>". $row['ilosc'] ."</td><td>". $row['valid'] ."</td><td>". $row['temp'] . "</td>";
						echo "</tr>";
					};
					echo "</table>";

					/* free result set */
					mysqli_free_result($result);
			};


			mysqli_close($link);
		};

 ?>
