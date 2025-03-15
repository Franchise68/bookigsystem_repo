<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if booking reference is provided
if (!isset($_GET['ref']) || empty($_GET['ref'])) {
    header("Location: wild.html");
    exit();
}

$bookingReference = htmlspecialchars($_GET['ref']);

// Database configuration
$host = "localhost";
$dbname = "wildlife_booking";
$username = "root";
$password = "";

// Get booking details
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("
        SELECT * FROM bookings WHERE booking_reference = :booking_reference LIMIT 1
    ");
    $stmt->execute(['booking_reference' => $bookingReference]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        echo "Booking not found!";
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation | WildVentures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2c5e1a;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px;
        }
        .success-message {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .booking-details {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .booking-details h2 {
            margin-top: 0;
            color: #2c5e1a;
        }
        .total-price {
            font-size: 22px;
            color: #2c5e1a;
            font-weight: bold;
        }
        .next-steps {
            background-color: #eaf7fd;
            padding: 15px;
            border-radius: 4px;
            border-left: 5px solid #5bc0de;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background-color: #2c5e1a;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #224915;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>WildVentures Booking Confirmation</h1>
        </div>
        
        <div class="success-message">
            <h2>Your booking has been confirmed!</h2>
            <p>Thank you for choosing WildVentures for your wildlife adventure.</p>
        </div>
        
        <div class="booking-details">
            <h2>Booking Details</h2>
            <p><strong>Booking Reference:</strong> <?php echo htmlspecialchars($booking['booking_reference']); ?></p>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['phone']); ?></p>
            <p><strong>Destination:</strong> <?php echo ucfirst(str_replace('-', ' ', htmlspecialchars($booking['destination']))); ?></p>
            <p><strong>Tour Package:</strong> <?php echo ucfirst(str_replace('-', ' ', htmlspecialchars($booking['tour_package']))); ?></p>
            <p><strong>Departure Date:</strong> <?php echo date('F j, Y', strtotime($booking['departure_date'])); ?></p>
            <p><strong>Return Date:</strong> <?php echo date('F j, Y', strtotime($booking['return_date'])); ?></p>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($booking['duration']); ?> days</p>
            <p><strong>Number of Participants:</strong> <?php echo htmlspecialchars($booking['participants']); ?></p>
            <p class="total-price"><strong>Total Price:</strong> $<?php echo number_format($booking['total_price'], 2); ?></p>
        </div>
        
        <div class="next-steps">
            <h3>What's Next?</h3>
            <p>A WildVentures representative will contact you within 24 hours to confirm your booking details and provide further information about your upcoming adventure.</p>
            <p>A confirmation email has been sent to your email address with all the details of your booking.</p>
        </div>
        
        <p>If you have any questions or need to make changes to your booking, please contact our customer support team at support@wildventures.com orcall us at +1 (800) 123-4567.</p>
        
        <p>We recommend reviewing our <a href="preparation_guide.php">Trip Preparation Guide</a> to ensure you have everything you need for your adventure.</p>
        
        <p><a href="index.php" class="btn">Return to Homepage</a></p>
        
        <div class="footer">
            <p>&copy; 2025 WildVentures. All rights reserved.</p>
            <p>123 Wildlife Way, Adventure City, AC 12345</p>
        </div>
    </div>
</body>
</html>