<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CroppingSeasonModel;
use App\Models\LogsModel;
use \DateTime;
class DashboardController extends BaseController
{
    /**
     * Checks if a cropping season already exists.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function checkSeasonExists()
    {
        $seasonName = $this->request->getPost( 'season_name' );
        $seasonYear = $this->request->getPost( 'season_year' );

        $model = new CroppingSeasonModel();

        $exists = $model
            ->where( 'season', $seasonName )
            ->where( 'year', $seasonYear )
            ->countAllResults() > 0;

        return $this->response->setJSON( [ 'exists' => $exists ] );
    }

    /**
     * Checks if the start date conflicts with existing cropping seasons.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function checkStartDateConflict()
    {
        $startDateInput = $this->request->getPost( 'season_start_date' );
        $convertedDate  = date( 'm-d-Y', strtotime( $startDateInput ) );

        $startDateObj   = DateTime::createFromFormat( 'm-d-Y', $convertedDate );
        $startTimestamp = $startDateObj ? $startDateObj->getTimestamp() : false;

        $model = new CroppingSeasonModel();
        $rows  = $model->findAll();


        foreach ( $rows as $row ) {
            $endDateRaw = $row[ 'date_end' ];

            $endDateObj   = DateTime::createFromFormat( 'm-d-Y', $endDateRaw );
            $endTimestamp = $endDateObj ? $endDateObj->getTimestamp() : false;

            if ( $endTimestamp >= $startTimestamp ) {
                $conflict = true;

                break;
            }
        }

        return $this->response->setJSON( [ 
            'conflict' => $conflict,
        ] );
    }

    /**
     * Handles the creation of a new cropping season.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function newSeason()
    {
        $model = new CroppingSeasonModel();

        $seasonName = $this->request->getPost( 'season_name' );
        $seasonYear = $this->request->getPost( 'season_year' );
        $dateStart  = date( 'm-d-Y', strtotime( $this->request->getPost( 'season_start_date' ) ) );
        $dateEnd    = date( 'm-d-Y', strtotime( $this->request->getPost( 'season_end_date' ) ) );

        $data = [ 
            'season'     => $seasonName,
            'year'       => $seasonYear,
            'date_start' => $dateStart,
            'date_end'   => $dateEnd,
            'status'     => 'Ongoing',
        ];

        $model->insert( $data );

        // Logging
        $logsModel     = new LogsModel();
        $formattedDate = getPhilippineTimeFormatted();
        $staffFullName = session( 'user_fullname' );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Add Cropping Season',
            'details'      => $staffFullName . ' added a new cropping season: "' . $seasonName . '" (' . $seasonYear . ') from ' . $dateStart . ' to ' . $dateEnd . '.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'New cropping season added successfully.',
            'icon'  => 'success',
        ] );

        return redirect()->to( base_url( 'admin/dashboard' ) );
    }

    /**
     * Checks if a cropping season already exists for editing.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editCheckSeasonExists()
    {
        $name      = $this->request->getPost( 'season_name' );
        $year      = $this->request->getPost( 'season_year' );
        $excludeId = $this->request->getPost( 'cropping_season_tbl_id' );

        $model = new CroppingSeasonModel();

        $exists = $model
            ->where( 'season', $name )
            ->where( 'year', $year )
            ->where( 'cropping_season_tbl_id !=', $excludeId ) // 👈 exclude self
            ->countAllResults() > 0;

        return $this->response->setJSON( [ 'exists' => $exists ] );
    }

    /**
     * Checks if the start date conflicts with existing cropping seasons for editing.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editCheckStartDateConflict()
    {
        $startDateInput = $this->request->getPost( 'season_start_date' );
        $currentId      = $this->request->getPost( 'cropping_season_tbl_id' ); // Get current ID

        $convertedDate  = date( 'm-d-Y', strtotime( $startDateInput ) );
        $startDateObj   = DateTime::createFromFormat( 'm-d-Y', $convertedDate );
        $startTimestamp = $startDateObj ? $startDateObj->getTimestamp() : false;

        $model = new CroppingSeasonModel();
        $rows  = $model->findAll();

        $conflict = false;

        foreach ( $rows as $row ) {
            // Exclude the current record
            if ( $row[ 'cropping_season_tbl_id' ] == $currentId ) {
                continue;
            }

            $endDateRaw   = $row[ 'date_end' ];
            $endDateObj   = DateTime::createFromFormat( 'm-d-Y', $endDateRaw );
            $endTimestamp = $endDateObj ? $endDateObj->getTimestamp() : false;

            if ( $endTimestamp >= $startTimestamp ) {
                $conflict = true;
                break;
            }
        }

        return $this->response->setJSON( [ 
            'conflict' => $conflict,
        ] );
    }

    /**
     * Updates an existing cropping season.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateSeason()
    {
        $model = new CroppingSeasonModel();

        $id         = $this->request->getPost( 'cropping_season_tbl_id' );
        $seasonName = $this->request->getPost( 'season_name' );
        $seasonYear = $this->request->getPost( 'season_year' );
        $dateStart  = date( 'm-d-Y', strtotime( $this->request->getPost( 'season_start_date' ) ) );
        $dateEnd    = date( 'm-d-Y', strtotime( $this->request->getPost( 'season_end_date' ) ) );

        $data = [ 
            'season'     => $seasonName,
            'year'       => $seasonYear,
            'date_start' => $dateStart,
            'date_end'   => $dateEnd,
            'status'     => 'Ongoing',
        ];

        // Update the record
        $model->update( $id, $data );

        // Logging
        $logsModel     = new LogsModel();
        $formattedDate = getPhilippineTimeFormatted();
        $staffFullName = session( 'user_fullname' );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Update Cropping Season',
            'details'      => $staffFullName . ' updated cropping season: "' . $seasonName . '" (' . $seasonYear . ') from ' . $dateStart . ' to ' . $dateEnd . '.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Cropping season updated successfully.',
            'icon'  => 'success',
        ] );

        return redirect()->to( base_url( '/admin/dashboard' ) );
    }

    /**
     * Deletes a cropping season by ID.
     *
     * @param int $id The ID of the cropping season to delete.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteSeason( $id )
    {
        $model  = new CroppingSeasonModel();
        $season = $model->find( $id );

        if ( !$season ) {
            return $this->response->setJSON( [ 
                'status'  => 'error',
                'message' => 'Season not found.',
            ] )->setStatusCode( 404 );
        }

        if ( $season[ 'status' ] === 'Current' ) {
            return $this->response->setJSON( [ 
                'status'  => 'error',
                'message' => 'Cannot delete the current season.',
            ] )->setStatusCode( 403 );
        }

        $model->delete( $id );

        // Logging
        $logsModel     = new LogsModel();
        $formattedDate = getPhilippineTimeFormatted();
        $staffFullName = session( 'user_fullname' );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Delete Cropping Season',
            'details'      => $staffFullName . ' deleted cropping season "' . $season[ 'season' ] . '" (' . $season[ 'year' ] . ').',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        return $this->response->setJSON( [ 
            'status'  => 'success',
            'message' => 'Cropping season deleted successfully.',
        ] );
    }


}
