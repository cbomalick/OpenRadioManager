<?

class RequestList{
    private $completeList;
    private $connect;

    public function __construct(){
        $connect = new DBConnect();
        $completeList = $connect->getData("SELECT requestid,artist,song,requestedby,status,createdtime from request WHERE status != 'Cancelled' ORDER BY createdtime DESC");
        $this->completeList = $completeList;
    }

    public function __destruct(){
    }

    public function printList($status){
        $filteredList = (array_filter($this->completeList, function ($var) {
            return ($var['status'] == 'Pending');
        }));
        
        if(!isset($filteredList)){
            Echo "No requests currently available";
            return;
        }

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

    public function updateList($action, $requests,$user){
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