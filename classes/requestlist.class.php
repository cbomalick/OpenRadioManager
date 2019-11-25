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
        $connect = new DBConnect();
        $completeList = $connect->getData("SELECT requestid,artist,song,requestedby,status,createdtime from request WHERE status != 'Cancelled' ORDER BY createdtime DESC");
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
        $this->filteredList = (array_filter($this->completeList, function ($var) use (&$filteredList) {
            return ($var['status'] == $this->status && $var['createdtime'] >= $this->startDate  &&  $var['createdtime'] <= $this->endDate);
        }));

        return $this->filteredList;
    }

    public function printManageList($filteredList){
        //Purpose: Prints list of pending Requests for the Manage Requests screen
        if(empty($filteredList)){
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

    public function printPlayList($inputList){
        //Purpose: Prints list of approved Requests for the Play List screen

        //Accept multiarray, convert to normal array with artist => song
        $tempArray = array();
        $filteredList = array();

        foreach($inputList as $row){
            $tempArray = array([$row['artist'] => $row['song']]);
            $filteredList = array_merge($filteredList, $tempArray);
        }

        //Remove duplicate artist/song pairs
        $uniqueList = array_unique($filteredList, SORT_REGULAR);

        if(empty($uniqueList)){
            Echo "No requests currently available";
            return;
        }

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
                    $this->artist = $artist;
                    $this->song = $song;
            // $mostRecent = date("m/d/Y g:i a", strtotime($mostRecent['createdtime']));

            $mostRecent = "";
            $totalCount = "";

           $totalCount = $request->countTimesRequested($artist,$song);

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