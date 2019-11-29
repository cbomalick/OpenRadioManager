<?
//TODO: Truncate values past limit
if(!empty($_POST["firstName"]) && !empty($_POST["lastName"]) && !empty($_POST["email"]) && !empty($_POST["userLevel"])){
    $firstName = htmlentities($_POST["firstName"], ENT_QUOTES);
    $lastName = htmlentities($_POST["lastName"], ENT_QUOTES);
    $email = htmlentities($_POST["email"], ENT_QUOTES);
    $hireDate = $CurrentDateTime;
    $userLevel = htmlentities($_POST["userLevel"], ENT_QUOTES);

    //Check if email has already been used
    $connect = new DBConnect();
    $sql = "SELECT id FROM accounts WHERE email = '$email'";
    $count = $connect->fetchCount($sql);

    if($count > 0){
        Echo"Error: This email has already been associated with a different account";
        exit;
    } else {
        $newRequest = new Staff("NEW");
        $newRequest->addNewStaff($firstName,$lastName,$email,$hireDate,$userLevel,$loggedInName);
    }
    
    
    

} else {
    Echo "Error: Please fill out all required fields";
}


?>