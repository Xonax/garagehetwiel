<!DOCTYPE html>
<html>
<head>
    <title>Your Car is Ready for Pickup</title>
</head>
<body style="background-color: #f3f4f6; padding: 40px; font-family: Arial, sans-serif;">
<div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 24px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); text-align: center;">

    <h1 style="color: #374151; font-size: 22px; font-weight: bold;">
        🚗 Your Car is Ready for Pickup!
    </h1>

    <p style="color: #4b5563; font-size: 16px; margin-top: 10px;">
        Dear <strong>{{ $car->user->name }}</strong>,
        <br>Your car (<strong>{{ $car->license_plate }}, {{ $car->model }}</strong>) is now ready for pickup.
    </p>

    <p style="color: #4b5563; font-size: 14px; margin-top: 10px;">
        Please visit our garage to collect your vehicle at your convenience.
    </p>

    <div style="margin-top: 20px;">
        <a href="{{ url('/admin') }}" style="display: inline-block; background-color: #4f46e5; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 16px;">
            View Details
        </a>
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">

    <p style="color: #6b7280; font-size: 14px;">
        Best Regards,
        <br><strong>The Garage Team</strong>
    </p>
</div>
</body>
</html>
