<html>

<head>
    <title>Order Placed Successfully</title>
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
    <div style="text-align: center;">
      <h4>Order Summary</h4>
    </div>
    <div style="border: 1px solid #ccc; padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <table style="width: 100%;">
        <?php foreach($order_products as $product): ?>
        <tr>
          <td style="padding: 5px;">
          <img src="http://fuegonetworxservices.com/products/images/<?= $product['images'][0]->filename; ?>" style="width: 200px">
          </td>
          <td style="padding: 5px;">
            <strong><?= $product['product_name']; ?></strong><br>
            Qty: <?= $product['qty']; ?>
          </td>
          <td style="padding: 5px;"><div style="vertical-align: top;">$<?= $product['total']; ?></div></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <strong>Address:</strong> <?= $order_data['address']; ?>
    </div>
    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <strong>Selected Schedule:</strong> <?= $order_data['delivery_schedule']; ?>
    </div>
    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <strong>Order Notes:</strong> <?= $order_data['order_notes']; ?>
    </div>

    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <strong>Payment Method:</strong> <?= ucfirst($order_data['payment_method']); ?>
    </div>

    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
    <p>Thanks,<br />Enjoymint Delivered</p>
    </div>
</body>

</html>