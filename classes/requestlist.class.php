<?

class RequestList{
    private $completeList;
    private $filteredList;
    private $connect;
    private $status;
    private $startDate;
    private $endDate;

    public function __construct(){
        //Generate a list of all requests. 'Cancelled' is reserved by system to remove a record without a true loss of data
        $connect = new DBConnect();
        $completeList = $connect->getData("SELECT requestid,artist,song,requestedby,status,createdtime from request WHERE status != 'Cancelled' ORDER BY createdtime DESC");
        $this->completeList = $completeList;
    }

    public function __destruct(){
    }

    public function filterList($status,$startDate,$endDate){
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        //Create a filtered array of requests matching the provided $status
        $this->filteredList = (array_filter($this->completeList, function ($var) use (&$filteredList) {
            return ($var['status'] == $this->status && $var['createdtime'] >= $this->startDate  &&  $var['createdtime'] <= $this->endDate);
        }));

        unset($this->completeList);
        return $this->filteredList; //TODO: Changing this to just $this causes error in Play List from obj conversion
    }

    public function printManageList($filteredList){
        if(empty($filteredList)){
            Echo "No requests currently available";
            return;
        }

        //Generate HTML table for Manage Requests screen
        Echo "<table class=\"table\">
             <thead>
             <tr>
             <th>Created Time</th>
             <th>Artist</th>
             <th>Song Name</th>
             <th>Requested By</th>
             <th>Select</th>
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

    public function printPlayList($filteredList){
        if(empty($filteredList)){
            Echo "No requests currently available";
            return;
        }

        //Generate HTML table for Manage Requests screen
        Echo "<table class=\"table\">
            <thead>
            <tr>
            <th>Last Requested</th>
            <th>Artist</th>
            <th>Song Name</th>
            <th>Requested Total</th>
            <th>Requested Today</th>
            </tr>
            </thead>
            <tbody>";

         foreach($filteredList as $row){
             $request = new Request($row['requestid']);

             Echo"<tr>
                    <td>{$request->createdTime}</td>
                    <td>{$request->artist}</td>
                    <td>{$request->song}</td>
                    <td>141</td>
                    <td>13</td>
                </tr>";
         }

         Echo"</tbody>
        </table>";
    }

    public function updateList($action, $requests,$user){
        //Update array of $requests to the provided status ($action)
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