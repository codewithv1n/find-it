<?php
session_start();
include ('../controllers/connect_db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} 

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Find-IT - Dashboard</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f2f2f2; color: #333; font-size: 14px; }

    header {
      background: #2c3e50;
      color: white;
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 { font-size: 18px; }
    header span { font-size: 12px; color: #aaa; }

    .container { padding: 20px; max-width: 1100px; margin: 0 auto; }

    .stats {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
    }
    .stat-card {
      flex: 1;
      background: white;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 14px 18px;
    }
    .stat-card .label { font-size: 12px; color: #888; margin-bottom: 6px; }
    .stat-card .number { font-size: 26px; font-weight: bold; }
    .stat-card.blue .number { color: #2980b9; }
    .stat-card.orange .number { color: #e67e22; }
    .stat-card.green .number { color: #27ae60; }
    .stat-card.gray .number { color: #7f8c8d; }

    .toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }
    .toolbar input[type="text"] {
      padding: 7px 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      width: 220px;
      font-size: 13px;
    }
    .toolbar select {
      padding: 7px 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      font-size: 13px;
      margin-left: 8px;
    }
    .toolbar button {
      padding: 7px 16px;
      background: #2c3e50;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      font-size: 13px;
    }
    .toolbar button:hover { background: #34495e; }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
    }
    thead { background: #ecf0f1; }
    th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #eee; font-size: 13px; }
    th { font-weight: bold; color: #555; font-size: 12px; text-transform: uppercase; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafafa; }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 10px;
      font-size: 11px;
      font-weight: bold;
    }
    .badge.lost { background: #fdecea; color: #c0392b; }
    .badge.found { background: #e9f7ef; color: #1e8449; }
    .badge.claimed { background: #eaf4fb; color: #2471a3; }

    .action-btn {
      padding: 4px 10px;
      font-size: 12px;
      border: 1px solid #ccc;
      background: white;
      border-radius: 3px;
      cursor: pointer;
      margin-right: 4px;
    }
    .action-btn:hover { background: #f0f0f0; }
    .action-btn.claim { border-color: #27ae60; color: #27ae60; }
    .action-btn.delete { border-color: #e74c3c; color: #e74c3c; }

    /* Modal */
    .modal-bg {
      display: none;
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.4);
      justify-content: center; align-items: center;
      z-index: 100;
    }
    .modal-bg.active { display: flex; }
    .modal {
      background: white;
      border-radius: 5px;
      padding: 24px;
      width: 400px;
      border: 1px solid #ccc;
    }
    .modal h2 { font-size: 16px; margin-bottom: 16px; }
    .modal label { display: block; font-size: 12px; margin-bottom: 4px; color: #555; }
    .modal input, .modal select, .modal textarea {
      width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;
      margin-bottom: 12px; font-size: 13px; font-family: Arial, sans-serif;
    }
    .modal textarea { resize: vertical; height: 70px; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 8px; }
    .modal-actions button {
      padding: 7px 16px; font-size: 13px; border-radius: 3px; cursor: pointer;
    }
    .btn-cancel { background: white; border: 1px solid #ccc; color: #333; }
    .btn-save { background: #2c3e50; color: white; border: none; }
    .btn-save:hover { background: #34495e; }

   .logout-btn {
      padding: 7px 16px;
      background: #e74c3c;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      font-size: 13px;
    }

    .logout-btn:hover { background: #c0392b; }

    .empty-msg { text-align: center; padding: 30px; color: #aaa; font-size: 13px; }
  </style>
</head>
<body>

<header>
  <h1>FIND IT</h1>
  <span id="date-display"></span>

  <div class="user-actions">
    <button onclick="logout()" class="logout-btn">Logout</button>
  </div>
</header>

<div class="container">

  <div class="stats">
    <div class="stat-card blue">
      <div class="label">Total Items</div>
      <div class="number" id="stat-total">0</div>
    </div>
    <div class="stat-card orange">
      <div class="label">Lost</div>
      <div class="number" id="stat-lost">0</div>
    </div>
    <div class="stat-card green">
      <div class="label">Found</div>
      <div class="number" id="stat-found">0</div>
    </div>
    <div class="stat-card gray">
      <div class="label">Claimed</div>
      <div class="number" id="stat-claimed">0</div>
    </div>
  </div>

  <div class="toolbar">
    <div>
      <input type="text" id="search-input" placeholder="Search item or location..." oninput="renderTable()" />
      <select id="filter-status" onchange="renderTable()">
        <option value="">All Status</option>
        <option value="Lost">Lost</option>
        <option value="Found">Found</option>
        <option value="Claimed">Claimed</option>
      </select>
    </div>
    <button onclick="openModal()">+ Add Item</button>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Item Name</th>
        <th>Category</th>
        <th>Location</th>
        <th>Date</th>
        <th>Reported By</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="table-body"></tbody>
  </table>

</div>

<!-- Modal -->
<div class="modal-bg" id="modal-bg">
  <div class="modal">
    <h2 id="modal-title">Add Item</h2>
    <label>Item Name</label>
    <input type="text" id="f-name" placeholder="e.g. Black Wallet" />
    <label>Category</label>
    <select id="f-category">
      <option>Electronics</option>
      <option>Clothing</option>
      <option>Accessories</option>
      <option>Documents</option>
      <option>Bag</option>
      <option>Keys</option>
      <option>Other</option>
    </select>
    <label>Location</label>
    <input type="text" id="f-location" placeholder="e.g. Building A Lobby" />
    <label>Reported By</label>
    <input type="text" id="f-reporter" placeholder="Name of reporter" />
    <label>Status</label>
    <select id="f-status">
      <option>Lost</option>
      <option>Found</option>
      <option>Claimed</option>
    </select>
    <label>Description (optional)</label>
    <textarea id="f-desc" placeholder="Additional details..."></textarea>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal()">Cancel</button>
      <button class="btn-save" onclick="saveItem()">Save</button>
    </div>
  </div>
</div>

<script>
  let items = [
    { id: 1, name: "Black Wallet", category: "Accessories", location: "Canteen", date: "2025-03-25", reporter: "Juan dela Cruz", status: "Lost" },
    { id: 2, name: "iPhone 13", category: "Electronics", location: "Library 2F", date: "2025-03-26", reporter: "Maria Santos", status: "Found" },
    { id: 3, name: "Blue Umbrella", category: "Other", location: "Main Entrance", date: "2025-03-27", reporter: "Jose Reyes", status: "Claimed" },
    { id: 4, name: "Student ID", category: "Documents", location: "Registrar", date: "2025-03-28", reporter: "Ana Lopez", status: "Found" },
    { id: 5, name: "Car Keys", category: "Keys", location: "Parking Lot B", date: "2025-03-29", reporter: "Carlos Bautista", status: "Lost" },
  ];
  let nextId = 6;
  let editingId = null;

  document.getElementById("date-display").textContent = new Date().toLocaleDateString("en-PH", { weekday: "long", year: "numeric", month: "long", day: "numeric" });

  function updateStats() {
    document.getElementById("stat-total").textContent = items.length;
    document.getElementById("stat-lost").textContent = items.filter(i => i.status === "Lost").length;
    document.getElementById("stat-found").textContent = items.filter(i => i.status === "Found").length;
    document.getElementById("stat-claimed").textContent = items.filter(i => i.status === "Claimed").length;
  }

  function renderTable() {
    const search = document.getElementById("search-input").value.toLowerCase();
    const filter = document.getElementById("filter-status").value;
    const tbody = document.getElementById("table-body");

    let filtered = items.filter(i => {
      const matchSearch = i.name.toLowerCase().includes(search) || i.location.toLowerCase().includes(search);
      const matchStatus = filter === "" || i.status === filter;
      return matchSearch && matchStatus;
    });

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="empty-msg">No items found.</td></tr>`;
      return;
    }

    tbody.innerHTML = filtered.map((item, idx) => `
      <tr>
        <td>${idx + 1}</td>
        <td>${item.name}</td>
        <td>${item.category}</td>
        <td>${item.location}</td>
        <td>${item.date}</td>
        <td>${item.reporter}</td>
        <td><span class="badge ${item.status.toLowerCase()}">${item.status}</span></td>
        <td>
          <button class="action-btn" onclick="editItem(${item.id})">Edit</button>
          ${item.status !== "Claimed" ? `<button class="action-btn claim" onclick="markClaimed(${item.id})">Claim</button>` : ""}
          <button class="action-btn delete" onclick="deleteItem(${item.id})">Delete</button>
        </td>
      </tr>
    `).join("");

    updateStats();
  }

  function openModal(prefill = null) {
    editingId = prefill ? prefill.id : null;
    document.getElementById("modal-title").textContent = prefill ? "Edit Item" : "Add Item";
    document.getElementById("f-name").value = prefill ? prefill.name : "";
    document.getElementById("f-category").value = prefill ? prefill.category : "Electronics";
    document.getElementById("f-location").value = prefill ? prefill.location : "";
    document.getElementById("f-reporter").value = prefill ? prefill.reporter : "";
    document.getElementById("f-status").value = prefill ? prefill.status : "Lost";
    document.getElementById("f-desc").value = prefill ? (prefill.desc || "") : "";
    document.getElementById("modal-bg").classList.add("active");
  }

  function closeModal() {
    document.getElementById("modal-bg").classList.remove("active");
    editingId = null;
  }

  function logout() {
    if (confirm("Are you sure you want to logout?")) {
      window.location.replace("../controllers/logout_process.php");
    } 
  }

  async function logoutUser() {
    const response = await fetch('logout.php');
    
    if (response.ok) {
        // Kapag tapos na ang PHP, linisin ang UI o i-redirect
        alert("Logged out successfully!");
        window.location.replace("login.php");
    }
}

  function saveItem() {
    const name = document.getElementById("f-name").value.trim();
    const location = document.getElementById("f-location").value.trim();
    const reporter = document.getElementById("f-reporter").value.trim();
    if (!name || !location || !reporter) { alert("Please fill in all required fields."); return; }

    const item = {
      id: editingId || nextId++,
      name,
      category: document.getElementById("f-category").value,
      location,
      reporter,
      status: document.getElementById("f-status").value,
      date: new Date().toISOString().split("T")[0],
      desc: document.getElementById("f-desc").value.trim()
    };

    if (editingId) {
      const idx = items.findIndex(i => i.id === editingId);
      items[idx] = item;
    } else {
      items.push(item);
    }

    closeModal();
    renderTable();
  }

  function editItem(id) {
    const item = items.find(i => i.id === id);
    if (item) openModal(item);
  }

  function markClaimed(id) {
    const item = items.find(i => i.id === id);
    if (item && confirm(`Mark "${item.name}" as Claimed?`)) {
      item.status = "Claimed";
      renderTable();
    }
  }

  function deleteItem(id) {
    const item = items.find(i => i.id === id);
    if (item && confirm(`Delete "${item.name}"?`)) {
      items = items.filter(i => i.id !== id);
      renderTable();
    }
  }

  document.getElementById("modal-bg").addEventListener("click", function(e) {
    if (e.target === this) closeModal();
  });

  renderTable();
</script>

</body>
</html>