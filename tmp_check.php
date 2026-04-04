<?php
$db = new PDO('mysql:host=127.0.0.1;dbname=restaurant_db', 'root', '');
$count = $db->query('SELECT count(*) as c from menus')->fetch(PDO::FETCH_ASSOC);
var_dump($count);
