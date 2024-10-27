<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Recipe Card</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .recipe-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }
        
        .recipe-title {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }
        
        .recipe-details {
            font-size: 1em;
            color: #666;
        }
        
        .price {
            font-size: 1.2em;
            margin: 10px 0;
            color: #333;
        }
        
        .approval {
            font-size: 1.2em;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
        }
        
        .approval.approved {
            background-color: #4CAF50; /* Green for approved */
        }
        
        .approval.rejected {
            background-color: #F44336; /* Red for rejected */
        }
        
        .btn {
            display: inline-block;
            background-color: #007bff; /* Blue button */
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        body {
        background-image: url('loginbackground.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        background-attachment: fixed;
    }

    </style>
</head>
<body>
    <div class="recipe-card">
        <h2 class="recipe-title">Booking Details</h2>
        <div class="recipe-details">
            <p class="price">Price: $250</p>
            <p class="approval approved">Approval: Approved</p>
            <p class="check-in-out">Check-in: July 1, 2024</p>
            <p class="check-in-out">Check-out: July 5, 2024</p>
            <a href="index1.php" class="btn">Home</a>
        </div>
    </div>
</body>
</html>
