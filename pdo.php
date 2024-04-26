<?php
try {
$pdo = new PDO('mysql:host=localhost;port=3307;dbname=project', 'project', 'Kony@8125');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo("Connected");
} catch (PDOException $e) {
    echo "<br><br><br>Error: ".$e->getMessage();
}