<?php
$id=$_POST['id'] ?? null;
if(!$id){
    header("location: index.php");
    exit;
}
$pdo=new PDO("mysql:host=localhost;port=3306;dbname=product_crud","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$statement=$pdo->prepare("DELETE FROM products WHERE id=:id"); 
$statement->bindValue(":id",$id);
$statement->execute();
header("location:index.php");