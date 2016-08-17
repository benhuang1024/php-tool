<?php
$tid = $_GET['tid'];
$id = $_GET['id'];
if(empty($tid)||empty($id)){
    echo '404';
    exit;
}
include 'lib/mysql.class.php';
$db = new MySQL();
$msg_sql = "SELECT title,keywords,description FROM `".MYSQL_PRELUDE."_archives` WHERE id = $id";
$msg = $db->query($msg_sql);
$msg = $msg[0];
?>
<!DOCTYPE HTML>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title><?php echo $msg['title'] ?></title>
    <meta name="description" content="<?php echo $msg['description'] ?>">
    <meta name="keywords" content="<?php echo $msg['keywords'] ?>">

</head>
<body>
<?php include 'temp/head.htm'; ?>
<div class="gb_weiz gb_pannel">
    &nbsp;&nbsp;&nbsp;&nbsp;当前位置：<span><a href="/">主页</a>><?php $sql = "SELECT id,typename FROM `".MYSQL_PRELUDE."_arctype` WHERE id = $tid";$res = $db->query($sql);echo $res[0]['typename']; ?></a></span></div>
<div class="arc_body">
    <h1><?php echo $msg['title'] ?></h1>
    <div class="body">

    <?php $sql = "SELECT body FROM `".MYSQL_PRELUDE."_addonarticle` WHERE aid = $id";$res = $db->query($sql);echo $res[0]['body']; ?>
    </div>
</div>
<?php include 'temp/foot.htm'; ?>
</body>
</html>