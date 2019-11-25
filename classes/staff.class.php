<?

class Staff {

    public $staffId;
    public $firstName;
    public $lastName;
    public $email;
    public $hireDate;
    public $role;

    public function __construct($staffId){
        if($staffId == "NEW"){
            return;
        } else {
        $connect = new DBConnect();
        }

        $row = $connect->getData("SELECT * from staff WHERE staffid = '$staffId' ORDER BY lastname,firstname") or die("Error: Staff not found.");
        foreach($row as $row){
            $this->staffId = $row['staffid'];
            $this->firstName = $row['firstname'];
            $this->lastName = $row['lastname'];
            $this->email = $row['email'];
            $this->hireDate = $row['hiredate'];
            $this->role = $row['userlevel'];
        }
    }
}

?>