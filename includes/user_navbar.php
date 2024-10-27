<?php
$current_page = basename($_SERVER['PHP_SELF']);
require_once('classes/database.php');
$con = new Database();




// Assuming the profile picture URL is stored in the session or fetched from the database
$profilePicture = $_SESSION['profile_picture'] ?? 'path/to/default/profile_picture.jpg';

if (isset($_POST['updatepassword'])) {
  $userId = $_SESSION['User_Id'];
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
  
  // Update the password in the database using the new method
          if ($con->updatePassword($userId, $hashedPassword)) {
              // Password updated successfully
              header('Location: user_account.php?status=success');
              exit();
          } else {
              // Failed to update password
              header('Location: user_account.php?status=error');
              exit();
          }
      
  } 
  if (isset($_POST['rooms'])) {
    $room_id = $_SESSION['room_id'];
    $room_cat = $_POST['room_cat'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $name = $_POST['name'];
    $phone = $_SESSION['phone'];
    $book = $_POST['book'];
    $persons = $_POST['persons'];
    $checkin_ampm = $_POST['checkin_ampm'];
    $checkout_ampm = $_POST['checkout_ampm'];
    
    // Update the password in the database using the new method
            if ($con->rooms($room_id, $room_cat, $checkin, $checkout, $name, $phone, $book  , $persons	, $checkin_ampm, $checkout_ampm)) {
                // Password updated address
                header('Location: user_account.php?status=success');
                exit();
            } else {
                // Failed to update address
                header('Location: user_account.php?status=error');
                exit();
            }
        
    } 



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome!</title>

  <!-- jQuery for Address Selector -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- For Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="includes/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- For Pop Up Notification -->
  <link rel="stylesheet" href="package/dist/sweetalert2.css">
  
  <style>
    .profile-header {
      text-align: center;
      margin: 20px 0;
    }
    .profile-pic {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      margin-bottom: 10px;
    }
    .profile-info, .address-info {
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #f9f9f9;
      margin-bottom: 20px;
    }
    .info-header {
      background-color: #007bff;
      color: white;
      padding: 10px;
      border-radius: 10px 10px 0 0;
    }
    .info-body {
      padding: 20px;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <!-- <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?>!</a> -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <div class="navbar-nav mr-auto mt-2 mt-lg-0">
        <a class="nav-link" href="index.php">Home<span class="sr-only"></span></a>
        <a class="nav-link" href="user_account.php">Bookings<span class="sr-only"></span></a>
        <a class="nav-link" href="room_list.php">Rooms<span class="sr-only"></span></a>
        <a class="nav-link" href="dashboard_admin.php">Dashboard<span class="sr-only"></span></a>
    </div>
  </div>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
 

    
    <ul class="navbar-nav ml-auto">
    <li><a class="nav-link scrollto" href="login.php">Log-out</a></li>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changeProfilePictureModal"><i class="fas fa-user-circle"></i> Change Profile Picture</a>
          
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal"><i class="fas fa-key"></i> Change Password</a>
            <a class="dropdown-item" href="logout.php" onclick="return confirm('Are you sure you want to leave?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>

      </li>
    </ul>
  </div>
</nav>






<!-- Change Profile Picture Modal -->
    <!-- Modal for Profile Picture Upload -->
    <div class="modal fade" id="" tabindex="-1" role="dialog" aria-labelledby="changeProfilePictureModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form id="uploadProfilePicForm" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadProfilePicModalLabel">Upload Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="file" class="form-control form-control-file" id="profilePictureInput" name="profile_picture" accept="image/*" required>
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



<!-- Modal for Change Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="changePasswordForm" method="POST">
          
        <div class="form-group">
          <label for="currentPassword">Current Password</label>
          <input type="password" class="form-control" id="currentPassword" name="current_password" required>
          <div id="currentPasswordFeedback" class="invalid-feedback"></div>
        </div>
        
        <div class="form-group">
          <label for="newPassword">New Password</label>
          <input type="password" class="form-control" id="newPassword" name="new_password" required readonly>
          <div id="newPasswordFeedback" class="invalid-feedback"></div>
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm New Password</label>
          <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required readonly>
          <div id="confirmPasswordFeedback" class="invalid-feedback"></div>
        </div>
        
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updatepassword" id="saveChangesBtn" disabled>Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</div>


<!-- Password Validation Logic Starts Here --><script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPasswordInput = document.getElementById('currentPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const saveChangesBtn = document.getElementById('saveChangesBtn');
    const currentPasswordFeedback = document.getElementById('currentPasswordFeedback');
    const newPasswordFeedback = document.getElementById('newPasswordFeedback');
    const confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');

    currentPasswordInput.addEventListener('input', function() {
        const currentPassword = currentPasswordInput.value;
        if (currentPassword) {
            fetch('check_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ 'current_password': currentPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    currentPasswordInput.classList.add('is-valid');
                    currentPasswordInput.classList.remove('is-invalid');
                    currentPasswordFeedback.textContent = '';

                    newPasswordInput.removeAttribute('readonly');
                    confirmPasswordInput.removeAttribute('readonly');
                } else {
                    currentPasswordInput.classList.add('is-invalid');
                    currentPasswordInput.classList.remove('is-valid');
                    currentPasswordFeedback.textContent = 'Current password is incorrect.';

                    newPasswordInput.setAttribute('readonly', 'readonly');
                    confirmPasswordInput.setAttribute('readonly', 'readonly');
                }
                toggleSaveButton();
            })
            .catch(error => {
                console.error('Error:', error);
                currentPasswordInput.classList.add('is-invalid');
                currentPasswordInput.classList.remove('is-valid');
                currentPasswordFeedback.textContent = 'An error occurred while verifying the current password.';

                newPasswordInput.setAttribute('readonly', 'readonly');
                confirmPasswordInput.setAttribute('readonly', 'readonly');
                toggleSaveButton();
            });
        } else {
            currentPasswordInput.classList.remove('is-valid', 'is-invalid');
            currentPasswordFeedback.textContent = '';
            newPasswordInput.setAttribute('readonly', 'readonly');
            confirmPasswordInput.setAttribute('readonly', 'readonly');
            toggleSaveButton();
        }
    });

    newPasswordInput.addEventListener('input', function() {
        const newPassword = newPasswordInput.value;
        const currentPassword = currentPasswordInput.value;

        if (newPassword === currentPassword) {
            newPasswordInput.classList.add('is-invalid');
            newPasswordInput.classList.remove('is-valid');
            newPasswordFeedback.textContent = 'New password cannot be the same as the current password.';
        } else if (validatePassword(newPasswordInput)) {
            newPasswordInput.classList.add('is-valid');
            newPasswordInput.classList.remove('is-invalid');
            newPasswordFeedback.textContent = '';
        } else {
            newPasswordInput.classList.add('is-invalid');
            newPasswordInput.classList.remove('is-valid');
            newPasswordFeedback.textContent = 'Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.';
        }
        validateConfirmPassword(confirmPasswordInput);
        toggleSaveButton();
    });

    confirmPasswordInput.addEventListener('input', function() {
        validateConfirmPassword(confirmPasswordInput);
        toggleSaveButton();
    });

    function validatePassword(passwordInput) {
        const password = passwordInput.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        return regex.test(password);
    }

    function validateConfirmPassword(confirmPasswordInput) {
        const password = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        if (password === confirmPassword && password !== '') {
            confirmPasswordInput.classList.add('is-valid');
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordFeedback.textContent = '';
            return true;
        } else {
            confirmPasswordInput.classList.add('is-invalid');
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordFeedback.textContent = 'Passwords do not match.';
            return false;
        }
    }

    function toggleSaveButton() {
        if (currentPasswordInput.classList.contains('is-valid') && validatePassword(newPasswordInput) && validateConfirmPassword(confirmPasswordInput)) {
            saveChangesBtn.removeAttribute('disabled');
        } else {
            saveChangesBtn.setAttribute('disabled', 'disabled');
        }
    }
});

</script><!-- Password Validation Logic Ends Here -->

<!-- SweetAlert2 Script For Pop Up Notification -->

<script src="package/dist/sweetalert2.js"></script>

<!-- After the message is shown the whole website will be reloaded and the query parameters after the url will be removed so that the message only appear once. -->
<!-- Pop Up Messages after a succesful transaction starts here --> <script>
document.addEventListener('DOMContentLoaded', function() {
  const params = new URLSearchParams(window.location.search);
  const status = params.get('status');

  if (status) {
    let title, text, icon;
    switch (status) {
      case 'success':
        title = 'Success!';
        text = 'Password updated successfully.';
        icon = 'success';
        break;
      case 'success1':
        title = 'Success!';
        text = 'Address was updated successfully.';
        icon = 'success';
        break;
      case 'error':
        title = 'Error!';
        text = 'Something went wrong.';
        icon = 'error';
        break;
      case 'nomatch':
        title = 'Error!';
        text = 'Passwords do not match.';
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
      // Remove the status parameter from the URL
      const newUrl = window.location.origin + window.location.pathname;
      window.history.replaceState(null, null, newUrl);
    });
  }
});
</script> <!-- Pop Up Messages after a succesful transaction ends here -->


<!-- Change Profile Picture Logic Starts here -->
<script>
    $(document).ready(function() {
        $('#profilePictureInput').change(function() {
            const file = this.files[0];
            if (file) {
                // Check file size
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'File size exceeds 5MB.',
                        icon: 'error'
                    });
                    $('#imagePreview').hide();
                    $('#uploadProfilePicForm').data('valid', false);
                    return;
                } else {
                    $('#fileSizeError').hide();
                    $('#uploadProfilePicForm').data('valid', true);
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

        $('#uploadProfilePicForm').submit(function(event) {
            event.preventDefault();
            if (!$(this).data('valid')) {
                Swal.fire({
                    title: 'Error!',
                    text: 'File size exceeds 5MB.',
                    icon: 'error'
                });
                return;
            }

            const formData = new FormData(this);
            $.ajax({
                url: 'upload_profile_picture.php', // Change this to your PHP file
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }
                        if (response.success) {
                            $('#profilePicPreview').attr('src', response.filepath);
                            $('#changeProfilePictureModal').modal('hide');
                            Swal.fire({
                                title: 'Success!',
                                text: 'Profile picture updated successfully.',
                                icon: 'success'
                            }).then(() => {
                                // Reload the page after displaying the success message
                                window.location.href = window.location.href.split('?')[0];
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error,
                                icon: 'error'
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while processing the response.',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while uploading the file.',
                        icon: 'error'
                    });
                }
            });
        });
    });
    </script><!-- Change Profile Picture Logic Ends here -->

<!-- For Address Selector Validation --><script>
document.addEventListener('DOMContentLoaded', function() {
  // Fetch region, province, city, and barangay options dynamically if needed
  // Example for adding event listeners for dynamic fetching
  // document.getElementById('region').addEventListener('change', fetchProvinces);

  // Example of a function to fetch provinces
  // function fetchProvinces() {
  //   const regionId = document.getElementById('region').value;
  //   // Fetch provinces based on regionId
  // }

  // Form validation
  var form = document.getElementById('updateAccountForm');
  form.addEventListener('submit', function(event) {
    if (form.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add('was-validated');
  }, false);
});
</script><!-- For Address Selector Validation -->


<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- Make Sure jquery3.6.0 is before the ph-address-selector.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="ph-address-selector.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>