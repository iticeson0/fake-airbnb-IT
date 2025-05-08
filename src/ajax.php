<?php
require_once 'functions.php';
$db = dbConnect();

// Check if 'id' is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing listing ID"]);
    exit;
}

$id = $_GET['id'];

// Fetch listing data using reusable function
$listing = getListingById($db, $id);

// Handle case where listing is not found
if (!$listing) {
    http_response_code(404);
    echo json_encode(["error" => "Listing not found"]);
    exit;
}

// Return listing data as JSON
header('Content-Type: application/json');
echo json_encode($listing);