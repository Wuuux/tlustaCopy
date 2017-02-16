
<?PHP

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


//POST
if ($_SERVER['REQUEST_METHOD']==="POST") {
  if ( isset($_POST['familyName']) && isset($_POST['name']) && isset($_POST['email']) ) {


                    $link = mysqli_connect("tlustalangusta.pl", "serwer13866_exer", "2xusta", "serwer13866_exercise");

                    if (!$link) {
                        echo "Error: Unable to connect to MySQL." . PHP_EOL;
                        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                        exit;
                    }

                    //receiving data
                    $nazwisko        = $_POST['familyName'];
                    $imie            = $_POST['name'];
                    $email           = $_POST['email'];
                    $valid           = false;

                    if ( $stmt = mysqli_prepare($link, "INSERT INTO newsletter (nazwisko, imie, email, valid)VALUES (?, ?, ?, ?)") )
                              {
                                  // insert user email into base - email is not valid yet!
                                  mysqli_stmt_bind_param($stmt, 'sssb', $nazwisko, $imie, $email, $valid);
                                  mysqli_stmt_execute($stmt);
                                  mysqli_stmt_close($stmt);

                                  require 'functions.php';
                                  // send email to user with question for copy request
                                  $nazwa    = $imie." ".$nazwisko;
                                  $subject  = "Newsletter - potwierdzenie";
                                  $body     =        $infoHead.
                                                     "<p>Witaj $imie $nazwisko!</p>
                                                      <p>Dziękujemy za zapisanie się na naszą listę odbiorców newslettera! </p>
                                                      <p>Twój adres email : ".$email."</p>

                                                      <p><span class='ważne'>WAŻNE!</span></p>
                                                      <p><span class='ważne'>Prosimy o potwierdzenie poprawności adresu!</span></p>

                                                      <p>Jeśli to Twój adres kliknij :</p>
                                                      <p><a href='http://tlustalangusta.pl/ww/php/newsletter.php?email=".$email."&action=set'>POTWIERDŹ</a></p>
                                                      <p>Jeśli to nie Twój adres kliknij : </p>
                                                      <p><a href='http://tlustalangusta.pl/ww/php/newsletter.php?email=".$email."&action=delete'>USUŃ</p>

                                                      <p>Pozdrawiamy Tłusto!</p>"
                                                      .$infoEnd;

                                  $statusEmail  = false;
                                  $messageError = "";

                                  sendEmailFromTlustaLangusta($subject, $body, $email, $nazwa, $statusEmail, $messageError );

                                  if ($statusEmail == true)    //sprawdzenie wysłania, jeśli wiadomość została pomyślnie wysłana
                                     {
                                           // show message obout sent email
                                           echo   $infoHead.
                                                  "<p>$imie $nazwisko!</p>
                                                   <p>Na Twój adres została wysłana wiadomość z prośbą o potwierdzenie adresu e-mail! </p>
                                                   <p>Sprawdź swoją skrzynkę i potwierdź adres. </p>
                                                   <p>Pozdrawiamy Tłusto!</p>
                                                   <p><a href='http://tlustalangusta.pl'>Republika Sztuki Tłusta Langusta</a></p>"
                                                  .$infoEnd;
                                     }
                                  else    //w przeciwnym wypadku
                                     {
                                           echo "E-mail nie mógł zostać wysłany - $messageError";
                                     };
                              } //end if
                    else
                              {
                                echo    $infoHead.
                                        "<p>$imie $nazwisko!</p>
                                        <p>Nie udało się dołączyć Twojego adresu do bazy! </p>
                                        <p>Spróbuj jeszcze raz.</p>
                                        <p>Pozdrawiamy Tłusto!</p>
                                        <p><a href='http://tlustalangusta.pl'>Republika Sztuki Tłusta Langusta</a></p>"
                                        .$infoEnd;

                              };

                    //close connection
                    mysqli_close($link);
  } // end of isset POST
  else {
    echo "Brak danych w POST";
  };
}; // end of POST




//GET
if ($_SERVER['REQUEST_METHOD']==="GET") {
  if ( isset($_GET['action']) && isset($_GET['email']) ) {

        //receiving data
        $action = $_GET['action'];
        $email  = $_GET['email' ];

        $link = mysqli_connect("tlustalangusta.pl", "serwer13866_exer", "2xusta", "serwer13866_exercise");

        if (!$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            exit;
        }


        if ( $action == 'set' )
            {
                $sql = "UPDATE newsletter SET valid=1 WHERE email=?";
            };

        if ( $action == 'delete' )
            {
                $sql = "UPDATE newsletter SET valid=0 WHERE email=?";
            };

        if ( $stmt = mysqli_prepare($link, $sql) )
              {
                  mysqli_stmt_bind_param($stmt, 's',$email);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);

                  if ($action == 'set')
                      {
                          echo    $infoHead.
                                 "<p>Dziękujemy!</p>
                                  <p>Adres $email został dołączony do listy mailingowej!</p>
                                  <p>Pozdrawiamy Tłusto!</p>
                                  <p><a href='http://tlustalangusta.pl'>Republika Sztuki Tłusta Langusta</a></p>"
                                  .$infoEnd;
                      };

                  if ($action == 'delete')
                      {
                          echo    $infoHead.
                                 "<p>Dziękujemy!</p>
                                  <p>Adres $email został usnięty z listy mailingowej!</p>
                                  <p>Pozdrawiamy Tłusto!</p>
                                  <p><a href='http://tlustalangusta.pl'>Republika Sztuki Tłusta Langusta</a></p>"
                                  .$infoEnd;
                      };

              }
        else
              {
                echo    $infoHead.
                       "<p>Przepraszamy!</p>
                        <p>Akcja zakończyła się niepowodzeniem!</p>
                        <p>Spróbuj jeszcze raz.</p>
                        <p>Pozdrawiamy Tłusto!</p>
                        <p><a href='http://tlustalangusta.pl'>Republika Sztuki Tłusta Langusta</a></p>"
                        .$infoEnd;
              };

        mysqli_close($link);



  }
  else {
    echo "Brak danych w GET";
  };
}; // end of GET

?>
