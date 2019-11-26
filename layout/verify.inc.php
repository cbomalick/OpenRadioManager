<?

$staff = new Staff("NEW");
    $verificationKey = $_GET['v'];
    $verificationEmail = $_GET['e'];
    $hashString = md5($verificationKey);

    if($staff->staffPasswordVerify($verificationEmail,$verificationKey)){
        Echo "<form form method=\"post\" action=\"?p=verifysub\">
        <h2>Add New Staff</h2>
        <input name=\"verificationKey\" id=\"verificationKey\" value=\"$verificationKey\" hidden>
        <input name=\"verificationEmail\" id=\"verificationEmail\" value=\"$verificationEmail\" hidden>
        <p class=\"header\">New Password</p>
            <p><input name=\"newpass1\" id=\"newpass1\" value=\"\"></p>
        <p class=\"header\">Confirm New Password</p>
            <p><input name=\"newpass2\" id=\"newpass2\" value=\"\"></p>
        <button class=\"actionbutton request\" type=\"submit\" name=\"submit\" id=\"submit\">Change Password</button>
    </form>";
    } else {
        Echo "<p style=\"text-align: center;\">Sorry, this code is no longer valid</p>";
    }

?>