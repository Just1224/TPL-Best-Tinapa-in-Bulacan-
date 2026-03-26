<?php
@include '../includes/config.php';
@include 'includes/auth.php';

// Delete message
if(isset($_GET['delete'])){
   $delete_id = intval($_GET['delete']);
   db_query("DELETE FROM messages WHERE id = :id", ['id' => $delete_id]);
   $_GET['delete'] = null;
}

// Mark as read
if(isset($_GET['read'])){
   $read_id = intval($_GET['read']);
   db_query("UPDATE messages SET is_read = 1 WHERE id = :id", ['id' => $read_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin - Messages</title>
   <link rel="stylesheet" href="../assets/css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      body {
         background: var(--light-color);
         min-height: 100vh;
         display: flex;
         flex-direction: column;
      }

      .admin-container {
         flex: 1;
         padding: 30px;
         max-width: 1200px;
         margin: 0 auto;
         width: 100%;
      }

      .admin-section {
         background: white;
         padding: 25px;
         border-radius: 8px;
         box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
         margin-bottom: 20px;
      }

      .admin-section h2 {
         color: var(--primary-color);
         margin-bottom: 20px;
         padding-bottom: 15px;
         border-bottom: 2px solid var(--primary-color);
      }

      .message-item {
         padding: 15px;
         margin-bottom: 15px;
         border-left: 4px solid var(--primary-color);
         background: var(--light-color);
         border-radius: 4px;
         display: grid;
         grid-template-columns: 1fr;
         gap: 10px;
      }

      .message-item.unread {
         background: rgba(196, 30, 58, 0.1);
         border-left-color: var(--danger-color);
      }

      .message-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         flex-wrap: wrap;
         gap: 10px;
      }

      .message-info {
         display: flex;
         gap: 20px;
         flex-wrap: wrap;
         font-size: 0.9rem;
      }

      .message-info strong {
         color: var(--primary-color);
      }

      .message-content {
         background: white;
         padding: 12px;
         border-radius: 4px;
         color: var(--text-color);
         line-height: 1.6;
         word-wrap: break-word;
      }

      .message-actions {
         display: flex;
         gap: 10px;
      }

      .message-actions .btn {
         font-size: 0.85rem;
         padding: 8px 12px;
      }

      .badge-unread {
         background: var(--danger-color);
         color: white;
         padding: 3px 8px;
         border-radius: 20px;
         font-size: 0.85rem;
      }

      @media (max-width: 768px) {
         .admin-container {
            padding: 15px;
         }

         .message-item {
            grid-template-columns: 1fr;
         }

         .message-header {
            flex-direction: column;
            align-items: flex-start;
         }
      }
   </style>
</head>
<body>

<?php @include 'header_admin.php'; ?>

<div class="admin-container">
   <div class="admin-section">
      <h2><i class="fas fa-envelope"></i> Customer Messages</h2>

      <?php
      $select_messages = db_query("SELECT * FROM messages ORDER BY id DESC");

      if(db_num_rows($select_messages) > 0){
         while($fetch_msg = db_fetch_assoc($select_messages)){
            $class = ($fetch_msg['is_read'] == 0) ? 'unread' : '';
      ?>
         <div class="message-item <?php echo $class; ?>">
            <div class="message-header">
               <div class="message-info">
                  <span><strong>Name:</strong> <?php echo htmlspecialchars($fetch_msg['name']); ?></span>
                  <span><strong>Email:</strong> <?php echo htmlspecialchars($fetch_msg['email']); ?></span>
                  <span><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($fetch_msg['created_at'])); ?></span>
                  <?php if($fetch_msg['is_read'] == 0){ ?>
                     <span class="badge-unread">NEW</span>
                  <?php } ?>
               </div>
            </div>
            <div class="message-content">
               <?php echo nl2br(htmlspecialchars($fetch_msg['message'])); ?>
            </div>
            <div class="message-actions">
               <?php if($fetch_msg['is_read'] == 0){ ?>
                  <a href="messages.php?read=<?php echo $fetch_msg['id']; ?>" class="btn" style="background: var(--success-color);">
                     <i class="fas fa-check"></i> Mark as Read
                  </a>
               <?php } ?>
               <a href="messages.php?delete=<?php echo $fetch_msg['id']; ?>" class="btn delete-btn" onclick="return confirm('Delete this message?');">
                  <i class="fas fa-trash"></i> Delete
               </a>
            </div>
         </div>
      <?php
         }
      } else {
         echo '<p style="text-align: center; color: var(--text-color); padding: 40px;">No messages yet.</p>';
      }
      ?>
   </div>
</div>

<?php @include 'footer_admin.php'; ?>

</body>
</html>


</body>
</html>