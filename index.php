<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

//Import classes
include('classes/packages.inc.php');

//Establish station variables
$station = new Station();
date_default_timezone_set($station->timeZone);
$CurrentDateTime = date("Y-m-d H:i:s");

if (isset($_GET['p'])) {
	$page = $_GET['p'];
} else {
	$page= "";
}

//User object
$currentlyLoggedIn = FALSE;
$userLevel = 0;
if(isset($_SESSION['loggedin'])){
    if (isset($_SESSION['id'])){
        $loggedInUser = new Staff($_SESSION['id']);
        $currentlyLoggedIn = $loggedInUser->currentlyLoggedIn;
        $userLevel = $loggedInUser->userLevel;
        $loggedInName = $loggedInUser->fullName;
} 
}

$loggedInEmployee = "John Smith";

?>

<html>
<head>
    <title><? Echo $station->stationName ?></title>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="panel">
            <h2><? Echo $station->stationName ?></h2>
            <? include('layout/navigation.inc.php'); ?>
            
            <? 
            switch($page){
                default:
                    include('layout/content.inc.php');
                    Echo "<br><br>";



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
                    include('layout/staff.inc.php');
                break;

                case"station":
                    
                break;

                case"view":
                    
                break;

                case"addstaff":
                    include('layout/addstaff.inc.php');
                break;

                case"addstaffsub":
                    include('layout/addstaffsub.inc.php');
                break;

                case"verify":
                    include('layout/verify.inc.php');
                break;

                case"verifysub":
                    include('layout/verifysub.inc.php');
                break;

                case"login":
                    include('layout/login.inc.php');
                break;

                case"auth":
                    include('layout/auth.inc.php');
                break;

                case"logout":
                    session_start();
                    session_destroy();
                    header('Location: index.html');
                    exit();
                break;
            
            }
            ?>

        </div>
            <? include('layout/footer.inc.php'); ?>
    </div>
</body>
</html>