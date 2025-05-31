<?php
// items_table.php
// Displays a table of items with optional admin actions

// Include database connection
include_once __DIR__ . '/../database/database.php';

// Check if admin view is requested
$isAdmin = isset($_GET['admin']) && $_GET['admin'] == 1;

try {
    // Fetch all items from database, ordered by update time
    $stmt = $pdo->query("SELECT * FROM items ORDER BY updated_at DESC");
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching items: " . $e->getMessage());
}
?>

<style>
 
  .table-container {
    overflow-x: auto;
    padding: 10px;
    box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    white-space: nowrap;
    font-family: 'Montserrat', sans-serif;
  }

  #itemsTable {
    width: 100%;
    table-layout: auto;
    white-space: nowrap;
  }

  .table-container th,
  .table-container td {
    text-align: center;
    padding: 10px 12px;
    border: 1px solid #ccc;
    vertical-align: middle;
    font-size: 15px;
  }

  .table-row:hover {
    background-color: #f2f2f2;
    cursor: pointer;
  }

  tr:hover .action-cell {
    background-color: #f5f5f5;
    border-left: 4px solid #007bff; 
  }

  .shape-container {
    display: inline-block;
    width: 50px;
    height: 50px;
  }

  .action-link {
    color: #333;
    text-decoration: none;
    margin: 0 5px;
    font-size: 15px;
    transition: color 0.3s;
  }

  .action-link:hover {
    color: #2196F3;
    text-decoration: underline;
  }

  .action-link.delete:hover {
    color: #f44336;
  }
</style>

<!-- Main table container -->
<div class="table-container">
  <table id="itemsTable" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>Timestamp</th>
        <th>Name</th>
        <th>ShapeColor</th>
        <?php if ($isAdmin): ?>
          <th>Actions</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $row): ?>
        <tr class="table-row">
          <td><?= htmlspecialchars($row['updated_at']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>
            <div class="shape-container">
              <?= generateShapeSVG($row['shape'], $row['color']) ?>
            </div>
          </td>
          <?php if ($isAdmin): ?>
            <td class="action-cell">
              <a href="#" class="action-link edit-button" data-id="<?= htmlspecialchars($row['id']) ?>">Edit</a>
              <a href="#" class="action-link delete btn-delete" data-id="<?= htmlspecialchars($row['id']) ?>">Delete</a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php
/**
 * Generates SVG markup for different shapes
 * @param string $shape The shape type (Circle, Square, Triangle, Rectangle)
 * @param string $color The fill color in HEX format
 * @return string SVG markup
 */
function generateShapeSVG($shape, $color) {
  $svg = '<svg width="50" height="50" viewBox="0 0 50 50">';
  
  switch (strtolower($shape)) {
    case 'circle':
      $svg .= '<circle cx="25" cy="25" r="20" fill="'.$color.'" />';
      break;
    case 'square':
      $svg .= '<rect x="5" y="5" width="40" height="40" fill="'.$color.'" />';
      break;
    case 'triangle':
      $svg .= '<polygon points="25,5 5,45 45,45" fill="'.$color.'" />';
      break;
    case 'rectangle':
      $svg .= '<rect x="5" y="10" width="40" height="30" fill="'.$color.'" />';
      break;
    default:
      $svg .= '<circle cx="25" cy="25" r="20" fill="'.$color.'" />';
  }
  
  $svg .= '</svg>';
  return $svg;
}
?>