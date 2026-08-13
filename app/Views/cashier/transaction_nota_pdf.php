<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 8px;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Courier New', monospace;
            font-size: 9px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .center {
            text-align: center;
        }
        .brand-name {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .muted {
            color: #444;
            font-size: 8px;
        }
        .divider {
            border-top: 1px dashed #333;
            margin: 6px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            margin-bottom: 2px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin-top: 4px;
        }
        table.items th {
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border-bottom: 1px dashed #333;
            padding-bottom: 3px;
        }
        table.items th.right,
        table.items td.right {
            text-align: right;
        }
        table.items td {
            padding: 3px 0;
            vertical-align: top;
        }
        .item-name {
            display: block;
        }
        .item-sub {
            display: block;
            font-size: 8px;
            color: #555;
        }
        table.totals {
            width: 100%;
            font-size: 9px;
            margin-top: 6px;
        }
        table.totals td {
            padding: 2px 0;
        }
        table.totals td.right {
            text-align: right;
        }
        .grand-total td {
            font-size: 10.5px;
            font-weight: bold;
            border-top: 1px dashed #333;
            padding-top: 4px;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #444;
        }
    </style>
</head>
<body>

    <div class="center">
        <div class="brand-name">KDMP</div>
        <div class="muted">Koperasi Desa Merah Putih</div>
        <div class="muted">Jl. Merdeka No. 17, Desa Makmur</div>
        <div class="muted">Telp: (+62) 812-3456-7890</div>
    </div>

    <div class="divider"></div>

    <div class="info-row">
        <span>No. Struk</span>
        <span><?= esc($transaction['transaction_id'] ?? '-') ?></span>
    </div>
    <div class="info-row">
        <span>Tanggal</span>
        <span><?= esc(!empty($transaction['created_at']) ? date('d/m/Y H:i', strtotime($transaction['created_at'])) : '-') ?></span>
    </div>
    <div class="info-row">
        <span>Kasir</span>
        <span><?= esc($cashier['name'] ?? '-') ?></span>
    </div>

    <div class="divider"></div>

    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Jml</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <span class="item-name"><?= esc($item['product_name'] ?? '-') ?></span>
                        <span class="item-sub">@ Rp<?= number_format((float) ($item['price'] ?? 0), 0, ',', '.') ?></span>
                    </td>
                    <td class="right"><?= esc($item['quantity'] ?? 0) ?></td>
                    <td class="right">Rp<?= number_format((float) ($item['subtotal'] ?? 0), 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td>Total Item</td>
            <td class="right"><?= esc(count($items)) ?></td>
        </tr>
        <tr>
            <td>Tipe Pembayaran</td>
            <td class="right capitalize"><?= esc($transaction['payment_type'] ?? '-') ?></td>
        </tr>
        <tr class="grand-total">
            <td>TOTAL</td>
            <td class="right">Rp<?= number_format((float) ($transaction['total'] ?? 0), 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">Rp<?= number_format((float) ($transaction['pay'] ?? 0), 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="right">Rp<?= number_format((float) ($transaction['change'] ?? 0), 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="footer">
        <div>Terima kasih atas kunjungan Anda</div>
        <div>Barang yang sudah dibeli tidak dapat ditukar</div>
    </div>

</body>
</html>