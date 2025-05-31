<?php
// admin.php
// Admin portal with item management functionality

// Include database connection
include_once __DIR__ . '/../database/database.php';
session_start();

// Check if user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once __DIR__ . '/../include/head.php'; ?>
    <title>Admin Portal</title>
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .modal {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 1050;
            display: none;
            justify-content: center;
            background-color: rgba(0,0,0,0.6);
        }

        .modal-dialog-centered {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 600px;
            padding: 0 20px;
            justify-content: center;
        }

        .modal-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            width: 100%;
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid #e0e0e0;
            background-color: #f8f9fa;
        }

        .modal-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        #closeModal {
            background: none;
            border: none;
            font-size: 28px;
            color: #333;
            cursor: pointer;
            line-height: 1;
        }

        .modal-body {
            padding: 0;
        }

        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
            min-height: 400px;
        }

        .btn-submit {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #1976D2;
        }

        .btn-submit:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        #addBtn, .logout-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 15px;
            margin-right: 50px;
            margin-top: 100px;
        }

        #addBtn {
            background-color: #2196F3;
            color: white;
            margin-right: 10px;
        }

        #addBtn:hover {
            background-color: #1976D2;
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
        }

        .logout-btn:hover {
           background-color: #d32f2f;
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

        .admin-actions {
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .page-title {
                margin-left: 20px;
                font-size: 24px;
            }
            
            #addBtn {
                margin-right: 10px;
            }
            
            #tableContainer {
                padding: 0 20px;
            }
            
            .modal-dialog-centered {
                width: 95%;
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Main container -->
    <div class="container p-4">
        <!-- Title & Add Button section -->
        <div class="header-container">
            <h1 class="page-title">Admin Portal</h1>
            <div class="admin-actions">
                <button id="addBtn">+ Add New</button>
                <button class="logout-btn" id="logoutBtn">Logout</button>
            </div>
        </div>

        <!-- Table container for items -->
        <div id="tableContainer"></div>

        <!-- Modal popup for forms -->
        <div id="formModal" class="modal">
            <div class="modal-dialog-centered">
                <div class="modal-box">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="modalTitle">Add Item</h5>
                        <button type="button" id="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <iframe id="formIframe" src=""></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript libraries and scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script>
        // Global variable for DataTable instance
        let dataTable = null;

        /**
         * Loads the items table data via AJAX
         * @return {Promise} A promise that resolves when table is loaded
         */
        function loadTable() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `../items/items_table.php?admin=1`,
                    method: 'GET',
                    success: function(data) {
                        $('#tableContainer').html(data);
                        
                        // Destroy previous DataTable if exists
                        if ($.fn.DataTable.isDataTable('#itemsTable')) {
                            $('#itemsTable').DataTable().destroy();
                        }
                        
                        // Initialize new DataTable
                        dataTable = $('#itemsTable').DataTable({
                            scrollX: true,
                            pageLength: 10,
                            responsive: true
                        });
                        
                        resolve();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading table:", error);
                        $('#tableContainer').html('<div class="alert alert-danger">Failed to load item data</div>');
                        reject(error);
                    }
                });
            });
        }

        /**
         * Opens the add form in a modal
         */
        function openAddForm() {
            const url = `../items/items_form.php`;
            $('#modalTitle').text('Add New Item');
            $('#formIframe').attr('src', url);
            $('#formModal').fadeIn();
        }

        // Document ready handler
        $(document).ready(function() {
            // Load table on page load
            loadTable();

            // Add button click handler
            $('#addBtn').on('click', openAddForm);
            
            // Close modal button handler
            $('#closeModal').on('click', function() {
                $('#formModal').fadeOut();
                $('#formIframe').attr('src', '');
            });
            
            // Close modal when clicking outside
            $(window).on('click', function(e) {
                if ($(e.target).is('#formModal')) {
                    $('#formModal').hide();
                    $('#formIframe').attr('src', '');
                }
            });

            // Handle window resize
            $(window).on('resize', function() {
                if (dataTable) {
                    dataTable.columns.adjust();
                }
            });

            // Logout button handler
            $('#logoutBtn').on('click', function() {
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = '../auth/logout.php';
                }
            });
        });

        // Edit button handler (delegated event)
        $(document).on('click', '.edit-button', function(e) {
            e.preventDefault();
            
            const id = $(this).data('id');
            
            const url = `../items/items_edit.php?id=${id}`;
            $('#modalTitle').text('Edit Item');
            $('#formIframe').attr('src', url);
            $('#formModal').fadeIn();
        });

        // Delete button handler (delegated event)
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to delete this item?')) return;
            
            const id = $(this).data('id');
            
            $.ajax({
                url: `../items/items_crud.php?delete=${id}`,
                method: 'GET',
                success: function(res) {
                    if (res.trim() === "success") {
                        alert('Item deleted successfully!');
                        loadTable();
                    } else {
                        alert("Failed to delete item. Server response: " + res);
                    }
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("Error: " + xhr.responseText);
                }
            });
        });

        /**
         * Global function to handle form submission success
         * @param {string} message Success message to display
         */
        window.handleFormSuccess = function(message) {
            alert(message);
            $('#formModal').fadeOut();
            $('#formIframe').attr('src', '');
            loadTable();
        };
    </script>
</body>
</html>