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

    // Wenn Logout geklickt wurde
    if (isset($_GET["action"]) && $_GET["action"] == "logout") {
        // echo "logout";
        session_destroy();
        header("Location: login.php");
        // echo "Logout erfolgreich";
    }

    // Prüft ob der user eingelogt ist.
    if ($_SESSION["isLoggedin"] == 1) {
        header("Location: ../index.php");
    }

    // Error array vorbereiten
    $errors = array();
    $error = false;

    // Variabeln definieren
    $username = "";

    // echo session_name();

    // Prüfen, ob auf Submit geklickt wurde
    if (isset($_POST["submit"])) {

        // Values speichern und desinfizieren
        $username = desinfect($_POST["username"]);
        $passwort = desinfect($_POST["password"]);

        // Error array vorbereiten
        $errors = array();
        $error = false;

        // print_r($username);

        // Query vorbereiten
        $query = "SELECT * FROM `users` WHERE `username`='$username'";
        $resultat = mysqli_query($conn, $query);
        $numRows = mysqli_num_rows($resultat);
        // $userInfos = mysqli_fetch_assoc($resultat);

        if($numRows == 1){
            $row = mysqli_fetch_assoc($resultat);
            if(password_verify($passwort,$row['passwortHash'])){
                // Infos an Session geben
                $_SESSION['isLoggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userID'] = $row['ID'];
                if ($row["roleID"] == 1) {
                    $_SESSION["role"] = "admin";
                } else {
                    $_SESSION["role"] = "user";
                }
                header("Location: ../index.php");

                // print_r($_SESSION["role"]);

                // print_r($_SESSION['username']);
                // echo "<pre>";
                // print_r($_SESSION['isLoggedin']);
                // echo "</pre>";
            }
            else{
                array_push($errors, "Passwort ist falsch");
                $error = true;
            }
        }
        else {
            array_push($errors, "Kein User gefunden");
            $error = true;
        } 
    
    }


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
    <link rel="stylesheet" href="../css/style_login.css">
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

        <section class="login">

            <div class="loginContainer">

                <h1>Login</h1>

                <hr>

                <form method="POST">

                    <label for="username">
                        <p>Username</p>
                        <input type="text" name="username" id="username" value="<?=$username?>">
                    </label>

                    <label for="password">
                        <p>Passwort</p>
                        <input type="password" name="password" id="password">
                    </label>

                    <button id="submit" name="submit">Login</button>

                </form>

                <?php 
            
                global $error;

                if ($error == true) {
                        echo "<div class='displayerrors'>"; 
                        foreach($errors as $error){
                            echo "<p>" . $error . "</p>";
                        }
                        echo "</div>";
                    } else {

                } ?>

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