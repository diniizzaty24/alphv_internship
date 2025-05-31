<?php  
// items_edit.php
// Handles the edit form for existing items

// Include required files
include_once __DIR__ . '/../database/database.php';
include_once __DIR__ . '/items_crud.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get item ID from URL parameter
$id = $_GET['id'] ?? 0;

try {
    // Prepare and execute SQL query to fetch item data
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if item exists
    if (!$editrow) {
        die("<script>alert('No record found'); window.parent.$('#formModal').fadeOut();</script>");
    }
} catch (PDOException $e) {
    die("<script>alert('Database error: " . addslashes($e->getMessage()) . "'); window.parent.$('#formModal').fadeOut();</script>");
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Prepare SQL statement for update
        $stmt = $pdo->prepare("UPDATE items SET 
            name = :name,
            shape = :shape,
            color = :color
            WHERE id =:id");

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $stmt->bindParam(':shape', $_POST['shape'], PDO::PARAM_STR);
        $stmt->bindParam(':color', $_POST['color'], PDO::PARAM_STR);
        
        // Execute update query
        $stmt->execute();
        
        // Return success response to parent window
        echo 
        "<script>
        if (window.parent) {
          window.parent.handleFormSuccess('Item updated successfully!');
        }
        window.parent.$('#formModal').fadeOut();
        </script>";

        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once __DIR__ . '/../include/head.php'; ?>
<style>

  body {
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9fafb;
    font-size: 14px;
  }

  .form-wrapper {
    max-width: 600px;
    margin: 0 auto;
    padding: 10px 40px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  }

  form table {
    width: 100%;
    border-collapse: collapse;
  }

  form th, form td {
    text-align: left;
    padding: 10px;
    font-size: 15px;
  }

  form th {
    width: 35%;
  }

  input[type="text"],
  input[type="color"],
  select {
    width: 100%;
    padding: 10px;
    margin-top: 4px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 15px;
  }

  .button-wrapper {
    text-align: center;
    margin-top: 20px;
  }

  .btn-submit {
    background-color: #2196F3;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
  }

  .btn-submit:hover {
    background-color: #1976D2;
  }

  @media (max-width: 768px) {
    form th, form td {
      display: block;
      width: 100%;
    }
  }

  .color-preview {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin-left: 10px;
  }

  .color-container {
    display: flex;
    align-items: center;
  }
</style>
</head>

<body>
  <!-- Main form container -->
  <div class="form-wrapper">
    <form action="" method="post">
      <!-- Hidden field for item ID -->
      <input type="hidden" name="id" value="<?= htmlspecialchars($editrow['id'] ?? '') ?>">
      <table>
        <tr>
          <th>Name</th>
          <td>
            <input name="name" type="text" value="<?= htmlspecialchars($editrow['name'] ?? '') ?>" required placeholder="Enter your name">
          </td>
        </tr>
        <tr>
          <th>Shape</th>
          <td>
            <select name="shape" style="padding: 10px 10px; padding-left: 10px;" required>
              <option value="Circle" <?= ($editrow['shape'] ?? '') === 'Circle' ? 'selected' : '' ?>>Circle</option>
              <option value="Square" <?= ($editrow['shape'] ?? '') === 'Square' ? 'selected' : '' ?>>Square</option>
              <option value="Triangle" <?= ($editrow['shape'] ?? '') === 'Triangle' ? 'selected' : '' ?>>Triangle</option>
              <option value="Rectangle" <?= ($editrow['shape'] ?? '') === 'Rectangle' ? 'selected' : '' ?>>Rectangle</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Color</th>
          <td>
            <div class="color-container">
              <input name="color" type="color" value="<?= htmlspecialchars($editrow['color'] ?? '#000000') ?>" required 
                     oninput="document.getElementById('colorPreview').style.backgroundColor = this.value">
              <div id="colorPreview" class="color-preview" style="background-color: <?= htmlspecialchars($editrow['color'] ?? '#000000') ?>"></div>
            </div>
          </td>
        </tr>
      </table>
      <div class="button-wrapper">
        <button type="submit" class="btn-submit" name="update">Update</button>
      </div>
    </form>
  </div>
</body>
</html>