<?

class RequestList{

    public function __construct(){
    }

    public function __destruct(){
    }

    public function printList($status){
        $connect = new DBConnect();
        $row = $connect->getData("SELECT requestid from request WHERE status = '$status' ORDER BY createdtime DESC");

        if(!isset($row)){
            Echo "No requests currently pending";
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

         foreach($row as $row){
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