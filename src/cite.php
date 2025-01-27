<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $product_image = $_POST['product_image'];
  $product_quantity = $_POST['product_quantity'];
  $product_author = $_POST['product_author'];
  $product_publisher = $_POST['product_publisher'];
  $product_publisihing_yr = $_POST['product_publisihing_yr'];
  $product_isbn = $_POST['product_isbn'];

  $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cite</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Generate citations :)</h3>
   <p> <a href="home.php">Home</a> / Cite </p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="Search book..." class="box">
      <input type="submit" name="submit" value="Cite" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">
   <?php
      if(isset($_POST['submit'])){
         $search_item = $_POST['search'];
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
         while($fetch_product = mysqli_fetch_assoc($select_products)){
   ?>
   <form action="" method="post" class="box">
      <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
      <div class="name"><?php echo $fetch_product['name']; ?></div>
      <div class="author" style="padding: None; font-size: 1.5rem; color: var(--black);"> Written by <?php echo $fetch_product['author'];?> </div>
      <div class="publisher" style="padding: None; font-size: 1.5rem; color: var(--black);"> Pulisher <?php echo $fetch_product['publisher'];?>(<?php echo $fetch_product['publishing_yr'];?>)</div>
      <div class="isbn" style="padding: None; font-size: 1.5rem; color: var(--black);"> ISBN: <?php echo $fetch_product['isbn']; ?> </div>

      <?php
         // Generate APA 7th Citation
         $title = $fetch_product['name'];
         $author = $fetch_product['author'];
         $publisher = $fetch_product['publisher'];
         $year = $fetch_product['publishing_yr'];

         // Format author name (Assumes "LastName, FirstName")
         $author_parts = explode(" ", $author);

         // Generate citation
         $citation = "{$author_parts[count($author_parts) - 1]}, {$author_parts[0][0]}. ({$year}). <em>{$title}</em>. {$publisher}.";
      ?>
      <br>
      <div class="citation" style="padding: 1px None;margin = 0; font-size: 1.5rem; color: var(--black); text-align-last: center; line height: 1.2;">APA 7th Citation: <?php echo $citation; ?></div>
      
   
    </form>
   <?php
            }
         }else{
            echo '<p class="empty">Not avaiable in the store!</p>';
         }
      }else{
         echo '<p class="empty">Search something!</p>';
      }
   ?>
   </div>
  

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>