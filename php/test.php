
<?PHP



//POST
if ($_SERVER['REQUEST_METHOD']==="POST") {
  if ( isset($_POST['x']) && isset($_POST['y']) && isset($_POST['z']) )
      {
        echo "Jestem z test.php POST";
      }
  else
      {
        echo "Brak danych w test.php POST";
      };
}; // end of POST




//GET
if ($_SERVER['REQUEST_METHOD']==="GET") {
  if ( isset($_GET['x']) && isset($_GET['y']) && isset($_GET['z']) )
      {
        // echo "Jestem z test.php GET";
        $x = $_GET['x'];
        $y = $_GET['y'];
        $z = $_GET['z'];
        $sum = $x + $y + $z;
        echo "suma=$sum";
      }
  else
      {
        echo "Brak danych w test.php GET";
      };
}; // end of GET


?>
