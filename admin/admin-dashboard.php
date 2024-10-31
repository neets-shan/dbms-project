<?php
session_start();
require "../include/database-connection.php";

// Ensure the user is logged in as admin
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin-login.php");
    exit;
}

echo "<h1>Welcome to the Admin Dashboard</h1>";

// Display Users
$result = mysqli_query($conn, "SELECT * FROM users");

echo "<h3>Users</h3>";
echo "<table border='1'>
      <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Date of Birth</th>
          <th>Actions</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['dob'] . "</td>";
    echo "<td>
            <a href='edit-user.php?id=" . $row['id'] . "'>Edit</a> | 
            <a href='delete-user.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>
          </td>";
    echo "</tr>";
}

echo "</table>";

// Display Expenses
$result = mysqli_query($conn, "SELECT * FROM expenses");

echo "<h3>Expenses</h3>";
echo "<table border='1'>
      <tr>
          <th>ID</th>
          <th>Date</th>
          <th>Item</th>
          <th>Price</th>
          <th>User Email</th>
          <th>Actions</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['date'] . "</td>";
    echo "<td>" . $row['item'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>
            <a href='edit-expense.php?id=" . $row['id'] . "'>Edit</a> | 
            <a href='delete-expense.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>
          </td>";
    echo "</tr>";
}

echo "</table>";
?>
