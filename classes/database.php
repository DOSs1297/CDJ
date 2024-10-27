<?php

session_start();  
//update this depending on the db setup
$dbcon = mysqli_connect("localhost","root","","bookingresort") or die ("could not connect database");

class database{

    function opencon(){
        return new PDO('mysql:host=localhost; dbname=bookingresort', 'root', '');
    }



function check($username, $password) {
        // Open database connection
        $con = $this->opencon();
    
        // Prepare the SQL query
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
    
        // Fetch the user data as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If a user is found, verify the password
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
    
        // If no user is found or password is incorrect, return false
        return false;
    }

    public function check_room_availability($checkin, $checkout,$roomid)
    {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM booking WHERE room_id=? and check_in <= ? AND check_out >= ?");
        $stmt->execute([$roomid, $checkin, $checkout]);

        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return false;
        }

        return true;
    }

    public function book_now($checkin, $checkout, $phone,$roomid, $persons, $userId)
    { 
        try
        {
            $con = $this->opencon();
            $stmt = $con->prepare("INSERT INTO `booking`(`room_id`, `check_in`, `check_out`, `pax`, `phone`, `user_id`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$roomid, $checkin, $checkout, $persons, $phone, $userId]);

            return true;
        }
        catch(e) 
        {
            return false;
        }
    }

function signup($username, $password, $firstname, $lastname, $birthday, $sex){
        $con = $this->opencon();

// Check if the username is already exists

    $query=$con->prepare("SELECT username FROM users WHERE username =?");
    $query->execute([$username]);
    $existingUser= $query->fetch();
    

// If the username already exists, return false
    if($existingUser){
    return false;
}
// Insert the new username and password into the database
    return $con->prepare("INSERT INTO users(username, password,firstname,lastname,birthday,sex)
VALUES (?, ?, ?, ?, ?, ?)")
           ->execute([$username,$password, $firstname, $lastname, $birthday, $sex]);
           
}



function signupUser($firstname, $lastname, $birthday, $sex, $email, $username, $password, $profilePicture)
{
    $con = $this->opencon();
    // Save user data along with profile picture path to the database
    $con->prepare("INSERT INTO users (firstname, lastname, birthday, sex, user_email, username, password, user_profile_picture) VALUES (?,?,?,?,?,?,?,?)")->execute([$firstname, $lastname, $birthday, $sex, $email, $username, $password, $profilePicture]);
    return $con->lastInsertId();
    }
// function insertAddress($User_Id, $street, $barangay, $city, $province){
//     $con = $this->opencon();
//      return $con->prepare("INSERT INTO user_address (User_Id,street, barangay, city,province) VALUES(?, ?, ?, ?, ?)") ->execute([$User_Id, $street, $barangay, $city, $province]);
    
 

// }


function insertAddress($User_Id, $street, $barangay, $city, $province)
{
    $con = $this->opencon();
    return $con->prepare("INSERT INTO user_address (User_Id, street, barangay, city, province) VALUES (?,?,?,?,?)")->execute([$User_Id, $street, $barangay,  $city, $province]);
      
}

function view(){
        $con = $this->opencon();
        return $con->query("SELECT
        users.User_Id,
        users.firstname,
        users.lastname,
        users.birthday,
        users.sex,
        users.username, 
        users.password,
        users.user_profile_picture, 
        CONCAT(
            user_address.street,' ',user_address.barangay,' ',user_address.city,' ',user_address.province
        ) AS address
    FROM
        users
    JOIN user_address ON users.User_Id = user_address.User_Id")->fetchAll();

    }

    
    function delete($id)
    {
        try
        {
            $con = $this->opencon();
            $con->beginTransaction();

            // Delete user address

            $query = $con->prepare("DELETE FROM booking
            WHERE user_id =?");
            $query->execute([$id]);
            $con->commit();
            return true; //Deletion successful
        } catch (PDOException $e) {
            $con->rollBack();
         return false;
        } 

    }

function getUserLoggedInData($id) {
    try {
        $con = $this->opencon();
        $query = $con->prepare("SELECT
        users.User_Id,
        users.firstname,
        users.lastname,
        users.birthday,
        users.sex,
        users.username, 
        users.password,
        users.user_profile_picture,
        user_address.street,user_address.barangay,user_address.city,user_address.province
        
    FROM
        users
    JOIN user_address ON users.User_Id = user_address.User_Id
    Where users.User_Id =?;");
        $query->execute([$id]);
        return $query->fetch();
    } catch (PDOException $e) {
        // Handle the exception (e.g. , log error, return empty array. etc.)
        return [];
    
  
        }
}

function viewdata(){
    try {
        $con = $this->opencon();
        $query = $con->prepare("SELECT
        users.User_Id,
        users.firstname,
        users.lastname,
        users.birthday,
        users.sex,
        users.username, 
        users.password,
        users.user_profile_picture,
        user_address.street,user_address.barangay,user_address.city,user_address.province,
        booking.*,
        rooms.*
        
    FROM
        users
    JOIN user_address ON users.User_Id = user_address.User_Id
    JOIN booking On users.User_Id = booking.user_id
    JOIN rooms On rooms.room_id = booking.room_id;");
     $query->execute();
        return $query->fetchAll();
    } catch (PDOException $e) {
        // Handle the exception (e.g. , log error, return empty array. etc.)
        return [];
    
  
        }
    }

    function updateUser($User_Id, $username,$password,$firstname, $lastname, $birthday, $sex) {
        try { 
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE users SET username=?, password=?, firstname=?, lastname=?, birthday=?, sex=? WHERE User_Id=?");
            $query->execute([$username, $password, $firstname, $lastname, $birthday, $sex, $User_Id]);
        
            // Update Successful
            $con->commit();
            return true;
        }catch (PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
            $con->rollBack();
            return false;

        }
    }


    function rooms($room_id, $room_cat, $checkin, $checkout, $name, $phone, $book  , $persons	, $checkin_ampm, $checkout_ampm) {
        try { 
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE rooms SET room_id=?, room_cat=?, checkout=?, name=? phone=?, book=? persons=?, checkin_ampm=?, checkout_ampm=?    WHERE User_Id=?");
            $query->execute([$room_id, $room_cat, $checkin, $checkout, $name, $phone, $book  , $persons	, $checkin_ampm, $checkout_ampm]);
        
            // Update Successful
            $con->commit();
            return true;
        }catch (PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
            $con->rollBack();
            return false;
      
        }
      }

      public function update_room($roomid, $roomname, $roomprice, $roomfacilities) {
        try {
        $con = $this->opencon();
        $con->beginTransaction();
        $query= $con->prepare("UPDATE rooms SET room_name=?, room_price=?, facility=? WHERE room_id=?");
        $query->execute([$roomname, $roomprice, $roomfacilities, $roomid]);
        $con->commit();
        return true;
        }
        catch(PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
            $con->rollBack();
            return false; // Update failed
        }
    }
    
        
 
        
    
    function validateCurrentPassword($User_Id, $currentPassword) {
        // Open database connection
        $con = $this->opencon();
    
        // Prepare the SQL query
        $query = $con->prepare("SELECT password FROM users WHERE User_Id = ?");
        $query->execute([$User_Id]);
    
        // Fetch the user data as an associative array
        $user = $query->fetch(PDO::FETCH_ASSOC);
    
        // If a user is found, verify the password
        if ($user && password_verify($currentPassword, $user['password'])) {
            return true;
        }
    
        // If no user is found or password is incorrect, return false
        return false;
    }
function updatePassword($userId, $hashedPassword){
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE users SET password = ? WHERE User_Id = ?");
            $query->execute([$hashedPassword, $userId]);
            // Update successful
            $con->commit();
            return true;
        } catch (PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
             $con->rollBack();
            return false; // Update failed
        }
        }
    function updateUserProfilePicture($userID, $profilePicturePath) {
            try {
                $con = $this->opencon();
                $con->beginTransaction();
                $query = $con->prepare("UPDATE users SET user_profile_picture = ? WHERE User_Id = ?");
                $query->execute([$profilePicturePath, $userID]);
                // Update successful`
                $con->commit();
                return true;
            } catch (PDOException $e) {
                // Handle the exception (e.g., log error, return false, etc.)
                 $con->rollBack();
                return false; // Update failed
            }
             }
    
    function updateRoomPicture($room_id, $roomPicturePath) {
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE rooms SET picture = ? WHERE room_id = ?");
            $query->execute([$roomPicturePath, $room_id]);
            // Update successful`
            $con->commit();
            return true;
        } catch (PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
                $con->rollBack();
            return false; // Update failed
        }
    }



    function booking(){
        $con = $this->opencon();
        return $con->query("SELECT * FROM booking;
")->fetchAll();

    }


    function rooms1() {
        $con = $this->opencon();
        $query = "SELECT * FROM rooms";
        return $con->query($query)->fetchAll();
    }

    function getRoomsData($con) {
        $query = "SELECT * FROM rooms";
        $stmt = $con->query($query);
        
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return []; // Return an empty array if query fails or no data found
        }
    }

    function insertBooking($room_id, $check_in, $check_out, $pax, $phone, $user_id)
{
    try {
        $con = $this->opencon();
        
        // Prepare and execute the SQL query
        $stmt = $con->prepare("INSERT INTO booking (room_id, check_in, check_out, pax, phone, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$room_id, $check_in, $check_out, $pax, $phone, $user_id]);
        
        // Return the last inserted ID
        return $con->lastInsertId();
    } catch (PDOException $e) {
        // Handle database errors
        // Example: log the error, display an error message, etc.
        echo "Error: " . $e->getMessage();
        return false;
    }
}

  

    function getRoomDetails($id) {  
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM rooms WHERE room_id = ?");
        $stmt->execute([$id]);    
        $roomDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        return $roomDetails;
    }

    public function booknow($roomid, $checkin, $checkout, $pax, $phone, $userId)
    {

        try 
        {    
            $con = $this->opencon();         
            $stmt = $con->prepare(
                "SELECT * FROM `booking` WHERE 
                (
                    `room_id` = ? AND 
                    (`Status` != ?  ) AND 
                    (
                        (`check_in` <= ? and `check_out` >= ?)
                    OR
                        (
                            (`check_in` <= ? and `check_out` <= ?) 
                            AND    
                            (`check_out` >= ?) 
                        )
                    OR    
                        (
                            (`check_in` >= ? and `check_out` >= ?) 
                            AND		
                            (`check_in` <= ?) 
                        )
                    OR	
                            (`check_in` >= ? and `check_out` <= ?) 
                    )
                )"
            );
            $stmt->execute([$roomid, "Cancelled", $checkin, $checkout, $checkin, $checkout, $checkout, $checkin, $checkout, $checkin, $checkin, $checkout]);   
           

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($result))
            {
                $con = $this->opencon();
                //save booking to db
                $inserStmt = $con->prepare("INSERT INTO `booking`(`room_id`, `check_in`, `check_out`, `pax`, `phone`, `user_id`, `Status`) VALUES (?,?,?,?,?,?,?)");
                $inserStmt->execute([$roomid, $checkin, $checkout, $pax, $phone, $userId, 'pending']);  
                $con->lastInsertId();              
                return 0;
            }
            else
            {
                return 1;
            }

        }catch (PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
            echo "Error: " . $e->getMessage();
            $con->rollBack();
            return 2;

        }       
    }
  

   



    function updateStatus($id, $status) {       
        try {
            echo "<script>alert(".$status.")</script>";
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE booking SET Status = ? WHERE booking_id = ?");
            $query->execute([$status, $id]);
            // Update successful`
            $con->commit();
            return true;
        } catch (PDOException $e) {
            // Handle the exception (e.g., log error, return false, etc.)
                $con->rollBack();
            return false; // Update failed
        }

    }




function getPendingCount()
    {
        try {
            $con = $this->opencon();
            $query = $con->prepare("SELECT COUNT(*) AS total FROM booking WHERE Status = 'Pending'");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function getBookingCount()
    {
        try {
            $con = $this->opencon();
            $query = $con->prepare("SELECT COUNT(*) AS totalbooks FROM `booking`");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['totalbooks'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function getTotalFacilityprice()
    {
        try {
            $con = $this->opencon();
            $query = $con->prepare("SELECT SUM(total_price) AS Total_price
FROM (
    SELECT rooms.room_id, SUM(rooms.room_price) AS total_price
    FROM booking
    JOIN rooms ON booking.room_id = rooms.room_id
    GROUP BY rooms.room_id
) AS subquery;");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['Total_price'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function getTotalUseFacilityPrice()
    {
        try {
            $con = $this->opencon();
            $query = $con->prepare("SELECT rooms.room_id, rooms.room_name, SUM(rooms.room_price) AS total_facility_price

FROM booking
JOIN rooms ON booking.room_id = rooms.room_id
GROUP BY rooms.room_id, rooms.room_name;");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total_facility_price'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    

 

    function getPersonPrice()
    {
        try {
            $con = $this->opencon();
            $query = $con->prepare("SELECT SUM(pax) * 100 AS total_pax_x100 FROM booking;'");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total_pax_x100'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

function getTotalMembersCountWithType($account_type)
{
    try {
        $con = $this->opencon();
        $query = $con->prepare("SELECT COUNT(*) AS total FROM users WHERE account_type = ?");
        $query->execute([$account_type]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function TotalUseFacility()
{
    try {
        $con = $this->opencon();
        $query = $con->prepare("SELECT rooms.room_id, rooms.room_name, SUM(rooms.room_price) AS total_price
FROM booking
JOIN rooms ON booking.room_id = rooms.room_id
GROUP BY rooms.room_id, rooms.room_name;
");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total_price'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function Totalofall()
{
    try {
        $con = $this->opencon();
        $query = $con->prepare("
            WITH room_totals AS (
                SELECT
                    rooms.room_id,
                    rooms.room_name,
                    SUM(rooms.room_price) AS total_facility_price
                FROM booking
                JOIN rooms ON booking.room_id = rooms.room_id
                WHERE booking.Status = 'Approved'
                GROUP BY rooms.room_id, rooms.room_name
            ),
            total_pax AS (
                SELECT SUM(pax) * 100 AS total_pax_x100 
                FROM booking
                WHERE booking.Status = 'Approved'
            )
            SELECT
                SUM(room_totals.total_facility_price) + SUM(total_pax.total_pax_x100) AS totalofall
            FROM room_totals, total_pax;
        ");
        
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        // Ensure the correct key is used to fetch the result
        return $result['totalofall'];
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

}