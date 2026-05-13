<?php
require 'vendor/autoload.php';
require_once('class.phpmailer.php');
include('db_connect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('admin_class.php');
    $action = new Action();
    
    // Call the register_landlord function
    $result = $action->register_landlord();
    
    if ($result == 1) {
        // Redirect to success page
        header("Location: register_success.php");
        exit();
    } elseif ($result == 2) {
        $error_message = "Landlord already exists. Details updated!";
    } else {
        $error_message = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #49bde4;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        #main {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        #login-right {
            width: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
            height: auto;
        }

        .container-fluid {
            max-width: 800px;
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-section {
            background-color: #49bde4;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section h5 {
            background-color: #0d2074;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 18px;
            text-align: center;
        }

        .form-check-label {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .formbtn button {
            background-color: #49bde4;
            border-color: #3B71CA;
        }

        .formbtn button:hover {
            background-color: #0d2074;
            border-color: #315a9e;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 768px) {
            #main {
                flex-direction: column;
            }

            #login-left {
                height: 30vh;
                width: 100%;
            }

            #login-right {
                width: 100%;
                height: auto;
                padding: 8px;
            }

            .container-fluid {
                max-width: 100%;
                padding: 15px;
                box-shadow: none;
            }
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 10px;
            }

            .form-section h5 {
                font-size: 16px;
                padding: 8px;
            }

            #login-left {
                height: 25vh;
            }

            .formbtn button {
                width: 50%;
                bottom: 30px;
            }
        }
    </style>
</head>
<body>
    
<main id="main" class="bg-light">
    <div id="login-left">
        <!-- Left side background image -->
    </div>
    <div id="login-right">
        <div class="container-fluid">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form id="registration-form" method="post" enctype="multipart/form-data">
                <!-- Background Data Section -->
                <div class="form-section">
                    <h5>Background Data</h5>
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="business_name">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="registration_no">Registration No</label>
                            <input type="text" class="form-control" id="registration_no" name="registration_no" required>
                        </div>
                    </div>
                </div>

                <!-- Geographical Location Section -->
                <div class="form-section">
                    <h5>Geographical Location</h5>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="geographical_description">Geographical Description</label>
                            <textarea class="form-control" id="geographical_description" name="geographical_description" required></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="district">District/City</label>
                            <select class="form-control" id="district" name="district" required>
                                <option value="">Select</option>
                                <!-- Populate dynamically -->
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="county">County/Municipality</label>
                            <select class="form-control" id="county" name="county">
                                <option value="">Select</option>
                                <!-- Populate dynamically -->
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sub_county">Sub County/Town Council/Division</label>
                            <input type="text" class="form-control" id="sub_county" name="sub_county">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="parish">Parish</label>
                            <input type="text" class="form-control" id="parish" name="parish">
                        </div>
                    </div>
                </div>

                <!-- Contact Details Section -->
                <div class="form-section">
                    <h5>Contact Details</h5>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="telephone_contact">Telephone Contact</label>
                            <input type="text" class="form-control" id="telephone_contact" name="telephone_contact" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email_address">Email Address</label>
                            <input type="email" class="form-control" id="email_address" name="email_address" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contact_person">Contact Person Name(s)</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                        </div>
                    </div>
                </div>

                <!-- Account Information Section -->
                <div class="form-section">
                    <h5>Account Information</h5>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="username">User Name</label> 
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="business_initials">Business Initials</label>
                            <input type="text" class="form-control" id="business_initials" name="business_initials" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="landlord_id"></label>
                            <input type="hidden" class="form-control" id="landlord_id" name="landlord_id" value="<?php echo isset($landlord_id) ? $landlord_id :'' ?>">
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the Urent <a href="#" target="_blank">Terms of Services</a> and <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <!-- Form Buttons -->
                <div class="formbtn">
                <div class="form-group text-center mt-3">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    const districts = [
        "Abim", "Adjumani", "Agago", "Alebtong", "Amolatar", "Amudat", "Amuria", "Amuru", "Apac", "Arua",
        "Budaka", "Bududa", "Bugiri", "Bugweri", "Buhweju", "Buikwe", "Bukedea", "Bukomansimbi", "Bukwa",
        "Bulambuli", "Buliisa", "Bundibugyo", "Bunyangabu", "Bushenyi", "Busia", "Butaleja", "Butambala",
        "Butebo", "Buvuma", "Buyende", "Dokolo", "Gomba", "Gulu", "Hoima", "Ibanda", "Iganga", "Isingiro",
        "Jinja", "Kaabong", "Kabale", "Kabarole", "Kaberamaido", "Kagadi", "Kakumiro", "Kalangala", "Kaliro",
        "Kalungu", "Kampala", "Kamuli", "Kamwenge", "Kanungu", "Kapchorwa", "Kapelebyong", "Karenga", "Kasanda",
        "Kasese", "Katakwi", "Kayunga", "Kazo", "Kibaale", "Kiboga", "Kibuku", "Kigezi", "Kikuube", "Kiruhura",
        "Kiryandongo", "Kisoro", "Kitagwenda", "Kitgum", "Koboko", "Kole", "Kotido", "Kumi", "Kwania", "Kween",
        "Kyankwanzi", "Kyegegwa", "Kyenjojo", "Kyotera", "Lamwo", "Lira", "Luwero", "Lwengo", "Lyantonde",
        "Madi Okollo", "Manafwa", "Maracha", "Masaka", "Masindi", "Mayuge", "Mbale", "Mbarara", "Mitooma",
        "Mityana", "Moroto", "Moyo", "Mpigi", "Mubende", "Mukono", "Nabilatuk", "Nakapiripirit", "Nakaseke",
        "Nakasongola", "Namayingo", "Namisindwa", "Namutumba", "Napak", "Nebbi", "Ngora", "Ntoroko", "Ntungamo",
        "Nwoya", "Omoro", "Otuke", "Oyam", "Pader", "Pakwach", "Pallisa", "Rakai", "Rubanda", "Rubirizi",
        "Rukiga", "Rukungiri", "Rwampara", "Sembabule", "Serere", "Sheema", "Sironko", "Soroti", "Tororo",
        "Wakiso", "Yumbe", "Zombo"
    ];

    // Populate the district dropdown
    districts.forEach(district => {
        $('#district').append(new Option(district, district));
    });

    // Event listener for district change to populate counties
    $('#district').on('change', function () {
        const selectedDistrict = $(this).val();
        let counties = [];

        // Dummy data for counties based on selected district
        switch (selectedDistrict) {
            case "Kampala":
                counties = ["Kampala Central", "Kawempe", "Makindye", "Nakawa", "Rubaga"];
                break;
            case "Wakiso":
                counties = ["Busiro", "Kyadondo", "Entebbe", "Kira", "Katabi"];
                break;
            default:
                counties = ["Select"];
                break;
        }

        // Populate the county dropdown
        $('#county').empty(); // Clear previous options
        counties.forEach(county => {
            $('#county').append(new Option(county, county));
        });
    });

    // Form validation
    $('#registration-form').submit(function(e) {
        // Client-side validation
        var email = $('#email_address').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,16}$/;

        // Check if terms are accepted
        if (!$('#terms').is(':checked')) {
            alert('You must agree to the terms and conditions.');
            e.preventDefault();
            return;
        }

        // Validate email
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            e.preventDefault();
            return;
        }

        // Validate password
        if (!passwordRegex.test(password)) {
            alert('Password must be between 8-16 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
            e.preventDefault();
            return;
        }

        // Check if passwords match
        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            e.preventDefault();
            return;
        }
    });
});
</script>

</body>
</html>