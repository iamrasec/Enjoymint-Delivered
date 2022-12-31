<html>

<head>
    <title>Order Placed Successfully</title>
    <style type='text/css'>
      
    </style>
</head>

<body>
    <div style="text-align: center; padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <img src="<?= $site_logo; ?>" alt="Enjoymint Delivered" style="margin: 0 auto 20px; width: 300px; background: rgba(38,50,48,0.95); padding: 10px; border-radius: 20px;">
      <br>
      <h3>ORDER CONFIRMATION</h3>
      <p>Hi <strong><?= $order_data['first_name']; ?></strong>, thank you for your order!</p>
      <p>We've received your order and will contact you soon.  You can find your purchase information below.</p>
      <br><br>
    </div>
    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <h1>Order Summary</h1>
      <p>Order ID: <strong><?= $order_data['id']; ?></strong></p>
      <p>Payment Method: <strong><?= ucfirst($order_data['payment_method']); ?></strong></p>
      <p>Address: <strong><?= $order_data['address']; ?></strong></p>
      
      <?php if($order_data['delivery_schedule'] != null): ?>
      <p>Selected Schedule: Thank you for placing your<strong>SCHEDULED ORDER FOR<?= $order_data['delivery_schedule']; ?></strong> between <strong><?= $order_data['delivery_time']; ?></strong> </p>
      <?php else: ?>
      <p>Selected Schedule:</p>
      <?php endif; ?>
      <p>Order Notes: <strong><?= $order_data['order_notes']; ?></strong></p>
      <p>If you need to edit your order or delivery address, please call <a href="tel:+14084844644" class="nav-cta btn btn-sm bg-primary-green mb-0 me-1 mt-2 mt-md-0">Call Now (408) 484-4644</a> </p>
    </div>
    <div style="border: 1px solid #ccc; padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <table style="width: 100%;">
        <?php foreach($order_products as $product): ?>
        <tr>
          <td>
          <img src="http://fuegonetworxservices.com/products/images/<?= $product['images'][0]->filename; ?>" style="width: 100px">
          </td>
          <td>
            <strong><?= $product['product_name']; ?></strong><br>
            Qty: <?= $product['qty']; ?>
          </td>
          <td style="padding: 5px; text-align: right;"><div style="vertical-align: top;">$<?= number_format($product['total'], 2, '.', ','); ?></div></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" style="text-align: right;">Subtotal: <strong>$<?= number_format($order_data['order_costs']['subtotal'], 2, '.', ','); ?></strong></td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: right;">Taxes: <strong>$<?= number_format($order_data['order_costs']['tax'], 2, '.', ','); ?></strong></td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong> <strong>$<?= number_format($order_data['order_costs']['total'], 2, '.', ','); ?></strong></td>
        </tr>
      </table>
    </div>

    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">                                                    
    <p>Thanks,<br />Enjoymint Delivered</p>
    </div>
</body>

</html>