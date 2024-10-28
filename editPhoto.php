<?php
require_once("dbtools.inc.php");
session_start();

$login_user = $_SESSION["login_user"];
$link = create_connection();

if (!isset($_POST["photo_name"])) {
    // 判斷 photo_id 是否存在於 $_GET 中
    if (!isset($_GET["photo_id"])) {
        die("錯誤：未提供 photo_id");
    }

    $photo_id = $_GET["photo_id"];

    // 取得相簿及相片資料
    $sql = "SELECT a.name, a.filename, a.comment, a.album_id, b.name AS album_name, b.owner
            FROM photo a, album b WHERE a.id = $photo_id AND b.id = a.album_id";
    $result = executed_sql($link, "album", $sql);

    if ($row = mysqli_fetch_object($result)) {
        $album_id = $row->album_id;
        $album_name = $row->album_name;
        $album_owner = $row->owner;
        $photo_name = $row->name;
        $file_name = $row->filename;
        $photo_comment = $row->comment;
    } else {
        die("錯誤：找不到相片");
    }

    mysqli_free_result($result);

    if ($album_owner != $login_user) {
        echo "<script type='text/javascript'>";
        echo "alert('您不是相片的主人，無法修改相片名稱。')";
        echo "</script>";
    }
} else {
    // 確認 $_POST 中的變數是否被設置
    $album_id = isset($_POST["album_id"]) ? $_POST["album_id"] : "";
    $photo_id = isset($_POST["photo_id"]) ? $_POST["photo_id"] : "";
    $photo_name = isset($_POST["photo_name"]) ? $_POST["photo_name"] : "";
    $photo_comment = isset($_POST["photo_comment"]) ? $_POST["photo_comment"] : "";

    // 確保所有變數都有值
    if ($album_id && $photo_id && $photo_name) {
        $sql = "UPDATE photo SET name = '$photo_name', comment = '$photo_comment'
                WHERE id = $photo_id AND EXISTS (SELECT * FROM album WHERE id = $album_id AND owner = '$login_user')";
        executed_sql($link, "album", $sql);

        mysqli_close($link);
        header("Location: showAlbum.php?album_id=$album_id");
    } else {
        echo "錯誤：更新資料不完整。";
    }
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
    <form action="editPhoto.php" method="post">
        <table align="center">
            <tr>
                <td>
                    相片名稱:

                </td>
                <td>
                    <input type="text" name="photo_name" size="31" 
                        value="<?php echo $photo_name;?>">
                </td>
            </tr>
            <tr>
                <td>
                    相片描述:
                </td>
                <td>
                    <textarea name="photo_comment" cols="25" rows="5">
                        <?php echo $photo_comment;?></textarea>
                    <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>">
                    <input type="hidden" name="album_id" value="<?php echo $album_id;?>">
                    <input type="submit" value="更新"
                        <?php if($album_owner!=$login_user) echo 'disabled';?>>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <br><a href="showAlbum.php?album_id=<?php echo $album_owner;?>">
                        回[ <?php echo $album_name?> ]相簿</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>