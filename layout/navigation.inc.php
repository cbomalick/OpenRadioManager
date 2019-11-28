<div class="navigation">
                    <ul>
                        <li><a class="active" href="index.php">Make Request</a></li>
                        <?
                        
                        if ($userLevel >= 1){
                        Echo'<li><a href="?p=playlist">Play List</a></li>';
                        }
                        
                        if ($userLevel >= 2){
                            Echo'<li><a href="?p=requests">Manage Requests</a></li>'; 
                        }

                        if ($userLevel >= 3){
                            Echo'<li><a href="?p=staff">Manage Staff</a></li>';
                        }

                        if ($userLevel >= 4){
                            Echo'<li><a href="?p=station">Manage Station</a></li>';
                        }
                        
                        if($currentlyLoggedIn != TRUE){
                            Echo'<li style="float:right"><a href="?p=login">Log In</a></li>'; 
                        } else {
                            Echo'<li style="float:right"><a href="logout.php">Log Out</a></li>';
                        }
                        ?>
                    </ul>
                </div>