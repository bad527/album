<?php
require_once("dbtools.inc.php");

//使得使用者登入的帳號
session_start();
$login_user=$_SESSION["login_user"];

//建立連線資料
$link=create_connection();

if(!isset($_POST["album_id"]))
{
    $album_id=$_GET["album_id"];
    //取得相簿名稱及主人
    $sql="SELECT name,owner FROM album WHERE id=$album_id";
    $result=executed_sql($link,"album",$sql);
    $row=mysqli_fetch_object($result);
    $album_name=$row->name;
    $album_owner=$row->owner;

    mysqli_free_result($result);

    mysqli_close($link);

    if($album_owner!=$login_user)
    {
        echo "<script type='text/javascript'>";
        echo "alert('您不是相簿的主人，無法修改相簿名稱。$album_owner')";
        echo "</script>";
    }
}
else
{
    $album_id=$_POST["album_id"];
    $albim_name=$_POST["album_name"];

    $sql="UPDATE album SET name='$albim_name'
        WHERE id='$album_id' AND owner='$login_user'";
    executed_sql($link,"album",$sql);

    //關閉資料連結
    mysqli_close($link);

    header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p align="center"><img src="Title.jpg" alt=""></p>
    <tr>
        <td>
            相簿名稱
        </td>
        <td>
            <input type="text" name="album_name" size="15" 
                value="<?php echo $album_name?>">
            <input type="hidden" name="album_id" value="<?php echo $album_id?>">
            <input type="submit" value="更新" 
                <?php if($album_owner!=$login_user) echo 'disabled'?>>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <br><a href="index.php">回首頁</a>
        </td>
    </tr>
</body>
</html>
