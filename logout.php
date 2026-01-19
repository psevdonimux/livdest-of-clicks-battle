<?php
require_once './php/Session.php';
Session::logout();
header('Location: join.php');
exit;
