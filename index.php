<?php
require_once 'src/functions.php';
$db = dbConnect();

// Use correct column names based on your schema
$neighborhoods = $db->query("SELECT id, neighborhood FROM neighborhoods ORDER BY neighborhood")->fetchAll(PDO::FETCH_ASSOC);
$roomTypes = $db->query("SELECT id, type FROM roomTypes ORDER BY type")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Fake Airbnb Search</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
  <div class="container my-5">
    <h1 class="mb-4">Search for listings in the Portland area:</h1>

    <form action="results.php" method="GET" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="neighborhood" class="form-label">Neighborhood</label>
        <select name="neighborhood" id="neighborhood" class="form-select">
          <option value="">Any</option>
          <?php foreach ($neighborhoods as $n): ?>
            <option value="<?= htmlspecialchars($n['id']) ?>"><?= htmlspecialchars($n['neighborhood']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="room_type" class="form-label">Room Type</label>
        <select name="room_type" id="room_type" class="form-select">
          <option value="">Any</option>
          <?php foreach ($roomTypes as $r): ?>
            <option value="<?= htmlspecialchars($r['id']) ?>"><?= htmlspecialchars($r['type']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="guests" class="form-label">Number of Guests</label>
        <select name="guests" id="guests" class="form-select">
          <?php foreach (range(1, 10) as $g): ?>
            <option value="<?= $g ?>"><?= $g ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Search</button>
    </form>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>