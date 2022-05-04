<?php
$pdo = new PDO('mysql:host=localhost;dbname=BookReview2;charset=utf8', 'bookreview2', '12345');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);