<?php
if (isset($_POST['mobile'])) {
    $mobile = $_POST['mobile'];

    include 'db.php'; // or your DB connection code

    $stmt = $conn->prepare("SELECT customer_name FROM invoices WHERE customer_mo = ? LIMIT 1");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $stmt->bind_result($name);

    if ($stmt->fetch()) {

        echo $name;
    }
    $stmt->close();
    $conn->close();
}
