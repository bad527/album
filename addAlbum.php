<?php
if(isset($_POST["album_name"]))
{
    require_once("dbtools.inc.php");
    $album_name=$_POST["album_name"];
    //取得使用者登入的帳號
    session_start();
    $login_user=$_SESSION["login_user"];

    $link=create_connection();

    //新增相簿
    $sql="SELECT ifnull(max(id),0)+1 AS album_id FROM album";
    $result=executed_sql($link,"album",$sql);
    $album_id=mysqli_fetch_object($result)->album_id;
    $sql="INSERT INTO album(id,name,owner)
        VALUES ($album_id,'$album_name','$login_user')";
    executed_sql($link,"album",$sql);

    mysqli_free_result($result);
    mysqli_close($link);
    
    header("location:showAlbum.php?album_id=$album_id");
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
    <form action="addAlbum.php" method="post">
        <table align="center">
            <tr>
                <td>相簿名稱</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="album_name" size="15">
                    <input type="submit" value="新增">
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <br><a href="index.php">回首頁</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>