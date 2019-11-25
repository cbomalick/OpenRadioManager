<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$employeeLevel = "4";
$currentlyLoggedIn = TRUE;

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

                case"login":
                    //$loggedInEmployee = new User();
                    //var_dump($loggedInEmployee);
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