<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Repair extends Model
{
    /** @use HasFactory<\Database\Factories\RepairFactory> */
    use HasFactory;

    protected $guarded = [];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'part_repair');
    }

    public function generateInvoice()
    {
        $pdf = Pdf::loadView('invoices.repair', ['repair' => $this]);

        $fileName = Str::slug($this->car->license_plate) . '-' . now()->timestamp . '.pdf';

        Storage::disk('invoices')->put($fileName, $pdf->output());

        $this->update(['invoice_path' => $fileName]);
    }
}
