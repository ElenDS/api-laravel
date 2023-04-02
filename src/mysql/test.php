<?php

$pdo = new PDO('mysql:dbname=api;host=api-mysql', 'root', 'root');

$sql = 'select @@version';

$stmt = $pdo->prepare($sql);
$stmt->execute();

print_r($stmt->fetchAll());
