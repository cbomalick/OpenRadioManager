<?

class RequestList{
    private $completeList;
    private $filteredList;
    private $connect;
    private $status;
    private $startDate;
    private $endDate;
    private $uniqueList;

    public function __construct(){
        //Purpose: Generate a list of all requests. 'Cancelled' is reserved by system to remove a record without a true loss of data
        $this->connect = DBConnect::getInstance()->getConnection();

        $sql = "SELECT requestid,artist,song,requestedby,status,createdtime from request WHERE status != 'Cancelled' ORDER BY artist,song,createdtime DESC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $completeList = $stmt->fetchAll();

        $this->completeList = $completeList;
    }

    public function __destruct(){
    }

    public function filterList($status,$startDate,$endDate){
        //Purpose: Takes unfiltered list of requests and filters by status and date range 
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        //Create a filtered array of requests matching the provided $status
        if(!empty($this->completeList)){
            $this->filteredList = (array_filter($this->completeList, function ($var) use (&$filteredList) {
                return ($var['status'] == $this->status && $var['createdtime'] >= $this->startDate  &&  $var['createdtime'] <= $this->endDate);
            }));
        }

        return $this->filteredList;
    }

    public function printManageList($filteredList){
        //Purpose: Prints list of pending Requests for the Manage Requests screen
        if(empty($filteredList)){
            Echo "No requests currently available";
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
             <th>Created Time</th>
             <th>Artist</th>
             <th>Song Name</th>
             <th>Requested By</th>
             <th><input type=\"checkbox\" onClick=\"toggle(this)\" /></th>
             </tr>
             </thead>
             <tbody>";

         foreach($filteredList as $row){
             $request = new Request($row['requestid']);

             Echo"<tr>
                 <td>{$request->createdTime}</td>
                 <td>{$request->artist}</td>
                 <td>{$request->song}</td>
                 <td>{$request->requestedBy}</td>
                 <td><input type=\"checkbox\" name=\"selectedrequest[]\" value=\"{$request->requestId}\" class=\"checkbox\"></td>
             </tr>";
         }

         Echo"</tbody>
        </table>";
    }

    public function printPlayList($inputList){
        //Purpose: Prints list of approved Requests for the Play List screen

        //Accept multiarray, convert to normal array with artist => song
        $tempArray = array();
        $filteredList = array();
        if(!empty($inputList)){
            foreach($inputList as $row){
                $tempArray = array([$row['artist'] => $row['song']]);
                $filteredList = array_merge($filteredList, $tempArray);
            }
        }

        //Removes duplicates from filteredList
        $uniqueList = array_map('unserialize', array_unique(array_map('serialize', $filteredList)));
        
        if(empty($uniqueList)){
            Echo "No requests currently available";
            return;
        } else {
            Echo "<table class=\"table\">
            <thead>
            <tr>
            <th>Artist</th>
            <th>Song Name</th>
            <th>Times Requested</th>
            <th>Last Requested</th>
            </tr>
            </thead>
            <tbody>";

            foreach($uniqueList as $row) {
                foreach($row as $artist => $song) {
                    $request = new Request("NEW");
                    $totalCount = $request->countTimesRequested($artist,$song);
                    $mostRecent = $request->fetchLastRequested($artist,$song);

                    Echo"<tr>
                            <td>{$artist}</td>
                            <td>{$song}</td>
                            <td>{$totalCount}</td>
                            <td>{$mostRecent}</td>
                        </tr>";
                }
            }
            Echo"</tbody>
            </table>";
        }
    }

    public function updateList($action, $requests,$user){
        //Purpose: Updates array of $requests to the provided status ($action)
        if($action == "approve") {
            $status = "Approved";
        } else if ($action == "reject"){
            $status = "Rejected";
        } else if ($action == "cancel"){
            $status = "Cancelled";
        } else{
            Echo "Invalid Status";
            return;
        }

        foreach ($requests as $result) {
            $request = new Request($result);
            $request->updateRequestStatus($request->requestId,$status,$user);
        } 
    }
}

?>