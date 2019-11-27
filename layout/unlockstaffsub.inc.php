<?
    if (isset($_POST['selectedstaff'])) {
        $selectedStaff = filter_var_array($_POST['selectedstaff'],FILTER_SANITIZE_STRING); //Accept as array and sanitize
    } else {
        $selectedStaff = [];
        Echo "<script type=\"text/javascript\">
        window.location.href = \"?p=requests\";
        </script>";
        Echo "<p>Staff unlocked. <a href=\"?p=staff\" class=\"clickable\">Click here</a> if your browser does not automatically take you back.</p>";
        return;
    }

    foreach ($selectedStaff as $result) {
        $staff = new Staff($result);
        $staff->unlockStaff($staff->staffId,$loggedInName);
    } 
    
    Echo "<script type=\"text/javascript\">
    window.location.href = \"?p=staff\";
    </script>";
    Echo "<p>Staff unlocked. <a href=\"?p=staff\" class=\"clickable\">Click here</a> if your browser does not automatically take you back.</p>";
?>