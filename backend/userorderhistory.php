
<?php

include("auth.php");

$host = "localhost";
$dbname = "smart_laundry";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_SESSION['user_id'];
    $firstName = $_SESSION['first_name'] ?? 'N/A';
    $lastName = $_SESSION['last_name'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :userId ORDER BY order_date DESC");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order History - Smart Laundry</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { margin: 0; display: flex; flex-direction: column; height: 100vh; background-color: #f5f7fa; }
    header { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #ddd; }
    header h1 { font-size: 16px; }
    header nav a { margin-left: 20px; text-decoration: none; color: #2c3e50; font-weight: 500; }
    nav a:hover { color: #039855; }
    .dashboard-container { display: flex; flex: 1; overflow: hidden; }
    .sidebar { width: 220px; background-color: #2c3e50; padding-top: 30px; display: flex; flex-direction: column; color: white; }
    .sidebar a { padding: 15px 30px; color: inherit; text-decoration: none; font-size: 15px; display: flex; align-items: center; gap: 10px; transition: background 0.3s; }
    .sidebar a:hover, .sidebar a.active { background-color: #34495e; }
    .main-content { flex: 1; padding: 40px 56px; overflow-y: auto; background: #fff; display: flex; flex-direction: column; }
    .main-heading h1 { font-size: 28px; font-weight: bold; margin-bottom: 20px; color: #8540cf; display: flex; align-items: center; gap: 10px; }
    .search-container { margin-bottom: 20px; max-width: 400px; }
    .search-container input { width: 100%; padding: 10px 15px; border-radius: 8px; border: 1.5px solid #ccc; font-size: 16px; transition: border-color 0.3s ease; }
    .search-container input:focus { outline: none; border-color: #486481; box-shadow: 0 0 8px #486481; }
    table { width: 100%; border-collapse: collapse; box-shadow: 0 2px 15px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden; background: white; }
    thead { background-color: #2c3e50; color: white; user-select: none; }
    thead th { padding: 16px 20px; text-align: left; font-weight: 600; font-size: 16px; cursor: pointer; position: relative; transition: background-color 0.3s ease; }
    thead th:hover { background-color: #6b2fb5; }
    thead th .sort-arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; opacity: 0.5; transition: opacity 0.3s; }
    thead th.sort-asc .sort-arrow { opacity: 1; transform: translateY(-50%) rotate(180deg); }
    thead th.sort-desc .sort-arrow { opacity: 1; }
    tbody tr { border-bottom: 1px solid #e0e0e0; transition: background-color 0.3s ease; }
    tbody tr:hover { background-color: #f9f0ff; }
    tbody td { padding: 14px 20px; font-size: 14px; color: #555; }
    .status { padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 13px; text-align: center; width: max-content; }
    .status.completed { background-color: #27ae60; color: white; }
    .status.pending { background-color: #f39c12; color: white; }
    .status.cancelled { background-color: #e74c3c; color: white; }
    .pagination { margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px; user-select: none; }
    .pagination button { background-color: #8540cf; border: none; color: white; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: background-color 0.3s ease; }
    .pagination button:disabled { background-color: #c9a9e4; cursor: not-allowed; }
    .pagination button:hover:not(:disabled) { background-color: #6b2fb5; }
    footer { background: #ffffff; padding: 20px 0; text-align: center; border-top: 1px solid #e1e4e8; font-size: 14px; color: #888; margin-top: auto; }
  </style>
</head>
<body>
<header>
  <h1>🧺 Smart Laundry Management System</h1>
  <nav>
    <a href="pricing.html">Pricing</a>
    <a href="blog.html">Blog</a>
    <a href="contact.html">Contact Us</a>
  </nav>
</header>
<div class="dashboard-container">
  <aside class="sidebar">
    <a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="profile.html"><i class="fas fa-user"></i> Profile</a>
    <a href="users_list.html"><i class="fa-solid fa-users"></i> Users List</a>
    <a href="orderdashboard.php" class="active"><i class="fas fa-history"></i> Order History</a>
    <a href="status.html"><i class="fa-solid fa-cash-register"></i> Payment History</a>
    <a href="status.html"><i class="fa-solid fa-list-check"></i> Reviews</a>
    <a href="settings.html"><i class="fas fa-cogs"></i> Settings</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
  </aside>
  <main class="main-content">
    <div class="main-heading">
      <h1><i class="fas fa-history"></i> Order History</h1>
    </div>
    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Search by Order ID or Customer Name..." />
    </div>
    <table id="orderTable">
      <thead>
        <tr>
          <th data-key="order-id">Order ID <span class="sort-arrow">&#9660;</span></th>
          <th data-key="order-date">Date <span class="sort-arrow">&#9660;</span></th>
          <th data-key="customer-name">Customer Name <span class="sort-arrow">&#9660;</span></th>
          <th data-key="product-type">Service <span class="sort-arrow">&#9660;</span></th>
          <th data-key="price">Total Amount <span class="sort-arrow">&#9660;</span></th>
          <th data-key="status">Status <span class="sort-arrow">&#9660;</span></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
<tr 
  data-order-id="<?= htmlspecialchars($order['order_id'] ?? 'N/A') ?>"
  data-order-date="<?= htmlspecialchars($order['order_date'] ?? 'N/A') ?>"
  data-customer-name="
  data-product-type="<?= htmlspecialchars($order['product_type'] ?? 'N/A') ?>"
  data-price="<?= htmlspecialchars($order['price'] ?? 0) ?>"
  data-status="<?= htmlspecialchars($order['status'] ?? 'Pending') ?>"
>
  <td data-label="Order ID">#<?= htmlspecialchars($order['order_id'] ?? 'N/A') ?></td>
  <td data-label="Date"><?= htmlspecialchars($order['order_date'] ?? 'N/A') ?></td>
  <td data-label="Customer Name"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></td>
  <td data-label="Service"><?= htmlspecialchars($order['product_type'] ?? 'N/A') ?></td>
  <td data-label="Total Amount">$<?= number_format($order['price'] ?? 0, 2) ?></td>
  <td data-label="Status">
    <span class="status <?= strtolower($order['status'] ?? 'pending') ?>">
      <?= htmlspecialchars($order['status'] ?? 'Pending') ?>
    </span>
  </td>
</tr>
<?php endforeach; ?>

        <?php else: ?>
          <tr><td colspan="6" class="no-results">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="pagination">
      <button id="prevPage" disabled>&laquo; Prev</button>
      <button id="nextPage" disabled>Next &raquo;</button>
    </div>
  </main>
</div>
<footer>&copy; 2025 Smart Laundry Management System. All rights reserved.</footer>
<script>
(() => {
  const rowsPerPage = 5;
  let currentPage = 1;
  const table = document.getElementById('orderTable');
  const tbody = table.querySelector('tbody');
  const allRows = Array.from(tbody.querySelectorAll('tr'));
  let filteredRows = [...allRows];
  const searchInput = document.getElementById('searchInput');
  const prevBtn = document.getElementById('prevPage');
  const nextBtn = document.getElementById('nextPage');
  let sortKey = null;
  let sortDirection = 'asc';

  function renderPage() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    filteredRows.forEach(row => row.style.display = 'none');
    filteredRows.slice(start, end).forEach(row => row.style.display = '');
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = end >= filteredRows.length;
  }

  function filterTable() {
    const query = searchInput.value.toLowerCase();
    filteredRows = allRows.filter(row =>
      row.innerText.toLowerCase().includes(query)
    );
    currentPage = 1;
    renderPage();
  }

  function compareRows(a, b, key, direction) {
    let valA = a.dataset[key];
    let valB = b.dataset[key];
    if (key === 'price') {
      valA = parseFloat(valA); valB = parseFloat(valB);
    } else if (key === 'orderDate') {
      valA = new Date(valA); valB = new Date(valB);
    } else {
      valA = valA.toLowerCase(); valB = valB.toLowerCase();
    }
    if (valA > valB) return direction === 'asc' ? 1 : -1;
    if (valA < valB) return direction === 'asc' ? -1 : 1;
    return 0;
  }

  function sortBy(key) {
    const attrKey = key.replace(/-/g, '');
    sortDirection = (sortKey === key && sortDirection === 'asc') ? 'desc' : 'asc';
    sortKey = key;
    filteredRows.sort((a, b) => compareRows(a, b, attrKey, sortDirection));
    currentPage = 1;
    renderPage();
    updateSortIcons();
  }

  function updateSortIcons() {
    document.querySelectorAll('thead th').forEach(th => {
      th.classList.remove('sort-asc', 'sort-desc');
      if (th.dataset.key === sortKey) {
        th.classList.add(sortDirection === 'asc' ? 'sort-asc' : 'sort-desc');
      }
    });
  }

  searchInput.addEventListener('input', filterTable);
  prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderPage(); } });
  nextBtn.addEventListener('click', () => { if (currentPage * rowsPerPage < filteredRows.length) { currentPage++; renderPage(); } });
  document.querySelectorAll('thead th').forEach(th => {
    th.addEventListener('click', () => sortBy(th.dataset.key));
  });

  renderPage();
})();
</script>
</body>
</html>
