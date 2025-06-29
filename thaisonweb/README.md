# Thai Son Seafood Project

## Overview
The Thai Son Seafood project is a web application designed to showcase and sell seafood products. It includes an admin panel for managing users, products, and orders, as well as a user-facing interface for browsing and purchasing products.

## Project Structure
```
thaisonweb
├── admin.html          # Admin page for managing users, products, and orders
├── get-products.php    # Retrieves product data from the database in JSON format
├── connect.php         # Establishes a connection to the database
├── style.css           # Styles for the web pages
├── webthuysanthaison.html # Main user-facing page showcasing products
├── product.html        # Displays individual product details
├── cart.html           # Shows the contents of the user's shopping cart
├── login.html          # Form for user login
├── register.html       # Form for user registration
├── ThaiSonSeafood_csdl.sql # SQL commands for database creation
└── README.md           # Documentation for the project
```

## Setup Instructions
1. **Database Setup**: 
   - Run the SQL commands in `ThaiSonSeafood_csdl.sql` to create the necessary database and tables.
   
2. **File Configuration**:
   - Ensure that `connect.php` is configured with the correct database credentials.

3. **Web Server**:
   - Host the project on a local server (e.g., XAMPP, WAMP) to test the application.

4. **Accessing the Application**:
   - Open `webthuysanthaison.html` in a web browser to view the main page.
   - Access the admin panel via `admin.html` for management tasks.

## Features
- **User Management**: Admin can view, add, edit, and delete users.
- **Product Management**: Admin can manage product listings, including adding new products and updating existing ones.
- **Order Management**: Admin can view and manage customer orders.
- **User Interface**: A clean and responsive design for users to browse and purchase seafood products.

## Technologies Used
- HTML, CSS, JavaScript for front-end development.
- PHP for server-side scripting.
- MySQL for database management. 

## Future Enhancements
- Implement user authentication for the admin panel.
- Add search and filter functionality for products.
- Improve the user interface with more advanced styling and layout options.