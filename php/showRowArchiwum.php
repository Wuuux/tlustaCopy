<?PHP
// nazwa tlustego miesiąca bieżącego oraz następnego wyświetla się automatycznie np. TŁUSTY LUTY/MARZEC
$months = ['STYCZEŃ','LUTY','MARZEC','KWIECIEŃ','MAJ','CZERWIEC','LIPIEC','SIERPIEŃ','WRZESIEŃ','PAŹDZIERNIK','LISTOPAD','GRUDZIEŃ'];
$monthsSmall = ['styczeń','luty','marzec','kwiecień','maj','czerwiec','lipiec','sierpień','wrzesień','październik','listopad','grudzień'];
$dzisiaj = date("Y-m-j");
//obliczenie indeksu nazwy miesiąca pobieranej z  tabeli (0 styczeń, 11 grudzień)
$currentMonth = intval($dzisiaj[5].$dzisiaj[6])-1;
$nextMonth = $currentMonth+1;
if ($nextMonth == 12) {
  $nextMonth = 0;
};
// wyświetlenie bieżącego miesiąca
// echo "<h2 class='monthName'> TŁUSTY ".$months[$currentMonth]."</h2>";
// echo "<h2> TŁUSTY ".$months[$currentMonth]." / ";
// // jeśli jest to grudzień, trzeba jako kolejny wyświetlić styczeń
// if ($currentMonth == 11) {echo $months[0]."</h2>";}
// // jeśli jest to inny miesiąc pokazujemy następny w kolejności
// else {echo $months[$currentMonth+1]."</h2>";};


      //łaczymy się z bazą danych
      require 'link.php';
      if (!$link) {
          echo "Error: Unable to connect to MySQL." . PHP_EOL;
          echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
          exit;
      }
      // wysyłamy zapytanie o program posortowany wg daty i godziny
      $query = "SELECT * FROM program WHERE archiwum=1 ORDER BY dzien DESC, godzina DESC ";
      $year = array();
      $month = array();
      $month[2015] = array();
      $month[2016] = array();
      $month[2017] = array();
      $month[2018] = array();
      $month[2019] = array();
      $month[2020] = array();
      $month[2021] = array();
      $month[2022] = array();

      // print_r($month);


      if ($result = mysqli_query($link, $query)) {
          $dzisiaj = date("Y-m-j");
          /* fetch associative array */
          $nextMonthFlag = false;
          while ($row = mysqli_fetch_assoc($result)) {
            // obliczamy ile dni zostalo do pobraneo wydarzenia od dnia bieżącego
            $x=round((strtotime($dzisiaj)-strtotime($row['dzien']))/86400);
            // zapisujemy miesiąc wydarzenia (0 styczeń, 11 grudzień)
            $eventYear = intval($row['dzien'][0].$row['dzien'][1].$row['dzien'][2].$row['dzien'][3]);
            $eventMonth = intval($row['dzien'][5].$row['dzien'][6])-1;

            if (($row['archiwum'] == 1)){

              if (!in_array($eventYear, $year)) {
                  echo "<h2 class='yearName'>$eventYear</h2>";
                  $year[] = $eventYear;
              };
              if (!in_array($eventMonth, $month[$eventYear])) {
                  echo "<h2 class='monthName'>$monthsSmall[$eventMonth]</h2>";
                  $month[$eventYear][] = $eventMonth;
              };

              if (( $nextMonthFlag == false) && (intval($row['dzien'][5].$row['dzien'][6]-1) == $nextMonth)) {
                echo "<h2 class='monthName'> TŁUSTY ".$months[$nextMonth]."</h2>";
                $nextMonthFlag = true;
              };

              echo "<div class='row wyda'"
              ."data-id='"
              .$row['id']
              ."' data-date='".$row['dzien']."'>"
              ."<div class='col-xs-2 col-sm-2 col-md-2 col-lg-1 dzien'><span>"
              .$row['dzien'][8].$row['dzien'][9]
              ."</span><span>"
              .dayOfEvent($row['dzien'])
              ."</span></div><div class='col-xs-2 col-sm-2 col-md-2 col-lg-1 time'>"
              // .$row['godzina'][0].$row['godzina'][1].$row['godzina'][2].$row['godzina'][3].$row['godzina'][4]
              ."</div><div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 title'><h2><a  class='eventTitle'>"
              .$row['tytul']
              ."</a></h2><span>"
              .$row['kto']
              ."</span></div><div class='col-xs-8 col-xs-offset-4 col-sm-8 col-sm-offset-4 col-md-8 col-md-offset-4 col-lg-2 col-lg-offset-0 buy-ticket'>"
              ."</div></div>"
              ."<div class='descriptionProgramEvent hiddenDescription'>"
              ."<img class='img-responsive img-rounded' src='images/".$row['plik']."'>"
              ."<p>"
              .$row['opis']
              ."</p></div>"; //obraz i opis;
            };


              // echo $row["id"]." ".$row["tytul"]." ".$row["kto"]." ".$row["dzien"]." "
              //     .$row["godzina"]." ".$row["iloscmiejsc"]." ".$row["opis"]." ".$row["rodzaj"]." "
              //     .$row["plik"]." ".$row["archiwum"]." ".$row["bilety"]." "
              //     .$row["biletyURL"]." ".$row["rezerwacje"]." ".$row["rezerwacjeURL"]." ";


          }

          /* free result set */
          mysqli_free_result($result);
      }


      mysqli_close($link);

      function dayOfEvent($day){
        $days = array(
            "Monday"    => "Pn",
            "Tuesday"   => "Wt",
            "Wednesday" => "Śr",
            "Thursday"  => "Czw",
            "Friday"    => "Pt",
            "Saturday"  => "Sb",
            "Sunday"    => "Nd"
        );
        return $days[date("l",strtotime($day))];
      };
  ?>
