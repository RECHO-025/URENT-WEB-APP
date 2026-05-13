
<?php
#include 'automatic_invoice.php';
include ("db_connect.php");
require_once('class.phpmailer.php');

include("class.smtp.php"); // Optional, only needed if not already loaded in PHPMailer

	if (isset($_POST['id'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $message = $_POST['message'];
    
    $mail = new PHPMailer();

    // SMTP Configuration
    $mail->IsSMTP();
    $mail->SMTPDebug  = 0;                     // Set to 2 for debugging in development
    $mail->SMTPAuth   = true;                  // Enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // Start with TLS encryption
    $mail->Host       = "mail.eljotellug.com"; // SMTP server

    // Try Port 587 (TLS)
    $mail->Port       = 587;                   // Correct port for TLS
    $mail->Username   = "urent@eljotellug.com"; // Email username
    $mail->Password   = "0702014626@Bjk";       // Email password (consider securing this)

    $mail->SetFrom('urent@eljotellug.com', 'Jotell Technologies Uganda Ltd');
    $mail->AddReplyTo('urent@eljotellug.com', 'Jotell Technologies Uganda Ltd');

    // Email Subject
    $mail->Subject = "REQUEST FOR A TOUR  - $name";

    // HTML Body
    $mail->isHTML(true); 
    $mail->MsgHTML($message); // Ensure $message is sanitized to prevent XSS or injection

    // Add recipient
    $mail->AddAddress($email, $name); // Use the provided email and name

    // Send email
    if(!$mail->Send()) {
        // If Port 587 fails, try Port 465 (SSL)
        $mail->SMTPSecure = "ssl";  // Switch to SSL
        $mail->Port = 465;          // Port for SSL

        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo; // Return error message if sending fails on both ports
        } else {
            $response =  "Request sucessful sent to Urent Administrator who will respond to you in a period of 03 hours";
        }
    } else {
         $response =  "Request sucessful sent to Urent Administrator who will respond to you in a period of 03 hours";
    }
    
} else {
    // Handle the case where id is not set
    $response =  "";
}
?>
<p align="center">
<?php

echo $response;

?>
</p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Management System - Jotell Technologies</title>
    
        <!-- Add Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body, html {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    overflow-x: hidden;

}

/* Navbar */
header {
    background-color: white;
    padding: 10px;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav ul {
    list-style-type: none;
    display: flex;
}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
}

.logo img {
    width: 100px;
    
}

/* Responsive Navbar for Mobile */
@media (max-width: 768px) {
    nav ul {
        flex-direction: column;
        text-align: center;
        width: 100%;
        padding-top: 10px;
    }

    nav ul li {
        margin-bottom: 10px;
    }
}

/* Hero Section */
.hero {
    background-image: url('images/rent-manage.jpg');
    background-size: cover;
    background-position: center;
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    text-align: center;
    position: relative;
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.hero-content {
    max-width: 600px;
    position: relative;
}

.hero h1 {
    font-size: 4em;
    margin-bottom: 15px;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.hero p {
    font-size: 1.5em;
    margin-bottom: 15px;
    font-weight: 300;
    line-height: 1.4;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
}

.hero .btn {
    background-color: #0d2074;
    color: white;
    padding: 15px 30px;
    text-decoration: none;
    border-radius: 50px;
    font-size: 1.2em;
    font-weight: 600;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
}

.hero .btn:hover {
    background-color: #2a7fc5;
    transform: translateY(-5px);
}

.hero .btn + .btn {
    margin-left: 10px;
}

/* Responsive Hero Section */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2.5em;
    }

    .hero p {
        font-size: 1.2em;
    }

    .hero .btn {
        font-size: 0.9rem;
        padding: 8px 15px;
        width: 80%; /* Full-width buttons on smaller screens */
        max-width: 300px; /* Prevent buttons from getting too wide */
    }
}
@media (max-width: 480px) {
    .hero-content h1 {
        font-size: 1.8rem;
    }

    .hero-content p {
        font-size: 0.9rem;
    }

    .hero-content .btn {
        font-size: 0.85rem;
        padding: 10px;
        width: 90%; /* Ensure better alignment for very small screens */
    }
}

/* Features Section */
.features {
    padding: 50px 20px;
    text-align: center;
    background-color: #f9f9f9;
}

.features h2 {
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #333;
}

.feature-cards {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 30px;
}

.feature-cards .card {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: calc(25% - 20px); /* Four cards in a row */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: left;
}

.feature-cards .card h3 {
    font-size: 1.6em;
    margin-bottom: 15px;
    color: #333;
}

.feature-cards .card p {
    font-size: 1em;
    color: #555;
    margin-bottom: 15px;
}

.feature-cards .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.feature-cards .card i {
    font-size: 2.5em;
    margin-bottom: 15px;
    color: #0d2074; /* Icon color */
}

/* Responsive Features Section */
@media (max-width: 1024px) {
    .feature-cards .card {
        width: calc(50% - 20px); /* Two cards in a row */
    }
}

@media (max-width: 768px) {
    .feature-cards {
        flex-direction: column;
    }

    .feature-cards .card {
        width: 100%; /* Stack cards in one column */
    }
}

/* Demo Section */
.demo {
    background-color: #f0f4fb;
    padding: 60px 20px;
    text-align: center;
    border-top: 2px solid #0d2074;
}

.demo h2 {
    font-size: 2.5em;
    margin-bottom: 40px;
    color: #0d2074;
    font-weight: 600;
}

.demo form {
    background-color: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: auto;
    text-align: left;
}

.demo label {
    font-size: 1.1em;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

.demo input,
.demo textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 2px solid #ddd;
    border-radius: 10px;
    font-size: 1em;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

.demo input:focus,
.demo textarea:focus {
    border-color: #0d2074;
    outline: none;
    background-color: #fff;
}

.demo .btn {
    background-color: #0d2074;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-size: 1.2em;
    font-weight: 600;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.demo .btn:hover {
    background-color: #2a7fc5;
    transform: translateY(-3px);
}

/* Footer */
footer {
    background-color: #0d2074;
    color: white;
    padding: 40px 20px;
}

footer .footer-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: auto;
}

footer .footer-column {
    width: calc(33% - 20px);
    margin-bottom: 20px;
}

footer h4 {
    font-size: 1.5em;
    margin-bottom: 20px;
    color: #f0f4fb;
    font-weight: 600;
}

footer ul {
    list-style-type: none;
    padding-left: 0;
}

footer ul li {
    margin-bottom: 10px;
}

footer ul li a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
}

footer ul li a:hover {
    color: #2a7fc5;
}

footer .social-icons {
    display: flex;
    justify-content: flex-start;
}

footer .social-icons a {
    color: white;
    margin-right: 15px;
    font-size: 1.5em;
    transition: color 0.3s ease;
}

footer .social-icons a:hover {
    color: #2a7fc5;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

/* Responsive Footer */
@media (max-width: 768px) {
    footer .footer-column {
        width: 100%;
    }
}
/* Add styles for the Available Houses section */
/* Available Properties Section */
/* Container styling */

.available-properties {
    font-family: Arial, sans-serif;
    text-align: center;
    padding: 50px 20px;
    background-color: #f9f9f9;
   /* max-width: 1200px;*/
    margin: auto;
}

/* Title styling */
.available-properties h2 {
    font-size: 2em;
    color: #0d2074;
    margin-bottom: 20px;
}

/* Cards layout */
/* .property-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 3 cards per row 
    gap: 20px;
    justify-content: center;
    margin-top: 20px;
    margin-left: 70px;
}*/
.property-cards{
    display: flex;
    justify-content: space-evenly;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 30px;
}
/* Container for each property card */
.property-card {
    position: relative;
    overflow: hidden;
    width: 300px; /* Adjust size as needed */
    border: 1px solid #ddd;
    border-radius: 8px;
    margin: 10px;
    transition: transform 0.3s;
}

.property-card:hover {
    transform: scale(1.05); /* Optional: Adds a slight zoom effect on hover */
}

/* Image styling */
.image-container img {
    width: 100%;
    height: auto;
    display: block;
}

/* Description styling, initially hidden */
.property-description {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7); /* Dark overlay */
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

/* Display the description on hover */
.property-card:hover .property-description {
    opacity: 1;
    visibility: visible;
}


/* Description styling */
.property-description {
    padding: 15px;
    text-align: left;
    flex-grow: 1;
}

.property-description h3 {
    font-size: 1.2em;
    color: #ffff;
    margin: 10px 0;
    text-align: center;
}

.contact-details p {
    font-size: 0.9em;
    color: #fff;
    margin: 5px 0;
}

/* Pagination styling */
.pagination {
    display: flex;
    justify-content:center;
    margin-top: 20px;
}

.pagination a {
    margin: 0 5px;
    padding: 8px 12px;
    color: #555;
    text-decoration: none;
    border-radius: 4px;
    background-color: #eee;
    transition: background-color 0.3s;
}

.pagination a.active, .pagination a:hover {
    background-color: #0d2074;
    color: #fff;
}

.pagination a.prev, .pagination a.next {
    font-weight: bold;
}

/* Search Bar Container */
.search_bar {
    display: flex;
    justify-content: center;
    align-items: center; /* Center vertically */
    padding: 10px;
    box-sizing: border-box;
    width: 100%;
    border-radius: 30px;
    border: 1px solid #0d2074;
    margin: 0 auto; /* Center container in the page */
}

/* Input Field Styling */
.search_bar input[type="text"] {
    width: 100%; /* Start with a larger width */
   /* max-width: 600px;  Limit the width for larger screens */
    padding: 12px; /* Slightly larger padding for better usability */
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 20px; /* Rounded corners */
    box-sizing: border-box;
    outline: none; /* Remove outline for a cleaner look */
    transition: all 0.3s ease-in-out; /* Smooth transitions */
}

/* Adjustments for Tablet Screens (768px and below) */
@media (max-width: 768px) {
    .search_bar input[type="text"] {
        width: 100%; /* Slightly smaller width */
        font-size: 14px; /* Reduce font size for better readability */
        padding: 10px; /* Adjust padding */
    }
}

/* Adjustments for Mobile Screens (480px and below) */
@media (max-width: 480px) {
    .search_bar input[type="text"] {
        width: 100%; /* Almost full width for compact screens */
        font-size: 5px; /* Further reduce font size */
        padding: 8px; /* Compact padding */
        text-align:center;
    }
}
/* Responsive layout adjustments */
@media (max-width: 768px) {
    .property-cards {
        grid-template-columns: 1fr 1fr; /* 2 cards per row on smaller screens */
    }
}
@media (max-width: 768px) {
    .property-cards {
        grid-template-columns: 1fr 1fr; /* 2 cards per row on smaller screens */
    }
}

@media (max-width: 480px) {
    .property-cards {
        grid-template-columns: 1fr; /* 1 card per row on very small screens */
    }
}

</style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">
                <a href="https://www.urent.eljotellug.com/"><img src="images/urentlogo.png" alt="Jotell Logo"></a>
            </div>
          <!--  <ul>
                <li><a href="https://www.jotellug.com/homepage.php">Home</a></li>
                <li><a href="https://www.jotellug.com/product.php">Products</a></li>
                <li><a href="https://www.jotellug.com/services.php">Services</a></li>
                <li><a href="https://www.jotellug.com/contact.php">Contact</a></li>
            </ul>-->
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Urent Management System</h1>
            <p>Efficiently manage rental properties with ease. From tenant management to payment tracking, we offer the complete solution for property owners and managers.</p>
            <a href="#features" class="btn">Explore Features</a>            
            <a href="signup.php" class="btn">SIGN UP</a>
            <a href="login.php" class="btn">LOGIN</a>

        </div>
    </section>
 <!--Available properties-->
 <section class="available-properties">
    <h2 style="text-align:center">Available for Rent</h2>

    <div class="search_bar">
    <input type="text" id="search"  placeholder="Search for Property by Description e.g. Single Room, Landlord's Name, Landlord's Contact Number">
    </div>
    </div>
    <?php
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    if ($page < 1) $page = 1;

    $resultsPerPage = 10;
    $offset = ($page - 1) * $resultsPerPage;

    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

    // Fetch total number of properties
    $totalQuery = "SELECT COUNT(*) AS total FROM houses WHERE status = 0";
    $totalResult = $conn->query($totalQuery);
    $totalRow = $totalResult->fetch_assoc();
    $totalProperties = $totalRow['total'];
    $totalPages = ceil($totalProperties / $resultsPerPage);

    $query77 = "SELECT landlords.landlord_id, 
                       landlords.telephone_contact, 
                       landlords.contact_person, 
                       landlords.email_address, 
                       houses.description, 
                       houses.unit_img AS houseImage 
                FROM landlords
                JOIN houses 
                ON landlords.landlord_id = houses.shopid
                WHERE houses.status = 0";

    if (!empty($search)) {
        $query77 .= " AND (houses.description LIKE ? 
                        OR landlords.telephone_contact LIKE ? 
                        OR landlords.contact_person LIKE ?)";
    }

    $query77 .= " ORDER BY houses.id DESC LIMIT ?, ?";

    $stmt = $conn->prepare($query77);
    if (!$stmt) die("Query preparation failed: " . $conn->error);

    if (!empty($search)) {
        $searchTerm = "%$search%";
        $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $offset, $resultsPerPage);
    } else {
        $stmt->bind_param("ii", $offset, $resultsPerPage);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="property-cards">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="property-card">
                <div class="image-container">
                    <?php
                    $defaultImage = 'uploads/house_images/BH001.jpeg';
                    $imagePath = (!empty($row['houseImage']) && file_exists("uploads/house_images/" . $row['houseImage']))
                        ? "uploads/house_images/" . $row['houseImage']
                        : $defaultImage;
                    ?>
                    <img src="<?php echo $imagePath; ?>" alt="House Image" style="max-width: 300px; height: auto;">
                </div>
                <div class="property-description">
                    <h3><?php echo htmlspecialchars($row['description']); ?></h3>
                    <div class="contact-details">
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['telephone_contact']); ?></p>
                        <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($row['contact_person']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email_address']); ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next &raquo;</a>
        <?php endif; ?>
    
</section>
    <!---Features Section -->
    <section id="features" class="features">
        <h2>Key Features</h2>
        <div class="feature-cards">
            <div class="card">
                <i class="fas fa-users"></i> <!-- Icon for Tenant Management -->
                <h3>Tenant Management</h3>
                <p>Manage tenant information, lease agreements, and tenancy periods.</p>
            </div>
            <div class="card">
                <i class="fas fa-money-check-alt"></i> <!-- Icon for Payment Tracking -->
                <h3>Payment Tracking</h3>
                <p>Track rent payments, due dates, and generate detailed reports.</p>
            </div>
            <div class="card">
                <i class="fas fa-chart-line"></i> <!-- Icon for Property Insights -->
                <h3>Property Insights</h3>
                <p>Get an overview of your properties with occupancy and revenue reports.</p>
            </div>
            <div class="card">
                <i class="fas fa-file-invoice-dollar"></i> <!-- Icon for Billing/Invoicing -->
                <h3>Tenants Billing/Invoicing</h3>
                <p>Automatically generate and manage bills and invoices for all tenants with ease.</p>
            </div>
            <div class="card">
                <i class="fas fa-tools"></i> <!-- Icon for Expense Management -->
                <h3>All Expense Management</h3>
                <p>Monitor and manage all expenses associated with your properties and operations.</p>
            </div>
            <div class="card">
                <i class="fas fa-piggy-bank"></i> <!-- Icon for Profit Management -->
                <h3>Profit Management</h3>
                <p>Get real-time insights into your profit margins with detailed financial reports.</p>
            </div>
            <div class="card">
                <i class="fas fa-bell"></i> <!-- Icon for Notifications -->
                <h3>SMS & Email Notifications</h3>
                <p>Send automated notifications to tenants via SMS or email for rent reminders or updates.</p>
            </div>
            <div class="card">
                <i class="fas fa-home"></i> <!-- Icon for Property Management -->
                <h3>Property Management</h3>
                <p>Manage all aspects of your properties, from tenant turnover to maintenance requests.</p>
            </div>
        </div>
    </section>
    <!-- Demo Request Section -->
    <section id="demo" class="demo">
	
        <h2>Request a Tour</h2>
        <form action="homepage.php" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>
			<input type="hidden" id="id" name="id" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit" class="btn">Request Tour</button>
        </form>
    </section>
    <!-- Footer -->
    <!-- Footer -->
<footer>
    <div class="footer-container">
        <!-- Company Info -->
        <div class="footer-column">
            <h4>About Us</h4>
            <p>Urent Systems Solutions UG Limited is a registered privately owned limited company dedicated to meeting the business needs of the people of Uganda through better service delivery using IT solutions. The company is strategically at Nansana Masitoowa</p>
        </div>
        
        <!-- Quick Links -->
        <div class="footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="https://www.urent.eljotellug.com/homepage.php">Home</a></li>
                <li><a href="#features">Products</a></li>
                <li><a href="#features">Services</a></li>
                <li><a href="#demo">Contact</a></li>
            </ul>
        </div>

        <!-- Social Media Links -->
        <div class="footer-column">
            <h4>Follow Us</h4>
            <div class="social-icons">
                <a href="https://www.facebook.com/share/15xuuDiRnY/"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com"><i class="fab fa-twitter"></i></a>
                <a href="https://linkedin.com"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://instagram.com"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
	
        <p>© 2025 Urent Systems Solutions UG Limited | All rights reserved.</p>
		
    </div>
</footer>
<script>
    document.getElementById('search').addEventListener('input', function() {
        const searchQuery = this.value;

        // Perform an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `search_houses.php?search=${encodeURIComponent(searchQuery)}`, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.querySelector('.property-cards').innerHTML = xhr.responseText;
            }
        };

        xhr.send();
    });
</script>

</body>
</html>