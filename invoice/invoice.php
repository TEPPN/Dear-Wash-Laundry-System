<?php
session_start();
include '../connect/connect.php';

// Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo "Akses ditolak.";
    exit;
}

// Ambil pesanan DONE + user_name
$sql = "SELECT o.*, u.user_name 
        FROM `order` o 
        LEFT JOIN `user` u ON o.order_id = u.order_count 
        WHERE o.status = 'DONE' 
        ORDER BY o.order_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Invoice - Laundry</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f0f8ff;
            color: #333;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 255, 0.1);
        }

        th,
        td {
            border: 1px solid #aaddff;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #b0dfff;
        }

        h1 {
            text-align: center;
            color: #004c99;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            color: #004c99;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h1>Daftar Invoice - Pesanan Selesai</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Nama Pemesan</th>
            <th>Pesanan Selesai</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo htmlspecialchars($row['customer_name'] ?? 'Tidak diketahui'); ?></td>
                <td><?php echo $row['time_finished']; ?></td>
                <td><?php echo $row['total_amount']; ?></td>
                <td><?php echo $row['payment_status']; ?></td>
                <td>
                    <form method="POST" action="update_payment.php">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <select name="payment_status">
                            <option value="BELUM_LUNAS" <?php if ($row['payment_status'] == 'BELUM_LUNAS') echo 'selected'; ?>>Belum Lunas</option>
                            <option value="LUNAS" <?php if ($row['payment_status'] == 'LUNAS') echo 'selected'; ?>>Lunas</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <div class="back">
        <a href="../track/track.php">← Kembali ke Tracking</a>
    </div>
</body>

</html>