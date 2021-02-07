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
    $nachname = "";
    $vorname = "";
    $username = "";
    $passwort = "";
    $email = "";

    // Prüfen, ob auf Submit geklickt wurde
    if (isset($_POST["submit"])) {
        
        // Values speichern und desinfizieren
        $nachname = desinfect($_POST["nachname"]);
        $vorname = desinfect($_POST["vorname"]);
        $username = desinfect($_POST["username"]);
        $passwort = desinfect($_POST["password"]);
        $email = desinfectEmail($_POST["email"]);

        // Passwort Hashen
        $hashPassword = password_hash($passwort,PASSWORD_DEFAULT);

        // echo ($email);
        // echo ($hashPassword);

        // Error array vorbereiten
        $errors = array();
        $error = false;

        // User Informationen zum validieren prüfen
        $queryUserInfo = "SELECT * FROM `users` WHERE `email`='$email'";
        $resultat = mysqli_query($conn, $queryUserInfo);
        $numRows = mysqli_num_rows($resultat);
        $row = mysqli_fetch_assoc($resultat);
        // echo $numRows;

        // Formular Validieren
        if ($nachname == "") {
            array_push($errors, "Kein Nachname gesetzt");
            $error = true;
        }
        if ($vorname == "") {
            array_push($errors, "Kein Vorname gesetzt");
            $error = true;
        }
        if ($username == "") {
            array_push($errors, "Kein Username gesetzt");
            $error = true;
        }
        if($numRows == 1){
            if ( $email == $row["email"] ) {
                array_push($errors, "Diese Email existiert schon");
                $error = true;
            } else if ($email == "") {
                array_push($errors, "Keine Email gesetzt");
                $error = true;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($errors, "Bitte eine echte Email eingeben");
                $error = true;
                // echo $email;
            }
        } else {
            if ($email == "") {
                array_push($errors, "Keine Email gesetzt");
                $error = true;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($errors, "Bitte eine echte Email eingeben");
                $error = true;
                // echo $email;
            }
        }

        if ($error == false) {
            // Query definieren mit stmt
            $query = "INSERT INTO `users` (`nachname`,`vorname`,`username`,`passwortHash`,`email`,`roleID`) 
            VALUES (?, ?, ?, ?, ?, 2)";

            $stmt = mysqli_prepare($conn, $query);

            mysqli_stmt_bind_param($stmt, "sssss", $nachname, $vorname, $username, $hashPassword, $email);

            if(mysqli_stmt_execute($stmt)){
                header("Location: login.php");
            }
            // else data not inserted
            else{
                echo "Ein Fehler ist aufgetreten";
            }
            
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
    <link rel="stylesheet" href="../css/style_register.css">
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

        <section class="register">

            <div class="registerContainer">

                <h1>Registrieren</h1>

                <hr>

                <form method="POST">

                    <label for="nachname">
                        <p>Nachname</p>
                        <input type="text" name="nachname" id="nachname" value="<?=$nachname?>">
                    </label>

                    <label for="vorname">
                        <p>Vorname</p>
                        <input type="text" name="vorname" id="vorname" value="<?=$vorname?>">
                    </label>

                    <label for="username">
                        <p>Username</p>
                        <input type="text" name="username" id="username" value="<?=$username?>">
                    </label>

                    <label for="password">
                        <p>Passwort</p>
                        <input type="password" name="password" id="password">
                    </label>

                    <label for="email">
                        <p>Email</p>
                        <input type="text" name="email" id="email" value="<?=$email?>">
                    </label>

                    <button id="submit" name="submit">Registrieren</button>

                </form>

            </div>

            <?php 
            
                global $error;

                if ($error == true) {
                        echo "<div class='displayerrors'>"; 
                        foreach($errors as $error){
                            echo "<p>" . $error . "</p>";
                        }
                        echo "</div>";
                    } else {

                } 
            ?>

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