<?php
$servername = "localhost";
$username = "root"; // Default for XAMPP
$password = "";     // Default is empty
$database = "tb";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql_invoices = "CREATE TABLE IF NOT EXISTS `tb`.`invoices` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `invoice_no` VARCHAR(50) NOT NULL,
    `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `customer_name` VARCHAR(55) NOT NULL,
    `customer_mo` VARCHAR(15) NOT NULL,
    `salesman` VARCHAR(55) NOT NULL,
    `qty` INT(11) NOT NULL,
    `total_mrp` DECIMAL(10,2) NOT NULL,
    `total_discount` DECIMAL(10,2) NOT NULL,
    `payable_amount` DECIMAL(10,2) NOT NULL,
    `selected_offer` VARCHAR(55) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;";
mysqli_query($conn, $sql_invoices);
