<? 
$loggedInUser->checkPagePermission(3);
?>
<div class="requestform">
    <form form method="post" action="?p=addstaffsub">
        <h2>Add New Staff</h2>
        <p class="header">First Name</p>
            <p><input name="firstName" id="firstName" value=""></p>
        <p class="header">Last Name</p>
            <p><input name="lastName" id="lastName" value=""></p>
        <p class="header">Email Address</p>
            <p><input name="email" id="email" value=""></p>
        <p class="header">Role</p>
            <p>
                <select name="userLevel" id="userLevel">
                <?
                $role = array(
                    1 => "DJ",
                    2 => "Supervisor",
                    3 => "Manager",
                    4 => "Owner",
                );

                foreach($role as $key => $value){
                    if($key <= $userLevel){
                        Echo "<option value=\"{$key}\">{$value}</option>";
                    }
                }
                ?>
                </select>
            </p>
        <button class="actionbutton request" type="submit" name="submit" id="submit">Add Staff</button>
    </form>
</div>