<?php
$con=mysqli_connect("localhost","root","","testing");
if(mysqli_connect_error()){
    echo"<script>alert('canot connect');</script>";
}
    else{
    echo"connected success";
    }
 

session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "testing";
$conn = new mysqli($host, $username, $password, $database);

// Ensure database connection works
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $usn = trim($_POST['usn']);
    $password = trim($_POST['password']); 
      // Regex pattern for USN validation
      $usn_pattern = "/^SG\d{2}BCA[0-9]{3}$/";

      // Validate USN format
      if (!preg_match($usn_pattern, $usn)) {
          die("Invalid USN format. It must be in the format SGxxBCAxxx, where x is a letter or digit.");
      }// Trim to remove any extra spaces

    if (empty($usn) || empty($password)) {
        die("USN and Password are required.");
    }

    // Prepare SQL query
    $query = "SELECT * FROM demo WHERE usn = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $usn); // Bind the USN parameter
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Debugging output
            //echo "Stored password: " . $user['password'] . "<br>";
           // echo "Entered password: " . $password . "<br>";

            // Plaintext or hashed password comparison
            //if ($password == $user['password'])
           /* if (password_verify($password, $user['password'])) { // Replace with `password_verify` if hashed
                echo "Login successful! Redirecting...";
                $_SESSION['user'] = $user; // Store user data in session
                header("Location: home.php");
                exit();
            } */
           // if (password_verify($password, $user['password']))
            if ($password == $user['password']) {
                echo "Login successful! Redirecting...";
                $_SESSION['user'] = $user; // Store user data in session
                header("Location: home.html");
                exit();
            }else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with this USN.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    if ($stmt) {
        $stmt->close(); // Close statement
    }
}
?>