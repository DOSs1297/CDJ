<?php
require_once('classes/database.php');
// Create an instance of your database class
$db = new database();
$user_id = $_SESSION['User_Id'];

if(isset($_GET['room_id'])) {
    $room = $db->getRoomDetails($_GET['room_id']); // Get room details
    $room_id = $_GET['room_id'];
} else if(isset($_POST['room_id'])) {
    $room = $db->getRoomDetails($_POST['room_id']); // Get room details
    $room_id = $_POST['room_id'];
}

$result = null;

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $check_in = $_POST['checkin'] ?? '';
    $check_out = $_POST['checkout'] ?? '';
    $pax = $_POST['persons'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Validate form data (you may add more validation as needed)
    if (!empty($check_in) && !empty($check_out) && !empty($pax) && !empty($phone)) {
        // Call insertBooking function if form data is valid
        $result = $db->booknow($room_id, $check_in, $check_out, $pax, $phone, $user_id);
    } 
    else 
    {
        $result = 2; // Indicates that not all fields are filled out
    }
}

// Check if room details exist
if ($room) {
    $room_name = htmlspecialchars($room['room_name']);
    $room_price = htmlspecialchars($room['room_price']);
    $facility = htmlspecialchars($room['facility']);
    // If your database stores a picture URL, update $picture accordingly
    if (!empty($room['picture'])) {
        $picture = htmlspecialchars($room['picture']);
    }
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Room Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('');
            background-size: cover;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .room-image {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .room-details {
            margin-bottom: 20px;
        }
        .room-details h2 {
            color: #333;
        }
        .room-details p {
            color: #666;
            line-height: 1.6;
        }
        .modal-header {
            background-color: #4CAF50;
            color: #fff;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
    </style>
</head>
<?php include('includes/navbar.php'); ?>
<body>

    <div class="container">
        <img src="<?php echo $picture; ?>" alt="Hotel Room" class="room-image" width=600 height=400>
        <div class="room-details">
            <h2>Deluxe Suite</h2>
            <p><strong>Room Name:</strong><?php echo $room_name; ?></p>
            <p><strong>Room Price:</strong><?php echo $room_price; ?> </p>
            <p><strong>Facility:</strong><?php echo $facility; ?></p>
            <p><strong>Available:</strong></p>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#reservationModal">Book Now</button>
        </div>
    </div>

    <!-- Reservation Modal -->
    <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Reserve Your Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="booknow.php" method="post" id="bookform">
                        <div class="form-group">
                            <label for="persons">Number of Persons</label>
                            <input type="hidden" value="<?php echo $room_id ?>" id="room_id" name="room_id">
                            <input type="number" class="form-control" id="persons" name="persons" placeholder="1" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="checkin">Check In</label>
                            <input type="datetime-local" class="form-control" id="checkin" name="checkin" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout">Check Out</label>
                            <input type="datetime-local" class="form-control" id="checkout" name="checkout" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="018XXXXXXX" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit" name="submit">Submit Reservation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Booking Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="resultMessage">
                    <!-- Message will be injected here by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        
        function validateDates(checkin , checkout){
            const diffTime = Math.abs(checkin - checkout);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)); 
            if(diffDays > 0 && checkin < checkout) {
                console.log('true');
                return true;
            } else {
                return false;
            }
        }
        
        $('#submit').prop('disabled', true);
        $("#bookform").change(function(){    
                
            var checkin = $("#checkin").val();
            var checkout = $("#checkout").val();   
            var persons = $("#persons").val();   
            var phone = $("#phone").val();      
            
            
            if(checkin.length > 0 && checkout.length > 0 && persons.length > 0 && phone.length > 0 ) {
                checkin = new Date(checkin)
                checkout = new Date(checkout)
                isvalid = validateDates(checkin,checkout);
                if(isvalid){                    
                    $('#submit').prop('disabled', false);
                }
                else {                    
                    $('#submit').prop('disabled', true);
                }
            } else {            
                $('#submit').prop('disabled', true);
            }
        });

        // Display result message if set
        <?php if ($result !== null): ?>
            var result = <?php echo $result; ?>;
            var message = "";
            if (result == 0) {
                message = "Booking inserted successfully.";
            } else if (result == 1) {
                message = "Room booked at selected dates.";
            } else {
                message = "Please fill out all fields in the form.";
            }
            $("#resultMessage").text(message);
            $("#resultModal").modal('show');
        <?php endif; ?>
    });
    </script>
</body>
</html>
