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
                <option value="1">DJ</option>
                <option value="2">Supervisor</option>
                <option value="3">Manager</option>
                <option value="4">Owner</option>
            </select></p>
        <button class="actionbutton request" type="submit" name="submit" id="submit">Submit Request</button>
    </form>
</div>