<?php
    include 'koneksi.php';

    $query = "SELECT * FROM tb_daftar;";
    $sql = mysqli_query($conn, $query);
    $no = 0;

    
?>


<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'delete_success'): ?>
        <div class="alert alert-success">✅ Buku berhasil dihapus!</div>
    <?php elseif ($_GET['status'] == 'delete_error'): ?>
        <div class="alert alert-danger">
            ❌ <?= $_GET['msg'] ?? 'Buku tidak bisa dihapus karena masih dipinjam atau terjadi kesalahan.' ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

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
    
    <title>E-Library Management System</title>
    
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
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('img/perpus2.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 50px;
            color: var(--secondary-color);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .quote-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 50px;
            border-left: 4px solid var(--primary-color);
        }
        
        .book-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            border: none;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--primary-color) !important;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .table th {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .search-box {
            max-width: 600px;
            margin: 0 auto;
        }
        
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-book-open me-2"></i>E-Library
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.html"><i class="fas fa-images me-1"></i> Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="peminjaman.php"><i class="fas fa-exchange-alt me-1"></i> Peminjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_anggota.php"><i class="fas fa-users me-1"></i> Anggota</a>
                    </li>
                               <li class="nav-item ms-lg-3">
                        <a href="kelola.php" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Book
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Welcome to Our Digital Library</h1>
            <p class="lead mb-4">"Reading helps you discover yourself in ways you never imagined."</p>
            <a href="#books" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-book me-2"></i>Browse Books
            </a>
        </div>
    </section>

    <!-- Quote Section -->
    <section class="container">
        <div class="quote-card text-center">
            <blockquote class="blockquote">
                <p class="mb-0 fs-4">"Books are windows to the world. Through their pages, we can explore distant realms, understand the breadth of life, and discover our true selves. Every book is a lesson; every story is a mirror, and every word is a seed of knowledge that can grow into wisdom."</p>
            </blockquote>
        </div>
    </section>

    <!-- Books Section -->
    <section id="books" class="container mb-5">
        <h2 class="section-title text-center">Book Collection</h2>
        
        <!-- Search Box -->
        <div class="card search-box mb-5">
            <div class="card-body">
                <h5 class="card-title text-center mb-4"><i class="fas fa-search me-2"></i>Find Your Book</h5>
                <form action="" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg" 
                               placeholder="Search by title, author, ISBN..." 
                               value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <?php if(isset($_GET['search'])): ?>
        <div class="card mb-5">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Results</h5>
            </div>
            <div class="card-body">
                <?php 
                    $con = mysqli_connect("localhost","root","","db_perpus");
                    if (!$con) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    if(isset($_GET['search'])) {
                        $filtervalues = $_GET['search'];
                        $query = "SELECT * FROM tb_daftar WHERE CONCAT(judul,penerbit,tahunTerbit,isbn,jumlah) LIKE '%$filtervalues%' ";
                        $query_run = mysqli_query($con, $query);

                        if(mysqli_num_rows($query_run) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Title</th>
                                            <th>Publisher</th>
                                            <th>Year</th>
                                            <th>ISBN</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($query_run as $items): ?>
                                        <tr>
                                            <td><?= $items['judul']; ?></td>
                                            <td><?= $items['penerbit']; ?></td>
                                            <td><?= $items['tahunTerbit']; ?></td>
                                            <td><?= $items['isbn']; ?></td>
                                            <td><?= $items['jumlah']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning text-center py-4">
                                <i class="fas fa-book-open fa-3x mb-3"></i>
                                <h4>No Books Found</h4>
                                <p class="mb-0">Try a different search term</p>
                            </div>
                        <?php endif;
                    }
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- All Books Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>All Books</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Title</th>
                                <th>Publisher</th>
                                <th width="10%">Year</th>
                                <th width="15%">ISBN</th>
                                <th width="10%">Quantity</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($result = mysqli_fetch_assoc($sql)): ?>
                            <tr>
                                <td class="text-center"><?= ++$no; ?></td>
                                <td><?= $result['judul']; ?></td>
                                <td><?= $result['penerbit']; ?></td>
                                <td><?= $result['tahunTerbit']; ?></td>
                                <td><?= $result['isbn']; ?></td>
                                <td><?= $result['jumlah']; ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="kelola.php?ubah=<?= $result['id_buku']; ?>" 
                                           class="btn btn-sm btn-success flex-grow-1">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="proses.php?hapus=<?= $result['id_buku']; ?>" 
                                           class="btn btn-sm btn-danger flex-grow-1" 
                                           onclick="return confirm('Are you sure you want to delete this book?')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <h5><i class="fas fa-book-open me-2"></i>E-Library</h5>
                    <p class="mb-0">Your gateway to knowledge and imagination.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <h5>Connect With Us</h5>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <p class="mb-0">&copy; <?= date('Y'); ?> E-Library Management System. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>