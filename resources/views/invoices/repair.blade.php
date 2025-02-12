<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $repair->car->license_plate }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; font-size: 24px; font-weight: bold; }
        .info { margin-top: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
<div class="header">Car Repair Invoice</div>

<div class="info">
    <p><strong>Car:</strong> {{ $repair->car->license_plate }}</p>
    <p><strong>Repair Date:</strong> {{ $repair->date }}</p>
    <p><strong>Repair Type:</strong> {{ $repair->type }}</p>
    <p><strong>Description:</strong> {{ $repair->description }}</p>
    <p><strong>Cost:</strong> €{{ number_format($repair->cost, 2) }}</p>
</div>

<div class="footer" style="margin-top: 40px; text-align: center;">
    <p>Thank you for choosing our service!</p>
</div>
</body>
</html>
