<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>電子相簿</title>
</head>
<p>
    <p align="center"><img src="Title.jpg"></p>
    <?php
    require_once("dbtools.inc.php");

    //取得使用者的帳號與名稱
    session_start();
    if(isset($_SESSION["login_user"]))
    {
        $login_user=$_SESSION["login_user"];
        $login_name=$_SESSION["login_name"];
    }
    
    $link=create_connection();

    //取得所有相簿資料
    $sql="SELECT `id`,`name`,`owner` FROM `album` ORDER BY `name`";
    $album_result=executed_sql($link,"album",$sql);

    //取得相簿數目
    $total_album=mysqli_num_rows($album_result);

    echo "<p align='center'>".$total_album. "Albums</p>";
    echo "<table border='0' align='center'>";

    //設定每列顯示幾個相簿
    $album_per_row=5;

    //顯示相簿清單
    $i=1;
    while($row=mysqli_fetch_assoc($album_result))
    {
        //取得相簿編號、名稱及主人
        $album_id=$row["id"];
        $album_name=$row["name"];
        $album_owner=$row["owner"];

        $sql="SELECT `flename` FROM `photo` WHERE `album_id`='$album_id'";
        $photo_result=executed_sql($link,"album",$sql);

        //取得相片包含的相片數目
        $total_photo=mysqli_num_rows($photo_result);

        //若相片數目大於0，就以第一張當作封面，否則以None.png當作封面
        if($total_photo > 0){
            mysqli_fetch_object($photo_result)->$filename;
        }else{
            $cover_photo="None.jpg";
            
        }
        mysqli_free_result($photo_result);

        if($i%$album_per_row==1){
            echo "<table align='center' valign='top'>";
        }
        echo "<td width='160px'>
              <a href='showAlbum.php?album_id=$album_id'>
              <img src='Thumbmail/$cover_photo'
                style='border-color:Black;border-width:1px'>
              <br>$album_name</a><br>$total_photo Pictures";
            
        if(isset($login_user) && $album_owner==$login_user)
        {
            echo "<br><a href='editAlbum.php?album_id=$album_id'>編輯</a>
                  <a href='#' onclick='DeleteAlbum($album_id)'>刪除</a>";
        }
        echo "</p></td>";

        if($i % $album_per_row==0 || $i==$total_album)
        {
            echo "</tr>";
        }
        $i++;
    }
    echo "</table>";
    //釋放記憶體空間
    mysqli_free_result($album_result);
    //關閉資料連接
    mysqli_close($link);
    echo "<hr><p align='center'>";

    //若isset(login_name)傳回FALSE，則表示使用者未登入
    if(!isset($login_name))
        echo "<a href='login.php'>登入</a>";
    else
    {
        echo "<a href='addAlbum.php'>新增相簿</a>
            <a href='logout.php'>登出[  $login_name ]</a>";
    }
    ?>
    </p>
</body>
</html>