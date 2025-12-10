<?php
header( 'Content-Type: application/json' );

if ( !isset( $_GET[ 'id' ] ) ) {
    echo json_encode( [ 'success' => false, 'message' => 'No ID provided' ] );
    exit;
}

$id = $_GET[ 'id' ];

$conn = new mysqli( "localhost", "u796340262_omas_user", "@Omas_db123", "u796340262_omas_db" );

if ( $conn->connect_error ) {
    echo json_encode( [ 'success' => false, 'message' => 'Database connection failed' ] );
    exit;
}
/* ---------------------------
   1. Get Beneficiary + KG + Inventory ID
---------------------------- */
$sql  = "SELECT b.*, sr.*, i.*, ci.*
        FROM beneficiaries b
        JOIN seed_requests sr ON sr.seed_requests_tbl_id = b.seed_requests_tbl_id
        JOIN inventory i ON i.inventory_tbl_id = sr.inventory_tbl_id
        JOIN client_info ci ON ci.client_info_tbl_id = sr.client_info_tbl_id
        WHERE b.beneficiaries_tbl_id = ?";
$stmt = $conn->prepare( $sql );
$stmt->bind_param( "i", $id );
$stmt->execute();
$result      = $stmt->get_result();
$beneficiary = $result->fetch_assoc();

if ( !$beneficiary ) {
    echo json_encode( [ 'success' => false, 'message' => 'Beneficiary not found' ] );
    exit;
}
function getPhilippineTimeFormatted( $format = 'm-d-Y h:i:s A' )
{
    $philTime = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
    return $philTime->format( $format );
}

$inventoryId   = $beneficiary[ 'inventory_tbl_id' ];
$kg            = (float) $beneficiary[ 'kg' ];
$rsbsa         = $beneficiary[ 'rsbsa_ref_no' ];
$fullName      = trim( $beneficiary[ 'first_name' ] . " " . $beneficiary[ 'middle_name' ] . " " . $beneficiary[ 'last_name' ] . " " . $beneficiary[ 'suffix_and_ext' ] );
$formattedDate = getPhilippineTimeFormatted();

/* ---------------------------
   2. Update Inventory (distributed + kg)
---------------------------- */
$sql  = "UPDATE inventory SET distributed = IFNULL(distributed, 0) + ? WHERE inventory_tbl_id = ?";
$stmt = $conn->prepare( $sql );
$stmt->bind_param( "di", $kg, $inventoryId );

if ( !$stmt->execute() ) {
    echo json_encode( [ 'success' => false, 'message' => 'Failed to update inventory' ] );
    exit;
}

/* ---------------------------
   3. Update Beneficiary (status + date_time_received)
---------------------------- */
$sql  = "UPDATE beneficiaries 
        SET status = 'Received', date_time_received = ? 
        WHERE beneficiaries_tbl_id = ?";
$stmt = $conn->prepare( $sql );
$stmt->bind_param( "si", $formattedDate, $id );

if ( !$stmt->execute() ) {
    echo json_encode( [ 'success' => false, 'message' => 'Failed to update beneficiary' ] );
    exit;
}

/* ---------------------------
   4. Insert Logs
---------------------------- */
$staffFullName = 'OMAS QR Code Scanner';
$userId        = Null;
$logDetails    = $staffFullName . ' marked beneficiary "' . $fullName . '" (RSBSA: ' . $rsbsa . ') as received.';

$sql  = "INSERT INTO logs (timestamp, action, details, users_tbl_id) VALUES (?, 'Marked as Received', ?, ?)";
$stmt = $conn->prepare( $sql );
$stmt->bind_param( "ssi", $formattedDate, $logDetails, $userId );

if ( !$stmt->execute() ) {
    echo json_encode( [ 'success' => false, 'message' => 'Failed to insert log' ] );
    exit;
}

/* ---------------------------
   ✅ Final Success
---------------------------- */
echo json_encode( [ 'success' => true, 'message' => 'Beneficiary marked as received successfully.' ] );

$conn->close();
