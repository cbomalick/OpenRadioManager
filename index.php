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

//If not logged in, user access level is 0
$currentlyLoggedIn = FALSE;
$userLevel = 0;
$welcomeLine = "";

//If logged in, pull access rights from staff profile
if(isset($_SESSION['loggedin'])){
    if (isset($_SESSION['id'])){
        $loggedInUser = new Staff($_SESSION['id']);
        $currentlyLoggedIn = $loggedInUser->currentlyLoggedIn;
        $userLevel = $loggedInUser->userLevel;
        $loggedInName = $loggedInUser->fullName;
        $welcomeLine = "<p style=\"text-align:right;\">Welcome {$loggedInName}</p>";
    } 
}
?>

<html>
<head>
    <title><? Echo $station->stationName ?></title>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="panel">
            <?
            Echo"<h2>{$station->stationName}</h2>";
            Echo $welcomeLine;
            include('layout/navigation.inc.php'); 
            
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
                    include('layout/staff.inc.php');
                break;

                case"station":
                    include('layout/station.inc.php');
                break;

                case"viewstaff":
                    include('layout/viewstaff.inc.php');
                break;

                case"addstaff":
                    include('layout/addstaff.inc.php');
                break;

                case"addstaffsub":
                    include('layout/addstaffsub.inc.php');
                break;

                case"unlockstaffsub":
                    include('layout/unlockstaffsub.inc.php');
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

                case"resetpw":
                    include('layout/resetpw.inc.php');
                break;

                case"resetpwsub":
                    //When new PW is requested, expire any existing request hashes that are not already Used
                break;
                
                case"auth":
                    include('layout/auth.inc.php');
                break;
            }
            ?>

        </div>
            <? include('layout/footer.inc.php'); ?>
    </div>
</body>
</html>