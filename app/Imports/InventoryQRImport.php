<?php

namespace App\Imports;

use App\Models\InventoryQR;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InventoryQRImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new InventoryQR([
            'item_number' => $row[1],
            'assets_number' => $row[2],
            'category' => $row[3],
            'brand' => $row[4],
            'type' => $row[5],
            'item_name' => $row[6],
            'serial_number' => $row[7],
            'incoming_date' => $row[8],
            'location' => $row[9],
            'void' => 'false'
        ]);
    }
}
