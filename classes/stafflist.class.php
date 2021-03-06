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
        $this->connect = DBConnect::getInstance()->getConnection();

        $sql = "SELECT * from staff WHERE status NOT IN ('Cancelled','InActive') ORDER BY lastname,firstname";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $completeList = $stmt->fetchAll();
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
            checkboxes = document.getElementsByName('selectedstaff[]');
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
             <th>Status</th>
             <th><input type=\"checkbox\" onClick=\"toggle(this)\" /></th>
             </tr>
             </thead>
             <tbody>";

         foreach($inputList as $row){
             $staff = new Staff($row['staffid']);

             Echo"<tr>
                 <td><a href=\"?p=viewstaff&id={$staff->staffId}\" class=\"clickable\">{$staff->lastName}, {$staff->firstName}</a></td>
                 <td>{$staff->hireDate}</td>
                 <td>{$staff->userRole}</td>
                 <td>{$staff->status}</td>
                 <td><input type=\"checkbox\" name=\"selectedstaff[]\" value=\"{$staff->staffId}\" class=\"checkbox\"></td>
             </tr>";
         }

         Echo"</tbody>
        </table>";
    }
}

?>