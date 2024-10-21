<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p align="center"><img src="Title.jpg" alt=""></p>
    <?php
    require_once("dbtools.inc.php");

    $album_id=$_GET["album"];
    $photo_id=$_GET["photo"];

    $link=create_connection();

    //取得並顯示相簿名稱
    $sql="SELECT name FROM album WHERE id=$album_id";
    $result=executed_sql($link,"album",$sql);
    $albim_name=mysqli_fetch_object($result)->name;
    echo "<p align='center'>$albim_name</p>";

    //取得並顯示相片資料
    $sql="SELECT filename,comment FROM photo WHERE id=$photo_id";
    $result=executed_sql($link,"album",$sql);
    $row=mysqli_fetch_object($result);
    $file_name=$row->filename;
    $comment=$row->comment;
    echo "<p align='center'><img src='Photo/$file_name'
        style='border-style:solid;border-width:1px;'></p>";
    echo "<p align='center'>$comment</p>";

    //取得並建立相片導覽資料
    $sql="SELECT a.id,a.filename FROM(SELECT id,filename FROM photo
      WHERE album_id=$album_id AND (id<=$photo_id)
      ORDER BY id DESC) a ORDER BY a.id";
    $result=executed_sql($link,"album",$sql);
    echo "<hr><p align='center'>";
    while($row=mysqli_fetch_assoc($result))
    {
        if($row["id"]==$photo_id)
        {
            echo "<img src='Thumbnail/".$row["filename"].
            "style='border-style:solid;border-color:Red;border-width:2px;'>";
        }
        else
        {
            echo "<a href='photoDetail.php?album=$album_id&photo=".$row["id"].
              "'><img src='Thumbnail/".$row["filename"].
              "' style='border-style:solid;border-color:Black;border-width:1px;'></a>";
        }
    }
    $sql="SELECT id,filename FROM photo WHERE album_id=$album_id AND 
      (id>$photo_id) ORDER BY id";
    $result=executed_sql($link,"album",$sql);
    while($row=mysqli_fetch_assoc($result))
    {
        echo "<a href='photoDetail.php?album_id&photo=".$row["id"].
        "'><img src='Thumbnail/".$row["filename"].
        "' style='border-style:solid;border-color:Black;border-width:1px;'></a>";
    }
    echo "</p>";

    mysqli_free_result($result);
    mysqli_close($link);
    ?>
    <p align="center">
        <a href="index.php">回首頁</a>
        <a href="showAlbum.php?album_id=<?php echo $album_id?>">
            回[<?php echo $albim_name?>]相簿</a>
    </p>
</body>
</html>