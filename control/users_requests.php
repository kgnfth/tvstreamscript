<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}
?>
<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=users">Users</a>
            </li>
            <li>
                Requests
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width:30px">ID</th>
                    <th>User</th>
                    <th style="width:120px">Request date</th>
                    <th>Message</th>
                    <th style="min-width:300px">Response</th>
                    <th style="width:40px">Votes</th>
                    <th style="width:60px">Status</th>
                    <th style="width:40px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $requests = $request->getAll("request_date DESC");
                    
                    if (count($requests)){
                        foreach($requests as $request_id => $request_data){
                            
                            $dropdown = "<select id='status_{$request_id}'>";
                            $dropdown.= "<option value='0'"; if ($request_data['status']==0){ $dropdown.=" selected='selected'"; } $dropdown.=">Waiting</option>";
                            $dropdown.= "<option value='1'"; if ($request_data['status']==1){ $dropdown.=" selected='selected'"; } $dropdown.=">Uploading</option>";
                            $dropdown.= "<option value='2'"; if ($request_data['status']==2){ $dropdown.=" selected='selected'"; } $dropdown.=">Finished</option>";
                            $dropdown.= "</select>"; 
                            
                            print("    <tr id=\"request_{$request_id}\">
                                        <td>{$request_id}</td>
                                        <td>{$request_data['username']}</td>
                                        <td>{$request_data['request_date']}</td>
                                        <td>{$request_data['message']}</td>
                                        <td><textarea id=\"response_{$request_id}\" style=\"width:98%\">{$request_data['response']}</textarea></td>
                                        <td>{$request_data['votes']}</td>
                                        <td>{$dropdown}</td>
                                        <td>
                                            <a href=\"javascript:void(0);\" onclick=\"updateRequest({$request_id})\">Update</a><br />
                                            <a href=\"javascript:void(0);\" onclick=\"deleteRequest({$request_id})\">Delete</a><br />
                                        </td>
                                    </tr>");
                        }    
                    } else {
                        print("<tr><td colspan='8' style='text-align:center'>No requests found</td></tr>");
                    }
                
                ?>    
            </tbody>
        </table>
    </div>
</div>