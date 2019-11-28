<?

if(!empty($_POST["stationName"]) && !empty($_POST["timeZone"]) && !empty($_POST["domain"]) && !empty($_POST["installPath"])){
    $station = new Station();

    $stationName = htmlentities($_POST['stationName'], ENT_QUOTES);
    $stationDescription = htmlentities($_POST['stationDescription'], ENT_QUOTES);
    $timeZone = htmlentities($_POST['timeZone'], ENT_QUOTES);
    $domain = htmlentities($_POST['domain'], ENT_QUOTES);
    $installPath = htmlentities($_POST['installPath'], ENT_QUOTES);

    $station->stationUpdateDetails($stationName,$stationDescription,$timeZone,$domain,$installPath);

    Echo"<div class=\"requestform\">
        <h2>Manage Station</h2>
            <p style=\"text-align: center;\">Station changes have been saved.</p>
        </div>";

    Echo "<script type=\"text/javascript\">
    window.location.href = \"?p=station\";
    </script>";
} else {
    Echo"<div class=\"requestform\">
        <h2>Manage Station</h2>
            <p style=\"text-align: center;\">Sorry, we could not process your changes</p>
            <p style=\"text-align: center;\">Please make sure all required fields are filled out</p>
        </div>";
}




?>