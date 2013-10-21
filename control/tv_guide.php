<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($date) || !$date){
    $date = date("Y-m-d");
}
?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=shows_manage">TV shows</a>
            </li>
            <li>
                TV guide
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">
            <h3 class="pull-left">TV schedule for <?php print($date); ?></h3>
            <a href="index.php?menu=tv_guide&date=<?php print(date("Y-m-d",strtotime("tomorrow",strtotime($date)))); ?>" class="btn btn-primary pull-right">Next day &raquo;</a>
            <a href="index.php?menu=tv_guide&date=<?php print(date("Y-m-d",strtotime("yesterday",strtotime($date)))); ?>" class="btn btn-primary pull-right">&laquo; Previous day</a>
        </div>
    </div>
</div>


<div class="content-box"><!-- Start Content Box -->
                

    
    <div class="content-box-content">
        
        <div id="output">
        
        </div>
        <script>
            jQuery(document).ready(function(){
                getTvGuide('<?php print($date); ?>');
            });
        </script>
    </div>
</div>