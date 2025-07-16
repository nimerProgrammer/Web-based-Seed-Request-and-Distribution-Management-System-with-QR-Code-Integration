<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostDescriptionModel;
use App\Models\PostImageModel;

class PublicPageController extends BaseController
{
    /**
     * Handles the upload of a new post with images.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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

        // ✅ Save post description
        $postID = $postModel->insert( [ 
            'description'  => $description,
            'created_at'   => $formattedDate,
            'updated_at'   => null,
            'users_tbl_id' => session()->get( 'user_id' ),
        ] );

        // ✅ Validate all uploaded images
        // ✅ Validate all uploaded images (only if there are valid files)
        if ( is_array( $images ) && count( array_filter( $images, fn( $img ) => $img->isValid() && !$img->hasMoved() ) ) > 0 ) {
            foreach ( $images as $image ) {
                $extension = strtolower( $image->getClientExtension() );

                if (
                    !$image->isValid() ||
                    $image->hasMoved() ||
                    !in_array( $extension, [ 'jpg', 'jpeg', 'png' ] )
                ) {
                    $hasInvalid = true;
                    break; // Stop right away if even one image is invalid
                }

                $validImages[] = $image;
            }

            if ( $hasInvalid ) {
                session()->setFlashdata( 'swal', [ 
                    'title' => 'Invalid Image',
                    'text'  => 'Only JPG or JPEG files are allowed. Please re-upload valid images.',
                    'icon'  => 'error',
                ] );
                return redirect()->back()->withInput();
            }

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
        }




        session()->setFlashdata( 'swal', [ 
            'title' => 'Posted!',
            'text'  => 'Your post was uploaded successfully.',
            'icon'  => 'success',
        ] );

        return redirect()->back();
    }

    /**
     * Deletes a post by ID.
     *
     * @param int $id The ID of the post to delete.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deletePost( $id )
    {
        $postModel  = new PostDescriptionModel();
        $imageModel = new PostImageModel();

        // Optional: delete related images
        $imageModel->where( 'post_description_tbl_id', $id )->delete();

        // Delete the post itself
        $postModel->delete( $id );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Deleted!',
            'text'  => 'Post deleted successfully.',
            'icon'  => 'success',
        ] );
        // Redirect back
        return redirect()->back();
    }

    /**
     * Updates a post description.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updatePost()
    {
        $id          = trim( $this->request->getPost( 'descriptionID' ) );
        $description = trim( $this->request->getPost( 'editDescription' ) );

        $postModel = new PostDescriptionModel();


        if ( empty( $id ) || empty( $description ) ) {
            session()->setFlashdata( 'swal', [ 
                'title' => 'Missing Data!',
                'text'  => 'Description or ID is missing.',
                'icon'  => 'warning',
            ] );
            return redirect()->back();
        }

        $updated = $postModel->update( $id, [ 'description' => $description ] );

        if ( $updated ) {
            session()->setFlashdata( 'swal', [ 
                'title' => 'Updated!',
                'text'  => 'Post updated successfully.',
                'icon'  => 'success',
            ] );
        } else {
            session()->setFlashdata( 'swal', [ 
                'title' => 'Update Failed!',
                'text'  => 'Could not update the post.',
                'icon'  => 'error',
            ] );
        }

        return redirect()->back();
    }

}
