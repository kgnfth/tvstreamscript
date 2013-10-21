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
                Users
            </li>
            <li>
                Manage users
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">

        <table class="table table-bordered table-striped table_vam" id="dt_gal">
            <thead>
                <tr>
                    <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th>
                    <th style="width:60px">Avatar</th>
                    <th>Username</th>
                    <th>Facebook</th>
                    <th>Language</th>
                    <th>Email</th>
                    <th>Delete</th>
                </tr>
            </thead>
         
            <tbody>
            </tbody>
        </table>
        
        <!-- hide elements (for later use) -->
        <div class="hide">
            <!-- actions for datatables -->
            <div class="dt_gal_actions">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle">With selected <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="delete_rows_dt" data-tableid="dt_gal"><i class="icon-trash"></i> Delete</a></li>
                    </ul>
                </div>
            </div>
            <!-- confirmation box -->
            <div id="confirm_dialog" class="cbox_content">
                <div class="sepH_c tac"><strong>Are you sure you want to delete these row(s)?</strong></div>
                <div class="tac">
                    <a href="#" class="btn btn-gebo confirm_yes">Yes</a>
                    <a href="#" class="btn confirm_no">No</a>
                </div>
            </div>
        </div>
        
    </div>
</div>
