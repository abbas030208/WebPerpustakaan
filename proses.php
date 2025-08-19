<?php
// Koneksi ke database
include 'koneksi.php';

// Fungsi sanitasi input
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// ------------------------------
// OPERASI BUKU: TAMBAH & EDIT
// ------------------------------
if (isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];

    // Sanitasi input
    $judul = sanitizeInput($_POST['judul']);
    $penerbit = sanitizeInput($_POST['penerbit']);
    $tahunTerbit = sanitizeInput($_POST['tahunTerbit']);
    $isbn = sanitizeInput($_POST['isbn']);
    $jumlah = sanitizeInput($_POST['jumlah']);

    // Validasi angka
    if (!is_numeric($tahunTerbit) || !is_numeric($jumlah)) {
        header("Location: kelola.php?status=error&msg=Angka tidak valid");
        exit();
    }

    // ------------------------------
    // TAMBAH BUKU
    // ------------------------------
    if ($aksi == "add") {
        $query = "INSERT INTO tb_daftar (judul, penerbit, tahunTerbit, isbn, jumlah) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($conn));
            header("Location: kelola.php?status=add_error");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ssssi", $judul, $penerbit, $tahunTerbit, $isbn, $jumlah);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?status=add_success");
        } else {
            header("Location: kelola.php?status=add_error");
        }
        mysqli_stmt_close($stmt);
        exit();
    }

    // ------------------------------
    // EDIT BUKU
    // ------------------------------
    elseif ($aksi == "edit") {
        $id_buku = (int)$_POST['id_buku'];

        // Cek apakah buku ada
        $check_query = "SELECT id_buku FROM tb_daftar WHERE id_buku = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "i", $id_buku);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            mysqli_stmt_close($check_stmt);
            header("Location: index.php?status=book_not_found");
            exit();
        }
        mysqli_stmt_close($check_stmt);

        // Update data buku
        $query = "UPDATE tb_daftar SET 
                  judul = ?, penerbit = ?, tahunTerbit = ?, isbn = ?, jumlah = ? 
                  WHERE id_buku = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $judul, $penerbit, $tahunTerbit, $isbn, $jumlah, $id_buku);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?status=edit_success");
        } else {
            header("Location: kelola.php?status=edit_error&id=$id_buku");
        }
        mysqli_stmt_close($stmt);
        exit();
    }
}

// ------------------------------
/// ------------------------------
// HAPUS BUKU
// ------------------------------
if (isset($_GET['hapus'])) {
    $id_buku = (int)$_GET['hapus'];

    // Cek apakah buku masih dipinjam
    $check_loan = "SELECT COUNT(*) as total FROM peminjaman WHERE id_buku = ? AND status = 'Dipinjam'";
    $stmt = mysqli_prepare($conn, $check_loan);
    mysqli_stmt_bind_param($stmt, "i", $id_buku);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['total'] > 0) {
        // Masih ada peminjaman aktif → tidak bisa hapus
        header("Location: index.php?status=delete_error&msg=Buku tidak bisa dihapus karena masih dipinjam");
        mysqli_stmt_close($stmt);
        exit();
    }

    mysqli_stmt_close($stmt);

    // Jika tidak ada peminjaman aktif, lanjutkan hapus
    $delete_query = "DELETE FROM tb_daftar WHERE id_buku = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $id_buku);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?status=delete_success");
    } else {
        header("Location: index.php?status=delete_error&msg=Gagal menghapus dari database");
    }
    mysqli_stmt_close($stmt);
    exit();
}   
// ------------------------------
// PINJAM BUKU (dengan input tanggal manual)
// ------------------------------
if (isset($_POST['pinjam'])) {
    $id_buku = (int)$_POST['id_buku'];
    $id_anggota = (int)$_POST['id_anggota'];
    $tanggal_pinjam = $_POST['tanggal_pinjam']; // Diambil dari input form

    // Validasi format tanggal (Y-m-d)
    $date_check = DateTime::createFromFormat('Y-m-d', $tanggal_pinjam);
    if (!$date_check || $date_check->format('Y-m-d') !== $tanggal_pinjam) {
        header("Location: peminjaman.php?status=tanggal_invalid");
        exit();
    }

    // Hitung tanggal kembali (7 hari setelah pinjam)
    $tanggal_kembali = date('Y-m-d', strtotime($tanggal_pinjam . ' +7 days'));

    // Cek stok buku
    $stock_query = "SELECT jumlah FROM tb_daftar WHERE id_buku = ? AND jumlah > 0";
    $stmt = mysqli_prepare($conn, $stock_query);
    mysqli_stmt_bind_param($stmt, "i", $id_buku);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        mysqli_stmt_close($stmt);
        header("Location: peminjaman.php?status=stok_habis");
        exit();
    }
    mysqli_stmt_close($stmt);

    // Kurangi stok buku
    $update_stock = "UPDATE tb_daftar SET jumlah = jumlah - 1 WHERE id_buku = ?";
    $stmt = mysqli_prepare($conn, $update_stock);
    mysqli_stmt_bind_param($stmt, "i", $id_buku);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Simpan data peminjaman
        $insert_query = "INSERT INTO peminjaman (id_buku, id_anggota, tanggal_pinjam, tanggal_kembali, status) 
                         VALUES (?, ?, ?, ?, 'Dipinjam')";
        $stmt2 = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt2, "iiss", $id_buku, $id_anggota, $tanggal_pinjam, $tanggal_kembali);

        if (mysqli_stmt_execute($stmt2)) {
            header("Location: peminjaman.php?status=pinjam_sukses");
        } else {
            // Rollback: tambah kembali stok jika gagal simpan peminjaman
            mysqli_query($conn, "UPDATE tb_daftar SET jumlah = jumlah + 1 WHERE id_buku = $id_buku");
            header("Location: peminjaman.php?status=pinjam_gagal");
        }
        mysqli_stmt_close($stmt2);
    } else {
        header("Location: peminjaman.php?status=pinjam_gagal");
    }
    mysqli_stmt_close($stmt);
    exit();
}

// ------------------------------
// KEMBALIKAN BUKU
// ------------------------------
if (isset($_POST['kembali'])) {
    $id_peminjaman = (int)$_POST['id_peminjaman'];
    $id_buku = (int)$_POST['id_buku'];

    // Gunakan transaksi untuk keamanan data
    mysqli_begin_transaction($conn);

    try {
        // Update status peminjaman
        $update_borrow = "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = ?";
        $stmt1 = mysqli_prepare($conn, $update_borrow);
        mysqli_stmt_bind_param($stmt1, "i", $id_peminjaman);
        mysqli_stmt_execute($stmt1);

        // Tambah stok buku
        $update_stock = "UPDATE tb_daftar SET jumlah = jumlah + 1 WHERE id_buku = ?";
        $stmt2 = mysqli_prepare($conn, $update_stock);
        mysqli_stmt_bind_param($stmt2, "i", $id_buku);
        mysqli_stmt_execute($stmt2);

        // Commit jika semua berhasil
        mysqli_commit($conn);
        header("Location: peminjaman.php?status=kembali_sukses");
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($conn);
        header("Location: peminjaman.php?status=kembali_gagal");
    }

    // Tutup statement
    if (isset($stmt1)) mysqli_stmt_close($stmt1);
    if (isset($stmt2)) mysqli_stmt_close($stmt2);
    exit();
}

// ------------------------------
// REDIRECT JIKA TIDAK ADA AKSI
// ------------------------------
header("Location: index.php");
exit();
?>