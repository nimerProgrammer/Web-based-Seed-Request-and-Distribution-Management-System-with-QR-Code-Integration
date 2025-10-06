<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class QrCodeController extends BaseController
{
    public function download()
    {
        $url = "https://oras-seed-request-distribution.com";  // Data to encode

        $logoPath = FCPATH . 'templates/img/Oras.png';

        $result = Builder::create()
            ->writer( new PngWriter() )
            ->data( $url )
            ->encoding( new Encoding( 'UTF-8' ) )
            ->errorCorrectionLevel( ErrorCorrectionLevel::High )
            ->size( 300 )
            ->margin( 10 )
            ->roundBlockSizeMode( RoundBlockSizeMode::Margin )
            ->logoPath( $logoPath )
            ->logoResizeToWidth( 50 )
            ->logoResizeToHeight( 50 )
            ->build();

        // Force download
        return $this->response
            ->setHeader( 'Content-Type', 'image/png' )
            ->setHeader( 'Content-Disposition', 'attachment; filename="qrcode.png"' )
            ->setBody( $result->getString() );
    }
}
