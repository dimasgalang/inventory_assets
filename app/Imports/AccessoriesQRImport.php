<?php

namespace App\Imports;

use App\Models\AccessoriesQR;
use App\Models\FabricsQR;
use App\Models\InventoryQR;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccessoriesQRImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        return new AccessoriesQR([
            'supplier' => $row[0],
            'contract_no' => $row[1],
            'delivery_note_no' => $row[2],
            'invoice_no' => $row[3],
            'style' => $row[4],
            'lot' => $row[5],
            'item_code' => $row[6],
            'item_name' => $row[7],
            'item_category' => $row[8],
            'color' => $row[9],
            'size' => $row[10],
            'composition' => $row[11],
            'box_no' => $row[12],
            'qty' => $row[13],
            'unit' => $row[14],
            'fabric_role_qr_code' => $row[15],
            'po_number' => $row[16],
            'source_region' => $row[17],
            'customs_declaration_number' => $row[18],
            'doc_date' => $row[19],
            'container_number' => $row[20],
            'ETA_date' => $row[21],
            'customs_code_shipping' => $row[22],
            'customs_code_warehouse' => $row[23],
            'customs_quantity' => $row[24],
            'customs_unit' => $row[25],
            'remark' => $row[26],
            'random_qr_code' => $row[12] . $row[16] . $row[4],
        ]);
    }
}
