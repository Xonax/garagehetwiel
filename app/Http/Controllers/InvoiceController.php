<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download($file)
    {
        // Check if the user is an admin
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // Construct the file path relative to the 'invoices' disk
        $filePath = $file;

        // Check if the file exists
        if (!Storage::disk('invoices')->exists($filePath)) {
            abort(404, 'Invoice not found');
        }

        // Return the file as a download response
        return Storage::disk('invoices')->download($filePath);
    }
}

