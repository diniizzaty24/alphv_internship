# Internship Assessment (Tech Generalist - Web Focused)

## 📌 Overview
This project is a web application with two portals:
- **Admin Portal**: For managing items (Create, Read, Update, Delete)
- **User Portal**: For viewing items (Read-only)

Built with PHP, MySQL, and modern web technologies.

## ✨ Features

### 🔐 Authentication System
- Secure login with password hashing
- Session management
- CSRF protection
- Proper logout functionality

### 🖥️ Admin Portal
- Add new items
- Edit existing items
- Delete items
- View all items in a responsive table
- Modern modal forms for editing/adding

### 👤 User Portal
- View all items
- Auto-refresh every 10 seconds
- Responsive design
- Visual representation of shapes with colors

### 🎨 Item Management
- Create items with:
  - Name
  - Shape (Circle, Square, Triangle, Rectangle)
  - Color (color picker)
- Visual SVG representation of items

## 🛠️ Technologies Used
- **Frontend**: HTML5, CSS3, JavaScript, jQuery, DataTables
- **Backend**: PHP, MySQL (PDO)
- **Security**: CSRF tokens, password hashing, session management
- **Styling**: Custom CSS with Montserrat font

## 📂 File Structure
```
├── auth/               # Authentication files
│   ├── login.php       # Login page
│   └── logout.php      # Logout handler
├── admin/              # Admin portal
│   └── admin.php       # Admin dashboard
├── user/               # User portal
│   └── user.php        # User view
├── items/              # Item management
│   ├── items_crud.php  # CRUD operations
│   ├── items_form.php  # Add form
│   ├── items_edit.php  # Edit form
│   └── items_table.php # Item table display
├── include/            # Shared files
│   └── head.php        # Common head content
├── database/           # Database connection
│   └── database.php    # DB config
├── index.php           # Portal selection
└── README.md           # This file
```
## 🎮 How to Use / Run Instructions
You can access the live deployed application here: http://143.198.202.168

On the portal selection page, choose the portal you want to use.

Admin Login Credentials:

Username: admin

Password: Admin@123

Use the admin portal to add, edit, or delete items.

The user portal is open without login to view items in real time.

## 🎨 Screenshots

![index php](https://github.com/user-attachments/assets/45ce5948-3833-4d83-967c-2fb748149097)
![admin login](https://github.com/user-attachments/assets/92bfa893-e374-415d-9ea0-280a982365ce)
![admin portal](https://github.com/user-attachments/assets/8caace2a-11eb-4669-8a45-849c18566b7e)
![add item](https://github.com/user-attachments/assets/3869b9a7-2e17-4e38-8b51-781a1e6220f4)
![edit item](https://github.com/user-attachments/assets/3cdb4344-607c-4c26-89a6-5d27bd229a26)
![user portal](https://github.com/user-attachments/assets/33bada7b-69d8-43e1-859b-feada84c8dd3)

## 📝 Notes
- The application follows security best practices
- Responsive design works on mobile and desktop
- Clean, modern UI with intuitive navigation


---

Made by dini for ALPHV Internship Assessment
