<?

class Staff {

    public $staffId;
    public $firstName;
    public $lastName;
    public $email;
    public $hireDate;
    public $userLevel;
    public $currentlyLoggedIn;
    public $status;
    public $loggedInName;

    public function __construct($staffId){
        if($staffId == "NEW"){
            return;
        } else {
        $connect = new DBConnect();
        }

        $row = $connect->getData("SELECT * from staff WHERE staffid = '$staffId' AND status != 'Cancelled' ORDER BY lastname,firstname") or die("Error: Staff not found.");
        foreach($row as $row){
            $this->staffId = $row['staffid'];
            $this->firstName = $row['firstname'];
            $this->lastName = $row['lastname'];
            $this->fullName = $this->firstName . " " . $this->lastName;
            $this->email = $row['email'];
            $this->hireDate = date("m/d/Y", strtotime($row['hiredate']));
            $this->userLevel = $row['userlevel'];
            $this->currentlyLoggedIn = TRUE;
            $this->status = $row['status'];

            $role = array(
                0 => "Public",
                1 => "DJ",
                2 => "Supervisor",
                3 => "Manager",
                4 => "Owner",
             );
            $this->userRole = $role[$this->userLevel];
        }
    }

    public function addNewStaff($firstName,$lastName,$email,$hireDate,$userLevel,$loggedInName){
        $ID = new IdNumber;
        $newId = $ID->generateID("STF");
        $CurrentDateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $this->staffId = $newId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->hireDate = $hireDate;
        $this->userLevel = $userLevel;
        $this->status = "Active";
        $this->createdDate = $CurrentDateTime;

        //Insert staff in database
        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "INSERT INTO staff 
        (staffid,firstname,lastname,email,hiredate,userlevel,status,createdby,createddate) VALUES 
        ('$this->staffId','$this->firstName','$this->lastName','$this->email','$this->hireDate','$this->userLevel','$this->status','$loggedInName','$this->createdDate')";
        $connect->runQuery($sql);

        $sql = "INSERT INTO accounts 
        (staffid,email,userlevel,status,createdby,createddate) VALUES 
        ('$this->staffId','$this->email','$this->userLevel','$this->status','$loggedInName','$this->createdDate')";
        $connect->runQuery($sql);
    
        //Generates a 32-character number and then hashes with md5
        //User gets an email with the number, and the hash is stored in the DB
        //Verification checks the hash of the number in email link. If it matches, password can be set
        $verificationString = $ID->generatePasswordString();
        $hashString = md5($verificationString);
        $sql = "INSERT INTO password_change_requests 
        (email,time,hashstring,ipaddress) VALUES 
        ('$this->email','$CurrentDateTime','$hashString','$ipAddress')";
        $connect->runQuery($sql);

        //Send verification email
        $station = new Station();
        $to = $email;
        $subject = "{$station->stationName} - Please verify your account";
        $message = "You have been added as a {$station->stationName} user! Please use the link below to verify your account and set your password. \n \n http://{$station->webAddress}/?p=verify&e={$email}&v={$verificationString}";
        $headers = "From: noreply@{$station->domain}" . "\r\n" .
            "Reply-To: noreply@{$station->domain}" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        mail($to, $subject, $message, $headers);

        Echo"<div class=\"requestform\">
            <h2>Add Staff</h2>
                <p style=\"text-align: center;\">Account created for {$firstName} {$lastName}</p>
        </div>";
    }

    public function staffPasswordVerify($email,$key){
        $connect = new DBConnect();
        $hashString = md5($key);

        $sql = "SELECT seqno FROM password_change_requests WHERE email = '$email' AND status != 'Used' AND hashstring = '$hashString' AND time >= now() - INTERVAL 1 DAY";
        if($connect->fetchCount($sql) == 1){
            return true;
        } else {
            return false;
        }
    }

    public function staffUpdatePassword($email,$key,$password,$loggedInName){
        $staff = new Staff("NEW");
        $newPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashString = md5($key);
        $CurrentDateTime = date("Y-m-d H:i:s");

        $connect = new DBConnect();
        //Mark reset key as Used
        $sql = "UPDATE password_change_requests set status = 'Used' WHERE email = '$email' AND status != 'Used' AND hashstring = '$hashString'";
        $connect->runQuery($sql);

        //Update password
        $sql = "UPDATE accounts set password = '$newPassword', forcereset = 'N', lastmodifiedby = '$loggedInName', lastmodifieddate = '$CurrentDateTime' WHERE email = '$email' AND status ='Active'";
        $connect->runQuery($sql);
        
    }

    public function staffForgotPassword($staffId){
        $CurrentDateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $ID = new IdNumber;
        $connect = new DBConnect();

        //Expire any previous password requests
        $this->expireAllResets();
        
        //Generates a 32-character number and then hashes with md5
        //User gets an email with the number, and the hash is stored in the DB
        //Verification checks the hash of the number in email link. If it matches, password can be set
        $verificationString = $ID->generatePasswordString();
        $hashString = md5($verificationString);
        $sql = "INSERT INTO password_change_requests 
        (email,time,hashstring,ipaddress) VALUES 
        ('$this->email','$CurrentDateTime','$hashString','$ipAddress')";
        $connect->runQuery($sql);

        //Send verification email
        $station = new Station();
        $to = $this->email;
        $subject = "{$station->stationName} - Forgot your {$station->stationName} password?";
        $message = "A password reset has been requested for your {$station->stationName} account by {$ipAddress}. Please use the link below to verify your account and set your new password. \n \n http://{$station->webAddress}/?p=verify&e={$this->email}&v={$verificationString} \n \n If you did not request this change you can ignore this email.";
        $headers = "From: noreply@{$station->domain}" . "\r\n" .
            "Reply-To: noreply@{$station->domain}" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        mail($to, $subject, $message, $headers);
    }

    public function threeStrikes($email,$reason){
        //User accounts will become locked if incorrect login is attempted 3x without success
        $CurrentDateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $connect = new DBConnect();
        $interval = 30;

        $sql = "INSERT INTO failed_login 
        (email,reason,createdtime,ip,status) VALUES 
        ('$email','$reason','$CurrentDateTime','$ipAddress','Active')";
        $connect->runQuery($sql);

        //Pull in staffid associated with email
        $staffRow = $connect->getData("SELECT staffid FROM staff WHERE email = '$email'");
        $staffId = $staffRow[0]["staffid"];

        //Get count of failed attempts. If more than 3, lock account
        $strikes = $connect->fetchCount("SELECT seqno FROM failed_login WHERE email = '$email' AND status = 'Active' AND createdtime  <= now() - INTERVAL $interval MINUTE");
        $staff = new Staff($staffId);
        if($strikes > 3){
            $staff->lockStaff($staffId);
            Echo "Your account has been locked from too many failed attempts";
        }
    }

    public function lockStaff($staffId){
        $CurrentDateTime = date("Y-m-d H:i:s");

        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        //Revoke account access
        $sql = "UPDATE accounts SET status = 'Locked' WHERE staffid = '$staffId'";
        $connect->runQuery($sql);

        $sql = "UPDATE staff SET status = 'Locked' WHERE staffid = '$staffId'";
        $connect->runQuery($sql);
    }

    public function unlockStaff($staffId){
        $CurrentDateTime = date("Y-m-d H:i:s");

        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        //Give back account access
        $sql = "UPDATE accounts SET status = 'Active' WHERE staffid = '$staffId'";
        $connect->runQuery($sql);

        $sql = "UPDATE staff SET status = 'Active' WHERE staffid = '$staffId'";
        $connect->runQuery($sql);

        $staff = new Staff($staffId);
        $sql = "UPDATE failed_login SET status = 'Expired' WHERE email = '$staff->email' AND status = 'Active'";
        $connect->runQuery($sql);
    }

    public function deactivateStaff($staffId,$loggedInEmployee){
        $CurrentDateTime = date("Y-m-d H:i:s");

        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        //Remove employee profile
        $sql = "UPDATE staff SET status = 'InActive', lastmodifieddate = '$CurrentDateTime', lastmodifiedby = '$loggedInEmployee' WHERE staffid = '$staffId'";
        $connect->runQuery($sql);

        //Revoke account access
        $sql = "UPDATE accounts SET status = 'InActive', lastmodifieddate = '$CurrentDateTime', lastmodifiedby = '$loggedInEmployee' WHERE staffid = '$staffId'";
        $connect->runQuery($sql);
    }

    public function expireAllResets(){
        //When a new password is requested, expire any previously requested hashes that haven't been used
        $connect = new DBConnect();
        $sql = "UPDATE password_change_requests SET status = 'Used' WHERE email = '$this->email'";
        $connect->runQuery($sql);
    }

    public function isValid($loggedInEmployee){
    return true;
    }

    public function checkPagePermission($checkLevel){

        if($this->userLevel < $checkLevel){
            Echo "Error: Insufficient access rights<br /></br />If problem persists, please contact your system administrator";
            exit;
        }

    }

    public function staffUpdateDetails($firstName,$lastName,$email,$hireDate,$userLevel){
        $connect = new DBConnect();
        $sql = "UPDATE staff SET firstname = '$firstName', lastname = '$lastName', email = '$email', hiredate = '$hireDate', userlevel = '$userLevel' WHERE staffid = '$this->staffId'";
        $connect->runQuery($sql);
    }
}

?>