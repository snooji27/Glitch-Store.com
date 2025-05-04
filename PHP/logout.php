<?php
session_start();
session_unset();
session_destroy();
header("Location: /Glitch-Store.com-main/Login_Signup/Adm_Log.html");
exit();
?>
