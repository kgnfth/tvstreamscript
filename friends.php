<?php
$user = new User();

$followed = $user->getFollows();
$followers = $user->getFollowers();

$smarty->assign("followed",$followed);
$smarty->assign("followers",$followers);

?>