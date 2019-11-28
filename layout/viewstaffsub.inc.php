<?
//TODO: Truncate values past limit
if(!empty($_POST["staffId"]) && !empty($_POST["firstName"]) && !empty($_POST["lastName"]) && !empty($_POST["email"]) && !empty($_POST["userLevel"])){
    $staffId = htmlentities($_POST["staffId"], ENT_QUOTES);
    $firstName = htmlentities($_POST["firstName"], ENT_QUOTES);
    $lastName = htmlentities($_POST["lastName"], ENT_QUOTES);
    $email = htmlentities($_POST["email"], ENT_QUOTES);
    $hireDate = $CurrentDateTime;
    $userLevel = htmlentities($_POST["userLevel"], ENT_QUOTES);
    
    $staff = new Staff($staffId);
    $staff->staffUpdateDetails($firstName,$lastName,$email,$hireDate,$userLevel);

    Echo "Account updated";
    Echo "<script type=\"text/javascript\">
    window.location.href = \"?p=staff\";
    </script>";

} else {
    Echo "Error: Please check all required fields";
}


?>