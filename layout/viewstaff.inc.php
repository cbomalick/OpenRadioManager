<?
//Permission check for page
$loggedInUser->checkPagePermission(3);

if (isset($_GET['id'])) {
    $staffId = htmlentities($_GET["id"], ENT_QUOTES);
    $staff = new Staff($staffId);
    
    //Permission check for employee (cannot edit superiors)
    $loggedInUser->checkPagePermission($staff->userLevel);
} else {
    Echo "Error: No staff ID given";
    exit;
}

Echo"
<div class=\"requestform\">
    <form method=\"post\" action=\"?p=viewstaffsub\">
    <input name=\"staffId\" value=\"{$staff->staffId}\" hidden>
        <h2>Manage Staff - {$staff->fullName}</h2>
        <p class=\"header\">First Name <span class=\"requiredfield\">*</span></p>
            <p><input name=\"firstName\" id=\"firstName\" value=\"{$staff->firstName}\"></p>
        <p class=\"header\">Last Name <span class=\"requiredfield\">*</span></p>
            <p><input name=\"lastName\" id=\"lastName\" value=\"{$staff->lastName}\"></p>
        <p class=\"header\">Email Address <span class=\"requiredfield\">*</span></p>
            <p><input name=\"email\" id=\"email\" value=\"{$staff->email}\"></p>
        <p class=\"header\">Hire Date <span class=\"requiredfield\">*</span></p>
            <p><input name=\"hireDate\" id=\"hireDate\" value=\"{$staff->hireDate}\"></p>
        <p class=\"header\">Role <span class=\"requiredfield\">*</span></p>
            <p>
            <select name=\"userLevel\" id=\"userLevel\">";
                $role = array(
                    1 => "DJ",
                    2 => "Supervisor",
                    3 => "Manager",
                    4 => "Owner",
                );

                foreach($role as $key => $value){
                    if($key == $staff->userLevel){
                        $selected = "selected";
                    } else { 
                        $selected = "";
                    }

                    if($key <= $userLevel){
                        Echo "<option value=\"{$key}\" {$selected}>{$value}</option>";
                    }
                }                
            
            Echo"</select>
                </p>
        <button class=\"actionbutton request\" type=\"submit\" name=\"submit\" id=\"submit\">Submit Request</button>
    </form>
    <form method=\"post\" action=\"?p=deletestaffsub\">
    <input name=\"staffId\" value=\"{$staff->staffId}\" hidden>
    <button class=\"actionbutton delete\" type=\"submit\" name=\"submit\" id=\"submit\">Delete Staff</button>
    </form>
</div>
";
?>