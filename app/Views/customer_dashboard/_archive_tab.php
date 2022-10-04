<div class="card my-3">
  <div class="card-header"><h6>Previous Orders</h6></div>
  <div class="card-body">
    <div class="row mb-5">
      <div class="col-12 col-md-12 col-xs-12">
        <?php foreach($orders as $previous_orders): ?>
        <div class="row border mb-3">
          <div class="col-12 col-md-12 col-xs-12 mx-0 px-0">
            <div class="order-list-header bg-primary-green d-flex flex-row text-white px-2 py-2">
              <div class="px-2 py-2">
                <strong>Order # <?= $previous_orders['id']; ?></strong>
              </div>
              <div class="px-4 py-2">
                <?php 
                $date = $previous_orders['created']; 
                $timestamp = strtotime($date);
                $display_date = date("F j, Y, g:i a", $timestamp);
                ?>
                <strong><?= $display_date; ?></strong>
              </div>
              <div class="px-4 py-2">
                <strong>Total Cost: $<?= $previous_orders['total']; ?></strong>
              </div>
            </div>
            <div class="row p-2">
              <table>
                <thead>
                  <td></td>
                  <td><strong>Product Title</strong></td>
                  <td><strong>Qty</strong></td>
                  <td><strong>Unit Price</strong></td>
                  <td><strong>Total</strong></td>
                </thead>
                <tbody>
                  <?php foreach($previous_orders['products'] as $active_order_products): ?>
                  <tr>
                    <td class="px-2 text-center"><img class="prod_image" src="<?= base_url('products/images/'.$active_order_products->images[0]->filename); ?>" /></td>
                    <td><?= $active_order_products->product_name; ?></td>
                    <td><?= $active_order_products->qty; ?></td>
                    <td>$<?= $active_order_products->unit_price; ?></td>
                    <td>$<?= $active_order_products->total; ?></td>
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
    <?php if(!empty($orders)): ?>
    <div><?= $pager->links() ?></div>
    <?php endif; ?>
  </div>
</div>