<?
//TODO: Truncate values past limit
if(!empty($_POST["firstName"]) && !empty($_POST["lastName"]) && !empty($_POST["email"]) && !empty($_POST["userLevel"])){
    $firstName = htmlentities($_POST["firstName"], ENT_QUOTES);
    $lastName = htmlentities($_POST["lastName"], ENT_QUOTES);
    $email = htmlentities($_POST["email"], ENT_QUOTES);
    $hireDate = $CurrentDateTime;
    $userLevel = htmlentities($_POST["userLevel"], ENT_QUOTES);
    
    $newRequest = new Staff("NEW");
    $newRequest->addNewStaff($firstName,$lastName,$email,$hireDate,$userLevel,$loggedInName);
    

}


?>