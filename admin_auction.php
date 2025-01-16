<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

// if(isset($_POST['add_auction'])){

//    $name = mysqli_real_escape_string($conn, $_POST['name']);
//    $image = $_FILES['image']['name'];
//    $image_size = $_FILES['image']['size'];
//    $image_tmp_name = $_FILES['image']['tmp_name'];
//    $image_folder = 'uploaded_img/'.$image;
//    $author = $_POST['author'];
//    $publisher = $_POST['publisher'];
//    $publishing_yr = $_POST['publishing_yr'];
//    $speciality = $_POST['speciality'];
//    $isbn = $_POST['isbn'];
//    $start_date = $_POST['start_date'];
//    $duration = intval($_POST['duration']); // Ensure duration is fetched correctly
//    $end_date = date('Y-m-d H:i:s', strtotime($start_date . " + {$duration} days"));   
//    $bid = $_POST['bid'];
//    $bidder = $_POST['bidder'];

//    $select_auction_name = mysqli_query($conn, "SELECT name FROM `auction` WHERE name = '$name'") or die('query failed');

//    if(mysqli_num_rows($select_auction_name) > 0){
//       $message[] = 'Already added';
//    }else{
//       $add_auction_query = mysqli_query($conn, "INSERT INTO `auction`(name, image, author, publisher, publishing_yr, speciality, isbn, start_date, duration, bid, bidder) VALUES('$name', '$image', '$author', '$publisher', '$publishing_yr', '$speciality', '$isbn','$start_date', '$duration', $bid, $bidder)") or die('query failed');

//       if($add_auction_query){
//          if($image_size > 2000000){
//             $message[] = 'Image size is too large';
//          }else{
//             move_uploaded_file($image_tmp_name, $image_folder);
//             $message[] = 'Added successfully!';
//          }
//       }else{
//          $message[] = 'Could not be added!';
//       }
//    }
// }


if (isset($_POST['add_auction'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $image = $_FILES['image']['name'];
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = 'uploaded_img/' . $image;
  $author = $_POST['author'];
  $publisher = $_POST['publisher'];
  $publishing_yr = $_POST['publishing_yr'];
  $speciality = $_POST['speciality'];
  $isbn = $_POST['isbn'];
  $start_date = $_POST['start_date'];
  $duration = ($_POST['duration']); // Fetch duration in days
  $end_date = date('Y-m-d H:i:s', strtotime($start_date . " + {$duration} days"));
  $bid = $_POST['bid'];
  $bidder = isset($_POST['bidder']) ? $_POST['bidder'] : 'Start'; // Default to 'Starting'

  // Check if auction name already exists
  $select_auction_name = mysqli_query($conn, "SELECT name FROM `auction` WHERE name = '$name'") or die(mysqli_error($conn));
  if (mysqli_num_rows($select_auction_name) > 0) {
      $message[] = 'Auction already added';
  } else {
      // Insert data into database
      $add_auction_query = mysqli_query($conn, "INSERT INTO `auction`(name, image, author, publisher, publishing_yr, speciality, isbn, start_date, end_date,duration, bid, bidder) VALUES('$name', '$image', '$author', '$publisher', '$publishing_yr', '$speciality', '$isbn', '$start_date', '$end_date', '$duration', '$bid', '$bidder')") or die(mysqli_error($conn));

      if ($add_auction_query) {
          if ($image_size > 2000000) {
              $message[] = 'Image size is too large';
          } else {
              move_uploaded_file($image_tmp_name, $image_folder);
              $message[] = 'Auction added successfully!';
          }
      } else {
          $message[] = 'Could not add auction!';
      }
  }
}





if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `auction` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `auction` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_auction.php');
}

if(isset($_POST['update_auction'])){

   $update_a_id = $_POST['update_a_id'];
   $update_duration = $_POST['update_duration'];
   mysqli_query($conn, "UPDATE `auction` SET duration = '$update_duration' WHERE id = '$update_a_id'") or die('query failed');

   $update_end_date = date('Y-m-d H:i:s', strtotime($update_start_date . " + {$update_duration} days"));
   mysqli_query($conn, "UPDATE `auction` SET end_date = '$update_end_date' WHERE id = '$update_a_id'") or die('query failed');


   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'Image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `auction` SET image = '$update_image' WHERE id = '$update_a_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_auction.php');

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

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- auction CRUD section starts  -->

<section class="add-products">

   <h1 class="title">Schedule Auction</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Add auction</h3>
      <input type="text" name="name" class="box" placeholder="Book Title" required>
      <input type="number" min="0" name="bid" class="box" placeholder="Starting Price" required>
      <input type="text" name="author" class="box" placeholder="Author" required>
      <input type="text" name="publisher" class="box" placeholder="Publisher" required>
      <input type="text" name="publishing_yr" class="box" placeholder="Publishing year" required>
      <input type="text" name="speciality" class="box" placeholder="Speciality" required>
      <input type="number" name="isbn" class="box" placeholder="ISBN" required>
      <input type="datetime-local" name="start_date" class="box" placeholder="Start Date" required>
      <input type="number" name="duration" class="box" placeholder="Duration" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add auction" name="add_auction" class="btn">
   </form>

</section>

<!-- auction CRUD section ends -->

<!-- show auction  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_auction = mysqli_query($conn, "SELECT * FROM `auction`") or die('query failed');
         if(mysqli_num_rows($select_auction) > 0){
            while($fetch_auction = mysqli_fetch_assoc($select_auction)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_auction['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_auction['name']; ?></div>
         <!-- <div class = "name" style = "padding: 0rem; font-size: 1.5rem"><?php echo $fetch_auction['start_date']; ?></div> -->
         <!-- <div class="end_date" style = "padding: 0rem; font-size: 1.5rem">Ending Date: <?php echo $fetch_auction['end_date']; ?></div> -->
         <div class="countdown" style = "padding: 0rem; font-size: 1.8rem">Remaining<p id="demo"></p></div>
         <!-- <div class="name"><?$end_date = date('Y-m-d H:i:s', strtotime($start_date . " + {$duration} days")); ?></div> -->
         <!-- <p id="demo"></p> -->
          <script>
              // Dynamically pass the start and end times from PHP
              var startTime = "<?php echo $fetch_auction['start_date']; ?>"; // Current time from PHP
              var endTime = "<?php echo $fetch_auction['end_date']; ?>"; // Replace with your desired end time

              // Initialize the countdown timer
              initializeCountdown(startTime, endTime, "demo");
          </script>
         
         
         
         <div class="price"><?php echo $fetch_auction['bid']; ?> Tk</div>
         <div class="bidder" style = "padding: 0rem; font-size: 1.5rem"> UserID: <?php echo $fetch_auction['bidder']; ?></div>
         <a href="admin_auction.php?update=<?php echo $fetch_auction['id']; ?>" class="option-btn">Update</a>
         <a href="admin_auction.php?delete=<?php echo $fetch_auction['id']; ?>" class="delete-btn" onclick="return confirm('Delete auction?');">Delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No auctions scheduled yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `auction` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_a_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter auction name">
      <input duration="number" name="update_duration" value="<?php echo $fetch_update['duration']; ?>" class="box" required placeholder="enter auction duration">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_auction" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
         
      }
   ?>

</section>







<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>