<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['auto_add_cart'])){

   $auction_name = $_POST['auction_name'];
   $auction_bid = $_POST['auction_bid'];
   $auction_image = $_POST['auction_image'];
   $auction_quantity = 1;
   $auction_winner = $_POST['auction_bidder'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$auction_name' AND user_id = '$user_id'") or die('query failed');

   if ((mysqli_num_rows($check_cart_numbers) > 0) && ($user_id == $auction_winner)) {
      $message[] = 'Already added to cart!';
  } elseif (($check_cart_numbers < 0) && ($user_id == $auction_winner)) {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$auction_name', '$auction_bid', '$auction_quantity', '$auction_image')") or die('Query failed');
      $message[] = 'You won the auction! Product has been added to your cart.';
  }
}

if(isset($_POST['place_bid'])){

   $update_a_id = $_POST['update_a_id'];
   // $auction_name = $_POST['auction_name'];
   $auction_bid = $_POST['auction_bid'];
   $auction_prev_bid = $_POST['auction_prev_bid'];
   // $auction_image = $_POST['auction_image'];
   // $auction_start_date = $_POST['auction_start_date'];
   // $auction_end_date = $_POST['auction_end_date'];
   $auction_bidder = $_POST['auction_bidder'];
   // $auction_duration = $_POST['auction_duration'];

   if ((strtotime($auction_end_date) > strtotime($auction_start_date)) && ($auction_bid > $auction_prev_bid)) {

      mysqli_query($conn, "UPDATE `auction` SET bid = '$auction_bid', bidder = '$user_id' WHERE id = '$update_a_id'") or die('query failed');
      $message[] = 'Bid placed!';
   } 
   elseif(strtotime($auction_end_date) > strtotime($auction_start_date) && $auction_bid < $auction_prev_bid) {
      $message[] = 'Place a value higher than previous bid!';
   } else {
      $message[] = 'Sorry time is up!!!';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="countdown.js"></script>
   <title>Auction</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Auction</h3>
   <p> <a href="home.php">Home</a> / Auction </p>
</div>

<section class="products">

   <h1 class="title">Latest auctioned items</h1>

   <div class="box-container">

      <?php  
         $select_auction = mysqli_query($conn, "SELECT * FROM `auction`") or die('query failed');
         if(mysqli_num_rows($select_auction) > 0){
            while($fetch_auction = mysqli_fetch_assoc($select_auction)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_auction['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_auction['name']; ?></div>
      <input type="number" min="<?php echo $fetch_auction['bid']; ?>" name="auction_bid" value="<?php echo $fetch_auction['bid']; ?>" class="qty">
      <input type="hidden" name="update_a_id" value="<?php echo $fetch_auction['id']; ?>">

      <!-- <input type="hidden" name="auction_bid" value="<?php echo $fetch_auction['bid']; ?>"> -->
 
      <!-- <input type="hidden" name="auction_start_date" value="<?php echo $fetch_auction['start_date']; ?>">
      <input type="hidden" name="auction_end_date" value="<?php echo $fetch_auction['end_date']; ?>"> -->
      <input type="hidden" name="auction_bidder" value="<?php echo $fetch_auction['bidder']; ?>">
      <!-- <input type="hidden" name="auction_duration" value="<?php echo $fetch_auction['duration']; ?>"> -->
      <input type="hidden" name="auction_prev_bid" value="<?php echo $fetch_auction['bid']; ?>">
      <div class="countdown" style = "padding: 0rem; font-size: 1.8rem">Remaining<p id="demo"></p></div>
         <!-- <div class="name"><?$auction_end_date = date('Y-m-d H:i:s', strtotime($auction_start_date . " + {$auction_duration} days")); ?></div> -->
         <!-- <p id="demo"></p> -->
          <script>
            // Dynamically pass the start and end times from PHP
            var startTime = "<?php echo $fetch_auction['start_date']; ?>"; // Current time from PHP
            var endTime = "<?php echo $fetch_auction['end_date']; ?>"; // Replace with your desired end time

              // Initialize the countdown timer
            initializeCountdown(startTime, endTime, "demo");
          </script>
      <input type="submit" value="Place Bid" name="place_bid" class="btn"></form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>