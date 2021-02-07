<?php 
    // echo "<pre>";
    // print_r($_GET);
    // echo "</pre>";

        include_once("../includes/functions.inc.php");
        include_once("../includes/mysqli-connect.php");
        // print_r($_SESSION);
    
        // Session Cookie Name (Key) umbenennen: gegen Hijacking / Spyware
        if( session_name() != 'MeineSessionID'){
            session_name('MeineSessionID');
        }
    
        // Session starten
        session_start();
        session_regenerate_id();
    
        // Prüft, ob die der eintrag "isLoggedin" schon existiert
        if (isset($_SESSION["isLoggedin"])) {
        } else {
            $_SESSION["isLoggedin"] = false;
        }

        // Prüft, ob die der eintrag "role" schon existiert
        if (isset($_SESSION["role"])) {
        } else {
            $_SESSION["role"] = "none";
        }

         // Prüft, ob die der eintrag "userID" schon existiert
         if (isset($_SESSION["userID"])) {
        } else {
            $_SESSION["userID"] = "none";
        }

        // Variabeln definieren
        $nofilter = true;
        $role = "";
        $champion = "";

        // Messages und Errors vorbereiten
        $message = false;
        $messages = array();
        $error = false;
        $errors = array();

        // Prüfen, ob schon Page existiert
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
            // echo $page;
        } else {
            $page = 1;
        }
        $guidesPerPage = 7;
        $maxPages = 1;
        $currentPage = $page * $guidesPerPage - $guidesPerPage;

        // Query vorbereiten
        $queryAll = "SELECT * FROM `guides`";
        $resultatall = mysqli_query($conn, $queryAll);
        $numRows = mysqli_num_rows($resultatall);

        $maxPages = $numRows / $guidesPerPage;

        if (is_float($maxPages)){
            $maxPages = ceil($maxPages);
        }
        // echo $maxPages;

        // Reset URL
        if ( isset($_GET["role"]) or isset($_GET["championSelection"])) {
            if ($_GET["role"] == "all" && $_GET["championSelection"] == "0"){
                header("Location: ./guides");
            } else {
                $role = $_GET["role"];
                $champion = $_GET["championSelection"];
                $nofilter = false;
            }
        }

        // Prüfen, ob der delete Button betätigt worden ist
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "deleteTRUE") {
                // Query vorbereiten
                $QueryDelete = "SELECT * FROM `guides` WHERE `ID`='" . $_GET['guide'] . "'";
                $resultatDelete = mysqli_query($conn, $QueryDelete);
                $guideinfo = mysqli_fetch_assoc($resultatDelete);
                $numRowsDelete = mysqli_num_rows($resultatDelete);

                if ($numRowsDelete == 1) {
                    if ($_SESSION["userID"] == $guideinfo["userID"] || $_SESSION["role"] == "admin") {
                        // echo "Delete completed";
                        $QueryDeletethis = "DELETE FROM `guides` WHERE `ID`='" . $_GET['guide'] . "'";
                        $dataDelete = mysqli_query($conn, $QueryDeletethis);
                        if ( $dataDelete ) {
                            $message = true;
                            array_push($messages, "Der Guide wurde erfolgreich gelöscht!");
                            // echo "Deleted";
                        } else {
                            // echo "Not Deleted";
                        }
                    } else {
                        $error = true;
                        array_push($errors, "Du hast keine Berechtigung diesen Guide zu löschen!");
                    }
                } else {
                    $error = true;
                    array_push($errors, "Kein Guide gefunden!");
                }
            }
        }

        // Prüfen ob Filter aktiv sind
        if ($nofilter == 1) {

            // Query vorbereiten
            $queryAll = "SELECT * FROM `guides` ORDER BY `ID` DESC LIMIT $currentPage,$guidesPerPage";
            $resultat = mysqli_query($conn, $queryAll);
            $numRows = mysqli_num_rows($resultat);

            $guides = array();
    
            $guides = mysqli_fetch_all($resultat, MYSQLI_ASSOC);
        } else {
            if ($role == "all" ) {
                // Query mit filtern vorbereiten
                // echo $champion;
                // echo $role;
                $page = 1;
                $queryfiltered = "SELECT * FROM `guides` WHERE `champion`='$champion' ORDER BY `ID` DESC LIMIT $currentPage,$guidesPerPage";
                $resultat = mysqli_query($conn, $queryfiltered);

                // Query für die Anzahl Pages
                $querypages = "SELECT * FROM `guides` WHERE `champion`='$champion'";
                $resultatpages = mysqli_query($conn, $querypages);
                $numRows = mysqli_num_rows($resultatpages);

                $maxPages = $numRows / $guidesPerPage;

                if (is_float($maxPages)){
                    $maxPages = ceil($maxPages);
                }

                $guides = array();

                $guides = mysqli_fetch_all($resultat, MYSQLI_ASSOC);
            } else if ($champion == "0") {
                // Query mit filtern vorbereiten
                // echo $champion;
                // echo $role;
                $page = 1;
                $queryfiltered = "SELECT * FROM `guides` WHERE `role`='$role' ORDER BY `ID` DESC LIMIT $currentPage,$guidesPerPage";
                $resultat = mysqli_query($conn, $queryfiltered);

                 // Query für die Anzahl Pages
                 $querypages = "SELECT * FROM `guides` WHERE `role`='$role'";
                 $resultatpages = mysqli_query($conn, $querypages);
                 $numRows = mysqli_num_rows($resultatpages);
 
                 $maxPages = $numRows / $guidesPerPage;
 
                 if (is_float($maxPages)){
                     $maxPages = ceil($maxPages);
                 }

                $guides = array();

                $guides = mysqli_fetch_all($resultat, MYSQLI_ASSOC);
            } else {
                // Query mit filtern vorbereiten
                // echo $champion;
                // echo $role;
                $page = 1;
                $queryfiltered = "SELECT * FROM `guides` WHERE `role`='$role' and `champion`='$champion' ORDER BY `ID` DESC LIMIT $currentPage,$guidesPerPage";
                $resultat = mysqli_query($conn, $queryfiltered);

                // Query für die Anzahl Pages
                $querypages = "SELECT * FROM `guides` WHERE `role`='$role' and `champion`='$champion'";
                $resultatpages = mysqli_query($conn, $querypages);
                $numRows = mysqli_num_rows($resultatpages);

                $maxPages = $numRows / $guidesPerPage;

                if (is_float($maxPages)){
                    $maxPages = ceil($maxPages);
                }

                $guides = array();

                $guides = mysqli_fetch_all($resultat, MYSQLI_ASSOC);
            }
        }
        
  

        // echo "<pre>";
        // print_r($guides);
        // echo "</pre>";



?>

<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/favicon/apple-touch-icon-180x180">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="../css/brain.css">
    <link rel="stylesheet" href="../css/style_guides.css">
    <title>League-Strategie</title>

</head>

<body>

    <!-- navigation -->

    <nav>
        
        <div class="desktopNavigation">

            <div class="desktopNavigationleft">
            
                <a href="../index"><img src="../img/logo/LogoWithText2small.png" alt="Picture of my Logo"></a>

                <ul class="navigationUL">
                    <li class="navigationLI"><a href="../index">Home</a></li>
                    <li class="navigationLI"><a href="guides">Guides</a></li>
                    <li class="navigationLI"><a href="kontakt">Kontakt</a></li>
                    <?php if ($_SESSION["isLoggedin"] == 1) {
                        echo "<li class='navigationLI'><a href='createGuide'>Guide erstellen</a></li>";
                    } ?>
                    <?php if ($_SESSION["isLoggedin"] == 1 && $_SESSION["role"] == "admin") {
                        echo "<li class='navigationLI'><a href='admindashboard'>Admin Dashboard</a></li>";
                    } ?>

                </ul>

            </div>

            <div class="desktopNavigationright">

                <ul class="navigationUL">
                    <?php if($_SESSION["isLoggedin"] == 1){
                        echo "<p>".$_SESSION['username']. "</p>
                        <li class='navigationLI'><a href='login?action=logout'>Logout</a></li>";
                    } else {
                        echo "<li class='navigationLI'><a href='login'>Login</a></li>
                        <li class='navigationLI'><a href='register'>Register</a></li>";
                    } ?>
                </ul>

            </div>

        </div>

        <div class="mobileNavigation">

            <i class="fas fa-bars"></i>

            <div class="mobileNavigationContainer">

                <div class="moblieNavigationWrapper">
                    <div class="mobileLogin">
                    <?php if($_SESSION["isLoggedin"] == 1){
                        echo "<p>".$_SESSION['username']. "</p>
                        <p><a href='login?action=logout'>Logout</a></p>";
                    } else {
                        echo "<p><a href='login'>Login</a></p>
                        <p><a href='register'>Register</a></p>";
                    } ?>
                      
                    </div>
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="guides">Guides</a></li>
                        <li><a href="kontakt">Kontakt</a></li>
                        <?php if ($_SESSION["isLoggedin"] == 1) {
                            echo "<li><a href='createGuide'>Guide erstellen</a></li>";
                        } ?>
                        <?php if ($_SESSION["isLoggedin"] == 1 && $_SESSION["role"] == "admin") {
                        echo "<li><a href='admindashboard'>Admin Dashboard</a></li>";
                        } ?>
                    </ul>
                </div>

            </div> 

        </div>

    </nav>

    <!-- Main -->

    <section class="main">

        <section class="guides">

            <h1 class="guidesTitle">Starte deine steigung!</h1>

            <?php
            
                // Errors und Messages anzeigen            
                global $error;
                global $message;

                if ($error == true) {
                        echo "<div class='displayerrors'>"; 
                        foreach($errors as $error){
                            echo "<p>" . $error . "</p>";
                        }
                        echo "</div>";
                    } else {

                } 

                if ($message == true) {
                    echo "<div class='successContainer'>"; 
                    foreach($messages as $message){
                        echo "<p>" . $message . "</p>";
                    }
                    echo "</div>";
                } else {

                } 

            ?>

            <div class="guidesContainer">
                
                <div class="guidesFilter">

                    <form method="GET">

                        <div class="guidesLanes">

                            <p>Lane:</p>

                            <div class="lanes">

                                <label>
                                    <input type="radio" name="role" value="all" checked>
                                    <div class="lane"> All</div>
                                </label>

                                <label>
                                    <input type="radio" name="role" value="top">
                                    <div class="lane"> Top</div>
                                </label>

                                <label>
                                    <input type="radio" name="role" value="jungle">
                                    <div class="lane"> Jungle</div>
                                </label>

                                <label>
                                    <input type="radio" name="role" value="Mid">
                                    <div class="lane"> Mid</div>
                                </label>

                                <label>
                                    <input type="radio" name="role" value="Bot">
                                    <div class="lane"> Bot</div>
                                </label>

                                <label>
                                    <input type="radio" name="role" value="Support">
                                    <div class="lane"> Support</div>
                                </label>

                            </div>

                        </div>

                        <div class="guidesChampions">

                            <p>Champion:</p>

                            <label class="championSelection" for="championSelection">
                                <select id="championSelect" name="championSelection">
                                    <option value="0">Choose a Champion...</option>
                                    <?php
                                    // Get Champion data from json file
                                    $myJsonFile = file_get_contents("../json/champion.json");
                                    $array = json_decode($myJsonFile, true);
                                    $championarray = $array["data"];

                                    // var_dump($championarray["Aatrox"]);

                                    // Making Selection of every Champion
                                    foreach($championarray as $champion){ ?>
                                        <option value=<?php echo $champion["id"] ?>><?php echo $champion["name"] ?></option>
                                    <?php } ?>

                                </select>

                            </label>

                            <input type="hidden" name="page" value="1">

                            </div>

                            <div class="filtern">
                            <a href="guides"><button>Filter</button></a>
                            </div>

                    </form>

                </div>

                <hr>

                <div class="guidesInhalt">

                    <?php 
                    
                    if($numRows >= 1){
                        foreach( $guides as $guide ) {
                            echo "<a href='guide?guide=" . $guide["ID"] . "'><div class='guide'>";
                            echo "<img src='../img/champion/tiles/" . $guide["champion"] . "_0.jpg' alt='Picture of " . $guide["champion"] . "'>";
                            echo "<div class='guideInfos'>";
                            echo "<p class='title'>" . $guide["guideTitle"] . "</p>";
                            echo "<p class='smallInfo'>By <span>" . $guide["username"] . "</span> | posted in " . $guide["createDate"] . "</p>";
                            echo "</div>";
                            echo "</div></a>";
                            if ($_SESSION["userID"] == $guide["userID"] || $_SESSION["role"] == "admin") {
                                echo "<div class='guideUserEdit'>";
                                echo "<div class='guideEdit'><a href='createGuide?action=edit&guide=" . $guide["ID"] . "'><i class='far fa-edit'></i></a></div>";
                                echo "<div class='guideDelete'><a href='guides?";
                                if (isset($_GET["role"])) {
                                    echo "role=";
                                    echo $_GET["role"];
                                    echo "&championSelection=";
                                    echo $_GET["championSelection"];
                                    echo "&page=" . $page;
                                    echo "&action=delete&guide=" . $guide["ID"] . "'>";
                                } else if (isset($_GET["page"])){
                                    echo "page=";
                                    echo $page;
                                    echo "&action=delete&guide=" . $guide["ID"] . "'>";
                                } else {
                                    echo "action=delete&guide=" . $guide["ID"] . "'>";
                                }
                                echo "<i class='far fa-trash-alt'></i></a>";
                                echo "</div>";
                                echo "</div>";
                            }
                            
                            
                        }
                    } else {
                        echo "<div class='displayerrors'>"; 
                            echo "<p>Keine Guides vorhanden!</p>";
                        echo "</div>";
                    }
                    
                    ?>

                    <?php 
                        if ( isset($_GET["action"]) ) {
                            if($_GET["action"] == "delete"){
                                echo "<div class='deleteContainer'>";
                                    echo "<div class='deleteQuestion'>";
                                        echo "<p>Willst du wirklich den Guide löschen?</p>";
                                        echo "<div class='deleteYesNo'>";
                                            echo "<a href='guides?action=deleteTRUE&guide=" . $_GET["guide"] . "'>Ja</a>";
                                            echo "<a href='guides";
                                            if (isset($_GET["role"])) {
                                                echo "?role=";
                                                echo $_GET["role"];
                                                echo "&championSelection=";
                                                echo $_GET["championSelection"];
                                                echo "&page=" . $page;
                                                
                                            } else if (isset($_GET["page"])){
                                                echo "?page=";
                                                echo $page;
                                               
                                            } else {
                                                
                                            }
                                            echo "'>Nein</a>";
                                        echo "</div>";
                                    echo "</div>";
                                echo "</div>";
                            }
                        }

                    ?>

                    <div class="guidesPageSelection">

                        <?php  

                            // Prüft, die Seite, auf der man sich befindet noch weiter zurück kann, wenn nicht, wird der "back" button entfernt
                            if (isset ($_GET["page"])) {
                                if($_GET["page"] <= 1){
                                    // echo "hello";
                                } else {
                                    echo "<div class='backpage'><a href='guides?";
                                    if (isset($_GET['role'])) {
                                        echo "role=";
                                        echo $_GET["role"];
                                        echo "&championSelection=";
                                        echo $_GET["championSelection"];
                                        echo "&page=";
                                        $nextPage = $_GET["page"] - 1;
                                        echo $nextPage;
                                        // echo $page + 1;
                                    } else {
                                        echo "page=";
                                        echo $page - 1;
                                    }
                                     echo "'>Back</a></div>";
                                }
                            } else {

                            }

                        ?>

                        <div class="pages">
                            <?php 

                                // Definiere einige Variabeln
                                $startToCut = 4;
                                $pagedistance = 5;
                                $startToCutEnd = $maxPages - 4;

                                // Prüft, wenn nicht mehr als 4 Seiten existieren, soll es eine simplere Ansicht für Seiten anzeigen
                                if ( $maxPages <= 4) {
                                    for ($x = 1; $x <= $maxPages; $x++) {
                                        echo "<a ";
                                        if( $x == $_GET["page"] ){
                                            echo "class='active' ";
                                        } 
                                        echo "href='guides?";
                                        if (isset($_GET['role'])) {
                                            echo "role=" . $_GET["role"] . "&championSelection=" . $_GET["championSelection"] . "&page=";
                                            echo $x;
                                        } else {
                                            echo "&page=";
                                            echo $x;
                                        }
                                        
                                        echo "'>";
                                        echo $x;
                                        echo "</a>";
                                    }
                                } else {
                                    // Prüft, ob Filter aktiv sind damit die URL richtig umgeschrieben wird
                                    if ( isset($_GET["role"]) ) {
                                        // Prüft, ob die Seitenzahl, auf der man sich befindet, kleiner ist als der "starttocut" wert
                                        if ( $_GET["page"] <= $startToCut ) {
                                            for($x = 1; $x <= $startToCut + 1; $x++){
                                                echo "<a ";
                                                    if( $x == $_GET["page"] ){
                                                        echo "class='active' ";
                                                    }
                                                echo "href='guides?";
                                                echo "role=";
                                                echo $_GET["role"];
                                                echo "&championSelection=";
                                                echo $_GET["championSelection"];
                                                echo "&page=";
                                                echo $x;
                                                echo "'>";
                                                echo $x;
                                                echo "</a>";
                                            }
                                            echo "<a href='guides?";
                                            echo "role=";
                                            echo $_GET["role"];
                                            echo "&championSelection=";
                                            echo $_GET["championSelection"];
                                            echo "&page=";
                                            echo $maxPages;
                                            echo "'>";
                                            echo $maxPages;
                                            echo "</a>";
                                        } else if ( $_GET["page"] >= $startToCutEnd) { // Prüft ob die Seitenzahl grösser ist als der "starttocutEnd" wert
                                            for($x = 1; $x <= $startToCut + 1; $x++){
                                                $y = $maxPages - $startToCut + $x - 1;
                                                echo "<a ";
                                                if( $y == $_GET["page"] ){
                                                    echo "class='active'";
                                                }
                                                echo "href='guides?";
                                                echo "role=";
                                                echo $_GET["role"];
                                                echo "&championSelection=";
                                                echo $_GET["championSelection"];
                                                echo "&page=";
                                                echo $x + $maxPages - 5;
                                                echo "'>";
                                                echo $x + $maxPages - 5;
                                                echo "</a>";
                                            }
                                        } else { // erstellt die Seiten die der Nutzer zum navigieren braucht (bsp: Seitenanzahl ist 7 also wird folgendes angezeigt: 5, 6, 7, 8, 9 )
                                            for($x = 1; $x <= $pagedistance; $x++){
                                                echo "<a ";
                                                if( $_GET["page"] - $pagedistance + 2 + $x == $_GET["page"] ){
                                                    echo " class='active' ";
                                                } 
                                                echo "href='guides?";
                                                echo "role=";
                                                echo $_GET["role"];
                                                echo "&championSelection=";
                                                echo $_GET["championSelection"];
                                                echo "&page=";
                                                echo $_GET["page"] - $pagedistance + 2 + $x;
                                                echo "'>";
                                                echo $_GET["page"] - $pagedistance + 2 + $x;
                                                echo "</a>";
                                            }
                                            echo "<a href='guides?";
                                            echo "role=";
                                            echo $_GET["role"];
                                            echo "&championSelection=";
                                            echo $_GET["championSelection"];
                                            echo "&page=";
                                            echo $maxPages;
                                            echo "'>";
                                            echo $maxPages;
                                            echo "</a>";
                                        }
                                    } else {
                                        // Prüft, ob die Seitenzahl, auf der man sich befindet, kleiner ist als der "starttocut" wert
                                        if ( $page <= $startToCut ) {
                                            for($x = 1; $x <= $startToCut + 1; $x++){
                                                echo "<a ";
                                                if( $x == $page ){
                                                    echo "class='active' ";
                                                }
                                                echo "href='guides?";
                                                echo "&page=";
                                                echo $x;
                                                echo "'>";
                                                echo $x;
                                                echo "</a>";
                                            }
                                            echo "<a href='guides?";
                                            echo "page=";
                                            echo $maxPages;
                                            echo "'>";
                                            echo $maxPages;
                                            echo "</a>";
                                        } else if ( $page >= $startToCutEnd) { // Prüft ob die Seitenzahl grösser ist als der "starttocutEnd" wert

                                            for($x = 1; $x <= $startToCut + 1; $x++){
                                                $y = $maxPages - $startToCut + $x - 1;
                                                echo "<a ";
                                                if( $y == $page ){
                                                    echo "class='active' ";
                                                }
                                                echo "href='guides?";
                                                echo "&page=";
                                                echo $x + $maxPages - 5;
                                                echo "'>";
                                                echo $x + $maxPages - 5;
                                                echo "</a>";
                                            }
                                            
                                        } else { // erstellt die Seiten die der Nutzer zum navigieren braucht (bsp: Seitenanzahl ist 7 also wird folgendes angezeigt: 5, 6, 7, 8, 9 )
                                            for($x = 1; $x <= $pagedistance; $x++){
                                                echo "<a ";
                                                if( $page - $pagedistance + 2 + $x == $page ){
                                                    echo "class='active' ";
                                                } 
                                                echo "href='guides?";
                                                echo "&page=";
                                                echo $page - $pagedistance + 2 + $x;
                                                echo "'>";
                                                echo $page - $pagedistance + 2 + $x;
                                                echo "</a>";

                                       
                                            }
                                            echo "<a href='guides?=";
                                            echo $maxPages;
                                            echo "'>";
                                            echo $maxPages;
                                            echo "</a>";
                                        }
                                    }
                                }
                                    
                                
                                
                            ?>
                        </div>

                        <?php  
                            // Prüft, die Seite, auf der man sich befindet noch weiter vorwärts kann, wenn nicht, wird der "next" button entfernt
                            if (isset ($_GET["page"])) {
                                if($_GET["page"] >= $maxPages){
                                    // echo "hello";
                                } else {
                                    echo "<div class='nextpage'><a href='guides?";
                                    if (isset($_GET['role'])) {
                                        echo "role=";
                                        echo $_GET["role"];
                                        echo "&championSelection=";
                                        echo $_GET["championSelection"];
                                        echo "&page=";
                                        $nextPage = $_GET["page"] + 1;
                                        echo $nextPage;
                                        // echo $page + 1;
                                    } else {
                                        echo "page=";
                                        echo $page + 1;
                                    }
                                     echo "'>Next</a></div>";
                                }
                            } else {
                                if ( $page >= $maxPages ) {

                                } else {
                                    echo "<div class='nextpage'>";
                                    echo "<a href='guides?page=";
                                    echo $page + 1;
                                    echo "'>Next</a>";
                                }
                            }

                        ?>

                        
                            
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </section>

    <!-- Footer -->

    <footer>
        <img src="../img/logo/Logosmall.png" alt="Picture of my Logo">
        <p>&copy; COPYRIGHT Damir Mavric</p>
    </footer>

    <!-- SCRIPTS -->

    <!-- <script src="../js/jquery.js"></script> -->
    <script src="../js/toggleNav.js"></script>
    <!-- <script src="../js/delete.js"></script> -->
    
</body>

</html>