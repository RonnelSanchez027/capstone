<?php
// Database connection parameters
$host = 'localhost'; 
$port = '3307'; 
$username = 'root'; 
$password = ''; 
$dbname = 'admin_db'; 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request for inserting or updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $category = $data['category'];
    $subcategory = $data['subcategory'];
    $details = $data['details'];
    $id = isset($data['id']) ? $data['id'] : null; // Get the ID if present

    if ($id) {
        // Update existing record
        $stmt = $conn->prepare("UPDATE appointment SET category = ?, subcategory = ?, details = ? WHERE id = ?");
        $stmt->bind_param("sssi", $category, $subcategory, $details, $id);
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO appointment (category, subcategory, details) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $category, $subcategory, $details);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        error_log("Error executing query: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    exit(); // Stop execution here after handling POST
}

// Handle DELETE request for deleting data
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM appointment WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    exit(); // Stop execution here after handling DELETE
}

// Fetch existing records from the database
$result = $conn->query("SELECT * FROM appointment");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS -->
	<style>
		h1 {
            text-align: center;
            margin-bottom: 20px;
        }
		.container {
            background-color: transparent; /* Transparent container */
            border-radius: 10px;
            padding: 2rem;
            transition: background-color 0.5s;
        }
        .card {
            background-color: #fff; /* White card background in light mode */
            color: #0b0c18; /* Card text color in light mode */
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem; /* Add margin below each card */
            transition: background-color 0.5s, color 0.5s;
        }
        .dark-mode .card {
            background-color: #1b1e2f; /* Dark mode card background */
            color: #e7e8f4; /* Dark mode card text color */
        }
        input, select, textarea {
            background-color: #fff; /* Default input background */
            color: #0b0c18; /* Default input text color */
            border: 1px solid #ccc; /* Default input border */
        }
        .dark-mode input, 
        .dark-mode select, 
        .dark-mode textarea {
            background-color: #2a2d3d; /* Dark mode input background */
            color: #e7e8f4; /* Dark mode input text color */
            border: 1px solid #444; /* Dark mode input border */
        }
        input::placeholder, textarea::placeholder {
            color: #b0b0b0; /* Placeholder text color */
        }
        .table {
            background-color: #fff; /* Default table background */
            color: #0b0c18; /* Default table text color */
        }
        .dark-mode .table {
            background-color: #2a2d3d; /* Dark mode table background */
            color: #e7e8f4; /* Dark mode table text color */
        }
        .table th, .table td {
            border: 1px solid #ccc; /* Default table border */
        }
        .dark-mode .table th, 
        .dark-mode .table td {
            border: 1px solid #444; /* Dark mode table border */
        }
		.dark-theme .forbid{
			color: #0b0c18;
		}
		@media (max-width: 576px) {
            .toggle-button {
                right: 10px;
                top: 10px;
            }
        }
	</style>
    <title>Public Consultation Dashboard</title>
</head>
<body>

<div class="taskbar">
    <i class="fas fa-bars taskbar-icon" id="toggleSidebar"></i>
    <div class="ml-auto">
        <i class="fas fa-sun taskbar-icon" id="themeToggle"></i>
        <i class="fas fa-comments taskbar-icon"></i>
        <i class="fas fa-bell taskbar-icon"></i>
        <i class="fas fa-user taskbar-icon" id="userIcon"></i>
    </div>
</div>

<div id="floatingTab" class="floating-tab" style="display: none;">
    <a href="#" onclick="logout()">Log Out</a>
</div>


<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="index.php" class="sidebar-header-link">
            <i class="fas fa-landmark"></i>
            <h4>Public Consultation</h4>
        </a>
    </div>
    <div class="menu-item">
        <a href="#consultation-scheduling" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('schedulingMenu')">
                <span><i class="fas fa-calendar-alt"></i> Consultation Scheduling</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="schedulingMenu">
            <div class="submenu-item" onclick="location.href='appointment.php'"><i class="fas fa-user-clock"></i>Appointment Booking</div>
            <div class="submenu-item" onclick="location.href='consultation.php'"><i class="fas fa-user-clock"></i>Consultation Management</div>
            <div class="submenu-item" onclick="location.href='schedule.php'"><i class="fas fa-user-clock"></i>Scheduling Management</div>
        </div>
    </div>
    <div class="menu-item">
        <a href="#consultation-tracking" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('trackingMenu')">
                <span><i class="fas fa-chart-line"></i> Consultation Tracking</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="trackingMenu">
            <div class="submenu-item" onclick="location.href='records.php'"><i class="fas fa-user-clock"></i>Consultation Records</div>
            <div class="submenu-item" onclick="location.href='tracking.php'"><i class="fas fa-user-clock"></i>Status Tracking</div>
        </div>
    </div>
    <div class="menu-item">
        <a href="#user-management" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('userMenu')">
                <span><i class="fas fa-user-friends"></i> User Management</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="userMenu">
            <div class="submenu-item" onclick="location.href='roles.php'"><i class="fas fa-user-clock"></i>Role Assignment</div>
        </div>
    </div>
    <div class="menu-item">
        <a href="#reporting-analytics" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('reportMenu')">
                <span><i class="fas fa-chart-line"></i> Reporting & Analytics</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="reportMenu">
            <div class="submenu-item" onclick="location.href='report.html'"><i class="fas fa-user-clock"></i>Consultation Reports</div>
            <div class="submenu-item" onclick="location.href='analytics.html'"><i class="fas fa-user-clock"></i>Analytics Dashboard</div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1>Appointment Booking</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card form-container">
                <h4 class="forbid">Book an Appointment</h4>
                <form id="projectForm">
    <!-- Hidden input for record ID -->
    <input type="hidden" id="recordId" value="">

    <div class="form-group">
        <label for="categorySelect">Select Category</label>
        <select class="form-control" id="categorySelect" onchange="updateSubcategories()" required>
            <option value="">Choose a category...</option>
            <option value="Infrastructure Development">Infrastructure Development</option>
            <option value="Public Health and Sanitation">Public Health and Sanitation</option>
            <option value="Education and Youth Development">Education and Youth Development</option>
            <option value="Public Safety and Disaster Risk Reduction">Public Safety and Disaster Risk Reduction</option>
            <option value="Economic Development">Economic Development</option>
            <option value="Environmental Protection and Natural Resource Management">Environmental Protection and Natural Resource Management</option>
            <option value="Social Welfare and Development">Social Welfare and Development</option>
            <option value="Urban Planning and Land Use">Urban Planning and Land Use</option>
            <option value="Governance and Institutional Development">Governance and Institutional Development</option>
            <option value="Cultural and Heritage Preservation">Cultural and Heritage Preservation</option>
        </select>
    </div>

    <div class="form-group">
        <label for="subcategorySelect">Select Subcategory</label>
        <select class="form-control" id="subcategorySelect" required>
            <option value="">Choose a subcategory...</option>
        </select>
    </div>

    <div class="form-group">
        <label for="detailsTextArea">Additional Details</label>
        <textarea class="form-control" id="detailsTextArea" rows="3" placeholder="Provide additional details or comments here..." required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

            </div>
        </div>

        <div class="col-md-6 form-spacing">
            <div class="card">
                <h4 class="forbid">Recorded Appointments</h4>
                <div class="table-container">
                <table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Additional Details</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="recordTableBody">
        <?php
            // Display records in the table
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>"; 
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['details']) . "</td>";
                    echo "<td>
                        <i class='fas fa-edit text-warning' style='cursor:pointer;' onclick=\"editRecord(" . $row['id'] . ", '" . htmlspecialchars($row['category']) . "', '" . htmlspecialchars($row['subcategory']) . "', '" . htmlspecialchars($row['details']) . "')\" title='Edit'></i>
                        <i class='fas fa-trash-alt text-danger' style='cursor:pointer;' onclick=\"deleteRecord(" . $row['id'] . ")\" title='Delete'></i>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No records found.</td></tr>"; // Update colspan to 4
            }
        ?>
    </tbody>
</table>



                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->
<script>
const subcategories = {
    "Infrastructure Development": [
        "Roads, bridges, and transportation systems",
        "Drainage and flood control",
        "Water supply and sanitation",
        "Public buildings and facilities"
    ],
    "Public Health and Sanitation": [
        "Healthcare services",
        "Sanitation and hygiene programs",
        "Disease prevention and control",
        "Nutrition programs"
    ],
    "Education and Youth Development": [
        "School construction and maintenance",
        "Early childhood education programs",
        "Scholarships and educational support",
        "Youth skills development and training"
    ],
    "Public Safety and Disaster Risk Reduction": [
        "Crime prevention and community policing",
        "Fire and rescue services",
        "Disaster preparedness, response, and recovery",
        "Climate change adaptation strategies"
    ],
    "Economic Development": [
        "Local business promotion and support",
        "Employment and livelihood programs",
        "Tourism development",
        "Agricultural and fisheries development"
    ],
    "Environmental Protection and Natural Resource Management": [
        "Solid waste management and recycling",
        "Pollution control (air, water, noise)",
        "Reforestation and urban greening projects",
        "Conservation of natural resources"
    ],
    "Social Welfare and Development": [
        "Support for vulnerable groups",
        "Housing and resettlement programs",
        "Gender and development initiatives",
        "Community development programs"
    ],
    "Urban Planning and Land Use": [
        "Zoning regulations",
        "Urban development and renewal projects",
        "Housing and community settlements",
        "Public space management"
    ],
    "Governance and Institutional Development": [
        "Good governance practices",
        "Capacity building for local officials and employees",
        "E-Governance and digital transformation",
        "Participatory governance and public consultation mechanisms"
    ],
    "Cultural and Heritage Preservation": [
        "Promotion of local arts, culture, and heritage",
        "Cultural festivals and events"
    ]
};

let currentEditId = null; // Store the ID of the record being edited

function updateSubcategories() {
    const categorySelect = document.getElementById("categorySelect");
    const subcategorySelect = document.getElementById("subcategorySelect");
    const selectedCategory = categorySelect.value;

    // Clear previous subcategory options
    subcategorySelect.innerHTML = '<option value="">Choose a subcategory...</option>';

    if (selectedCategory) {
        const options = subcategories[selectedCategory];
        options.forEach(option => {
            const newOption = document.createElement("option");
            newOption.value = option;
            newOption.textContent = option;
            subcategorySelect.appendChild(newOption);
        });
    }
}

function editRecord(id, category, subcategory, details) {
    // Populate the form with the data to be edited
    document.getElementById("categorySelect").value = category;
    document.getElementById("subcategorySelect").value = subcategory;
    document.getElementById("detailsTextArea").value = details;
    
    // Set the current edit ID
    currentEditId = id; // Store the ID for the current record being edited
}

function deleteRecord(id) {
    if (confirm("Are you sure you want to delete this record?")) {
        fetch('appointment.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id }) // Pass the ID of the record to delete
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadRecords(); // Reload the records to reflect deletion
            } else {
                console.error("Error:", data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
}

document.getElementById("projectForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const category = document.getElementById("categorySelect").value;
    const subcategory = document.getElementById("subcategorySelect").value;
    const details = document.getElementById("detailsTextArea").value;

    // Send data to server using Fetch API
    fetch('appointment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            category: category,
            subcategory: subcategory,
            details: details,
            id: currentEditId // Include the ID for updates
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadRecords(); // Reload the records to display the updated entry
            document.getElementById("projectForm").reset(); // Clear the form fields
            updateSubcategories(); // Reset subcategories
            currentEditId = null; // Reset the current edit ID
        } else {
            console.error("Error:", data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

// Function to load existing records
function loadRecords() {
    fetch('appointment.php')
    .then(response => response.text())
    .then(html => {
        // Update the table body with the new HTML
        document.getElementById('recordTableBody').innerHTML = new DOMParser().parseFromString(html, 'text/html').getElementById('recordTableBody').innerHTML;
    });
}

</script>
</body>
</html>