<?
$staff = new Staff("NEW");
    $verificationKey = $_POST['verificationKey'];
    $verificationEmail = $_POST['verificationEmail'];
    $password = $_POST['newpass1'];
    $hashString = md5($verificationKey);
    if(empty($loggedInName)){
        $loggedInName = "Not logged in";
    }

    if($staff->staffPasswordVerify($verificationEmail,$verificationKey)){
        $staff->staffUpdatePassword($verificationEmail,$verificationKey,$password,$loggedInName);
        Echo "<p style=\"text-align: center;\">Password changed successfully</p>";
    } else {
        Echo "<p style=\"text-align: center;\">Sorry, this code is no longer valid</p>";
    }
?>