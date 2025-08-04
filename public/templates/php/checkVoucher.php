<?php
header( 'Content-Type: application/json' );

if ( !isset( $_GET[ 'code' ] ) ) {
    echo json_encode( [ 'error' => 'No code provided' ] );
    exit;
}

$code = $_GET[ 'code' ];

// ✅ Connect to DB
$conn = new mysqli( "localhost", "u796340262_omas_user", "@Omas_db123", "u796340262_omas_db" );

if ( $conn->connect_error ) {
    echo json_encode( [ 'error' => 'Database connection failed' ] );
    exit;
}

// ✅ Query using full code (no splitting yet)
$stmt = $conn->prepare( "SELECT status FROM beneficiaries WHERE qr_code = ?" );
$stmt->bind_param( "s", $code );
$stmt->execute();
$result = $stmt->get_result();

// ✅ If found, split the code just for the response
if ( $row = $result->fetch_assoc() ) {
    $parts = explode( '-', $code );

    // Build code parts only if format is valid
    if ( count( $parts ) >= 6 ) {
        $part1 = $parts[ 0 ];                             // e.g., "1st CROPPING 2025"
        $part2 = $parts[ 1 ];                             // e.g., "RC18 (Rice)Improved"
        $part3 = $parts[ 2 ];                             // e.g., "34343"
        $ref   = implode( '-', array_slice( $parts, 3 ) );  // e.g., "REF-08042025-XXXXXXX"

        echo json_encode( [ 
            'id'         => $row[ 'beneficiaries_tbl_id' ],
            'status'     => $row[ 'status' ],
            'code_parts' => [ 
                'part1' => $part1,
                'part2' => $part2,
                'part3' => $part3,
                'ref'   => $ref
            ]
        ] );
    } else {
        // Fallback: Return status only if code format is unexpected
        echo json_encode( [ 
            'status'     => $row[ 'status' ],
            'code_parts' => null,
            'note'       => 'Code format is invalid or incomplete.'
        ] );
    }

} else {
    echo json_encode( [ 'status' => 'Not Found' ] );
}

$conn->close();
