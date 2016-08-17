<?php
$parm = $_GET['parm'];
$msg = file_get_contents("http://api.mrtimo.com/Simsimi.ashx?parm=".$parm);
echo $msg;