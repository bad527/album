<?php
require_once("dbtools.inc.php");
$album_id = $_GET["album_id"];

// 取得使用者登入的帳號
session_start();
$login_user = $_SESSION["login_user"];

// 建立資料連結
$link = create_connection();

// 取得相簿的擁有者
$sql = "SELECT owner FROM album WHERE id=$album_id";
$result = executed_sql($link, "album", $sql); // 使用 executed_sql
$row = mysqli_fetch_assoc($result);
$album_owner = $row['owner'];

// 刪除儲存在硬碟的相片檔案
$sql = "SELECT filename FROM photo WHERE album_id=$album_id AND EXISTS(SELECT '*' FROM album WHERE id=$album_id AND owner='$login_user')";
$result = executed_sql($link, "album", $sql); // 使用 executed_sql

while ($row = mysqli_fetch_assoc($result)) {
    $file_name = $row["filename"];
    $photo_path = realpath("./Photo/$file_name");
    $thumbnail_path = realpath("./Thumbnail/$file_name");

    if (file_exists($photo_path)) {
        unlink($photo_path);
    }
    if (file_exists($thumbnail_path)) {
        unlink($thumbnail_path);
    }
}

// 刪除儲存在資料庫的相片資料
$sql = "DELETE FROM photo WHERE album_id=$album_id AND EXISTS(SELECT '*' FROM album WHERE id=$album_id AND owner='$album_owner')";
executed_sql($link, "album", $sql); // 使用 executed_sql

// 刪除儲存在資料庫的相簿檔案
$sql = "DELETE FROM album WHERE id=$album_id AND owner='$login_user'";
executed_sql($link, "album", $sql); // 使用 executed_sql

mysqli_free_result($result);
mysqli_close($link);
header("location:index.php");
?>
