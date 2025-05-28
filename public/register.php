<?php
// register.php

// Include database configuration
require_once '../src/config/database.php';

// Initialize variables
$name = $email = $password = $department = $sub_department = "";
$name_err = $email_err = $password_err = $department_err = $sub_department_err = "";
$ssn = "";
$ssn_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    }

    // Validate department
    if (empty(trim($_POST["department"]))) {
        $department_err = "Please select a department.";
    } else {
        $department = trim($_POST["department"]);
    }

    // Validate sub-department
    if (empty(trim($_POST["sub_department"]))) {
        $sub_department_err = "Please select a sub-department.";
    } else {
        $sub_department = trim($_POST["sub_department"]);
    }

    // Validate SSN
    if (empty(trim($_POST["ssn"]))) {
        $ssn_err = "Please enter your SSN.";
    } else {
        $ssn = trim($_POST["ssn"]);
    }

    // Check for errors before inserting into database
    if (
        empty($name_err) && empty($ssn_err) && empty($email_err) && empty($password_err)
        && empty($department_err) && empty($sub_department_err)
    ) {
        // Handle file upload for KCSE Certificate
        $kcsePath = null;
        if (isset($_FILES['kcse_certificate']) && $_FILES['kcse_certificate']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            $kcsePath = uniqid() . '_' . basename($_FILES['kcse_certificate']['name']);
            move_uploaded_file($_FILES['kcse_certificate']['tmp_name'], $uploadDir . $kcsePath);
        }

        // KCPE Certificate
        $kcpePath = null;
        if (isset($_FILES['kcpe_certificate']) && $_FILES['kcpe_certificate']['error'] === UPLOAD_ERR_OK) {
            $kcpePath = uniqid() . '_' . basename($_FILES['kcpe_certificate']['name']);
            move_uploaded_file($_FILES['kcpe_certificate']['tmp_name'], $uploadDir . $kcpePath);
        }

        // Diploma Certificate
        $diplomaPath = null;
        if (isset($_FILES['diploma_certificate']) && $_FILES['diploma_certificate']['error'] === UPLOAD_ERR_OK) {
            $diplomaPath = uniqid() . '_' . basename($_FILES['diploma_certificate']['name']);
            move_uploaded_file($_FILES['diploma_certificate']['tmp_name'], $uploadDir . $diplomaPath);
        }

        // Degree Certificate
        $degreePath = null;
        if (isset($_FILES['degree_certificate']) && $_FILES['degree_certificate']['error'] === UPLOAD_ERR_OK) {
            $degreePath = uniqid() . '_' . basename($_FILES['degree_certificate']['name']);
            move_uploaded_file($_FILES['degree_certificate']['tmp_name'], $uploadDir . $degreePath);
        }

        // Masters Certificate
        $mastersPath = null;
        if (isset($_FILES['masters_certificate']) && $_FILES['masters_certificate']['error'] === UPLOAD_ERR_OK) {
            $mastersPath = uniqid() . '_' . basename($_FILES['masters_certificate']['name']);
            move_uploaded_file($_FILES['masters_certificate']['tmp_name'], $uploadDir . $mastersPath);
        }

        // PhD Certificate
        $phdPath = null;
        if (isset($_FILES['phd_certificate']) && $_FILES['phd_certificate']['error'] === UPLOAD_ERR_OK) {
            $phdPath = uniqid() . '_' . basename($_FILES['phd_certificate']['name']);
            move_uploaded_file($_FILES['phd_certificate']['tmp_name'], $uploadDir . $phdPath);
        }

        // Professional Certifications (multiple)
        $professionalCerts = [];
        if (isset($_FILES['professional_certifications']) && is_array($_FILES['professional_certifications']['name'])) {
            foreach ($_FILES['professional_certifications']['name'] as $idx => $name) {
                if ($_FILES['professional_certifications']['error'][$idx] === UPLOAD_ERR_OK) {
                    $fileName = uniqid() . '_' . basename($name);
                    move_uploaded_file($_FILES['professional_certifications']['tmp_name'][$idx], $uploadDir . $fileName);
                    $professionalCerts[] = $fileName;
                }
            }
        }
        $professionalCertsJson = json_encode($professionalCerts);

        // National ID/Passport
        $idDocPath = null;
        if (isset($_FILES['id_document']) && $_FILES['id_document']['error'] === UPLOAD_ERR_OK) {
            $idDocPath = uniqid() . '_' . basename($_FILES['id_document']['name']);
            move_uploaded_file($_FILES['id_document']['tmp_name'], $uploadDir . $idDocPath);
        }

        // CV/Resume
        $cvPath = null;
        if (isset($_FILES['cv_document']) && $_FILES['cv_document']['error'] === UPLOAD_ERR_OK) {
            $cvPath = uniqid() . '_' . basename($_FILES['cv_document']['name']);
            move_uploaded_file($_FILES['cv_document']['tmp_name'], $uploadDir . $cvPath);
        }

        // Other Supporting Documents (multiple)
        $otherDocs = [];
        if (isset($_FILES['other_documents']) && is_array($_FILES['other_documents']['name'])) {
            foreach ($_FILES['other_documents']['name'] as $idx => $name) {
                if ($_FILES['other_documents']['error'][$idx] === UPLOAD_ERR_OK) {
                    $fileName = uniqid() . '_' . basename($name);
                    move_uploaded_file($_FILES['other_documents']['tmp_name'][$idx], $uploadDir . $fileName);
                    $otherDocs[] = $fileName;
                }
            }
        }
        $otherDocsJson = json_encode($otherDocs);

        // Prepare an insert statement using PDO
        $sql = "INSERT INTO users (name, ssn, email, password, department, sub_department, kcpe_certificate, kcse_certificate, diploma_certificate, degree_certificate, masters_certificate, phd_certificate, professional_certifications, id_document, cv_document, other_documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name, $ssn, $email, $password, $department, $sub_department,
            $kcpePath, $kcsePath, $diplomaPath, $degreePath, $mastersPath, $phdPath,
            $professionalCertsJson, $idDocPath, $cvPath, $otherDocsJson
        ]);
        if ($stmt->execute([$name, $ssn, $email, $password, $department, $sub_department, $kcsePath])) {
            // Redirect to login page or show success message
            header("location: login.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/register.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register New Staff</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div>
                <label>Name</label>
                <input type="text" name="name" value="<?php echo $name; ?>">
                <span><?php echo $name_err; ?></span>
            </div>    
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email_err; ?></span>
            </div> 
             <div>
                <label>Social Security Number (SSN)</label>
                <input type="text" name="ssn" value="<?php echo isset($ssn) ? $ssn : ''; ?>">
                <span><?php echo isset($ssn_err) ? $ssn_err : ''; ?></span>
            </div>   
            <div>
                <label>Password</label>
                <input type="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <label>Department</label>
                <select name="department" id="department" required>
                    <option value="">Select Department</option>
                    <option value="Health" <?php if($department=="Health") echo "selected"; ?>>Health</option>
                    <option value="Education" <?php if($department=="Education") echo "selected"; ?>>Education</option>
                    <option value="Finance" <?php if($department=="Finance") echo "selected"; ?>>Finance</option>
                    <option value="Public Works" <?php if($department=="Public Works") echo "selected"; ?>>Public Works</option>
                    <option value="Agriculture" <?php if($department=="Agriculture") echo "selected"; ?>>Agriculture</option>
                    <option value="Water & Sanitation" <?php if($department=="Water & Sanitation") echo "selected"; ?>>Water & Sanitation</option>
                    <option value="Trade & Industry" <?php if($department=="Trade & Industry") echo "selected"; ?>>Trade & Industry</option>
                    <option value="Lands & Housing" <?php if($department=="Lands & Housing") echo "selected"; ?>>Lands & Housing</option>
                    <option value="Transport" <?php if($department=="Transport") echo "selected"; ?>>Transport</option>
                    <option value="ICT" <?php if($department=="ICT") echo "selected"; ?>>ICT</option>
                </select>
                <span><?php echo $department_err; ?></span>
            </div>
            <div>
                <label>Sub-Department</label>
                <select name="sub_department" id="sub_department" required>
                    <option value="">Select Sub-Department</option>
                </select>
                <span><?php echo $sub_department_err; ?></span>
            </div>
            <div>
                <label>Level of Education</label>
                <select name="education_level" id="education_level" required>
                    <option value="">Select Level</option>
                    <option value="KCPE">primary</option>
                    <option value="KCSE">Secondary</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Degree">Degree</option>
                    <option value="Masters">Masters</option>
                    <option value="PhD">PhD</option>
                </select>
            </div>

            <div id="academic_certificates_section">
                <label>Academic Certificates</label>
                <div id="kcpe_cert_div" style="display:none;">
                    <label>KCPE Certificate</label>
                    <input type="file" name="kcpe_certificate">
                </div>
                <div id="kcse_cert_div" style="display:none;">
                    <label>KCSE Certificate</label>
                    <input type="file" name="kcse_certificate">
                </div>
                <div id="diploma_cert_div" style="display:none;">
                    <label>Diploma Certificate</label>
                    <input type="file" name="diploma_certificate">
                </div>
                <div id="degree_cert_div" style="display:none;">
                    <label>Degree Certificate</label>
                    <input type="file" name="degree_certificate">
                </div>
                <div id="masters_cert_div" style="display:none;">
                    <label>Masters Certificate</label>
                    <input type="file" name="masters_certificate">
                </div>
                <div id="phd_cert_div" style="display:none;">
                    <label>PhD Certificate</label>
                    <input type="file" name="phd_certificate">
                </div>
            </div>

            <div>
                <label>Professional Certifications (if any)</label>
                <input type="file" name="professional_certifications[]" multiple>
            </div>

            <div>
                <label>National ID/Passport</label>
                <input type="file" name="id_document" required>
            </div>

            <div>
                <label>CV/Resume</label>
                <input type="file" name="cv_document" required>
            </div>

            <div>
                <label>Other Supporting Documents</label>
                <input type="file" name="other_documents[]" multiple>
            </div>
           

            <div>
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
    <script>
    const subDepartments = {
        "Health": [
            "Public Health",
            "Medical Services",
            "Pharmacy",
            "Nutrition",
            "Health Administration"
        ],
        "Education": [
            "Primary Education",
            "Secondary Education",
            "Adult Education",
            "Special Needs",
            "Education Administration"
        ],
        "Finance": [
            "Budgeting",
            "Revenue Collection",
            "Procurement",
            "Audit",
            "Accounting"
        ],
        "Public Works": [
            "Roads",
            "Building Maintenance",
            "Mechanical",
            "Electrical",
            "Planning"
        ],
        "Agriculture": [
            "Crop Production",
            "Livestock",
            "Fisheries",
            "Agricultural Extension",
            "Irrigation"
        ],
        "Water & Sanitation": [
            "Water Supply",
            "Sanitation",
            "Water Quality",
            "Sewerage"
        ],
        "Trade & Industry": [
            "Trade Licensing",
            "Industrial Development",
            "Cooperative Development",
            "Weights & Measures"
        ],
        "Lands & Housing": [
            "Land Survey",
            "Housing Development",
            "Physical Planning",
            "Land Administration"
        ],
        "Transport": [
            "Fleet Management",
            "Traffic Management",
            "Public Transport"
        ],
        "ICT": [
            "Network Administration",
            "Software Development",
            "ICT Support",
            "Data Management"
        ]
    };

    document.getElementById('department').addEventListener('change', function() {
        const dept = this.value;
        const subDeptSelect = document.getElementById('sub_department');
        subDeptSelect.innerHTML = '<option value="">Select Sub-Department</option>';
        if (subDepartments[dept]) {
            subDepartments[dept].forEach(function(sub) {
                const option = document.createElement('option');
                option.value = sub;
                option.text = sub;
                subDeptSelect.appendChild(option);
            });
        }
        // Retain previous selection if any
        <?php if (!empty($sub_department)): ?>
            subDeptSelect.value = "<?php echo $sub_department; ?>";
        <?php endif; ?>
    });

    // Trigger change event on page load to populate sub-departments if editing
    window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('department').dispatchEvent(new Event('change'));
    });

    document.getElementById('education_level').addEventListener('change', function() {
        const level = this.value;
        document.getElementById('kcpe_cert_div').style.display = (["KCPE","KCSE","Diploma","Degree","Masters","PhD"].includes(level)) ? "block" : "none";
        document.getElementById('kcse_cert_div').style.display = (["KCSE","Diploma","Degree","Masters","PhD"].includes(level)) ? "block" : "none";
        document.getElementById('diploma_cert_div').style.display = (["Diploma","Degree","Masters","PhD"].includes(level)) ? "block" : "none";
        document.getElementById('degree_cert_div').style.display = (["Degree","Masters","PhD"].includes(level)) ? "block" : "none";
        document.getElementById('masters_cert_div').style.display = (["Masters","PhD"].includes(level)) ? "block" : "none";
        document.getElementById('phd_cert_div').style.display = (["PhD"].includes(level)) ? "block" : "none";
    });
    </script>
</body>
</html>