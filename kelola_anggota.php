<?php
include 'koneksi.php';

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

if(isset($_POST['aksi'])){
    // Sanitize all input data
    $nama = sanitizeInput($_POST['nama']);
    $email = sanitizeInput($_POST['email']);
    $no_telp = sanitizeInput($_POST['no_telp']);
    $alamat = sanitizeInput($_POST['alamat']);
    $tanggal_daftar = date('Y-m-d');

    if($_POST['aksi'] == "add"){
        $query = "INSERT INTO anggota VALUES(null, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $no_telp, $alamat, $tanggal_daftar);
        
        if(mysqli_stmt_execute($stmt)){
            header("location: kelola_anggota.php?status=sukses&action=add");
        } else {
            header("location: kelola_anggota.php?status=gagal&action=add");
        }
        mysqli_stmt_close($stmt);

    } else if($_POST['aksi'] == "edit"){
        $id_anggota = (int)$_POST['id_anggota'];
        
        $query = "UPDATE anggota SET nama=?, email=?, no_telp=?, alamat=? WHERE id_anggota=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $nama, $email, $no_telp, $alamat, $id_anggota);
        
        if(mysqli_stmt_execute($stmt)){
            header("location: kelola_anggota.php?status=sukses&action=edit");
        } else {
            header("location: kelola_anggota.php?status=gagal&action=edit");
        }
        mysqli_stmt_close($stmt);
    }
    exit();
}

if(isset($_GET['hapus'])){
    $id_anggota = (int)$_GET['hapus'];
    
    $query = "DELETE FROM anggota WHERE id_anggota = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_anggota);
    
    if(mysqli_stmt_execute($stmt)){
        header("location: kelola_anggota.php?status=sukses&action=delete");
    } else {
        header("location: kelola_anggota.php?status=gagal&action=delete");
    }
    mysqli_stmt_close($stmt);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Management | E-Library</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1.25rem;
            border-bottom: none;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 500;
            padding: 1rem;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }
        
        .action-btn {
            min-width: 80px;
            margin: 0 3px;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }
        
        .icon-box {
            width: 50px;
            height: 50px;
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--primary-color);
            font-size: 1.25rem;
        }
        
        .empty-state {
            padding: 3rem 0;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-3"><i class="fas fa-users me-3"></i>Member Management</h1>
                    <p class="lead mb-0">Manage library members efficiently</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="index.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <!-- Notification Alerts -->
        <?php
        if(isset($_GET['status'])){
            $alert_class = '';
            $alert_icon = '';
            $alert_message = '';
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            
            if($_GET['status'] == 'sukses'){
                $alert_class = 'alert-success';
                $alert_icon = 'fa-check-circle';
                
                switch($action){
                    case 'add':
                        $alert_message = 'New member added successfully!';
                        break;
                    case 'edit':
                        $alert_message = 'Member data updated successfully!';
                        break;
                    case 'delete':
                        $alert_message = 'Member deleted successfully!';
                        break;
                    default:
                        $alert_message = 'Operation completed successfully!';
                }
            } else {
                $alert_class = 'alert-danger';
                $alert_icon = 'fa-exclamation-circle';
                
                switch($action){
                    case 'add':
                        $alert_message = 'Failed to add new member. Please try again.';
                        break;
                    case 'edit':
                        $alert_message = 'Failed to update member data. Please try again.';
                        break;
                    case 'delete':
                        $alert_message = 'Failed to delete member. Please try again.';
                        break;
                    default:
                        $alert_message = 'Operation failed. Please try again.';
                }
            }
            
            echo '<div class="alert '.$alert_class.' alert-dismissible fade show mb-4">
                    <i class="fas '.$alert_icon.' me-2"></i>'.$alert_message.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
        ?>
        
        <!-- Member Form Card -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <div class="icon-box">
                    <i class="fas <?php echo isset($_GET['ubah']) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                </div>
                <h5 class="mb-0">
                    <?php echo isset($_GET['ubah']) ? 'Edit Member' : 'Add New Member'; ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="kelola_anggota.php">
                    <input type="hidden" name="id_anggota" value="<?php echo isset($_GET['ubah']) ? $_GET['ubah'] : ''; ?>">
                    
                    <div class="mb-4">
                        <label for="nama" class="form-label fw-bold">Full Name</label>
                        <input required type="text" name="nama" class="form-control form-control-lg" id="nama" 
                               value="<?php if(isset($_GET['ubah'])){ 
                                   $query = "SELECT * FROM anggota WHERE id_anggota = '".$_GET['ubah']."';";
                                   $sql = mysqli_query($conn, $query);
                                   $result = mysqli_fetch_assoc($sql);
                                   echo htmlspecialchars($result['nama']);
                               } ?>">
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input required type="email" name="email" class="form-control form-control-lg" id="email" 
                                   value="<?php if(isset($_GET['ubah'])) echo htmlspecialchars($result['email']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="no_telp" class="form-label fw-bold">Phone Number</label>
                            <input required type="tel" name="no_telp" class="form-control form-control-lg" id="no_telp" 
                                   value="<?php if(isset($_GET['ubah'])) echo htmlspecialchars($result['no_telp']); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="alamat" class="form-label fw-bold">Address</label>
                        <textarea name="alamat" class="form-control form-control-lg" id="alamat" rows="3"><?php if(isset($_GET['ubah'])) echo htmlspecialchars($result['alamat']); ?></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <?php if(isset($_GET['ubah'])): ?>
                            <button type="submit" name="aksi" value="edit" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="kelola_anggota.php" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        <?php else: ?>
                            <button type="submit" name="aksi" value="add" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save Member
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Members List Card -->
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="icon-box">
                    <i class="fas fa-users"></i>
                </div>
                <h5 class="mb-0">Registered Members</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Join Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM anggota ORDER BY nama;";
                            $sql = mysqli_query($conn, $query);
                            $no = 1;
                            
                            if(mysqli_num_rows($sql) > 0){
                                while($result = mysqli_fetch_assoc($sql)){
                                    echo '<tr>
                                            <td>'.$no++.'</td>
                                            <td>'.htmlspecialchars($result['nama']).'</td>
                                            <td>'.htmlspecialchars($result['email']).'</td>
                                            <td>'.htmlspecialchars($result['no_telp']).'</td>
                                            <td>'.date('M d, Y', strtotime($result['tanggal_daftar'])).'</td>
                                            <td>
                                                <a href="kelola_anggota.php?ubah='.$result['id_anggota'].'" class="btn btn-sm btn-warning action-btn">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <a href="kelola_anggota.php?hapus='.$result['id_anggota'].'" class="btn btn-sm btn-danger action-btn" 
                                                   onclick="return confirm(\'Are you sure you want to delete this member?\')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr>
                                        <td colspan="6" class="empty-state">
                                            <i class="fas fa-user-friends"></i>
                                            <p class="text-muted mt-2">No members registered yet</p>
                                        </td>
                                      </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>