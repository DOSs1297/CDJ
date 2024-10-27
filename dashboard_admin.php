<?php
require_once('classes/database.php');
$con = new database();

$pendingcount = $con ->getPendingCount();

$BookingCount = $con ->getBookingCount();



$getPersonPrice = $con->getPersonPrice();


$getTotalFacilityprice = $con ->getTotalFacilityprice();

$Totalofall = $con ->Totalofall();

echo $Totalofall;







?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="12/css/sb-admin-2.css">
    <style>
        body {
            margin-top: 100px; /* Add margin to the top */
            background-color: #f8f9fc; /* Light gray background color */
            background-image: url('loginbackground.jpg'); /* Optional: Background image URL */
            background-size: cover; /* Cover the entire background */
            background-repeat: no-repeat; /* Prevent repeating background */
            background-position: center; /* Center the background image */
        }
        
        /* Example targeting the navbar */
        .navbar {
            margin-bottom: 50px; /* Add margin below the navbar */
            background-color: #ffffff; /* Example background color for the navbar */
            /* Add other styles specific to your navbar */
        }

        
    </style>
</head>
<body>
<?php include('includes/user_navbar.php'); ?>
            <!-- Nav Item - Charts -->
        

            
    
    
        <!-- End of Sidebar -->

                    <!-- Content Row -->
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Facility Earnings</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo $getTotalFacilityprice; ?></div>
                                            </div>
                                            <div class="col-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Earnings (Person)</div>

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo $getPersonPrice;?></div>
                                            </div>
                                            <div class="col-auto">
                                   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Bookings
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $BookingCount; ?></div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                          
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Pending Requests</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pendingcount; ?></div>
                                            </div>
                                            <div class="col-auto">
                             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row no-gutters ">
        <!-- Content Row -->
        <div class="col-xl-8">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
        </div>
        <div class="card-body">
            <canvas id="earningsChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<div class="col-xl-4 pl-xl-1">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Total Use Earnings</h6>
        </div>
        <div class="card-body" style="height: 400px;">
            <!-- Adjusted height to 400px, you can change this value as per your requirement -->
            <div class="row no-gutters align-items-center">
            <div class="col mr-2 d-flex justify-content-center align-items-center">
    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 text-center" style="font-size: 30px;">
        Total Earnings:
    </div>
    <div class="h5 mb-0 font-weight-bold text-gray-800 text-center" style="font-size: 30px;">
        <?php echo $Totalofall; ?>
    </div>
</div>
                <div class="col-auto">
                    <!-- If you have any additional content for the 'col-auto' section, place it here -->
                </div>
            </div>
            <!-- Additional content or adjustments can be made here -->
        </div>
    </div>
</div>



    

<script src="package/dist/chart.min.js"></script>



                            <script>
    // Sample data for earnings from January to December
    var earningsData = [1000, 1200, 1500, 1800, 2000, 2200, 2500, 2800, 3000, 3200, 3500, 3800];

    // Configure the line chart
    var ctx = document.getElementById('earningsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Earnings Overview',
                data: earningsData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>


                        

        

    </body>

    </html>