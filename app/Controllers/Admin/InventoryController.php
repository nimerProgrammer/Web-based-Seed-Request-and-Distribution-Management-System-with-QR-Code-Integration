<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\InventoryModel;


class InventoryController extends BaseController
{
    public function saveInventory()
    {
        $inventoryModel = new InventoryModel();

        $data = [ 
            'seed_name' => $this->request->getPost( 'add_seed_name' ),
            'seed_class' => $this->request->getPost( 'add_seed_class' ),
            'stock' => $this->request->getPost( 'add_stock' ),
            'date_stored' => date( 'm-d-Y h:i A' ), // You can also get from input if needed
            'cropping_season_tbl_id' => $this->request->getPost( 'cropping_season_tbl_id' )
        ];

        $inventoryModel->insert( $data ); // Inserts into the database

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text' => 'Seed added successfully.',
            'icon' => 'success',
            'confirmButtonText' => 'OK' // <- custom button text
        ] );

        return redirect()->to( '/admin/inventory' );
    }

    public function updateInventory( $id )
    {
        $inventoryModel = new InventoryModel();

        $data = [ 
            'seed_name' => $this->request->getPost( 'edit_seed_name' ),
            'seed_class' => $this->request->getPost( 'edit_seed_class' ),
            'stock' => $this->request->getPost( 'edit_stock' ),
        ];


        $inventoryModel->update( $id, $data );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Updated!',
            'text' => 'Seed inventory updated successfully.',
            'icon' => 'success'
        ] );

        return redirect()->to( '/admin/inventory' );
    }


    public function deleteInventory( $id )
    {
        $inventoryModel = new \App\Models\InventoryModel();

        if ( $inventoryModel->delete( $id ) ) {
            session()->setFlashdata( 'swal', [ 
                'title' => 'Deleted!',
                'text' => 'Seed entry deleted successfully.',
                'icon' => 'success'
            ] );
        } else {
            session()->setFlashdata( 'swal', [ 
                'title' => 'Error!',
                'text' => 'Failed to delete the seed entry.',
                'icon' => 'error'
            ] );
        }

        return redirect()->to( '/admin/inventory' );
    }

}
