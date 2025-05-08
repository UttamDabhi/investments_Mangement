<?php
session_start();
require 'inc/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle Add Investment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $stmt = $pdo->prepare("INSERT INTO investments (user_id, type, amount, date, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $_POST['type'], $_POST['amount'], $_POST['date'], $_POST['location']]);
    header("Location: dashboard.php");
    exit();
}

// Handle Edit Investment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    $stmt = $pdo->prepare("UPDATE investments SET type=?, amount=?, date=?, location=? WHERE id=? AND user_id=?");
    $stmt->execute([$_POST['type'], $_POST['amount'], $_POST['date'], $_POST['location'], $_POST['id'], $user_id]);
    header("Location: dashboard.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM investments WHERE id=? AND user_id=?");
    $stmt->execute([$_GET['delete'], $user_id]);
    header("Location: dashboard.php");
    exit();
}

// Fetch All
$stmt = $pdo->prepare("SELECT * FROM investments WHERE user_id=? ORDER BY date DESC");
$stmt->execute([$user_id]);
$investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Investment Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary">Investment Dashboard</h2>
    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
  </div>

  <!-- Add Investment Form -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-primary text-white fw-semibold">Add New Investment</div>
    <div class="card-body">
      <form method="POST" class="row g-3">
        <input type="hidden" name="action" value="add">
        <div class="col-md-3">
          <select name="type" class="form-select" required>
            <option value="Fixed Deposit">Fixed Deposit</option>
            <option value="Property">Property</option>
            <option value="Stock">Stock</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" name="amount" class="form-control" placeholder="Amount" required>
        </div>
        <div class="col-md-2">
          <input type="date" name="date" class="form-control" required>
        </div>
        <div class="col-md-3">
          <input type="text" name="location" class="form-control" placeholder="Location / Symbol" required>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-success w-100">Add</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Investment Table -->
  <div class="card shadow">
    <div class="card-header bg-dark text-white fw-semibold">Your Investments</div>
    <div class="card-body">
      <table id="investmentTable" class="table table-bordered table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>Type</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Location</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($investments as $inv): ?>
          <tr>
            <form method="POST" class="align-middle">
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="id" value="<?= $inv['id'] ?>">
              <td>
                <select name="type" class="form-select form-select-sm">
                  <option value="Fixed Deposit" <?= $inv['type'] == 'Fixed Deposit' ? 'selected' : '' ?>>Fixed Deposit</option>
                  <option value="Property" <?= $inv['type'] == 'Property' ? 'selected' : '' ?>>Property</option>
                  <option value="Stock" <?= $inv['type'] == 'Stock' ? 'selected' : '' ?>>Stock</option>
                </select>
              </td>
              <td><input type="number" name="amount" value="<?= $inv['amount'] ?>" class="form-control form-control-sm"></td>
              <td><input type="date" name="date" value="<?= $inv['date'] ?>" class="form-control form-control-sm"></td>
              <td><input type="text" name="location" value="<?= htmlspecialchars($inv['location']) ?>" class="form-control form-control-sm"></td>
              <td>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-sm btn-primary">Update</button>
                  <a href="?delete=<?= $inv['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                </div>
              </td>
            </form>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
    $('#investmentTable').DataTable();
  });
</script>

</body>
</html>
