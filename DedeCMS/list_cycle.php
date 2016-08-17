<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <base target="_blank" />
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
<div class="fy yahei">
    <h3><span><a href="/">主页</a>><?php $sql = "SELECT typedir,typename FROM `".MYSQL_PRELUDE."_arctype` WHERE id = $tid";$res = $db->query($sql);echo $res[0]['typename']; ?></a></span></h3>
    <ul>
		<?php $sql = "SELECT id,typename FROM `".MYSQL_PRELUDE."_arctype` WHERE reid = $tid ORDER BY `id`";$res = $db->query($sql);foreach($res as $key=>$val){ ?>
        <li>
            <dl>
                <dt><img src="/ben/img/<?php echo $val['typename'];?>.png"></dt>
                <dd id="listname"><?php echo $val['typename']; ?></dd>
            </dl>
            <div class="fy_c">
				<?php $sqlarc = 'SELECT id,title,description FROM `".MYSQL_PRELUDE."_archives` WHERE typeid = '.$val['id'].' limit 0,1';$resac = $db->query($sqlarc);$resac = $resac[0]?>
             	   <a href="article.php?id=<?php echo $resac['id'] ?>&tid=<?php echo $tid; ?>" title="<?php echo $resac['title'] ?>" target="_blank">
                    <h2>·<?php echo $resac['title'] ?></h2>
                    <p><?php echo $resac['description'] ?><font class="red">【详细】</font>&nbsp;&nbsp;</p>
                </a>

            </div>
        </li>
       <?php }?>
    </ul>
</div>
<?php include 'temp/foot.htm'; ?>
</body>

</html>
