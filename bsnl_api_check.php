<?php
$googleApiKey = 'AIzaSyAmP90skchAtlESn2MoO6vkBOMKKFpwtI0';

// Example address — replace or make dynamic as needed
$addressLatLong = '1600 Amphitheatre Parkway, Mountain View, CA';

// Construct API URL
$apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($addressLatLong) . "&key=" . $googleApiKey;

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Optional: skip SSL check for testing
$response = curl_exec($ch);

// Check for cURL error
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Get HTTP status code
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Decode API response
$data = json_decode($response, true);

// Output debug info
echo "<h3>API Debug Output</h3>";
echo "HTTP Code: $httpCode<br>";
echo "Raw Response: <pre>" . htmlspecialchars($response) . "</pre>";

// Check API status
if ($data['status'] === 'OK') {
    echo "<h4>Address:</h4> " . $data['results'][0]['formatted_address'];
} else {
    echo "<h4>API Error:</h4> " . $data['status'];
    if (!empty($data['error_message'])) {
        echo "<br><b>Message:</b> " . $data['error_message'];
    }
}
?>
