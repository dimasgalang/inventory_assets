<?php

namespace App\Imports;

use App\Models\FabricsQR;
use App\Models\InventoryQR;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FabricsQRImport implements ToModel, WithStartRow
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
        return new FabricsQR([
            'supplier' => $row[0],
            'contract_no' => $row[1],
            'delivery_note_no' => $row[2],
            'invoice_no' => $row[3],
            'style' => $row[4],
            'item_code' => $row[5],
            'item_name' => $row[6],
            'item_category' => $row[7],
            'color' => $row[8],
            'composition' => $row[9],
            'lot' => $row[10],
            'roll_no' => $row[11],
            'pl_qty_yield' => $row[12],
            'pl_qty_kg' => $row[13],
            'weight' => $row[14],
            'fabric_role_qr_code' => $row[15],
            'po_number' => $row[16],
            'po_fabric_width' => $row[17],
            'source_region' => $row[18],
            'customs_declaration_number' => $row[19],
            'doc_date' => $row[20],
            'container_number' => $row[21],
            'ETA_date' => $row[22],
            'customs_code_shipping' => $row[23],
            'customs_code_warehouse' => $row[24],
            'customs_quantity' => $row[25],
            'customs_unit' => $row[26],
            'remark' => $row[27],
            'random_qr_code' => $row[11] . $row[16] . $row[4],
        ]);
    }
}
