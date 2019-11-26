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

        Echo "
        <script language=\"JavaScript\">
        //Select All Checkbox
            function toggle(source) {
            checkboxes = document.getElementsByName('selectedrequest[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
		}
        </script>
        <table class=\"table\">
             <thead>
             <tr>
             <th>Name</th>
             <th>Hire Date</th>
             <th>Role</th>
             <th><input type=\"checkbox\" onClick=\"toggle(this)\" /></th>
             </tr>
             </thead>
             <tbody>";

         foreach($inputList as $row){
             $staff = new Staff($row['staffid']);

             Echo"<tr>
                 <td><a href=\"?p=view&id={$staff->staffId}\" class=\"clickable\">{$staff->lastName}, {$staff->firstName}</a></td>
                 <td>{$staff->hireDate}</td>
                 <td>{$staff->userRole}</td>
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