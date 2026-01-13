<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory; // Usamos la librería que ya tienes en el composer

class ProductController extends Controller
{
    // Muestra la galería (antiguo galeria.html)
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Muestra el detalle (antiguo productDetail.php)
    public function show($id)
    {
        // Busca por ID, si no existe da error 404 automáticamente
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Muestra formulario de importación (antiguo admin_importar.html)
    public function importView()
    {
        return view('admin.import');
    }

    // Procesa el Excel (Lógica de migración C4)
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Saltamos la cabecera (primera fila)
            array_shift($rows);

            $count = 0;
            foreach ($rows as $row) {
                // Asegúrate que el orden coincida con tu Excel (A, B, C...)
                // Ejemplo: 0=SKU, 1=Nombre, 2=Desc, 3=Precio, 4=Stock, 5=Imagen
                if (!empty($row[0])) { 
                    Product::updateOrCreate(
                        ['sku' => $row[0]], 
                        [
                            'name' => $row[1],
                            'description' => $row[2] ?? '',
                            'price' => (float)($row[3] ?? 0),
                            'stock' => (int)($row[4] ?? 0),
                            'image' => $row[5] ?? 'img/placeholder.jpg',
                            'category' => $row[6] ?? 'General',
                        ]
                    );
                    $count++;
                }
            }

            return redirect()->route('products.index')->with('success', "Se han importado $count productos correctamente.");
            
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Error al leer el archivo: ' . $e->getMessage()]);
        }
    }
    
    // API para JSON (Requisito C5)
    public function apiIndex() {
        return response()->json(Product::all());
    }
}