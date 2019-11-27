<?
//TODO: Truncate values past limit
if(!empty($_POST["email"])){
    $email = htmlentities($_POST["email"], ENT_QUOTES);

    //Pull in staffid associated with email
    $connect = new DBConnect();
    $staffRow = $connect->getData("SELECT staffid FROM staff WHERE email = '$email' AND status = 'Active'");
    $staffId = $staffRow[0]["staffid"];

    //Expire any previous requests, and send new password reset email to user if they exist
    $staff = new Staff($staffId);
    $staff->staffForgotPassword($staffId);

    Echo"<div class=\"requestform\">
        <h2>Reset Password</h2>
            <p style=\"text-align: center;\">Thank you. Please check your email for a reset link within the next few minutes.
            <br>
            If you do not receive an email please check your spam folder, or check with your system administrator.</p>
        </div>";
} else {
    Echo"<div class=\"requestform\">
        <h2>Reset Password</h2>
            <p style=\"text-align: center;\">Sorry, we could not process your request!</p>
            <p style=\"text-align: center;\">Please make sure email is provided</p>
        </div>";
}
?>

