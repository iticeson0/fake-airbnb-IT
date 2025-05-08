<?php
require_once 'src/functions.php';
$db = dbConnect();

$neighborhood_id = $_GET['neighborhood'] ?? '';
$room_type_id = $_GET['room_type'] ?? '';
$guests = $_GET['guests'] ?? 1;

// Get readable names using correct columns
$neighborhood_name = $neighborhood_id ? getName($db, 'neighborhoods', $neighborhood_id) : 'Any';
$room_type_name = $room_type_id ? getName($db, 'roomTypes', $room_type_id) : 'Any';

$sql = "
SELECT 
    listings.id, listings.name, listings.price, listings.rating, listings.accommodates,
    listings.pictureUrl AS image_url
FROM listings
WHERE 1=1
";

$params = [];
if ($neighborhood_id) {
    $sql .= " AND listings.neighborhoodId = :neighborhood_id";
    $params[':neighborhood_id'] = $neighborhood_id;
}
if ($room_type_id) {
    $sql .= " AND listings.roomTypeId = :room_type_id";
    $params[':room_type_id'] = $room_type_id;
}
if ($guests) {
    $sql .= " AND listings.accommodates >= :guests";
    $params[':guests'] = $guests;
}

$sql .= " ORDER BY listings.rating DESC LIMIT 20";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Fake Airbnb Results</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
  <link rel="icon" href="images/house-heart-fill.svg">
  <link rel="mask-icon" href="images/house-heart-fill.svg" color="#000000">   
</head>
<body>

<header>
  <div class="collapse bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">About</h4>
          <p class="text-muted">Fake Airbnb. Data c/o http://insideairbnb.com/get-the-data/</p>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="index.php" class="navbar-brand d-flex align-items-center">
        <i class="bi bi-house-heart-fill my-2"></i>    
        <strong> Fake Airbnb</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</header>

<main>
  <div class="container my-4">
    <h1>Results (<?= count($listings) ?>)</h1>
    <p><strong>Neighborhood:</strong> <?= htmlspecialchars($neighborhood_name) ?></p>
    <p><strong>Room Type:</strong> <?= htmlspecialchars($room_type_name) ?></p>
    <p><strong>Accommodates:</strong> <?= htmlspecialchars($guests) ?></p>

    <?php if (count($listings) === 0): ?>
      <div class="alert alert-warning mt-4">
        No results found — <a href="index.php">search again</a>.
      </div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
      <?php foreach ($listings as $listing): ?>
        <div class="col">
          <div class="card shadow-sm">
            <img src="<?= htmlspecialchars($listing['image_url']) ?>" class="card-img-top" alt="Listing image">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($listing['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($neighborhood_name) ?> neighborhood</p>
              <p class="card-text"><?= htmlspecialchars($room_type_name) ?> room type</p>
              <p class="card-text">Accommodates <?= htmlspecialchars($listing['accommodates']) ?></p>
              <p class="card-text">
                <i class="bi bi-star-fill"></i> <?= htmlspecialchars($listing['rating']) ?>
              </p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" 
                          id="<?= $listing['id'] ?>" 
                          class="btn btn-sm btn-outline-secondary viewListing"
                          data-bs-toggle="modal" 
                          data-bs-target="#modal<?= $listing['id'] ?>">
                    View
                  </button>
                </div>
                <small class="text-muted">$<?= number_format($listing['price'], 2) ?></small>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal (updated layout) -->
        <div class="modal fade modal-lg" id="modal<?= $listing['id'] ?>" tabindex="-1" aria-labelledby="modal<?= $listing['id'] ?>Label" aria-modal="true" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <img src="" class="img-fluid mb-3" alt="Listing image">
              </div>
              <div class="modal-footer flex-column align-items-start text-start">
                <!-- Filled by script.js -->
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Load Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Load your custom JS LAST -->
<script src="js/script.js" type="application/javascript"></script>

</body>
</html>