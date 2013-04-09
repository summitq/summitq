<?php

header("Content-Type:text/html;charset=utf-8");
include_once( 'config.php' );

//===========================插入===================

if(isset($_POST['userQQ']) && is_numeric( $_POST['userQQ'])){

    $nowTime = time();
    $userQQ = $_POST['userQQ'];
    $isInSql = "select * from `qq` where qq=".$userQQ;
    $query = mysql_query($isInSql);
    $row = mysql_fetch_array($query);
    if(!$row){
        $sql = "INSERT INTO `qq` (`qq` ,`id` ,`dateline`)VALUES ('".$userQQ."', NULL , '".$nowTime."');";
        mysql_query($sql);
		echo "添加成功";
    }else{
        echo "此QQ已存在！";
    }
}
else{
    echo "请输入正确的QQ号码";
}

  //===========================分页===================
    //1.定义一些分页变量
    $page = isset($_GET['page'])?$_GET['page']:1 ;       //当前页号
    $pageSize = 10;  //页大小
    $maxRows;        //最大数据条
    $maxPages;		 //最大页数

    //2.获取最大数据条数
    $sql = "select count(*) from `qq`";
    $res = mysql_query($sql);
    $maxRows = mysql_result($res,0,0); //定义从结果集中获取数据条数
    //3.计算出总计最大页数
    $maxPages = ceil($maxRows/$pageSize);//采用进一求整发算出最大页数

    //4.校验当前页数
    if($page > $maxPages){
         $page = $maxPages;
    }
    if($page < 1){
        $page = 1;
    }

    //5.拼装分页sql语句片段
     $limit = " limit ".(($page-1)*$pageSize).",{$pageSize}";
  //执行查询，并返回结果集
    $sql = "select * from `qq` order by id asc {$limit}";
    $result = mysql_query($sql);

?>
<!html>
<html>
<head>
    <title>插入用户QQ</title>
</head>
<body>
<div style="width:960px;margin: 0 auto;">
    <div style="width:400px;height:200px;float: left;">
        <form action="insertUserQQ.php" method="post">
            QQ: <input type="text" name="userQQ" /> <br />
            <input type="submit" />
        </form>
    </div>

    <div style="width:400px;float: right;">
        <ul style="height:200px;overflow: hidden;">
            <?php
            while($row = mysql_fetch_array($result))
            {
                echo "<li>".$row['qq']."</li>";
            }

            mysql_free_result($result);
            ?>
        </ul>
        <p>
            <?php
                echo "当前{$page}/{$maxPages} 共计{$maxRows}";
                echo "<a href='insertUserQQ.php?page=1'> 首页 </a>";
                echo "<a href='insertUserQQ.php?page=".($page-1)."'> 上一页 </a>";
                echo "<a href='insertUserQQ.php?page=".($page+1)."'> 下一页 </a>";
                echo "<a href='insertUserQQ.php?page=".$maxPages."'> 末页 </a>";
            ?>
        </p>
    </div>
</div>


</body>
</html>