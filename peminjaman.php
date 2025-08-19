<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Loan System | E-Library</title>
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
        .navbar-brand {
            font-weight: 600;
            color: white !important;
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
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
            transform: translateY(-2px);
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
        .status-dipinjam {
            background-color: #ffebee;
            color: var(--accent-color);
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
        }
        .status-dikembalikan {
            background-color: #e8f5e9;
            color: #2ecc71;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
        }
        .alert {
            border-radius: 10px;
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
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-3"><i class="fas fa-exchange-alt me-3"></i>Book Loan System</h1>
                    <p class="lead mb-0">Manage book borrowing with custom date</p>
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
            switch($_GET['status']){
                case 'sukses':
                case 'pinjam_sukses':
                    $alert_class = 'alert-success';
                    $alert_icon = 'fa-check-circle';
                    $alert_message = 'Book loan successful!';
                    break;
                case 'gagal':
                case 'pinjam_gagal':
                    $alert_class = 'alert-danger';
                    $alert_icon = 'fa-exclamation-circle';
                    $alert_message = 'Book loan failed. Please try again.';
                    break;
                case 'stok_habis':
                    $alert_class = 'alert-warning';
                    $alert_icon = 'fa-exclamation-triangle';
                    $alert_message = 'Book is out of stock or unavailable.';
                    break;
                case 'kembali_sukses':
                    $alert_class = 'alert-success';
                    $alert_icon = 'fa-check-circle';
                    $alert_message = 'Book returned successfully!';
                    break;
                case 'kembali_gagal':
                    $alert_class = 'alert-danger';
                    $alert_icon = 'fa-exclamation-circle';
                    $alert_message = 'Return failed. Please try again.';
                    break;
                case 'tanggal_invalid':
                    $alert_class = 'alert-danger';
                    $alert_icon = 'fa-calendar-times';
                    $alert_message = 'Invalid date selected!';
                    break;
            }
            echo '<div class="alert '.$alert_class.' alert-dismissible fade show mb-4">
                    <i class="fas '.$alert_icon.' me-2"></i>'.$alert_message.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
        ?>

        <div class="row g-4">
            <!-- Borrow Book Form -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="icon-box">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h5 class="mb-0">Borrow a Book</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="proses.php">
                            <div class="mb-4">
                                <label for="id_buku" class="form-label fw-bold">Select Book</label>
                                <select class="form-select form-select-lg" id="id_buku" name="id_buku" required>
                                    <option value="" selected disabled>-- Select Book --</option>
                                    <?php
                                    $query_buku = "SELECT * FROM tb_daftar WHERE jumlah > 0;";
                                    $sql_buku = mysqli_query($conn, $query_buku);
                                    while($buku = mysqli_fetch_assoc($sql_buku)){
                                        echo '<option value="'.$buku['id_buku'].'">'.$buku['judul'].' (Available: '.$buku['jumlah'].')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="id_anggota" class="form-label fw-bold">Select Member</label>
                                <select class="form-select form-select-lg" id="id_anggota" name="id_anggota" required>
                                    <option value="" selected disabled>-- Select Member --</option>
                                    <?php
                                    $query_anggota = "SELECT * FROM anggota;";
                                    $sql_anggota = mysqli_query($conn, $query_anggota);
                                    while($anggota = mysqli_fetch_assoc($sql_anggota)){
                                        echo '<option value="'.$anggota['id_anggota'].'">'.$anggota['nama'].' ('.$anggota['email'].')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <!-- Tambahkan input tanggal peminjaman -->
                            <div class="mb-4">
                                <label for="tanggal_pinjam" class="form-label fw-bold">Date of Borrow</label>
                                <input 
                                    type="date" 
                                    class="form-control form-control-lg" 
                                    id="tanggal_pinjam" 
                                    name="tanggal_pinjam" 
                                    required
                                    value="<?= date('Y-m-d') ?>" 
                                >
                            </div>

                            <button type="submit" name="pinjam" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-check me-2"></i>Borrow Book
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Active Loans -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="icon-box">
                            <i class="fas fa-list"></i>
                        </div>
                        <h5 class="mb-0">Active Loans</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_peminjaman = "SELECT p.*, a.nama as nama_anggota, b.judul as judul_buku 
                                                         FROM peminjaman p
                                                         JOIN anggota a ON p.id_anggota = a.id_anggota
                                                         JOIN tb_daftar b ON p.id_buku = b.id_buku
                                                         WHERE p.status = 'Dipinjam';";
                                    $sql_peminjaman = mysqli_query($conn, $query_peminjaman);
                                    if(mysqli_num_rows($sql_peminjaman) > 0){
                                        while($pinjam = mysqli_fetch_assoc($sql_peminjaman)){
                                            echo '<tr>
                                                    <td>'.$pinjam['nama_anggota'].'</td>
                                                    <td>'.$pinjam['judul_buku'].'</td>
                                                    <td>'.date('M d, Y', strtotime($pinjam['tanggal_kembali'])).'</td>
                                                    <td><span class="status-dipinjam">'.$pinjam['status'].'</span></td>
                                                    <td>
                                                        <form method="POST" action="proses.php" style="display:inline;">
                                                            <input type="hidden" name="id_peminjaman" value="'.$pinjam['id_peminjaman'].'">
                                                            <input type="hidden" name="id_buku" value="'.$pinjam['id_buku'].'">
                                                            <button type="submit" name="kembali" class="btn btn-success btn-sm">
                                                                <i class="fas fa-undo me-1"></i>Return
                                                            </button>
                                                        </form>
                                                    </td>
                                                  </tr>';
                                        }
                                    } else {
                                        echo '<tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="fas fa-book-open fa-2x mb-3 text-muted"></i>
                                                    <p class="text-muted">No active book loans</p>
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
        </div>

        <!-- Loan History -->
        <div class="card mt-4">
            <div class="card-header d-flex align-items-center">
                <div class="icon-box">
                    <i class="fas fa-history"></i>
                </div>
                <h5 class="mb-0">Loan History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_riwayat = "SELECT p.*, a.nama as nama_anggota, b.judul as judul_buku 
                                             FROM peminjaman p
                                             JOIN anggota a ON p.id_anggota = a.id_anggota
                                             JOIN tb_daftar b ON p.id_buku = b.id_buku
                                             ORDER BY p.tanggal_pinjam DESC LIMIT 10;";
                            $sql_riwayat = mysqli_query($conn, $query_riwayat);
                            if(mysqli_num_rows($sql_riwayat) > 0){
                                while($riwayat = mysqli_fetch_assoc($sql_riwayat)){
                                    $status_class = ($riwayat['status'] == 'Dipinjam') ? 'status-dipinjam' : 'status-dikembalikan';
                                    echo '<tr>
                                            <td>'.$riwayat['nama_anggota'].'</td>
                                            <td>'.$riwayat['judul_buku'].'</td>
                                            <td>'.date('M d, Y', strtotime($riwayat['tanggal_pinjam'])).'</td>
                                            <td>'.date('M d, Y', strtotime($riwayat['tanggal_kembali'])).'</td>
                                            <td><span class="'.$status_class.'">'.$riwayat['status'].'</span></td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-book-open fa-2x mb-3 text-muted"></i>
                                            <p class="text-muted">No loan history available</p>
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