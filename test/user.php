<?php
require_once '../lib/common.php';

$pdo = getPDO();
$id = 4;
$sql = "SELECT username FROM user WHERE id=$id";

$stmt = $pdo->prepare($sql);
if ($stmt === false) {
    echo "Error preparing sql statement";
} else {
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        print_r($result); // Output the result
    } else {
        echo "No user found with ID = " . $id;
    }
}
?>