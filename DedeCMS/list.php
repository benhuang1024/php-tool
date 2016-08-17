<!doctype html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-siteapp; charset=utf-8" />

    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">


</head>
<body>
<?php
$tid = $_GET['tid'];
if(empty($tid)){
    echo '404';
    exit;
}
include 'temp/head.htm';
include 'lib/mysql.class.php';
$db = new MySQL();
?>

<div class="Big_dis">
    <a href="list.php?tid=<?php echo $tid;?>" target="_blank"> <?php $sql = "SELECT typedir,typename FROM `".MYSQL_PRELUDE."_arctype` WHERE id = $tid";$res = $db->query($sql);echo $res[0]['typename']; ?> </a>
</div>
<div class="Ent_list">
    <section class="list">

        <div class="list_dis">

            <?php
            $sql = "SELECT id,title FROM `".MYSQL_PRELUDE."_archives` WHERE typeid = $tid ORDER BY `id`";

            $res = $db->query($sql);

            foreach($res as $key=>$val){
                ?>
                <a href="article.php?id=<?php echo $val['id'] ?>&tid=<?php echo $tid; ?>" title="<?php echo $val['title'] ?>" target="_blank"><?php echo $val['title'] ?>
                </a>
            <?php }?>
   </div>

        </section>
    </div>

    <?php include 'temp/foot.htm'; ?>
</body>
</html>