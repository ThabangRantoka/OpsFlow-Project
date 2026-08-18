<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


requireRole(["Admin"]);


/*
|--------------------------------------------------------------------------
| Load company settings
|--------------------------------------------------------------------------
*/

$settings = [];


$result = $conn->query("SELECT * FROM settings LIMIT 1");


if ($result && $result->num_rows > 0) {

    $settings = $result->fetch_assoc();

}


/*
|--------------------------------------------------------------------------
| Default values
|--------------------------------------------------------------------------
*/

$company_name = $settings["company_name"] ?? "OpsFlow Enterprise";

$currency_code = $settings["currency_code"] ?? "LSL";

$timezone = $settings["timezone"] ?? "Africa/Maseru";

$company_email = $settings["company_email"] ?? "admin@opsflow.com";

$company_phone = $settings["company_phone"] ?? "+266 50000000";

$company_address = $settings["company_address"] ?? "Maseru, Lesotho";

$website = $settings["website"] ?? "www.opsflow.com";

$theme = $settings["theme"] ?? "Light";

$company_logo = $settings["company_logo"] ?? "";


/*
|--------------------------------------------------------------------------
| Logo path
|--------------------------------------------------------------------------
*/

$logoPath = "../Assets/Images/" . $company_logo;


if (
    empty($company_logo) ||
    !file_exists(__DIR__ . "/../Assets/Images/" . $company_logo)
) {

    $logoPath = "../Assets/Images/opsflow-logo-light.png";

}


?>

<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>OpsFlow | Settings</title>


    <link
        rel="stylesheet"
        href="../Assets/CSS/style.css"
    >


    <link
        rel="stylesheet"
        href="../Assets/CSS/dashboard.css"
    >


    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    <style>

        .settings-card {
            max-width: 1180px;
            margin: 0 auto 40px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.05);
        }

        .settings-section {
            margin-bottom: 35px;
        }

        .settings-section:last-child {
            margin-bottom: 0;
        }

        .settings-section h2 {
            margin: 0 0 6px;
            font-size: 20px;
            color: #12233f;
        }

        .settings-section p {
            margin: 0 0 24px;
            color: #64748b;
            font-size: 14px;
        }

        .settings-divider {
            border: 0;
            border-top: 1px solid #e2e8f0;
            margin: 30px 0;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            min-height: 46px;
            border: 1px solid #d7e0ea;
            border-radius: 10px;
            padding: 11px 13px;
            font-size: 14px;
            background: #ffffff;
            color: #172b4d;
            outline: none;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #1688bb;
            box-shadow: 0 0 0 3px rgba(22, 136, 187, 0.10);
        }

        .logo-upload-box {
            display: flex;
            align-items: center;
            gap: 22px;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            padding: 22px;
            background: #f8fafc;
        }

        .logo-preview {
            width: 120px;
            height: 120px;
            border-radius: 14px;
            background: #102a43;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo-preview img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .logo-upload-content {
            flex: 1;
        }

        .logo-upload-content h3 {
            margin: 0 0 6px;
            color: #172b4d;
            font-size: 16px;
        }

        .logo-upload-content p {
            margin: 0 0 14px;
            color: #64748b;
            font-size: 13px;
        }

        .logo-upload-content input[type="file"] {
            width: 100%;
            max-width: 420px;
        }

        .settings-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-save {
            border: 0;
            border-radius: 10px;
            background: #1688bb;
            color: white;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #1177a5;
        }

        .success-message {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {

            .settings-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .logo-upload-box {
                flex-direction: column;
                align-items: flex-start;
            }

        }

        .logo-preview {
                            width: 180px;
                            height: 120px;
                            border-radius: 14px;
                            background: #102a43;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            overflow: hidden;
                            flex-shrink: 0;
                            padding: 10px;
                            box-sizing: border-box;
                        }

                        .logo-preview img {
                            display: block;
                            width: 100%;
                            height: 100%;
                            max-width: 100%;
                            max-height: 100%;
                            object-fit: contain;
                        }

    </style>


</head>


<body>


<div class="dashboard">


    
<?php include("../Includes/sidebar.php");
 
?>

    <main class="main-content">


        
<?php include("../Includes/header.php");
 
?>

        <section class="page-heading">


            <div class="page-toolbar">


                <div>


                    <h1>Settings</h1>


                    <p>
                        Workspace-wide configuration
                    </p>


                </div>


            </div>


        </section>



        <div class="settings-card">


            
<?php if (isset($_GET["success"])) {
 
?>

                <div class="success-message">

                    <i class="fa-solid fa-circle-check">
</i>
                    Settings saved successfully.
                </div>


            
<?php }
 
?>


            
<?php if (isset($_GET["error"])) {
 
?>

                <div class="error-message">

                    <i class="fa-solid fa-circle-exclamation">
</i>

                    
<?php echo htmlspecialchars($_GET["error"]);
 
?>
                </div>


            
<?php }
 
?>


            <form
                action="save.php"
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- COMPANY INFORMATION -->


                <div class="settings-section">


                    <h2>Company information</h2>


                    <p>
                        Configure the basic information used throughout OpsFlow.
                    </p>


                    <div class="settings-grid">


                        <div class="form-group">


                            <label for="company_name">
                                Company name
                            </label>


                            <input
                                type="text"
                                id="company_name"
                                name="company_name"
                                value="
<?php echo htmlspecialchars($company_name);
 
?>"
                                required
                            >


                        </div>



                        <div class="form-group">


                            <label for="currency_code">
                                Currency code
                            </label>


                            <input
                                type="text"
                                id="currency_code"
                                name="currency_code"
                                value="
<?php echo htmlspecialchars($currency_code);
 
?>"
                                maxlength="10"
                                required
                            >


                        </div>



                        <div class="form-group">


                            <label for="timezone">
                                Timezone
                            </label>


                            <input
                                type="text"
                                id="timezone"
                                name="timezone"
                                value="
<?php echo htmlspecialchars($timezone);
 
?>"
                                required
                            >


                        </div>



                        <div class="form-group">


                            <label for="company_email">
                                Company email
                            </label>


                            <input
                                type="email"
                                id="company_email"
                                name="company_email"
                                value="
<?php echo htmlspecialchars($company_email);
 
?>"
                                required
                            >


                        </div>



                        <div class="form-group">


                            <label for="company_phone">
                                Company phone
                            </label>


                            <input
                                type="text"
                                id="company_phone"
                                name="company_phone"
                                value="
<?php echo htmlspecialchars($company_phone);
 
?>"
                            >


                        </div>



                        <div class="form-group">


                            <label for="website">
                                Website
                            </label>


                            <input
                                type="text"
                                id="website"
                                name="website"
                                value="
<?php echo htmlspecialchars($website);
 
?>"
                            >


                        </div>



                        <div class="form-group full">


                            <label for="company_address">
                                Company address
                            </label>


                            <textarea
                                id="company_address"
                                name="company_address"
                            >
<?php echo htmlspecialchars($company_address);
 
?></textarea>


                        </div>


                    </div>


                </div>



                <hr class="settings-divider">



                <!-- APPEARANCE -->


                <div class="settings-section">


                    <h2>Appearance</h2>


                    <p>
                        Configure the appearance of your OpsFlow workspace.
                    </p>


                    <div class="settings-grid">


                        <div class="form-group">


                            <label for="theme">
                                Theme
                            </label>


                            <select
                                id="theme"
                                name="theme"
                            >


                                <option
                                    value="Light"
                                    
<?php echo $theme === "Light" ? "selected" : "";
 
?>
                                >
                                    Light
                                </option>


                                <option
                                    value="Dark"
                                    
<?php echo $theme === "Dark" ? "selected" : "";
 
?>
                                >
                                    Dark
                                </option>


                            </select>


                        </div>


                    </div>


                </div>



                <hr class="settings-divider">



                    <!-- COMPANY LOGO -->


                    <div class="settings-section">


                        <h2>Company logo</h2>


                        <p>
                            Upload the logo that should be used throughout the workspace.
                        </p>


                        <div class="logo-upload-box">


                            <div class="logo-preview">


                                <img
                                    id="logoPreview"
                                    src="
<?php echo htmlspecialchars($logoPath);
 
?>"
                                    alt="OpsFlow Company Logo"
                                >


                            </div>


                            <div class="logo-upload-content">


                                <h3>
                                    Upload company logo
                                </h3>


                                <p>
                                    PNG, JPG or WEBP. Recommended size:
                                    512 × 512px.
                                </p>


                                <input
                                    type="file"
                                    name="company_logo"
                                    id="company_logo"
                                    accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp"
                                >


                            </div>


                        </div>


                    </div>


        </div>


    </main>


</div>


<script>

const logoInput = document.getElementById("company_logo");
const logoPreview = document.getElementById("logoPreview");

if (logoInput && logoPreview) {

    logoInput.addEventListener("change", function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        const allowedTypes = [
            "image/png",
            "image/jpeg",
            "image/webp"
        ];

        if (!allowedTypes.includes(file.type)) {

            alert("Please select a PNG, JPG or WEBP image.");

            this.value = "";

            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {

            logoPreview.src = event.target.result;

        };

        reader.readAsDataURL(file);

    });

}

</script>


</body>


</html>