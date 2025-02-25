<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $expense_date = $_POST['expense_date'];

    // If category is 'vendor', get the vendor_id
    if ($category == 'petty_cash') {
        // Set vendor_id to 0 if category is petty_cash
        $vendor_id = 0;
    } else {
        // Otherwise, get the vendor_id from the POST data
        $vendor_id = $_POST['vendor_id'];
    }

    // Validate required fields
    if (empty($amount) || empty($description) || empty($category) || empty($expense_date)) {
        echo json_encode(["statusCode" => 400, "message" => "All fields are required"]);
        exit;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO tbl_expenses (amount, description, category, expense_date, vendor_id, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dsssi", $amount, $description, $category, $expense_date, $vendor_id);

    if ($stmt->execute()) {
        // Redirect to the expense page with a success message
        header("Location: ../manage_expenses.php?status=success");
        exit; // Ensure the script stops after redirection
    } else {
        // If the query fails, display an error message
        echo json_encode(["statusCode" => 500, "message" => "Error: " . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
