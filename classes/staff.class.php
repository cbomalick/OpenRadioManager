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
    private $loggedInName;

    public function __construct($staffId){
        $this->connect = DBConnect::getInstance()->getConnection();
        
        if($staffId == "NEW"){
            return;
        }

        $sql = "SELECT * from staff WHERE staffid = ? AND status != 'Cancelled' ORDER BY lastname,firstname";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$staffId]);
        $row = $stmt->fetchAll();
        
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
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "INSERT INTO staff 
        (staffid,firstname,lastname,email,hiredate,userlevel,status,createdby,createddate) VALUES 
        (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->staffId, $this->firstName, $this->lastName, $this->email, $this->hireDate, $this->userLevel, $this->status, $loggedInName, $this->createdDate]);

        $sql = "INSERT INTO accounts 
        (staffid,email,userlevel,status,createdby,createddate) VALUES 
        (?,?,?,?,?,?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->staffId, $this->email, $this->userLevel, $this->status, $loggedInName, $this->createdDate]);
    
        //Generates a 32-character number and then hashes with md5
        //User gets an email with the number, and the hash is stored in the DB
        //Verification checks the hash of the number in email link. If it matches, password can be set
        $verificationString = $ID->generatePasswordString();
        $hashString = md5($verificationString);

        $sql = "INSERT INTO password_change_requests 
        (email,time,hashstring,ipaddress) VALUES 
        (?,?,?,?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->email, $CurrentDateTime, $hashString, $ipAddress]);


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
        $hashString = md5($key);

        $sql = "SELECT COUNT(seqno) FROM password_change_requests WHERE email = ? AND status != 'Used' AND hashstring = ? AND time >= now() - INTERVAL 1 DAY";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $hashString]);
        $row = $stmt->fetchAll();

        $sql = "";
        if($row == 1){
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

        //Mark reset key as Used
        $sql = "UPDATE password_change_requests set status = 'Used' WHERE email = ? AND status != 'Used' AND hashstring = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $hashString]);

        //Update password
        $sql = "UPDATE accounts set password = ?, forcereset = 'N', lastmodifiedby = ?, lastmodifieddate = ? WHERE email = ? AND status ='Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$newPassword, $loggedInName, $CurrentDateTime, $email]);
    }

    public function staffForgotPassword($staffId){
        $CurrentDateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $ID = new IdNumber;

        //Expire any previous password requests
        $this->expireAllResets();
        
        //Generates a 32-character number and then hashes with md5
        //User gets an email with the number, and the hash is stored in the DB
        //Verification checks the hash of the number in email link. If it matches, password can be set
        $verificationString = $ID->generatePasswordString();
        $hashString = md5($verificationString);

        $sql = "INSERT INTO password_change_requests 
        (email,time,hashstring,ipaddress) VALUES 
        (?,?,?,?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->email, $CurrentDateTime, $hashString, $ipAddress]);

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
        $interval = 30;

        $sql = "INSERT INTO failed_login 
        (email,reason,createdtime,ip,status) VALUES 
        (?,?,?,?,'Active')";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $reason, $CurrentDateTime, $ipAddress]);

        //Pull in staffid associated with email
        $sql = "SELECT staffid FROM staff WHERE email = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email]);
        $staffRow = $stmt->fetch();
        $staffId = $staffRow[0]["staffid"];

        //Get count of failed attempts. If more than 3, lock account
        $sql = "SELECT COUNT(seqno) FROM failed_login WHERE email = ? AND status = 'Active' AND createdtime  <= now() - INTERVAL ? MINUTE";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $interval]);
        $strikes = $stmt->fetch();

        $staff = new Staff($staffId);
        if($strikes > 3){
            $staff->lockStaff($staffId);
            Echo "Your account has been locked from too many failed attempts";
        }
    }

    public function lockStaff($staffId){
        $CurrentDateTime = date("Y-m-d H:i:s");

        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        //Revoke account access
        $sql = "UPDATE accounts SET status = 'Locked' WHERE staffid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$staffId]);

        $sql = "UPDATE staff SET status = 'Locked' WHERE staffid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$staffId]);
    }

    public function unlockStaff($staffId){
        $CurrentDateTime = date("Y-m-d H:i:s");

        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        //Give back account access
        $sql = "UPDATE accounts SET status = 'Active' WHERE staffid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$staffId]);

        $sql = "UPDATE staff SET status = 'Active' WHERE staffid = '$staffId'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$staffId]);

        $staff = new Staff($staffId);
        $sql = "UPDATE failed_login SET status = 'Expired' WHERE email = ? AND status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$staff->email]);
    }

    public function deactivateStaff($staffId,$loggedInEmployee){
        $CurrentDateTime = date("Y-m-d H:i:s");

        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        //Remove employee profile
        $sql = "UPDATE staff SET status = 'InActive', lastmodifieddate = ?, lastmodifiedby = ? WHERE staffid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$CurrentDateTime, $loggedInEmployee, $staffId]);

        //Revoke account access
        $sql = "UPDATE accounts SET status = 'InActive', lastmodifieddate = ?, lastmodifiedby = ? WHERE staffid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$CurrentDateTime, $loggedInEmployee, $staffId]);
    }

    public function expireAllResets(){
        //When a new password is requested, expire any previously requested hashes that haven't been used
        $sql = "UPDATE password_change_requests SET status = 'Used' WHERE email = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->email]);
    }

    public function isValid($loggedInEmployee){
    return true;
    }

    public function checkPagePermission($checkLevel){
    //Can be inserted in any page to verify that user has access. If they do not, they cannot access the screen/section
        if($this->userLevel < $checkLevel){
            Echo "Error: Insufficient access rights<br /></br />If problem persists, please contact your system administrator";
            exit;
        }

    }

    public function staffUpdateDetails($firstName,$lastName,$email,$hireDate,$userLevel){
    //Updates staff details when View Staff screen is saved
        $sql = "UPDATE staff SET firstname = ?, lastname = ?, email = ?, hiredate = ?, userlevel = ? WHERE staffid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$firstName, $lastName, $email, $hireDate, $userLevel, $this->staffId]);
    }
}

?>