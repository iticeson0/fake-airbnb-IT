<?php

/* Add your functions here */

require_once(__DIR__ . '/../config/config.php');

function dbConnect(){
    /* defined in config/config.php */
    /*** connection credentials *******/
    $servername = SERVER;
    $username = USERNAME;
    $password = PASSWORD;
    $database = DATABASE;
    $dbport = PORT;
    /****** connect to database **************/

    try {
        $db = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4;port=$dbport", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // helpful for debugging
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
    return $db;
}

/* get the name of a neighborhood or room type by ID */
function getName($db, $table, $id) {
    $columnMap = [
        'neighborhoods' => 'neighborhood',
        'roomTypes' => 'type'
    ];

    $column = $columnMap[$table] ?? 'name'; // fallback to 'name' if not mapped

    $stmt = $db->prepare("SELECT $column FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: 'Any';
}

/* get full listing data (used for modal via AJAX) */
function getListingById($db, $id) {
    $sql = "
        SELECT 
            listings.id, listings.name, listings.price, listings.rating, listings.accommodates,
            listings.pictureUrl AS image_url,
            ANY_VALUE(neighborhoods.neighborhood) AS neighborhood,
            ANY_VALUE(roomTypes.type) AS room_type,
            ANY_VALUE(hosts.hostName) AS host,
            GROUP_CONCAT(amenities.amenity SEPARATOR ', ') AS amenities
        FROM listings
        JOIN neighborhoods ON listings.neighborhoodId = neighborhoods.id
        JOIN roomTypes ON listings.roomTypeId = roomTypes.id
        JOIN hosts ON listings.hostId = hosts.id
        LEFT JOIN listingAmenities ON listings.id = listingAmenities.listingID
        LEFT JOIN amenities ON listingAmenities.amenityID = amenities.id
        WHERE listings.id = :id
        GROUP BY listings.id
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>