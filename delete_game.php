<?php
session_start();
include 'db_connect.php';

$id = intval($_GET['game_id']);
$query = "DELETE FROM game WHERE game_id = $id";
mysqli_query($conn, $query);
header("Location: view_games.php");
exit();
?>
