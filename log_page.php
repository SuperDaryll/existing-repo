<?php 
session_start();
include 'db.php';

// Check if the client is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['client_id'])) {
    header("Location: signup.php");
    exit();
}

// Retrieve client ID from session
$client_id = $_SESSION['client_id'];

// Fetch client information
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

// Check if client data was fetched successfully
if ($result_client && $result_client->num_rows > 0) {
    $client = $result_client->fetch_assoc();
    $client_name = htmlspecialchars($client['full_name']);
} else {
    $client_name = "Guest"; // Default name if client data not found
}

// Fetch products
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

if (!$result_products) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <style>
    body {
        background-color: black;
    }
    /* Header styling */
    header {
        background-color: #1F1F1F; /* Dark background */
        color: white; /* Neon blue text */
        padding: 20px;
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        text-shadow: 0 0 6px rgba(255, 204, 0, 0.4); /* Glow effect */
        box-shadow: 0 6px 12px rgba(255, 204, 0, 0.4); /* Neon shadow */
    }

    /* Navigation bar styling */
    nav {
        background-color: #333;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(255, 204, 0, 0.4); /* Subtle shadow for nav */
        display: flex;
        justify-content: center;
    }

    nav a {
        float: left;
        display: block;
        color: white; /* Neon blue text */
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: background-color 0.3s, color 0.3s; /* Smooth transition */
    }

    nav a:hover {
        background-color: lightgreen; /* Neon background on hover */
        color: black; /* Dark text on hover */
    }

    /* Container for product cards */
    .product-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 30px auto;
        max-width: 1200px;
        justify-content: center;
    }

    /* Style for individual product cards */
    .product-card {
        width: 220px;
        background-color: grey; /* Dark card background */
        border: 1px solid #333;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 6px 12px rgba(255, 204, 0, 0.4); /* Neon shadow */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        
    }

    .product-card:hover {
        transform: scale(1.05); /* Slightly enlarge on hover */
        box-shadow: 0 8px 16px lightgreen; /* Enhanced glow on hover */
    }

    /* Image styling */
    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-bottom: 1px solid #00E5FF; /* Neon border at the bottom */
    }

    /* Product details styling */
    .product-info {
        padding: 15px;
        color: black; /* Light text color */
    }

    /* Button styling */
    .product-card button {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: none;
        color: #121212;
        background-color: gold; /* Neon blue */
        cursor: pointer;
        transition: background-color 0.3s, box-shadow 0.3s;
        border-radius: 10px;
    }

    .product-card button:hover {
        background-color: lightgreen; /* Slightly darker neon on hover */
        box-shadow: 0 4px 8px rgba(255, 204, 0, 0.4); /* Enhanced glow on hover */
    }

    /* Footer styling */
    footer {
        background-color: #1F1F1F;
        color: #00E5FF;
        text-align: center;
        padding: 15px;
        box-shadow: 0 -2px 6px rgba(0, 229, 255, 0.2); /* Glow on top */
        font-size: 14px;
        text-shadow: 0 0 4px rgba(0, 229, 255, 0.8);
    }
</style>

</head>
<body>
    <header>
        Thank you Come again!, <?= $client_name ?>!
    </header>

    <!-- Navigation bar -->
    <nav>
        <a href="login.php">Product Dashboard</a>
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>

    </nav>

   <!-- Product cards container -->
<div class="product-container">
    <?php while ($row = $result_products->fetch_assoc()): ?>
    <div class="product-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
        <div class="product-info">
            <h4><?= htmlspecialchars($row['product_name']) ?></h4>
            <p><?= htmlspecialchars($row['caption']) ?></p> <!-- This line adds the caption -->
            <p>Price: ₱<?= htmlspecialchars($row['price']) ?></p>
            <form action="purchase.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" min="1" value="1" required>
                <a href="login.php">Buy Now</a>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>
