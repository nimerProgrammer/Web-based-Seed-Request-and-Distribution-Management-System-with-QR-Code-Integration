<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostDescriptionModel;
use App\Models\PostImageModel;

class PublicPageController extends BaseController
{
    public function uploadPost()
    {
        helper( [ 'form', 'url' ] );

        $description = trim( $this->request->getPost( 'addDescription' ) );
        $images      = $this->request->getFiles()[ 'images' ] ?? [];

        $formattedDate = getPhilippineTimeFormatted();

        $postModel  = new PostDescriptionModel();
        $imageModel = new PostImageModel();

        $validImages = [];
        $hasInvalid  = false;

        // ✅ Validate all uploaded images
        if ( !empty( $images ) ) {
            foreach ( $images as $image ) {
                $extension = strtolower( $image->getClientExtension() );

                if (
                    !$image->isValid() ||
                    $image->hasMoved() ||
                    !in_array( $extension, [ 'jpg', 'jpeg' ] )
                ) {
                    $hasInvalid = true;
                    break; // Stop right away if even one image is invalid
                }

                $validImages[] = $image;
            }

            // 🚫 If any image is invalid, do not proceed
            if ( $hasInvalid ) {
                session()->setFlashdata( 'swal', [ 
                    'title' => 'Invalid Image',
                    'text'  => 'Only JPG or JPEG files are allowed. Please re-upload valid images.',
                    'icon'  => 'error',
                ] );
                return redirect()->back()->withInput();
            }
        }

        // ✅ Save post description
        $postID = $postModel->insert( [ 
            'description'  => $description,
            'created_at'   => $formattedDate,
            'updated_at'   => null,
            'users_tbl_id' => session()->get( 'user_id' ),
        ] );

        // ✅ Process valid images
        foreach ( $validImages as $image ) {
            $newName   = $image->getRandomName();
            $tempPath  = WRITEPATH . 'temp/' . $newName;
            $finalPath = FCPATH . 'uploads/images/' . $newName;

            $image->move( WRITEPATH . 'temp', $newName );

            $imageInfo = getimagesize( $tempPath );
            $width     = $imageInfo[ 0 ];
            $height    = $imageInfo[ 1 ];

            $imageService = \Config\Services::image()->withFile( $tempPath );

            if ( $width > 1920 || $height > 1080 ) {
                $imageService->resize( 1920, 1080, true );
            }

            $imageService->save( $finalPath );
            unlink( $tempPath );

            $imageModel->insert( [ 
                'image_path'              => 'uploads/images/' . $newName,
                'post_description_tbl_id' => $postID,
            ] );
        }

        session()->setFlashdata( 'swal', [ 
            'title' => 'Posted!',
            'text'  => 'Your post was uploaded successfully.',
            'icon'  => 'success',
        ] );

        return redirect()->back();
    }


}
