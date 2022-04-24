<?php
$pdo=new PDO("mysql:host=localhost;port=3306;dbname=product_crud","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
$errors =[];
$title="";
$price="";
$description="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
$title = $_POST["title"];
$description = $_POST["description"];
$price = $_POST["price"];
$date = date("Y-m-d H:i:s");

if(!$title){
    $errors[]='product title is required';
};
if(!$price){
    $errors[]="product price is required";
};
if(!is_dir("images")){
  mkdir("images");
}
if(empty($errors)){
  $image = $_FILES['img'] ?? null;
  $imagePath="";
  if($image && $image["tmp_name"]){

    $imagePath="images/".randomString(8)."/".$image["name"];
    mkdir(dirname($imagePath));
    move_uploaded_file($image['tmp_name'],$imagePath);
  }
$statement=$pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
 VALUES (:title, :image, :description, :price, :date)
 ");
 $statement->bindValue(':title', $title);
 $statement->bindValue('image', "$imagePath");
 $statement->bindValue(':description', $description);
 $statement->bindValue(':price', $price);
 $statement->bindValue(':date', $date);
 $statement->execute();
 header('location:index.php');
};
};
function randomString($n){
  $characters='123456789abcdefghijklmnopqrstuvwxyzABCDEFJHIGKLMNOPQRSTUVWXYZ';
  $str="";
  for($i = 0 ; $i < $n ; $i++){
    $index=rand(0,strlen($characters)-1);
    $str .=$characters[$index];
  }
  return $str;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Create New Product</title>
  </head>
  <body>
      
    <h1>ProductsCRUD</h1>
    <?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach($errors as $error): ?>
            <div><?php echo $error ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <form action=""  method="POST" enctype="multipart/form-data">
    <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Image</label>
    <input type="file" name="img">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Title</label>
    <input type="text" value="<?php echo $title?>" name="title" class="form-control">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Description</label>
    <textarea class="form-control" name="description"><?php echo $description?></textarea>
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Price</label>
    <input type="number" step=".01" value="<?php echo $price?>" class="form-control" name="price">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
  </body>
</html>