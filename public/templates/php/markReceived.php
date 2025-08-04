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

$stmt = $conn->prepare( "UPDATE beneficiaries SET status = 'Received' WHERE beneficiaries_tbl_id = ?" );
$stmt->bind_param( "i", $id );

if ( $stmt->execute() ) {
    echo json_encode( [ 'success' => true, 'message' => 'Status updated to Received' ] );
} else {
    echo json_encode( [ 'success' => false, 'message' => 'Update failed' ] );
}

$conn->close();
