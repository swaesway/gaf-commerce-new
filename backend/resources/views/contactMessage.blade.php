<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Email</title>
</head>

<body style="background-color: #f9fafb; font-family: Arial, sans-serif; padding: 20px;">

    <table
        style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <tr>
            <td
                style="background-color: #3b82f6; color: white; text-align: center; padding: 20px; font-size: 20px; font-weight: bold; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                Reporter Issue
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <h2 style="color: #111827; font-size: 18px; font-weight: bold; margin-bottom: 10px;">Reporter Details
                </h2>
                <p style="margin: 0; line-height: 1.5; color: #374151;"><strong>Email:</strong> {{ $email }} </p>
                <p style="margin: 0; line-height: 1.5; color: #374151;"><strong>Problem Statement:</strong>
                    {{ $problem }}</p>
                <p
                    style="margin: 10px 0 0; padding: 10px; background-color: #f3f4f6; border-radius: 4px; color: #374151;">
                </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding: 20px; background-color: #f9fafb; color: #6b7280; font-size: 14px;">
                Thank you for reporting this issue. Our team will get back to you soon.
            </td>
        </tr>
    </table>

</body>

</html>
