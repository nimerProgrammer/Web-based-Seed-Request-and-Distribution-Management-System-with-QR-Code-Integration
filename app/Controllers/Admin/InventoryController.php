<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\InventoryModel;
use App\Models\LogsModel;

class InventoryController extends BaseController
{
    /**
     * Saves a new inventory item.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function saveInventory()
    {
        $inventoryModel = new InventoryModel();
        $logsModel      = new LogsModel();

        // Get Philippine time
        $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
        $formattedDate = $philTime->format( 'm-d-Y h:i A' );

        // Collect form data
        $data = [ 
            'seed_name'              => $this->request->getPost( 'add_seed_name' ),
            'seed_class'             => $this->request->getPost( 'add_seed_class' ),
            'stock'                  => $this->request->getPost( 'add_stock' ),
            'date_stored'            => $formattedDate,
            'cropping_season_tbl_id' => $this->request->getPost( 'cropping_season_tbl_id' )
        ];

        $inventoryModel->insert( $data ); // Insert into the database

        // Build user's full name (handle null middle name and suffix)
        $fullName = ucwords( strtolower( trim(
            session( 'user_firstname' ) . ' ' .
            ( session( 'user_middlename' ) ? session( 'user_middlename' ) . ' ' : '' ) .
            session( 'user_lastname' ) .
            ( session( 'user_suffix_and_ext' ) ? ' ' . session( 'user_suffix_and_ext' ) : '' )
        ) ) );

        // Insert log entry
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Add Inventory',
            'details'      => $fullName . ' added new inventory: "' . $data[ 'seed_name' ] . '" (' . $data[ 'seed_class' ] . ') with stock ' . $data[ 'stock' ] . '.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title'             => 'Success!',
            'text'              => 'Seed added successfully.',
            'icon'              => 'success',
            'confirmButtonText' => 'OK'
        ] );

        return redirect()->to( '/admin/inventory' );
    }


    /**
     * Updates an existing inventory item by ID.
     *
     * @param int $id The ID of the inventory item to update.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateInventory( $id )
    {
        $inventoryModel = new InventoryModel();
        $logsModel      = new LogsModel();

        // Get current inventory data from DB
        $original = $inventoryModel->find( $id );

        // New data from form
        $newData = [ 
            'seed_name'  => $this->request->getPost( 'edit_seed_name' ),
            'seed_class' => $this->request->getPost( 'edit_seed_class' ),
            'stock'      => $this->request->getPost( 'edit_stock' ),
        ];

        // Check changes
        $changes = [];
        foreach ( $newData as $key => $value ) {
            if ( $original[ $key ] != $value ) {
                $changes[] = ucfirst( str_replace( '_', ' ', $key ) ) . ' changed from "' . $original[ $key ] . '" to "' . $value . '"';
            }
        }

        // Update only if something changed
        if ( !empty( $changes ) ) {
            $inventoryModel->update( $id, $newData );

            // Prepare log entry
            $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
            $formattedDate = $philTime->format( 'm-d-Y h:i A' );

            $fullName = ucwords( strtolower( trim(
                session( 'user_firstname' ) . ' ' .
                ( session( 'user_middlename' ) ? session( 'user_middlename' ) . ' ' : '' ) .
                session( 'user_lastname' ) .
                ( session( 'user_suffix_and_ext' ) ? ' ' . session( 'user_suffix_and_ext' ) : '' )
            ) ) );

            $logsModel->insert( [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Update Inventory',
                'details'      => $fullName . ' updated inventory: ' . implode( '; ', $changes ) . '.',
                'users_tbl_id' => session( 'user_id' ),
            ] );

            session()->setFlashdata( 'swal', [ 
                'title' => 'Updated!',
                'text'  => 'Seed inventory updated successfully.',
                'icon'  => 'success'
            ] );
        } else {
            session()->setFlashdata( 'swal', [ 
                'title' => 'No Changes',
                'text'  => 'No changes were made to the inventory.',
                'icon'  => 'info'
            ] );
        }

        return redirect()->to( '/admin/inventory' );
    }

    /**
     * Deletes an inventory item by ID.
     *
     * @param int $id The ID of the inventory item to delete.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteInventory( $id )
    {
        $inventoryModel = new InventoryModel();
        $logsModel      = new LogsModel();

        $seed = $inventoryModel->find( $id ); // Get before deleting

        if ( $inventoryModel->delete( $id ) ) {
            $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
            $formattedDate = $philTime->format( 'm-d-Y h:i A' );

            $fullName = ucwords( strtolower( trim(
                session( 'user_firstname' ) . ' ' .
                ( session( 'user_middlename' ) ? session( 'user_middlename' ) . ' ' : '' ) .
                session( 'user_lastname' ) .
                ( session( 'user_suffix_and_ext' ) ? ' ' . session( 'user_suffix_and_ext' ) : '' )
            ) ) );

            $logsModel->insert( [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Delete Inventory',
                'details'      => $fullName . ' deleted seed "' . ( $seed[ 'seed_name' ] ?? 'Unknown' ) . '" (' . ( $seed[ 'seed_class' ] ?? '-' ) . ').',
                'users_tbl_id' => session( 'user_id' ),
            ] );

            session()->setFlashdata( 'swal', [ 
                'title' => 'Deleted!',
                'text'  => 'Seed entry deleted successfully.',
                'icon'  => 'success'
            ] );
        } else {
            session()->setFlashdata( 'swal', [ 
                'title' => 'Error!',
                'text'  => 'Failed to delete the seed entry.',
                'icon'  => 'error'
            ] );
        }

        return redirect()->to( '/admin/inventory' );
    }


}
