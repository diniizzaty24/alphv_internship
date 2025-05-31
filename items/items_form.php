<?php 
// items_form.php
// Form for creating new items

// Include CRUD operations handler
include_once __DIR__ . '/items_crud.php'; 
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
    <form method="POST">
      <table>
        <tr>
          <th>Name</th>
          <td>
            <input name="name" type="text" required placeholder="Enter your name">
          </td>
        </tr>
        <tr>
          <th>Shape</th>
          <td>
            <select name="shape" style="padding: 10px 10px; padding-left: 10px;" required>
              <option value="Circle">Circle</option>
              <option value="Square">Square</option>
              <option value="Triangle">Triangle</option>
              <option value="Rectangle">Rectangle</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Color</th>
          <td>
            <div class="color-container">
              <input name="color" type="color" value="#000000" required 
                     oninput="document.getElementById('colorPreview').style.backgroundColor = this.value">
              <div id="colorPreview" class="color-preview" style="background-color: #000000"></div>
            </div>
          </td>
        </tr>
      </table>
      <div class="button-wrapper">
        <button type="submit" class="btn-submit" name="create">Save</button>
      </div>
    </form>
  </div>
</body>
</html>