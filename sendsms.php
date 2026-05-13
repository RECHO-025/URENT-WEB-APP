<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Notification System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            background-color: #0d2074;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: none;
            font-size: 16px;
        }

    input[type="submit"] {
    background-color: #49bde4;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    width: 50%;
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: inline-block;
    margin-top: 15px;
}

input[type="submit"]:hover {
    background-color: #007aff;
    transform: translateY(-2px);
}

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .column {
            flex: 48%;
        }

        .full-width {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Send Notifications to Tenants</h2>

    <form method="POST" action="index.php?page=send_email">
        <!-- Dropdown for Message Type -->
        <div class="">
            <label for="message_type">Select Message Type:</label>
            <select id="message_type" name="message_type" required>
                <option value="" disabled selected>Select</option>
                <option value="Rent Payment Reminder">Rent Payment Reminder</option>
                <option value="General Notifications">General Notifications</option>
                <option value="Other (Invitations, Alerts, Awareness)">Other (Invitations, Alerts, Awareness)</option>
            </select>
        </div>

        <!-- Input field for Sender's Contact -->
        <div class="">
            <label for="sender_contact">Sender's Contact:</label>
            <input type="text" id="sender_contact" name="sender_contact" placeholder="Enter your contact number" required>
        </div>

        <!-- Submit Button -->
         <div class="send">
        <input type="submit" value="Send Message">
    </form>
</div>
</div>
</body>
</html>
