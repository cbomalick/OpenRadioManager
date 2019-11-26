<?

class Staff {

    public $staffId;
    public $firstName;
    public $lastName;
    public $email;
    public $hireDate;
    public $userLevel;
    public $currentlyLoggedIn;

    public function __construct($staffId){
        if($staffId == "NEW"){
            return;
        } else {
        $connect = new DBConnect();
        }

        $row = $connect->getData("SELECT * from staff WHERE staffid = '$staffId' AND status = 'Active' ORDER BY lastname,firstname") or die("Error: Staff not found.");
        foreach($row as $row){
            $this->staffId = $row['staffid'];
            $this->firstName = $row['firstname'];
            $this->lastName = $row['lastname'];
            $this->fullName = $this->firstName . " " . $this->lastName;
            $this->email = $row['email'];
            $this->hireDate = date("m/d/Y", strtotime($row['hiredate']));
            $this->userLevel = $row['userlevel'];
            $this->currentlyLoggedIn = TRUE;
        }
    }

    public function addNewStaff($firstName,$lastName,$email,$hireDate,$userLevel){
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
        $this->createdBy = "John Doe";
        $this->createdDate = $CurrentDateTime;

        //Insert staff in database
        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "INSERT INTO staff 
        (staffid,firstname,lastname,email,hiredate,userlevel,status,createdby,createddate) VALUES 
        ('$this->staffId','$this->firstName','$this->lastName','$this->email','$this->hireDate','$this->userLevel','$this->status','$this->createdBy','$this->createdDate')";
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
        $message = "You have been added as a {$station->stationName} user! Please use the link below to verify your account and set your password. \n \n {$station->webAddress}/?p=verify&e={$email}&v={$verificationString}";
        $headers = 'From: noreply@scrillas.com' . "\r\n" .
            'Reply-To: noreply@scrillas.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
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

    public function staffUpdatePassword($email,$key,$password){
        $staff = new Staff("NEW");
        $newPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashString = md5($key);
        $CurrentDateTime = date("Y-m-d H:i:s");

        $connect = new DBConnect();
        //Mark reset key as Used
        $sql = "UPDATE password_change_requests set status = 'Used' WHERE email = '$email' AND status != 'Used' AND hashstring = '$hashString'";
        $connect->runQuery($sql);

        //Update password
        $sql = "UPDATE accounts set password = '$newPassword', forcereset = 'N', lastmodifiedby = 'John Doe', lastmodifieddate = '$CurrentDateTime' WHERE email = '$email' AND status ='Active'";
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
}

?>