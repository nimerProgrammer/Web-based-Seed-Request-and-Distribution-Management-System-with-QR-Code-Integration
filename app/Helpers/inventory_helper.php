<?php

use App\Models\InventoryModel;

/**
 * Get a list of inventory items with their cropping season details.
 *
 * @return array
 */
function getInventoryList() : array
{
    $inventoryModel = new InventoryModel();
    return $inventoryModel
        ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
        ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
        ->where( 'cropping_season.status', 'Current' ) // ✅ only Current season
        ->orderBy( 'inventory.seed_name', 'ASC' )
        ->findAll();
}
