<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_message'])){

   $message_update_id = $_POST['message_id'];
   $update_status = $_POST['update_status'];
   mysqli_query($conn, "UPDATE `message` SET status = '$update_status' WHERE id = '$message_update_id'") or die('query failed');
   $output[] = 'Status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> messages </h1>

   <div class="box-container">
   <?php
      $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
      if(mysqli_num_rows($select_message) > 0){
         while($fetch_message = mysqli_fetch_assoc($select_message)){
      
   ?>
   <div class="box">
      <p> user id : <span><?php echo $fetch_message['user_id']; ?></span> </p>
      <p> name : <span><?php echo $fetch_message['name']; ?></span> </p>
      <p> number : <span><?php echo $fetch_message['number']; ?></span> </p>
      <p> email : <span><?php echo $fetch_message['email']; ?></span> </p>
      <p> message : <span><?php echo $fetch_message['message']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="message_id" value="<?php echo $fetch_message['id']; ?>">
         <select name="update_status" style="border-radius: 0.5rem; margin: 0.5rem 0; width: 100%; background-color: var(--light-bg); border: var(--border); padding: 1.2rem 1.4rem; font-size: 1.8rem; color: var(--black);">
            <option value="Pending" <?php if ($fetch_message['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Approved" <?php if ($fetch_message['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
            <option value="Rejected" <?php if ($fetch_message['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
         </select>
         <input type="submit" value="update" name="update_message" class="option-btn">
         <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="delete-btn">Delete</a>
      </form>


   <?php
      };
   }else{
      echo '<p class="empty">you have no messages!</p>';
   }
   ?>
   </div>

</section>









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>