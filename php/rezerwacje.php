<?php
require('functions.php');

$infoHead = "<html lang='en'>
                    <head>
                        <meta charset='utf-8'>
                        <title>Republika Sztuki Tłusta Langusta</title>
                        <style >
                            .ważne {color: red; }
                            .wydarzenie {font-size: 26px; font-weight: bold; margin: 20px 0px;}
                            a {color: white;}
                            a:hover {background-color: red; text-decoration: line-through;}
                        </style
                    </head>
                    <body style='background:black; padding: 10% 20%; line-height: 1.8em; color: white;'>";

$infoEnd =         "</body>
            </html>";
if ($_SERVER['REQUEST_METHOD']==="GET") {

  //make temporary reservation
  if ( isset($_GET['email']) && isset($_GET['count']) && isset($_GET['id']) ) {

          $id     = $_GET['id'];
          $email  = $_GET['email'];
          $count  = $_GET['count'];

          // server settings
          $link = mysqli_connect("tlustalangusta.pl", "serwer13866_exer", "2xusta", "serwer13866_exercise");

          if (!$link) {
              echo "Error: Unable to connect to MySQL." . PHP_EOL;
              echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
              exit;
          };
          // status reservation
          $status = false;
          // check total number of reservation
          $query = "SELECT rezerwacje FROM program WHERE id=".$id;

          if ($result = mysqli_query($link, $query)) {

              while ($row = mysqli_fetch_assoc($result)) {
                $rezerwacje = $row["rezerwacje"];
              };
              mysqli_free_result($result);

              // remove rows with timeouts
              $query = "UPDATE rezerwacje SET valid=0,temp=0 WHERE temp=1 AND czas<'".date('Y-m-d H:i:s',time()-35*60)."'";
              mysqli_query($link, $query);

              // count sum of reserations (valid plus temporary)
              $query = "SELECT SUM(ilosc) AS 'sum' FROM rezerwacje WHERE (temp=1 OR valid=1) AND idwydarzenia=".$id;
              if ($result = mysqli_query($link, $query)) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $sum = $row["sum"];
                  };
                  mysqli_free_result($result);
              };
              // check possibility of reservation
              if ($rezerwacje < $sum + $count)
                  { //not possible
                    echo $infoHead;
                    echo "<p>Niestety! Pula możliwych rezerwacji wynosi ".($rezerwacje-$sum)."</p>";
                    echo "<p>Próbujesz zarezerwować ilość miejsc : $count </p>";
                    echo "<p>Pozdrawiamy Tłusto!</p>";
                    echo "<p><a href='http://tlustalangusta.pl' class='link'>Republika Sztuki Tłusta Langusta</a><p>";
                    echo $infoEnd;
                    $status = false;
                  }
              else
                  { //possible
                    echo $infoHead;
                    echo "<p>Dziękujemy! Potwierdź rezerwację klikając w link, który otrzymasz w emailu.</p>";
                    echo "<p>Brak potwierdzenia będzie skutkował anulowaniem rezerwacji!</p>";
                    echo "<p>Pozdrawiamy Tłusto!</p>";
                    echo "<p><a href='http://tlustalangusta.pl' class='link'>Republika Sztuki Tłusta Langusta</a><p>";
                    echo $infoEnd;
                    $status = true;
                  };
              // check actual time
              $czas = time();
              // set temporary reservation
              if ( ( $status==true ) && ( $stmt = mysqli_prepare($link, "INSERT INTO rezerwacje (idwydarzenia, ilosc, email, czas)VALUES (?, ?, ?, ?)") )) {
                  mysqli_stmt_bind_param($stmt, 'iiss', $id, $count, $email, date("Y-m-d H:i:s",$czas));
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);
                  // get the title and date of event
                  $query = "SELECT tytul,dzien, godzina FROM program WHERE id=".$id;
                  if ($result = mysqli_query($link, $query)) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        $tytul    = $row["tytul"];
                        $data     = $row["dzien"];
                        $godzina  = $row["godzina"];
                      };
                      mysqli_free_result($result);
                  };

                  $body = $infoHead.
                          "<p>Witaj!<br>Potwierdź swoją rezerwację! </p>
                           <p>Wydarzenie: $tytul </p>
                           <p>Data: $data </p>
                           <p>Godzina: $godzina </p>
                           <p>Ilość rezerwacji: $count </p>
                           <p>Twoj adres e-mail: $email </p>
                           <p>Potwierdź rezerwację klikając w link :
                           <a href='http://tlustalangusta.pl/ww/php/rezerwacje.php?action=set&tytul="
                           ."$tytul&data=$data&godzina=$godzina&czas=$czas&email=$email&ilosc=$count'>POTWIERDZAM REZERWACJĘ</a></p>
                           <p>Anuluj rezerwację klikając w link :
                           <a href='http://tlustalangusta.pl/ww/php/rezerwacje.php?action=delete&tytul="
                           ."$tytul&data=$data&godzina=$godzina&czas=$czas&email=$email&ilosc=$count'>ANULUJĘ REZERWACJĘ</a></p>
                           <p>Po 30 minutach niepotwierdzona rezerwacja może zostać anulowana!</p>
                           <p>Pozdrawiamy Tłusto!</p>"
                           .$infoEnd;
                  $subject = "$tytul || $data || $godzina - potwierdź rezerwację";
                  $nazwa = $email;
                  $statusEmail = false;
                  $messageError = "";
                  sendEmailFromTlustaLangusta($subject, $body, $email, $nazwa, $statusEmail, $messageError);
                  if($statusEmail == true)    //sprawdzenie wysłania, jeśli wiadomość została pomyślnie wysłana
                         {
                             echo 'E-mail został wysłany!';
                         }
                  else
                         {
                             echo 'E-mail nie mógł zostać wysłany';
                             echo "Mailer Error: " . $mail->ErrorInfo;
                         };
              };
          };
  };

  // make confirmation
  if ( isset($_GET['action']) && isset($_GET['tytul']) && isset($_GET['data']) && isset($_GET['godzina']) && isset($_GET['czas']) && isset($_GET['email']) && isset($_GET['ilosc'])) {

          $action   = $_GET['action'];
          $tytul    = $_GET['tytul'];
          $data     = $_GET['data'];
          $godzina  = $_GET['godzina'];
          $email    = $_GET['email'];
          $czas     = $_GET['czas'];
          $ilosc     = $_GET['ilosc'];

          if ($action == 'delete')
          {
                  $link = mysqli_connect("tlustalangusta.pl", "serwer13866_exer", "2xusta", "serwer13866_exercise");

                  if (!$link)
                  {
                      echo "Error: Unable to connect to MySQL." . PHP_EOL;
                      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                      exit;
                  };
                      $query = "UPDATE rezerwacje SET valid=0,temp=0 WHERE czas='".date('Y-m-d H:i:s',$czas)."'";
                      if ($result = mysqli_query($link, $query))
                              {
                                echo    $infoHead.
                                       "<p>Twoja rezerwacja:<p>
                                        <p>Wydarzenie: $tytul </p>
                                        <p>Data: $data </p>
                                        <p>Godzina: $godzina </p>
                                        <p>Ilość rezerwacji: $ilosc </p>
                                        <p>Adres e-mail: $email </p>
                                        <p>Została anulowana!</p>
                                        <p>Pozdrawiamy Tłusto!</p>
                                        <p><a href='http://tlustalangusta.pl' class='link'>Republika Sztuki Tłusta Langusta</a></p>"
                                        .$infoEnd;
                              }
                      else
                              {
                                echo "Cos poszło nie tak z anulowaniem rezerwacji...";
                              };
          };

          if ($action == 'set')
          {
            $link = mysqli_connect("tlustalangusta.pl", "serwer13866_exer", "2xusta", "serwer13866_exercise");

                if (!$link)
                  {
                      echo "Error: Unable to connect to MySQL." . PHP_EOL;
                      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                      exit;
                  };

                $query = "SELECT temp FROM rezerwacje WHERE czas='".date('Y-m-d H:i:s',$czas)."'";


                mysqli_query($link, $query);
                if ($result = mysqli_query($link, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                      $temp = $row["temp"];
                    };
                };

                if ($temp == 1) //reservation still valid
                      {
                            $query = "UPDATE rezerwacje SET valid=1,temp=0 WHERE czas='".date('Y-m-d H:i:s',$czas)."'";

                            if ($result = mysqli_query($link, $query))
                                    {
                                      echo    $infoHead.
                                             "<p>Twoja rezerwacja:<p>
                                              <p>Wydarzenie: $tytul </p>
                                              <p>Data: $data </p>
                                              <p>Godzina: $godzina </p>
                                              <p>Ilość rezerwacji: $ilosc </p>
                                              <p>Adres e-mail: $email </p>
                                              <p>Została potwierdzona!</p>
                                              <p>Pozdrawiamy Tłusto!</p>
                                              <p><a href='http://tlustalangusta.pl'>Republika Sztuki Tłusta Langusta</a></p>"
                                              .$infoEnd;

                                              //send email
                                              $body = $infoHead.
                                                      "<p>Witaj!</p>
                                                       <p>Oto Twoja rezerwacja! </p>
                                                       <p>Wydarzenie: $tytul </p>
                                                       <p>Data: $data </p>
                                                       <p>Godzina: $godzina </p>
                                                       <p>Ilość rezerwacji: $ilosc </p>
                                                       <p>Twoj adres e-mail: $email </p>
                                                       <p>Przyjdź najpóźniej na 20 minut przed wydarzeniem i odbierz bilety!</p>
                                                       <p>Jeśli się rozmyślisz - prosimy o anulowanie rezerwacji!</p>

                                                       <p>Anulujesz rezerwację klikając w link :
                                                       <a href='http://tlustalangusta.pl/ww/php/rezerwacje.php?action=delete&tytul="
                                                       ."$tytul&data=$data&godzina=$godzina&czas=$czas&email=$email'>ANULUJĘ REZERWACJĘ</a></p>
                                                       <p>Pozdrawiamy Tłusto!</p>"
                                                       .$infoEnd;
                                              $subject = "$tytul || $data || $godzina - rezerwacja POTWIERDZONA!";
                                              $nazwa = $email;
                                              $statusEmail = false;
                                              $messageError = "";
                                              sendEmailFromTlustaLangusta($subject, $body, $email, $nazwa, $statusEmail, $messageError);
                                              if($statusEmail == true)    //sprawdzenie wysłania, jeśli wiadomość została pomyślnie wysłana
                                                     {
                                                         echo 'E-mail został wysłany!';
                                                     }
                                              else
                                                     {
                                                         echo 'E-mail nie mógł zostać wysłany';
                                                         echo "Mailer Error: " . $mail->ErrorInfo;
                                                     };
                                    }
                            else
                                    {
                                      echo "Cos poszło nie tak z potwierdzeniem rezerwacji...";
                                    };
                      }
                else
                      { // reservation invalid - timeout
                        echo    $infoHead.
                               "<p>Niestety! Twoja rezerwacja:<p>
                                <p>Wydarzenie: $tytul </p>
                                <p>Data: $data </p>
                                <p>Godzina: $godzina </p>
                                <p>Ilość rezerwacji: $count </p>
                                <p>Adres e-mail: $email </p>
                                <p>Wygasła!</p>
                                <p>Spróbuj wykonać nową rezerwację!</p>
                                <p>Pozdrawiamy Tłusto!</p>
                                <p><a href='http://tlustalangusta.pl' class='link'>Republika Sztuki Tłusta Langusta</a></p>"
                                .$infoEnd;
                      };
          };
  };


};



?>
