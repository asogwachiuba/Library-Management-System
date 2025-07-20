<?php
$id=htmlentities($_GET['id']);
require_once("conn.php");
$req="DELETE FROM users WHERE id=?";
$ps=$pdo->prepare($req);
$params=array($id);
$ps->execute($params);
header("location:listusers.php");

?>