<?php
include 'db.php';

function findProductByBarcode($conn, $barcode)
{
    $stmt = $conn->prepare("SELECT * FROM masterlist WHERE barcode = ?");
    $stmt->bind_param("s", $barcode); // use "i" if barcode is INT
    $stmt->execute();

    $result = $stmt->get_result();
    $product = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;

    $stmt->close();
    return $product;
    
}


             
                                

