<?

class StaffList{
    public $completeList;
    private $filteredList;
    private $connect;
    private $status;
    private $startDate;
    private $endDate;
    private $uniqueList;

    public function __construct(){
        //Purpose: Generate a list of all staff. 'Cancelled' is reserved by system to remove a record without a true loss of data
        $connect = new DBConnect();
        $completeList = $connect->getData("SELECT * from staff WHERE status != 'Cancelled' ORDER BY lastname,firstname");
        $this->completeList = $completeList;
    }

    public function printStaffList($inputList){
        //Purpose: Prints list of pending Requests for the Manage Requests screen
        if(empty($inputList)){
            Echo "No staff currently available";
            return;
        }

        Echo "<table class=\"table\">
             <thead>
             <tr>
             <th>Name</th>
             <th>Hire Date</th>
             <th>Role</th>
             <th>Select</th>
             </tr>
             </thead>
             <tbody>";

         foreach($inputList as $row){
             $staff = new Staff($row['staffid']);

             Echo"<tr>
                 <td>{$staff->lastName}, {$staff->firstName}</td>
                 <td>{$staff->hireDate}</td>
                 <td>{$staff->role}</td>
                 <td><input type=\"checkbox\" name=\"selectedrequest[]\" value=\"{$staff->staffId}\" class=\"checkbox\"></td>
             </tr>";
         }

         Echo"</tbody>
        </table>";
    }

    // public function updateStaff($action,$staff,$user){
    //     //Purpose: Updates array of $requests to the provided status ($action)
    //     if($action == "approve") {
    //         $status = "Approved";
    //     } else if ($action == "reject"){
    //         $status = "Rejected";
    //     } else if ($action == "cancel"){
    //         $status = "Cancelled";
    //     } else{
    //         Echo "Invalid Status";
    //         return;
    //     }

    //     foreach ($staff as $result) {
    //         $request = new Request($result);
    //         $request->updateRequestStatus($request->requestId,$status,$user);
    //     } 
    // }
}

?>