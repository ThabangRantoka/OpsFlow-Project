<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


requireRole(["Admin"]);



/*
|--------------------------------------------------------------------------
| Only POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {


    header("Location: index.php");


    exit();


}



/*
|--------------------------------------------------------------------------
| Get form values
|--------------------------------------------------------------------------
*/

$company_name = trim($_POST["company_name"] ?? "");

$currency_code = trim($_POST["currency_code"] ?? "");

$timezone = trim($_POST["timezone"] ?? "");

$company_email = trim($_POST["company_email"] ?? "");

$company_phone = trim($_POST["company_phone"] ?? "");

$company_address = trim($_POST["company_address"] ?? "");

$website = trim($_POST["website"] ?? "");

$theme = trim($_POST["theme"] ?? "Light");



/*
|--------------------------------------------------------------------------
| Validate
|--------------------------------------------------------------------------
*/

if ($company_name === "") {


    header(
        "Location: index.php?error=" .
        urlencode("Company name is required.")
    );


    exit();


}



if (!filter_var($company_email, FILTER_VALIDATE_EMAIL)) {


    header(
        "Location: index.php?error=" .
        urlencode("Please enter a valid company email.")
    );


    exit();


}



if (!in_array($theme, ["Light", "Dark"], true)) {


    $theme = "Light";


}



/*
|--------------------------------------------------------------------------
| Existing settings
|--------------------------------------------------------------------------
*/

$currentLogo = "";


$result = $conn->query(
    "SELECT * FROM settings LIMIT 1"
);


if ($result && $result->num_rows > 0) {


    $currentSettings = $result->fetch_assoc();


    $currentLogo = $currentSettings["company_logo"] ?? "";


}



/*
|--------------------------------------------------------------------------
| Handle logo upload
|--------------------------------------------------------------------------
*/

$newLogo = $currentLogo;



if (
    isset($_FILES["company_logo"]) &&
    $_FILES["company_logo"]["error"] !== UPLOAD_ERR_NO_FILE
) {


    if ($_FILES["company_logo"]["error"] !== UPLOAD_ERR_OK) {


        header(
            "Location: index.php?error=" .
            urlencode("There was a problem uploading the logo.")
        );


        exit();


    }



    /*
    | Maximum size: 5 MB
    */

    if ($_FILES["company_logo"]["size"] > 5 * 1024 * 1024) {


        header(
            "Location: index.php?error=" .
            urlencode("Logo must be smaller than 5MB.")
        );


        exit();


    }



    /*
    | Check MIME type
    */

    $finfo = finfo_open(FILEINFO_MIME_TYPE);


    $mime = finfo_file(
        $finfo,
        $_FILES["company_logo"]["tmp_name"]
    );


    finfo_close($finfo);



    $allowedTypes = [
        "image/png" => "png",
        "image/jpeg" => "jpg",
        "image/webp" => "webp"
    ];



    if (!isset($allowedTypes[$mime])) {


        header(
            "Location: index.php?error=" .
            urlencode("Only PNG, JPG and WEBP logos are allowed.")
        );


        exit();


    }



    /*
    | Create unique filename
    */

    $extension = $allowedTypes[$mime];


    $newLogo = "opsflow-logo-" . time() . "." . $extension;



    /*
    | Upload directory
    */

    $uploadDirectory = __DIR__ . "/../Assets/Images/";



    if (!is_dir($uploadDirectory)) {


        mkdir(
            $uploadDirectory,
            0755,
            true
        );


    }



    /*
    | Move uploaded file
    */

    $destination = $uploadDirectory . $newLogo;



    if (
        !move_uploaded_file(
            $_FILES["company_logo"]["tmp_name"],
            $destination
        )
    ) {


        header(
            "Location: index.php?error=" .
            urlencode("Unable to save the uploaded logo.")
        );


        exit();


    }



    /*
    | Delete previous uploaded logo
    |
    | Do NOT delete the default OpsFlow logo.
    */

    if (
        !empty($currentLogo) &&
        strpos($currentLogo, "opsflow-logo-") === 0
    ) {


        $oldFile = $uploadDirectory . $currentLogo;


        if (
            file_exists($oldFile) &&
            is_file($oldFile)
        ) {


            unlink($oldFile);


        }


    }


}



/*
|--------------------------------------------------------------------------
| Save settings
|--------------------------------------------------------------------------
*/

$check = $conn->query(
    "SELECT id FROM settings LIMIT 1"
);



if ($check && $check->num_rows > 0) {


    $row = $check->fetch_assoc();


    $id = (int)$row["id"];



    $stmt = $conn->prepare("
        UPDATE settings
        SET
            company_name = ?,
            currency_code = ?,
            timezone = ?,
            company_email = ?,
            company_phone = ?,
            company_address = ?,
            website = ?,
            theme = ?,
            company_logo = ?
        WHERE id = ?
    ");



    if (!$stmt) {


        die(
            "Database error: " .
            $conn->error
        );


    }



    $stmt->bind_param(
        "sssssssssi",
        $company_name,
        $currency_code,
        $timezone,
        $company_email,
        $company_phone,
        $company_address,
        $website,
        $theme,
        $newLogo,
        $id
    );



}
 else {



    /*
    | No settings row exists yet
    */

    $stmt = $conn->prepare("
        INSERT INTO settings
        (
            company_name,
            currency_code,
            timezone,
            company_email,
            company_phone,
            company_address,
            website,
            theme,
            company_logo
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");



    if (!$stmt) {


        die(
            "Database error: " .
            $conn->error
        );


    }



    $stmt->bind_param(
        "sssssssss",
        $company_name,
        $currency_code,
        $timezone,
        $company_email,
        $company_phone,
        $company_address,
        $website,
        $theme,
        $newLogo
    );


}



/*
|--------------------------------------------------------------------------
| Execute
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {


    $stmt->close();


    header("Location: index.php?success=1");


    exit();


}



$error = $stmt->error;


$stmt->close();



header(
    "Location: index.php?error=" .
    urlencode($error)
);


exit();


?>