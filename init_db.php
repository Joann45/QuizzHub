<?php
require 'Classes/AutoLoader.php';
AutoLoader::register();
use Database\Connection;
Connection::initDB();
?>