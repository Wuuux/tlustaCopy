<?PHP
    // pobiera dane email: 'email' i wysyła pocztę

    $link = mysqli_connect("tlustalangusta.pl", "serwer13866_exer", "2xusta", "serwer13866_exercise");

    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    // echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
    // echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

    $adres = $_POST['email'];

    require 'PHPMailerAutoload.php';

    require_once('class.phpmailer.php');      // dodanie klasy phpmailer
    require_once('class.smtp.php');           // dodanie klasy smtp

    $mail = new PHPMailer();                  //utworzenie nowej klasy phpmailer
    $mail->CharSet  = 'UTF-8';
    $mail->From     = "tlustalangusta@tlustalangusta.pl";     //Pełny adres e-mail
    $mail->FromName = "Tłusty Miesiąc";                       //imię i nazwisko lub nazwa użyta do wysyłania wiadomości
    $mail->Host     = "mail-serwer13866.lh.pl";               //adres serwera pocztowego SMTP
    $mail->Mailer   = "smtp";                                 //do wysłania zostanie użyty serwer SMTP
    $mail->SMTPAuth = true;                                   //włączenie autoryzacji do serwera SMTP
    $mail->Username = "tlustalangusta@tlustalangusta.pl";     //nazwa użytkownika do skrzynki e-mail
    $mail->Password = "2XXusta2XXusta";                       //hasło użytkownika do skrzynki e-mail
    $mail->Port     = 587;                                    //port serwera SMTP
    $mail->Subject  = "Temat - Tłusty Program";               //Temat wiadomości, można stosować zmienne i znaczniki HTML
    $mail->Body     = "Treść wiadomości<br>"
    .file_get_contents('contents.html')                       //zawartość email
    ."<a href='http://tlustalangusta.pl/demo/usun.php?adresEmail="
    .$adres."'>wypisz się</a>";                               //Treść wiadomości, można stosować zmienne i znaczniki HTML

     $mail->IsHTML(true);
     $mail->AddReplyTo('tlustalangusta@tlustalangusta.pl', 'Republika Sztuki Tłusta Langusta');
     $mail->AddAttachment('images/tlustaBW.jpg');       // attachment
     $mail->SMTPAutoTLS = true;                         //włączenie TLS
     $mail->SMTPSecure = '';    //
     $mail->AddAddress ("wojciech.winski@gmail.com","Marek Nowak");    //adres skrzynki e-mail oraz nazwa
     //   $mail->AddAddress('whoto@otherdomain.com', 'John Doe');
     //   $mail->SetFrom('name@yourdomain.com', 'First Last');
     //   $mail->AddReplyTo('name@yourdomain.com', 'First Last');
      //  $mail->Subject = 'Temat wiadomości';
     //   $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //  $mail->MsgHTML(file_get_contents('contents.html'));

     //   $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
     //   $mail->Send();
     //   echo "Message Sent OK<p></p>\n";
     // } catch (phpmailerException $e) {
     //   echo $e->errorMessage(); //Pretty error messages from PHPMailer
     // } catch (Exception $e) {
     //   echo $e->getMessage(); //Boring error messages from anything else!
     // }
    if($mail->Send())    //sprawdzenie wysłania, jeśli wiadomość została pomyślnie wysłana
        {
            // echo 'E-mail został wysłany'; //wyświetl ten komunikat
        }
        else    //w przeciwnym wypadku
            {
            echo 'E-mail nie mógł zostać wysłany';    //wyświetl następujący
            }
    mysqli_close($link);
?>
