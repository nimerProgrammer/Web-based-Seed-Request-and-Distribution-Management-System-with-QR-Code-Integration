<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BeneficiariesModel;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Label;

class SentRequestsController extends BaseController
{
    public function downloadVoucher()
    {
        $requestID = $this->request->getPost( 'seed_requests_tbl_id' );

        $beneficiariesModel = new BeneficiariesModel();
        $inventoryModel     = new InventoryModel();

        $beneficiary = $beneficiariesModel
            ->select( '
            beneficiaries.*,
            seed_requests.seed_requests_tbl_id,
            seed_requests.inventory_tbl_id,
            inventory.seed_class,
            inventory.seed_name,
            client_info.brgy,
            client_info.mun
        ' )
            ->join( 'seed_requests', 'seed_requests.seed_requests_tbl_id = beneficiaries.seed_requests_tbl_id', 'left' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id', 'left' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id', 'left' )
            ->where( 'beneficiaries.seed_requests_tbl_id', $requestID )
            ->first();

        if ( !$beneficiary ) {
            return $this->response->setStatusCode( 404 )->setBody( 'Beneficiary not found.' );
        }

        $fullName      = strtoupper( session()->get( 'public_user_fullname' ) );
        $address       = strtoupper( $beneficiary[ 'brgy' ] . ', ' . $beneficiary[ 'mun' ] );
        $seedType      = strtoupper( $beneficiary[ 'seed_class' ] );
        $seedName      = strtoupper( $beneficiary[ 'seed_name' ] );
        $ref_no        = $beneficiary[ 'ref_no' ];
        $season        = session()->get( 'current_season_name' );
        $dateGenerated = date( 'F j, Y' );

        // Generate QR code
        $qrData = "$season\n$ref_no";
        $qr     = Builder::create()
            ->writer( new PngWriter() )
            ->writerOptions( [] )
            ->data( $qrData )
            ->encoding( new Encoding( 'UTF-8' ) )
            ->size( 200 )
            ->margin( 1 )
            ->build();

        $qrImg = imagecreatefromstring( $qr->getString() );

        // Final image
        $width   = 750;
        $height  = 310;
        $voucher = imagecreatetruecolor( $width, $height );

        // Colors
        $white = imagecolorallocate( $voucher, 255, 255, 255 );
        $black = imagecolorallocate( $voucher, 0, 0, 0 );
        $green = imagecolorallocate( $voucher, 0, 114, 54 );

        // Fill background
        imagefill( $voucher, 0, 0, $white );

        // Draw border
        imagerectangle( $voucher, 0, 0, $width - 1, $height - 1, $black );

        // Load font
        $font = FCPATH . 'assets/fonts/arial.ttf';
        if ( !file_exists( $font ) ) {
            return $this->response->setBody( 'Font file not found.' );
        }

        // Load and draw the logo
        $logoPath = FCPATH . 'templates/img/icon.png';
        if ( file_exists( $logoPath ) ) {
            $logoImg     = imagecreatefrompng( $logoPath );
            $logoSize    = 65;
            $logoResized = imagecreatetruecolor( $logoSize, $logoSize );
            imagealphablending( $logoResized, false );
            imagesavealpha( $logoResized, true );
            imagecopyresampled( $logoResized, $logoImg, 0, 0, 0, 0, $logoSize, $logoSize, imagesx( $logoImg ), imagesy( $logoImg ) );
            imagecopy( $voucher, $logoResized, 20, 25, 0, 0, $logoSize, $logoSize );
            imagedestroy( $logoImg );
            imagedestroy( $logoResized );
        }

        // Top text
        imagettftext( $voucher, 14, 0, 90, 50, $green, $font, 'Republic of the Philippines' );
        imagettftext( $voucher, 14, 0, 90, 75, $green, $font, 'DEPARTMENT OF AGRICULTURE' );

        // Fields
        $left       = 40;
        $top        = 130;
        $lineHeight = 30;

        imagettftext( $voucher, 12, 0, $left, $top, $black, $font, 'Recipient Name:' );
        imagettftext( $voucher, 12, 0, $left + 140, $top, $black, $font, $fullName );

        $top += $lineHeight;
        imagettftext( $voucher, 12, 0, $left, $top, $black, $font, 'Address:' );
        imagettftext( $voucher, 12, 0, $left + 140, $top, $black, $font, $address );

        $top += $lineHeight;
        imagettftext( $voucher, 12, 0, $left, $top, $black, $font, 'Intervention:' );
        imagettftext( $voucher, 12, 0, $left + 140, $top, $black, $font, "$seedName SEEDS — $seedType" );

        $top += $lineHeight;
        imagettftext( $voucher, 12, 0, $left, $top, $black, $font, 'Date Generated:' );
        imagettftext( $voucher, 12, 0, $left + 140, $top, $black, $font, $dateGenerated );

        // Footer
        imagettftext( $voucher, 14, 0, 200, $height - 35, $black, $font, 'Certified Voucher — Seed Request Program' );

        // QR Code
        imagecopy( $voucher, $qrImg, $width - 230, 30, 0, 0, imagesx( $qrImg ), imagesy( $qrImg ) );

        // Output
        ob_start();
        imagepng( $voucher );
        $imageData = ob_get_clean();

        imagedestroy( $voucher );
        imagedestroy( $qrImg );

        return $this->response
            ->setHeader( 'Content-Type', 'image/png' )
            ->setHeader( 'Content-Disposition', 'attachment; filename="' . $seedName . ' — seeds — voucher_' . $requestID . '.png"' )
            ->setBody( $imageData );
    }

}
