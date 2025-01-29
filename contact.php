<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);


   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pulication Proposal</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Publication proposal</h3>
   <p> <a href="home.php">Home</a> / Publication Proposal </p>
</div>

<section class="contact">

   <form action="" method="post">
      <h3>Upload the draft in Google Drive & share your idea with us</h3>
      <input type="text" name="name" required placeholder="Enter Name" class="box">
      <input type="email" name="email" required placeholder="Enter Email" class="box">
      <input type="number" name="number" required placeholder="Enter Number" class="box">
      <textarea name="message" class="box" placeholder="Link & message" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

</section>


<section class="placed-orders">

   <h1 class="title">Proposals</h1>

   <div class="box-container">

      <?php
         $message_query = mysqli_query($conn, "SELECT * FROM `message` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($message_query) > 0){
            while($fetch_message = mysqli_fetch_assoc($message_query)){
      ?>
      <div class="box">
         <p> Name : <span><?php echo $fetch_message['name']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_message['email']; ?></span> </p>
         <p> Number : <span><?php echo $fetch_message['number']; ?></span> </p>
         <p> Message : <span><?php echo $fetch_message['message']; ?></span> </p>
         <p> Status : <span style="color:<?php if($fetch_message['status'] == 'Pending'){ echo 'grey'; }elseif($fetch_message['status'] == 'Approved'){ echo 'green'; }else{ echo 'red'; } ?>;"><?php echo $fetch_message['status']; ?></span> </p>

      </div>
      <?php
       }
      }else{
         echo '<p class="empty">No messages yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>