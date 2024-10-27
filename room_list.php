<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.js"></script>
    
    <!-- Custom CSS for room pictures and modal image previews -->
    <style>
        body {
            background-image: url('loginbackground.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
            padding: 20px; /* Adjust padding as needed */
        }

        .room-picture {
            max-width: 100%;
            max-height: 300px; /* Adjust height as needed */
            object-fit: cover; /* Ensures the image covers the entire container */
        }

        .modal-body img {
            max-width: 100%;
            max-height: 300px; /* Adjust height for modal image preview */
        }
    </style>
</head>
<body></body>

<?php
// Include necessary files and start session if needed
require_once('classes/database.php');
include('includes/user_navbar.php');

// Fetch room data from database
$data = $con->rooms1();

// Process form submission for updating room details
if (isset($_POST['updateroom'])) {
    $roomId = $_POST['room_id'];
    $roomName = $_POST['room_name'];
    $roomPrice = $_POST['price'];
    $roomFacilities = $_POST['facilities'];

    // Update room details in database
    $result = $con->update_room($roomId, $roomName, $roomPrice, $roomFacilities);
    if ($result) {
        // Handle success or redirect as needed
        // For example, redirect back to the same page
  
        exit;
    } else {
        // Handle update failure
        $error = "Failed to update room details. Please try again.";
    }
}
?>

<!-- HTML Structure for Displaying Rooms and Modals -->
<div class="container my-3">
    <div class="row">
        <?php foreach ($data as $row) : ?>
            <div class="col-md-12">
                <div class="profile-info">
                    <div class="info-header">
                        <h3>Facilities</h3>
                    </div>
                    <div class="row room-container">
                        <div class="info-body col-md-4">
                            <p><strong>Room ID: </strong><?php echo $row['room_id'] ?></p>
                            <p id="roomname<?php echo $row['room_id']; ?>"><strong>Room Name: </strong><?php echo $row['room_name'] ?></p>
                            <p id="price<?php echo $row['room_id']; ?>"><strong>Price: </strong><?php echo $row['room_price'] ?></p>
                            <p id="facility<?php echo $row['room_id']; ?>"><strong>Facility: </strong><?php echo $row['facility'] ?></p>
                            <input type="hidden" id="picture<?php echo $row['room_id']; ?>" value="<?php echo $row['picture'] ?>">
                            <div class="action-container">
                                <button class="dropdown-item edit-picture" data-toggle="modal" data-target="#changeRoomPictureModal" data-roomid="<?php echo $row['room_id']; ?>"><i class="fas fa-image"></i> Replace Picture</button>
                                <button class="dropdown-item edit" data-toggle="modal" data-target="#editRoomModal" data-roomid="<?php echo $row['room_id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                            </div>
                        </div>
                        <div class="info-body col-md-4">
                            <img class="room-picture" src="<?php echo $row['picture']; ?>" alt="room image">
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal for Update Room -->
<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateRoomForm" method="POST">
                    <div class="form-group">
                        <label for="room_name">Name</label>
                        <input type="hidden" class="form-control" id="room_id" name="room_id">
                        <input type="text" class="form-control" id="room_name" name="room_name" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="facilities">Facilities</label>
                        <input type="text" class="form-control" id="facilities" name="facilities" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="updateroom">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Picture -->
<div class="modal fade" id="changeRoomPictureModal" tabindex="-1" role="dialog" aria-labelledby="changeRoomPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadRoomPicForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadRoomPicModalLabel">Change Room Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="room_id-picture" name="room_id-picture">
                        <input type="file" class="form-control form-control-file" id="roomPictureInput" name="room_picture" accept="image/*" required>
                        <small id="fileSizeError" class="form-text text-danger" style="display:none;">File size exceeds 5MB</small>
                    </div>
                    <div class="form-group">
                        <img id="imagePreview" src="#" alt="Image Preview" style="display:none; width: 100%; height: auto;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include necessary JavaScript and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.edit').click(function() {
            var roomId = $(this).data('roomid');
            var roomname = $('#roomname' + roomId).text().trim().split(': ')[1];
            var roomprice = $('#price' + roomId).text().trim().split(': ')[1];
            var facility = $('#facility' + roomId).text().trim().split(': ')[1];

            $('#editRoomModal').modal('show');
            $('#room_id').val(roomId);
            $('#room_name').val(roomname);
            $('#price').val(parseInt(roomprice));
            $('#facilities').val(facility);
        });

        $('.edit-picture').click(function() {
            var roomId = $(this).data('roomid');
            $('#changeRoomPictureModal').modal('show');
            $('#room_id-picture').val(roomId);
        });

        $('#roomPictureInput').change(function() {
            const file = this.files[0];
            if (file) {
                // Check file size
                if (file.size > 5 * 1024 * 1024) {
                    $('#fileSizeError').show();
                    $('#imagePreview').hide();
                    return;
                } else {
                    $('#fileSizeError').hide();
                }

                // Preview the image
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(file);
            }
        });

        $('#uploadRoomPicForm').submit(function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: 'upload_room_picture.php', // Adjust URL as needed
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle response from server after picture upload
                    console.log(response);
                    // Example: Display success or error message using Swal.fire or similar
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
