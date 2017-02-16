
<?PHP
    // data form program.html
    // server settings

    require './link.php';


    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        exit;
    }

    //receiving data
    $id           = $_POST['id'];
    $tytul        = $_POST['tytul'];
    $kto          = $_POST['kto'];
    $dzien        = $_POST['dzien'];
    $godzina      = $_POST['godzina'];
    $iloscMiejsc  = $_POST['iloscMiejsc'];
    $opis         = $_POST['opis'];
    $rodzaj       = $_POST['rodzaj'];
    $plik         = $_POST['plik'];
    $archiwum     = $_POST['archiwum'];
    $bilety       = $_POST['bilety'];
    $biletyURL    = $_POST['biletyURL'];
    $rezerwacje   = $_POST['rezerwacje'];
    $rezerwacjeURL= $_POST['rezerwacjeURL'];


    if ( $stmt = mysqli_prepare($link,
    "UPDATE program SET tytul=?, kto=?, dzien=?, godzina=?, iloscmiejsc=?,
                        opis=?, rodzaj=?, plik=?, archiwum=?, bilety=?,
                        biletyURL=?, rezerwacje=?, rezerwacjeURL=?
                        WHERE id=?") ) {
    // "UPDATE program SET tytul=$tytul, kto=$kto, dzien=$dzien, godzina=$godzina, iloscmiejsc=$iloscmiejsc,
    //                     opis=$opis, rodzaj=$rodzaj, plik=$plik, archiwum=$archiwum, bilety=$bilety,
    //                     biletyURL=$biletyURL, rezerwacje=$rezerwacje, rezerwacjeURL=$rezerwacjeURL
    //                     WHERE id=$id") ) {

        /* bind parameters for markers */
        mysqli_stmt_bind_param($stmt, 'ssssisssiisisi', $tytul, $kto, $dzien, $godzina, $iloscMiejsc, $opis, $rodzaj, $plik, $archiwum, $bilety, $biletyURL, $rezerwacje, $rezerwacjeURL, $id);

        /* execute query */
        mysqli_stmt_execute($stmt);
        // printf("%d Row inserted.\n", mysqli_stmt_affected_rows($stmt));
        /* close statement */
        mysqli_stmt_close($stmt);


    } //end if

    //close connection
    mysqli_close($link);
    header("Location: ".$_SERVER['HTTP_REFERER']);
?>
