<?php
require_once('classes/database.php');

// Assuming $con is the instance of your Database class that handles database operations
$con = new Database();



// Fetch rooms data
$rooms = $con->rooms1(); // Adjust this method name based on your Database class implementation
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resort Booking</title>
    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
    /* Body and Container Styles */
    body {
        background-image: url("");
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
        margin: 20px auto; /* Center the container horizontally and add top and bottom margin */
        max-width: 800px; /* Increase max-width for larger container */
        padding: 0 15px; /* Add padding to the sides */
    }

    .room-container {
        background-color: #ffffff;
        border: 1px solid #ddd; /* Light gray border */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
        padding: 30px; /* Increase padding for more space inside */
        margin-bottom: 30px; /* Bottom margin for spacing */
    }

    .room-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px; /* Top margin for spacing */
    }

    .room-details-inner {
        flex: 1;
    }

    .room-details-inner h4 {
        margin-top: 0;
        font-size:30px;
        color: #333;
    }

    .room-details-inner h6 {
        color: #666;
        font-size:20px;
        margin-bottom: 5px;
    }

    .action-container {
        margin-top: 10px;
    }

    .room-img {
    width: 100%; /* Ensures the image fills its container */
    max-width: 300px; /* Adjust this value to control the maximum width */
    height: auto; /* Maintains aspect ratio */
    border-radius: 6px; /* Adds rounded corners */
}

    .button {
    font-size: 14px;
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #45a049;
}
</style>
</head>
<body>
    <div class="container">
        <?php if ($rooms && count($rooms) > 0): ?>
            <?php foreach ($rooms as $row): ?>
                <div class="row room-container">
                    <div class="col-md-12">
                        <div class="room-details">
                            <div class="room-details-inner">
                                <h4><?= htmlspecialchars($row['room_name']); ?></h4>

                                <h6>Facilities: <?= htmlspecialchars($row['facility']); ?></h6>
                                <h6>Price: <?= htmlspecialchars($row['room_price']); ?> tk/night.</h6>
                                <div class="action-container">
                                    <a href="./booknow.php?room_id=<?= htmlspecialchars($row['room_id']); ?>&room_name=<?= urlencode(htmlspecialchars($row['room_name'])); ?>&facility=<?= htmlspecialchars($row['facility']); ?>&room_price=<?= htmlspecialchars($row['room_price']); ?>" class="btn btn-primary button">Book Now</a>
                                </div>
                            </div>
                            <img class="room-img" src="<?= htmlspecialchars($row['picture']); ?>" alt='room image'>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No rooms available.</p>
        <?php endif; ?>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
