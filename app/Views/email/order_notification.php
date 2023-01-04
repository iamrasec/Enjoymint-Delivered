<html>

<head>
    <title>Order Placed Successfully</title>
    <style type='text/css'>
      
    </style>
</head>

<body>
    <div style="padding: 20px 15px; max-width: 1080px; margin: 0 auto;">
      <h1>Order Summary</h1>
      <p>Order ID: <strong><?= $order_data['id']; ?></strong></p>
      <p>Customer Name: <strong><?= $order_data['first_name']; ?><?= $order_data['last_name']; ?></strong></p>
      <p>Email: <strong><?= $order_data['email']; ?></strong></p>
      <p>Phone number: <strong><?= $order_data['phone']; ?></strong></p>
      <p>Payment Method: <strong><?= ucfirst($order_data['payment_method']); ?></strong></p>
      <p>Address: <strong><?= $order_data['address']; ?></strong></p>
      <?php if($order_data['delivery_schedule'] != '' && $order_data['delivery_time'] != ''): ?>
          <?php
                $del_time = explode("-", $order_data['delivery_time']);
                $del_time_from = $del_time[0];
                $del_time_to = $del_time[1];

                if($del_time_from > 1200) {
                  $del_time_from = ($del_time_from - 1200) . ' PM';
                }
                else {
                  $del_time_from = $del_time_from . ' AM';
                }

                if($del_time_to > 1200) {
                  $del_time_to = ($del_time_to - 1200) . ' PM';
                }
                else {
                  $del_time_to = $del_time_to . ' AM';
                }

                $del_time_from = substr_replace($del_time_from, ':', -5, 0);
                $del_time_to = substr_replace($del_time_to, ':', -5, 0);
              ?>
               <p>Selected Schedule: <strong> <?= $order_data['delivery_schedule']; ?> between <?= $del_time_from; ?> - <?= $del_time_to; ?></strong></p>
      <?php else: ?> 
      <p>Selected Schedule:</p>
      <?php endif; ?>
      <p>Order Notes: <strong><?= $order_data['order_notes']; ?></strong></p>
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