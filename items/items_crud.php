<?php
// items_crud.php
// Handles CRUD operations for items (Create, Read, Update, Delete)

// Include database connection
include_once __DIR__ . '/../database/database.php';

/**
 * Create a new item
 */
if (isset($_POST['create'])) {
    try {
        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['shape']) || empty($_POST['color'])) {
            throw new Exception("All fields are required");
        }

        // Shape validation (only allow specific shapes)
        $allowedShapes = ['Circle', 'Square', 'Triangle', 'Rectangle'];
        if (!in_array($_POST['shape'], $allowedShapes)) {
            throw new Exception("Invalid shape value");
        }

        // Color validation (must be a valid HEX color)
        if (!preg_match('/^#[0-9A-F]{6}$/i', $_POST['color'])) {
            throw new Exception("Invalid color value");
        }

        // Prepare SQL statement for insertion
        $stmt = $pdo->prepare("INSERT INTO items(name, shape, color) VALUES(:name, :shape, :color)");
        
        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $stmt->bindParam(':shape', $_POST['shape'], PDO::PARAM_STR);
        $stmt->bindParam(':color', $_POST['color'], PDO::PARAM_STR);

        // Execute query
        $stmt->execute();
        
        // Return success response to parent window
        echo 
        "<script>
        if (window.parent) {
            window.parent.handleFormSuccess('Item created successfully!');
        }
        window.parent.$('#formModal').fadeOut();
        </script>";
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
 
/**
 * Update an existing item
 */
if (isset($_POST['update'])) {
    try {
        // Validate required fields
        if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['shape']) || empty($_POST['color'])) {
            throw new Exception("All fields are required");
        }

        // Prepare SQL statement for update
        $stmt = $pdo->prepare("UPDATE items SET
            name = :name,
            shape = :shape, 
            color = :color
            WHERE id = :id");

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $stmt->bindParam(':shape', $_POST['shape'], PDO::PARAM_STR);
        $stmt->bindParam(':color', $_POST['color'], PDO::PARAM_STR);
        
        // Execute query
        $stmt->execute();
        
        // Return success response to parent window
        echo 
        "<script>
        if (window.parent) {
            window.parent.handleFormSuccess('Item updated successfully!');
        }
        window.parent.$('#formModal').fadeOut();
        </script>";
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
 
/**
 * Delete an item
 */
if (isset($_GET['delete'])) {
    try {
        // Validate ID
        if (empty($_GET['delete'])) {
            throw new Exception("Item ID is required");
        }

        $id = $_GET['delete'];
        
        // Prepare SQL statement for deletion
        $stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
        
        // Bind parameter to prevent SQL injection
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Execute query
        $stmt->execute();
        
        // Return success response
        echo "success";
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}