<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - #<?php echo e($order->order_number); ?></title>
    <style>
        @page  {
            size: 4in 6in;
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
        }
        .label-container {
            width: 3.6in;
            height: 5.6in;
            border: 2px solid #000;
            padding: 10px;
            position: relative;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
        }
        .carrier {
            font-size: 14px;
            font-weight: bold;
        }
        .from-section {
            font-size: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .to-section {
            padding: 10px 0;
            margin-bottom: 15px;
        }
        .to-label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .to-address {
            font-size: 18px;
            line-height: 1.2;
            font-weight: bold;
        }
        .footer {
            margin-top: auto;
            border-top: 2px solid #000;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .tracking-placeholder {
            width: 100%;
            height: 60px;
            background: #eee;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #666;
            font-size: 10px;
            color: #666;
        }
        .barcode {
            width: 100%;
            margin: 10px 0;
            text-align: center;
        }
        .order-meta {
            font-size: 10px;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .weight-zone {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        @media  print {
            .no-print { display: none; }
            body { padding: 0; }
            .label-container { border: 2px solid #000; }
        }
        .print-btn-float {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 100;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button class="print-btn-float no-print" onclick="window.print()">PRINT LABEL</button>

    <div class="label-container">
        <div class="header">
            <h1>STANDARD</h1>
            <div class="carrier">POSTAL SERVICE</div>
        </div>

        <div class="from-section">
            <strong>FROM:</strong><br>
            <?php echo e($gs->title); ?> - FULFILLMENT CENTER<br>
            <?php echo e($gs->email); ?><br>
            <?php echo e($gs->header_address); ?>

        </div>

        <div class="to-section">
            <div class="to-label">SHIP TO:</div>
            <div class="to-address">
                <?php echo e(strtoupper($order->shipping_name ?? $order->customer_name)); ?><br>
                <?php echo e(strtoupper($order->shipping_address ?? $order->customer_address)); ?><br>
                <?php echo e(strtoupper($order->shipping_city ?? $order->customer_city)); ?>, <?php echo e(strtoupper($order->shipping_zip ?? $order->customer_zip)); ?><br>
                <?php echo e(strtoupper($order->shipping_country ?? $order->customer_country)); ?>

            </div>
        </div>

        <div class="barcode">
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo e($order->order_number); ?>&code=Code128&dpi=96" alt="Barcode">
            <div style="font-size: 12px; font-weight: bold; margin-top: 5px;">#<?php echo e($order->order_number); ?></div>
        </div>

        <div class="footer">
            <div class="order-meta">
                <span>ORDER DATE: <?php echo e($order->created_at->format('Y-M-d')); ?></span>
                <span>ITEMS: <?php echo e($order->totalQty); ?></span>
            </div>
            <div class="weight-zone">
                <span>PARCEL WT: 0.5 KG</span>
                <span>ZONE: LOCAL</span>
            </div>
        </div>
    </div>

    <script>
        // Auto-open print dialog
        window.onload = function() {
            // setTimeout(() => window.print(), 500);
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\printer\label.blade.php ENDPATH**/ ?>