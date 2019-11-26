<?
$staff = new Staff("NEW");
    $verificationKey = $_POST['verificationKey'];
    $verificationEmail = $_POST['verificationEmail'];
    $password = $_POST['newpass1'];
    $hashString = md5($verificationKey);

    if($staff->staffPasswordVerify($verificationEmail,$verificationKey)){
        $staff->staffUpdatePassword($verificationEmail,$verificationKey,$password);
        Echo "<p style=\"text-align: center;\">Password changed successfully</p>";
    } else {
        Echo "<p style=\"text-align: center;\">Sorry, this code is no longer valid</p>";
    }
?>