<?php
@include '../includes/config.php';
@include 'includes/auth.php';

// Update content
if(isset($_POST['update'])){
   $section = $_POST['section'] ?? '';
   $title = $_POST['title'] ?? '';
   $content = $_POST['content'] ?? '';

   if($section && $title){
      $stmt = $conn->prepare("UPDATE site_content SET title = ?, content = ?, updated_at = NOW() WHERE section = ?");
      $stmt->bind_param("sss", $title, $content, $section);
      
      if($stmt->execute()){
         $success_msg = "Content updated successfully!";
      } else {
         $error_msg = "Failed to update content.";
      }
   } else {
      $error_msg = "Please fill in all required fields.";
   }
}

// Fetch content
$select_content = mysqli_query($conn, "SELECT * FROM site_content ORDER BY section") or die('query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Content</title>
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

        .form-group {
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: 1fr;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(196, 30, 58, 0.3);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .form-group.readonly input {
            background: var(--light-color);
            cursor: not-allowed;
        }

        .content-editor {
            padding: 20px;
            background: var(--light-color);
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary-color);
        }

        .content-editor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .content-editor-header h3 {
            color: var(--primary-color);
            margin: 0;
        }

        .content-editor-header .section-badge {
            background: var(--secondary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert.error {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 15px;
            }

            .content-editor-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<?php @include 'header_admin.php'; ?>

<div class="admin-container">
    <?php if(isset($success_msg)){ ?>
        <div class="alert success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success_msg; ?>
        </div>
    <?php } ?>

    <?php if(isset($error_msg)){ ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error_msg; ?>
        </div>
    <?php } ?>

    <div class="admin-section">
        <h2><i class="fas fa-file-alt"></i> Edit Website Content</h2>

        <?php while($row = mysqli_fetch_assoc($select_content)){ ?>
            <div class="content-editor">
                <div class="content-editor-header">
                    <h3><?php echo htmlspecialchars($row['section']); ?></h3>
                    <span class="section-badge"><?php echo strtoupper(htmlspecialchars($row['section'])); ?></span>
                </div>

                <form method="POST">
                    <input type="hidden" name="section" value="<?php echo htmlspecialchars($row['section']); ?>">

                    <div class="form-group">
                        <label for="title_<?php echo htmlspecialchars($row['section']); ?>">Section Title</label>
                        <input type="text" id="title_<?php echo htmlspecialchars($row['section']); ?>" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="content_<?php echo htmlspecialchars($row['section']); ?>">Content</label>
                        <textarea id="content_<?php echo htmlspecialchars($row['section']); ?>" name="content" required><?php echo htmlspecialchars($row['content']); ?></textarea>
                    </div>

                    <button type="submit" name="update" class="btn" style="width: 100%;">
                        <i class="fas fa-save"></i> Update Content
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<?php @include 'footer_admin.php'; ?>

</body>
</html>