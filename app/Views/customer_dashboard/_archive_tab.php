<div class="card mt-3">
  <div class="card-header pb-0"><h6>Previous Orders</h6></div>
  <div class="card-body pt-1">
    <div class="row mb-5">
      <div class="col-12 col-md-12 col-xs-12">
        <?php foreach($orders as $previous_orders): ?>
        <div class="row border mb-3">
          <div class="col-12 col-md-12 col-xs-12 mx-0 px-0">
            <div class="order-list-header bg-primary-green d-flex flex-column flex-md-row text-white px-2 py-2">
              <div class="px-2 py-0 py-md-2">
                <strong>Order # <?= $previous_orders['id']; ?></strong>
              </div>
              <div class="px-2 px-md-4 py-0 py-md-2">
                <?php 
                $date = $previous_orders['created']; 
                $timestamp = strtotime($date);
                $display_date = date("F j, Y, g:i a", $timestamp);
                ?>
                <strong><?= $display_date; ?></strong>
              </div>
              <div class="px-2 px-md-4 py-0 py-md-2">
                <strong>Total Cost: $<?= $previous_orders['total']; ?></strong>
              </div>
              <?php if($previous_orders['delivery_schedule'] != '' && $previous_orders['delivery_time'] != ''): ?>
                <?php
                $del_time = explode("-", $previous_orders['delivery_time']);
                $del_time_from = $del_time[0];
                $del_time_to = $del_time[1];

                if($del_time_from > 1200) {
                  $del_time_from = ($del_time_from - 1200) . ' pm';
                }
                else {
                  $del_time_from = $del_time_from . ' am';
                }

                if($del_time_to > 1200) {
                  $del_time_to = ($del_time_to - 1200) . ' pm';
                }
                else {
                  $del_time_to = $del_time_to . ' am';
                }

                $del_time_from = substr_replace($del_time_from, ':', -5, 0);
                $del_time_to = substr_replace($del_time_to, ':', -5, 0);
                ?>
                <div class="px-2 px-md-4 py-0 py-md-2">
                  <strong>Delivery Schedule: <?= $previous_orders['delivery_schedule']; ?> @ <?= $del_time_from; ?> - <?= $del_time_to; ?></strong>
                </div>
              <?php endif; ?>
            </div>
            <?php foreach($previous_orders['products'] as $products): ?>
            <div class="row p-2 d-flex d-md-none">
              <div class="col-4 col-sm-4 text-center">
                <img class="prod_image" src="<?= base_url('products/images/'.$products->images[0]->filename); ?>" />
              </div>
              <div class="col-8 col-sm-8">
                <?= $products->product_name; ?>
              </div>
              <div class="col-4 col-sm-4">
                <strong>Qty:</strong><br><?= $products->qty; ?>
              </div>
              <div class="col-4 col-sm-4">
                <strong>Unit Price:</strong><br><?= $products->unit_price; ?>
              </div>
              <div class="col-4 col-sm-4">
                <strong>Total:</strong><br><?= $products->total; ?>
              </div>
            </div>
            <?php endforeach; ?>
            <div class="row p-2 d-none d-md-block">
              <table>
                <thead>
                  <td></td>
                  <td><strong>Product Title</strong></td>
                  <td><strong>Qty</strong></td>
                  <td><strong>Unit Price</strong></td>
                  <td><strong>Total</strong></td>
                </thead>
                <tbody>
                  <?php foreach($previous_orders['products'] as $products): ?>
                  <tr>
                    <td class="px-2 text-center"><img class="prod_image" src="<?= base_url('products/images/'.$products->images[0]->filename); ?>" /></td>
                    <td><?= $products->product_name; ?></td>
                    <td><?= $products->qty; ?></td>
                    <td>$<?= $products->unit_price; ?></td>
                    <td>$<?= $products->total; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div><?= $pager->links() ?></div>
  </div>
</div>