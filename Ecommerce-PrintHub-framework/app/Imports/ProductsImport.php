<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * Transforma cada fila del Excel en un Producto.
     */
    public function model(array $row)
    {
        return Product::updateOrCreate(
            ['sku' => $row['sku']], // Busca por SKU
            [
                'name'        => $row['name'],
                'description' => $row['description'] ?? null,
                'price'       => $row['price'],
                'stock'       => $row['stock'],
                'category'    => $row['category'] ?? 'General',
                'image'       => $row['image'] ?? null, // Nombre de la imagen (ej: lambo.jpg)
            ]
        );
    }

    /**
     * Reglas de validación para evitar errores feos.
     */
    public function rules(): array
    {
        return [
            'sku'   => 'required|string',
            'name'  => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }
}