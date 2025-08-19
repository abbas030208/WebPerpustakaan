<?php
    include 'koneksi.php';  
    $judul = ''; 
    $penerbit = ''; 
    $tahunTerbit = ''; 
    $isbn = ''; 
    $jumlah = '';

    if(isset($_GET['ubah'])) {
        $id_siswa = $_GET['ubah'];   
        
        $query = "SELECT * FROM tb_daftar WHERE id_buku = '$id_siswa';";
        $sql = mysqli_query($conn, $query); 

        $result = mysqli_fetch_assoc($sql);

        $judul = $result['judul'];
        $penerbit = $result['penerbit'];
        $tahunTerbit = $result['tahunTerbit'];
        $isbn = $result['isbn'];
        $jumlah = $result['jumlah'];
        $id_siswa = $result['id_buku'];
    }
?>     

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <title>Book Management | E-Library</title>
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .navbar {
            background-color: var(--secondary-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 50px;
        }
        
        .form-title {
            color: var(--secondary-color);
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        
        .form-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        .form-control {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background-color: var(--accent-color);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .input-group-text {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book-open me-2"></i>E-Library
            </a>
            <div class="ms-auto">
                <span class="text-white fs-4 fw-light">Book Management</span>
            </div>
        </div>
    </nav>

    <!-- Main Form Container -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <h2 class="form-title">
                        <i class="fas fa-book me-2"></i>
                        <?php echo isset($_GET['ubah']) ? 'Edit Book' : 'Add New Book'; ?>
                    </h2>
                    
                    <form method="POST" action="proses.php" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $id_siswa; ?>" name="id_buku">
                        
                        <!-- Book Title -->
                        <div class="mb-4">
                            <label for="judul" class="form-label">Book Title</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                <input required type="text" name="judul" class="form-control" id="judul" 
                                       placeholder="Enter book title" value="<?php echo $judul; ?>">
                            </div>
                        </div>
                        
                        <!-- Publisher -->
                        <div class="mb-4">
                            <label for="penerbit" class="form-label">Publisher</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                <input required type="text" name="penerbit" class="form-control" id="penerbit" 
                                       placeholder="Enter publisher name" value="<?php echo $penerbit; ?>">
                            </div>
                        </div>
                        
                        <!-- Year of Publication -->
                        <div class="mb-4">
                            <label for="tahunTerbit" class="form-label">Year of Publication</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                <input required type="text" name="tahunTerbit" class="form-control" id="tahunTerbit" 
                                       placeholder="Enter publication year" value="<?php echo $tahunTerbit; ?>">
                            </div>
                        </div>
                        
                        <!-- ISBN -->
                        <div class="mb-4">
                            <label for="isbn" class="form-label">ISBN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input required type="text" name="isbn" class="form-control" id="isbn" 
                                       placeholder="Enter ISBN" value="<?php echo $isbn; ?>">
                            </div>
                        </div>
                        
                        <!-- Quantity -->
                        <div class="mb-4">
                            <label for="jumlah" class="form-label">Quantity</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-copy"></i></span>
                                <input required type="number" class="form-control" id="jumlah" name="jumlah" 
                                       placeholder="Enter quantity" value="<?php echo $jumlah; ?>">
                            </div>
                        </div>
                        
                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-between mt-5">
                            <a href="index.php" class="btn btn-cancel text-white">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            
                            <?php if(isset($_GET['ubah'])): ?>
                                <button type="submit" name="aksi" value="edit" class="btn btn-submit text-white">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            <?php else: ?>
                                <button type="submit" name="aksi" value="add" class="btn btn-submit text-white">
                                    <i class="fas fa-plus me-2"></i>Add Book
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>