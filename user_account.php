<?php
require_once('classes/database.php');
$con = new database();

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if ($con->delete($id)) {
        header('location.php?status=success');
    } else {
        echo "Something went wrong";
    }
}

// Check if the form was submitted with action 'approved'
if (isset($_POST['approved'])) {
    $bookingId = $_POST['approved']; // This should be sanitized and validated
    $result = $con->updateStatus($bookingId, "approved");
    echo "booking approved";
}

// Check if the form was submitted with action 'cancelled'
if (isset($_POST['cancelled'])) {
    $bookingId = $_POST['cancelled']; // This should be sanitized and validated
    $result = $con->updateStatus($bookingId, "cancelled");
    echo "booking cancelled";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- For Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="LOGO.png">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <!-- For Pop Up Notification -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="package/dist/sweetalert2.css">
</head>
<style>
    th, td {
        background-color: aliceblue;
        text-align: center;
    }
    .container.custom-container {
        background-color: white;
        max-width: 95%;
        padding: 20px;
        margin: auto;
        border-radius: 10px;
    }
    body {
        background-image: url('loginbackground.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        background-attachment: fixed;
    }

    
</style>
<body>

<?php include('includes/user_navbar.php'); ?>

<div class="container custom-container rounded-3 shadow my-5 p-3 px-5">
    <h2 class="text-center mb-2">Date Reservation</h2>
    <!-- Search input -->
    <div class="mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Search users...">
    </div>

    <div class="table-responsive text-center">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><i class="fa fa-th" aria-hidden="true"></i></th>
                    <th colspan="11" style="text-align: left">Reservation Table</th>
                </tr>
                <tr>
                    <th>Booking ID</th>
                    <th>Facilities ID</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Pax</th>
                    <th>Phone Number</th>
                    <th>User Id</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Pending</th>|
                </tr>
            </thead>
            <tbody id="table-body">
            <?php
            $counter = 1;
            $data = $con->booking();
            foreach ($data as $rows) {
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($rows['booking_id']); ?></td>
                    <td><?php echo htmlspecialchars($rows['room_id']); ?></td>
                    <td><?php echo htmlspecialchars($rows['check_in']); ?></td>
                    <td><?php echo htmlspecialchars($rows['check_out']); ?></td>
                    <td><?php echo htmlspecialchars($rows['pax']); ?></td>
                    <td><?php echo htmlspecialchars($rows['phone']); ?></td>
                    <td><?php echo htmlspecialchars($rows['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($rows['Status']); ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $rows['user_id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </form>
                    </td>
                    <td>
                    <?php if($rows['Status'] == "Pending"){?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="approved" value="<?php echo $rows['booking_id']; ?>">
                            <button type="submit" class='btn btn-primary'>Approve</button>
                        </form>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="cancelled" value="<?php echo $rows['booking_id']; ?>">
                            <button type="submit" class='btn btn-danger'>Cancel </button>
                        </form>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- SweetAlert2 Script For Pop Up Notification -->
<script src="package/dist/sweetalert2.js"></script>

<script>
    $(document).ready(function () {
        $("#search-input").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const status = params.get('status');
 
        if (status) {
            let title, text, icon;
            switch (status) {
                case 'delete':
                    title = 'Success!';
                    text = 'Record is successfully deleted.';
                    icon = 'success';
                    break;
                case 'error':
                    title = 'Error!';
                    text = 'Something went wrong.';
                    icon = 'error';
                    break;
                default:
                    return;
                   
            }
            Swal.fire({
                title: title,
                text: text,
                icon: icon
            }).then(() => {
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState(null, null, newUrl);
            });
        }
    });
</script>

</body>
</html>
