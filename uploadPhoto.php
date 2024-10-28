<?php
require_once("dbtools.inc.php");

$link=create_connection();

if (!file_exists('./Photo')) {
    mkdir('./Photo', 0777, true);
}
if (!file_exists('./Thumbnail')) {
    mkdir('./Thumbnail', 0777, true);
}

if(!isset($_POST["album_id"]))
{
    $album_id=$_GET["album_id"];
    
    //取得相簿名稱及相簿主人
    $sql="SELECT name,owner FROM album WHERE id=$album_id";
    $result=executed_sql($link,"album",$sql);
    $row=mysqli_fetch_object($result);
    $album_name=$row->name;
    $album_owner=$row->owner;

    mysqli_free_result($result);
}
else
{
    $album_id=$_POST["album_id"];
    $album_owner=$_POST["album_owner"];
    //取得使用者登入的帳號
    session_start();
    $login_user=$_SESSION["login_user"];

    if(isset($login_user) && $album_owner==$login_user)
    {
        for($i=0;$i<=3;$i++)
        {
            //若檔名不是空字串，表示上傳成功，就將暫存檔案移至指定的資料夾
            if($_FILES["myfile"]["name"][$i]!=="")
            {
                $src_file=$_FILES["myfile"]["tmp_name"][$i];
                $src_file_name=$_FILES["myfile"]["name"][$i];
                $src_ext=strtolower(strchr($_FILES["myfile"]["name"][$i],"."));
                $desc_file_name=uniqid().".jpg";

                $photo_file_name="./Photo/$desc_file_name";
                $thumbnail_file_name="./Thumbnail/$desc_file_name";

                resize_photo($src_file,$src_ext,$photo_file_name,600);
                resize_photo($src_file,$src_ext,$thumbnail_file_name,150);

                $sql="INSERT INTO photo(name,filename,album_id)VALUES('$src_file_name'
                    ,'$desc_file_name',$album_id)";
                executed_sql($link,"album",$sql);
            }
        }
    }
    mysqli_close($link);
    header("location:showAlbum.php?album_id=$album_id");
}

function resize_photo($src_file,$src_ext,$dest_name,$max_size)
{
    switch($src_ext)
    {
        case ".jpg":
            $src=imagecreatefromjpeg($src_file);
            break;
        case ".png":
            $src=imagecreatefrompng($src_file);
            break;
        case ".gif":
            $src=imagecreatefromgif($src_file);
            break;
    }
    $src_w=imagesx($src);
    $src_h=imagesy($src);

    //建立新的空圖形
    if($src_w>$src_h)
    {
        $thumb_w=$max_size;
        $thumb_h=intval($src_h/$src_w * $thumb_w);
    }
    else
    {
        $thumb_h=$max_size;
        $thumb_w=intval($src_w/$src_h * $thumb_h);
    }
    $thumb=imagecreatetruecolor($thumb_w,$thumb_h);
    //進行複製並縮圖
    imagecopyresampled($thumb,$src,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
    //儲存相片
    imagejpeg($thumb,$dest_name,100);

    imagedestroy($src);
    imagedestroy($thumb);
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
    <p align="center">
        <?php echo $album_name?>
        <form action="uploadPhoto.php" enctype="multipart/form-data" method="post">
            <input type="file" name="myfile[]" size="50"><br>
            <input type="file" name="myfile[]" size="50"><br>
            <input type="file" name="myfile[]" size="50"><br>
            <input type="file" name="myfile[]" size="50"><br><br>
            <input type="hidden" name="album_id" value="<?php echo $album_id?>">
            <input type="hidden" name="album_owner" value="<?php echo $album_owner?>">
            <input type="submit" value="上傳">
            <input type="reset" value="重新設定">
        </form>
        <a href="showAlbum.php?album_id=<?php echo $album_id?>">
            回[<?php echo $album_name?>]相簿</a>
    </p>
</body>
</html>