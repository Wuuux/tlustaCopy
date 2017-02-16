
<?php


                function sendEmailFromTlustaLangusta( $subject, $body, $email, $nazwa, &$status, &$message ){

                        require 'PHPMailerAutoload.php';

                        require_once('class.phpmailer.php');      // dodanie klasy phpmailer
                        require_once('class.smtp.php');           // dodanie klasy smtp

                        $mail = new PHPMailer();                  //utworzenie nowej klasy phpmailer
                        $mail->CharSet  = 'UTF-8';
                        $mail->From     = "tlustalangusta@tlustalangusta.pl";     //Pełny adres e-mail
                        $mail->FromName = "Republika Sztuki Tłusta Langusta";                       //imię i nazwisko lub nazwa użyta do wysyłania wiadomości
                        $mail->Host     = "mail-serwer13866.lh.pl";               //adres serwera pocztowego SMTP
                        $mail->Mailer   = "smtp";                                 //do wysłania zostanie użyty serwer SMTP
                        $mail->SMTPAuth = true;                                   //włączenie autoryzacji do serwera SMTP
                        $mail->Username = "tlustalangusta@tlustalangusta.pl";     //nazwa użytkownika do skrzynki e-mail
                        $mail->Password = "2XXusta2XXusta";                       //hasło użytkownika do skrzynki e-mail
                        $mail->Port     = 587;                                    //port serwera SMTP
                        $mail->Subject  = $subject;               //Temat wiadomości, można stosować zmienne i znaczniki HTML
                        $mail->Body     = $body;
                        $mail->IsHTML(true);
                        $mail->AddReplyTo('tlustalangusta@tlustalangusta.pl', 'Republika Sztuki Tłusta Langusta');
                        $mail->SMTPAutoTLS = true;                         //włączenie TLS
                        $mail->SMTPSecure = '';    //
                        $mail->AddAddress ($email,$nazwa);    //adres skrzynki e-mail oraz nazwa

                          if($mail->Send())    //sprawdzenie wysłania, jeśli wiadomość została pomyślnie wysłana
                            {
                                $status = true;
                                $message = "Email został wysłany";
                            }
                          else    //w przeciwnym wypadku
                            {
                                $status = false;
                                $message = "Mailer Error: " . $mail->ErrorInfo;
                            };
                };



?>
