<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Import classes
include('classes/packages.inc.php');

if (isset($_GET['p'])) {
	$page = $_GET['p'];
} else {
	$page= "";
}

//Station object
$stationName = "My Radio Station";
$stationDescription = "Welcome to My Radio Station! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
$currentDJ = "Jim Jones";
$nowPlaying = "Rage Against the Machine - Killing in the Name";
date_default_timezone_set('America/Chicago');

//User object
$loggedInEmployee = "John Doe";
$employeeLevel = "2";
$currentlyLoggedIn = TRUE;

?>

<html>
<head>
    <title><? Echo $stationName ?></title>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="panel">
            <h2><? Echo $stationName ?></h2>
            <? include('layout/navigation.inc.php'); ?>
            
            <? 
            switch($page){
                default:
                    include('layout/content.inc.php');    
                    include('layout/requestform.inc.php'); 
                break;

                case"rsub":
                    include('layout/content.inc.php');
                    include('layout/requestsub.inc.php');
                break;

                case"requests":
                    include('layout/requestlist.inc.php');
                break;

                case"msub":
                    include('layout/managesub.inc.php');
                break;

                case"playlist":
                    include('layout/playlist.inc.php');
                    
                break;
                
                case"staff":
                break;

                case"login":
                break;

                case"logout":
                break;
            
            }
            ?>

        </div>
            <? include('layout/footer.inc.php'); ?>
    </div>
</body>
</html>