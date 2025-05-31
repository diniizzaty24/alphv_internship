<?php
// user.php
// User portal for viewing items (read-only access)

// Include database connection
include_once __DIR__ . '/../database/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once __DIR__ . '/../include/head.php'; ?>
    <title>User Portal</title>
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-extrabold {
            font-weight: 800;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            margin-top: 100px;
            margin-left: 50px;
            font-size: 30px;
            font-weight: 800;
            color: #333;
        }

        #tableContainer {
            padding: 0 50px;
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .page-title {
                margin-left: 20px;
                font-size: 24px;
            }
            
            #tableContainer {
                padding: 0 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Main container -->
    <div class="container p-4">
        <!-- Title section -->
        <div class="header-container">
            <h1 class="page-title">User Portal</h1>
        </div>

        <!-- Table container -->
        <div id="tableContainer">
            <?php 
            // Force-disable admin actions for user portal
            $_GET['admin'] = 0;
            include __DIR__ . '/../items/items_table.php'; 
            ?>
        </div>
    </div>

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script>
        // Document ready handler
        $(document).ready(function() {
            // Initialize DataTable
            $('#itemsTable').DataTable({
                scrollX: true,
                pageLength: 10,
                responsive: true
            });

            // Auto-refresh table every 10 seconds
            setInterval(function() {
                $.get('../items/items_table.php?admin=0', function(data) {
                    $('#tableContainer').html(data);
                    $('#itemsTable').DataTable({ 
                        scrollX: true,
                        pageLength: 10 
                    });
                });
            }, 10000);
        });
    </script>
</body>
</html>