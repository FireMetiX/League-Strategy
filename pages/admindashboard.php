<?php 
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

    // Variabeln vorbereiten
    if ( isset($_GET["action"]) ) {
        $deletingUser = $_GET["user"];
    } else {
        $deletingUser = "";
    }

    // Messages und Errors vorbereiten
    $message = false;
    $messages = array();
    $error = false;
    $errors = array();


    // Prüft, ob ein Admin angemeldet ist
    if ($_SESSION["isLoggedin"] == 1 && $_SESSION["role"] == "admin") {

        // Prüft, ob man auf Delete User gedrückt hat
        if (isset($_GET["action"]) ) {
            if ($_GET["action"] == "deleteTRUE") {

                // User Query vorbereiten
                $queryuser = "SELECT * FROM `users` WHERE`ID`='$deletingUser'";
                $resultatuser = mysqli_query($conn, $queryuser);
                $numRowsuser = mysqli_num_rows($resultatuser);

                $deletedUser = mysqli_fetch_assoc($resultatuser);

                // alle Guides, die vom User erstellt worden sind, aufrufen
                $queryuserGuides = "SELECT * FROM `guides` WHERE `userID`='" . $deletedUser['ID'] . "'";
                $resultatuserGuides = mysqli_query($conn, $queryuserGuides);
                $numRowsuserGuides = mysqli_num_rows($resultatuserGuides);

                $userGuides = mysqli_fetch_all($resultatuserGuides, MYSQLI_ASSOC);
                // echo "Deleted";

                // Prüfen ob der User existiert
                if ($numRowsuser == 1) {

                    // Delete Querys vorbereiten

                    if ($numRowsuserGuides >= 1) {
                        $QueryDeleteguides = "DELETE FROM `guides` WHERE `userID`='" . $deletedUser['ID'] . "'";
                        $dataDelete = mysqli_query($conn, $QueryDeleteguides);
                        if ( $dataDelete ) {
                            $message = true;
                            array_push($messages, "Alle Guides vom User wurden erfolgreich gelöscht!");
                            // echo "Deleted";
                        } else {
                            // echo "Not Deleted";
                        }
                        // echo "Guides gefunden";
                    } 

                    $QueryDeletethis = "DELETE FROM `users` WHERE `ID`='" . $deletedUser['ID'] . "'";
                        $dataDelete = mysqli_query($conn, $QueryDeletethis);
                        if ( $dataDelete ) {
                            $message = true;
                            array_push($messages, "Der User wurde erfolgreich gelöscht!");
                            // echo "Deleted";
                        } else {
                            $error = true;
                            array_push($errors, "Der User konnte nicht gelöscht werden!");
                        }
                } else {
                    $error = true;
                    array_push($errors, "Kein User gefunden!");
                }
            }
        }
    


        // Guides Query vorbereiten
        $queryguides = "SELECT * FROM `guides` ORDER BY `ID` DESC LIMIT 0,3";
        $resultatguides = mysqli_query($conn, $queryguides);
        $numRowsguides = mysqli_num_rows($resultatguides);

        $guides = mysqli_fetch_all($resultatguides, MYSQLI_ASSOC);

        // Users Query vorbereiten
        $queryusers = "SELECT * FROM `users` ORDER BY `ID` DESC LIMIT 0,3";
        $resultatusers = mysqli_query($conn, $queryusers);
        $numRowsusers = mysqli_num_rows($resultatusers);

        $users = mysqli_fetch_all($resultatusers, MYSQLI_ASSOC);

    } else {
        die("Du hast keine Berechtigung diese Page aufzurufen");
    }

    // Query einstellen
    // $query = "SELECT * FROM `users` WHERE `username`='$username'";
    // $resultat = mysqli_query($conn, $query);

    // Prüfen ob der User angemeldet ist und die Rolle Admin hat

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
    <link rel="stylesheet" href="../css/style_admindashboard.css">
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

        <section class="admin">

            <h1>Admin Dashboard</h1>

            <div class="adminContainer">

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

                <div class="latestGuidesContainer">

                    <h2>Neusten Guides</h2>

                    <?php 
                    
                    if($numRowsguides >= 1){
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

                    <a href="guides"><p class="latestGuidesToTheGuides">Zu den Guides</p></a>

                </div>

                <div class="latestRegistrationsContainer">

                    <h2>Neuste Registrierungen</h2>

                    <?php

                        if($numRowsusers >= 1){
                            foreach( $users as $user ) {
                                echo "<div class='user'>";
                                echo "<div class='userinfo'><p class='username'>" . $user["username"] . "</p></div>";
                                echo "<div class='useremail'><p class='email'>" . $user["email"] . "</p></div>";
                                echo "<div class='userdate'><p class='date'>created in " . $user["created"] . "</p></div>";
                                echo "</div>";

                                echo "<div class='usereditContainer'>";
                                echo "<div class='userDelete'><a href='admindashboard?";
                                echo "action=delete&user=" . $user["ID"] . "'>";
                                echo "<i class='far fa-trash-alt'></i></a>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='displayerrors'>"; 
                                echo "<p>Keine User vorhanden!</p>";
                            echo "</div>";
                        }

                        if ( isset($_GET["action"]) ) {
                            if($_GET["action"] == "delete"){
                                echo "<div class='deleteContainer'>";
                                    echo "<div class='deleteQuestion'>";
                                        echo "<p>Willst du wirklich den User löschen?</p>";
                                        echo "<p>Alle vom User erstellten Guides werden auch gelöscht!</p>";
                                        echo "<div class='deleteYesNo'>";
                                            echo "<a href='admindashboard?action=deleteTRUE&user=" . $_GET["user"] . "'>Ja</a>";
                                            echo "<a href='admindashboard'>Nein</a>";
                                        echo "</div>";
                                    echo "</div>";
                                echo "</div>";
                            }
                        }

                    ?>

                    <!-- <div class='user'>

                        <div class="userinfo">
                            <p class="username">TrollmasterxXxX</p>
                        </div>
                        <div class="useremail">
                            <p class="email">Trollmaster@gmail.com</p>
                        </div>
                        <div class="userdate">
                            <p class="date">created in 05.07.2020</p>
                        </div>

                    </div>

                    <div class="user">

                        <div class="userinfo">
                            <p class="username">TrollmasterxXxX</p>
                        </div>
                        <div class="useremail">
                            <p class="email">Trollmaster@gmail.com</p>
                        </div>
                        <div class="userdate">
                            <p class="date">created in 05.07.2020</p>
                        </div>

                    </div>

                    <div class="user">

                        <div class="userinfo">
                            <p class="username">TrollmasterxXxX</p>
                        </div>
                        <div class="useremail">
                            <p class="email">Trollmaster@gmail.com</p>
                        </div>
                        <div class="userdate">
                            <p class="date">created in 05.07.2020</p>
                        </div>

                    </div> -->

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
    
</body>

</html>