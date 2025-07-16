<?php

use App\Models\InventoryModel;
use App\Models\CroppingSeasonModel;
use App\Models\BeneficiariesModel;
use App\Models\SeedRequestsModel;

function countCurrentSeasonSeeds() : int
{
    $inventoryModel = new InventoryModel();
    $seasonModel    = new CroppingSeasonModel();

    $currentSeason = $seasonModel->where( 'status', 'Current' )->first();

    if ( !$currentSeason ) {
        return 0;
    }

    $result = $inventoryModel
        ->select( 'COUNT(*) as seed_count' )
        ->where( 'cropping_season_tbl_id', $currentSeason[ 'cropping_season_tbl_id' ] )
        ->first();

    return (int) ( $result[ 'seed_count' ] ?? 0 );
}

function getAvailableSeeds() : array
{
    $inventoryModel = new InventoryModel();
    return $inventoryModel
        ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
        ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
        ->where( 'cropping_season.status', 'Current' ) // ✅ only Current season
        ->orderBy( 'inventory.seed_name', 'ASC' )
        ->findAll();
}

function countCurrentSeasonBeneficiaries() : int
{
    $beneficiariesModel = new BeneficiariesModel();

    return $beneficiariesModel
        ->join( 'seed_requests', 'seed_requests.seed_requests_tbl_id = beneficiaries.seed_requests_tbl_id' )
        ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
        ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
        ->where( 'cropping_season.status', 'Current' )
        ->countAllResults();
}

function countCurrentSeasonSeedRequests() : int
{
    $seedRequestsModel = new SeedRequestsModel();

    return $seedRequestsModel
        ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
        ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
        ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
        ->where( 'cropping_season.status', 'Current' )
        ->countAllResults();
}


