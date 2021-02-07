<?php
    include_once("./includes/functions.inc.php");
    include_once("./includes/mysqli-connect.php");
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


?>

<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="180x180" href="./img/favicon/apple-touch-icon-180x180">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="./css/brain.css">
    <link rel="stylesheet" href="./css/style_index.css">
    <title>League-Strategie</title>

</head>

<body>

    <!-- navigation -->

    <nav>
        
        <div class="desktopNavigation">

            <div class="desktopNavigationleft">
            
                <a href="./index"><img src="./img/logo/LogoWithText2small.png" alt="Picture of my Logo"></a>

                <ul class="navigationUL">
                    <li class="navigationLI"><a href="./index">Home</a></li>
                    <li class="navigationLI"><a href="./pages/guides">Guides</a></li>
                    <li class="navigationLI"><a href="./pages/kontakt">Kontakt</a></li>
                    <?php if ($_SESSION["isLoggedin"] == 1) {
                        echo "<li class='navigationLI'><a href='./pages/createGuide'>Guide erstellen</a></li>";
                    } ?>
                    <?php if ($_SESSION["isLoggedin"] == 1 && $_SESSION["role"] == "admin") {
                        echo "<li class='navigationLI'><a href='./pages/admindashboard'>Admin Dashboard</a></li>";
                    } ?>

                </ul>

            </div>

            <div class="desktopNavigationright">

                <ul class="navigationUL">
                    <?php if($_SESSION["isLoggedin"] == 1){
                        echo "<p>".$_SESSION['username']. "</p>
                        <li class='navigationLI'><a href='./pages/login?action=logout'>Logout</a></li>";
                    } else {
                        echo "<li class='navigationLI'><a href='./pages/login'>Login</a></li>
                        <li class='navigationLI'><a href='./pages/register'>Register</a></li>";
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
                        <p><a href='./pages/login?action=logout'>Logout</a></p>";
                    } else {
                        echo "<p><a href='./pages/login'>Login</a></p>
                        <p><a href='./pages/register'>Register</a></p>";
                    } ?>
                      
                    </div>
                    <ul>
                        <li><a href="./index.php">Home</a></li>
                        <li><a href="./pages/guides">Guides</a></li>
                        <li><a href="./pages/kontakt">Kontakt</a></li>
                        <?php if ($_SESSION["isLoggedin"] == 1) {
                            echo "<li><a href='./pages/createGuide'>Guide erstellen</a></li>";
                        } ?>
                        <?php if ($_SESSION["isLoggedin"] == 1 && $_SESSION["role"] == "admin") {
                        echo "<li><a href='./pages/admindashboard'>Admin Dashboard</a></li>";
                        } ?>
                    </ul>
                </div>

            </div> 

        </div>

    </nav>

    <!-- Main -->

    <section class="main">

        <section class="welcomePage">

            <img src="./img/logo/LogoWithTextsmall.png" alt="My Logo">
            <h1 class="welcomeText">Kreiere deinen Weg zum Sieg!</h1>
            <p><a class="welcomeButton" href="./pages/guides">Zu den Guides <img src="./img/symbols/other/Arrowsmall.png" alt="Arrow"></a></p>

        </section>

        <section class="aboutme">

            <p class="aboutTitle">Über mich</p>

            <div class="aboutmeContainer">

                <div class="information">

                    <p class="aboutmeTitle">Name</p>
                    <p class="aboutmeText">| Damir Mavric</p>
                    
                    <p class="aboutmeTitle">Alter</p>
                    <p class="aboutmeText">| 21 Jahre alt</p>

                    <p class="aboutmeTitle">Schule</p>
                    <p class="aboutmeText">| SAE Institute Zürich als Webdesigner und Webentwickler</p>

                    <p class="aboutmeTitle">Diese Webseite</p>
                    <p class="aboutmeText">| Diese Webseite wurde für das Modul WBD5100 0920 [ZRH] entwickelt. In diesem Modul gilt es ein 
                        funktionierendes CMS für eine Webseite zu erstellen.</p>

                </div>

                <img src="./img/potrait/Potrait.png" alt="A Potrait of myself">

            </div>

        </section>

        <!-- Footer -->

        <footer>
            <img src="./img/logo/Logosmall.png" alt="Picture of my Logo">
            <p>&copy; COPYRIGHT Damir Mavric</p>
        </footer>

    </section>

    <!-- SCRIPTS -->

    <!-- <script src="./js/jquery.js"></script> -->
    <script src="./js/toggleNav.js"></script>

    
</body>

</html>