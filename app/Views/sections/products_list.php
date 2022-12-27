<?php if(!empty($products)): ?>
<div id="products-list-view" class="row">

  <?php if($fast_tracked == false): ?>
  <div class="input-group" style="float: right; margin-top:-45px; margin-right:-45px;">
    <div class="input-group-prepend">
      <button type="button" id="toggle" class="input-group-text">
      <i class="fa fa-calendar-alt"></i>&nbsp;&nbsp; 
      <input style="width: 240px;" type="text" id="picker" value="" placeholder="delivery schedule" name="delivery_schedule" class="form-control datetime_picker">
      </button>
    </div>
  </div>
  <?php endif; ?>

  <?php foreach($products as $product): ?>
  <div class="col-lg-3 col-sm-6 pt-4 pb-4 reveal-fadein zoom">
    <div class="card product-featured">
      <div class="img-wrap">
        <?php 
          $url = !empty($searchData) ? $product['url'] : $product['url'];
          if(is_array($product['images']) && isset($product['images'][0])):
        ?>
        <a href="<?= base_url('products/'. $url); ?>"><img class="prod_image" src="<?= base_url('products/images/'.$product['images'][0]->filename); ?>" /></a>
        <?php else: ?>
        <a href="<?= base_url('products/'. $url); ?>"><img class="prod_image" src="" /></a>
        <?php endif; ?>
      </div>
      <div class="product-info d-flex flex-column px-2">
        <a href="<?= base_url('products/'. $product['url']); ?>"><h5><?=  $product['name']; ?></h5></a>
        <div class="product-info-bottom d-flex flex-column mt-auto">
          <p>
            <span class="badge bg-dark"><span class="text-warning">THC</span> <?= $product['thc_value'] . (($product['thc_unit'] == 'pct') ? '%' : $product['thc_unit']); ?></span> 
            <?php if($product['stocks'] > 0): ?>
            <?php $btn_disabled = ''; ?>
            <span class="badge text-bg-success">In Stock</span>
            <?php else: ?>
            <?php $btn_disabled = 'disabled'; ?>
            <span class="badge text-bg-danger">Out Of Stock</span>
            <?php endif; ?>
          </p>
          <hr id="color" class="mt-0">
          <p class="price">$<span><?= $product['price']; ?></span></p>
          <hr id="color" class="mt-0">
          <?php if($product['stocks'] > 0): ?>
          <?php if($product['stocks'] <= 5): ?>  
          <div class="low-stock-indicator text-xs text-danger mb-2 fw-bold">Only <?= $product['stocks']; ?> left!</div>
          <?php endif; ?>
          <button class="btn add-to-cart add-product-<?= $product['id']; ?> btn-md bg-warning text-white" name="add-to-cart" data-pid="<?= $product['id']; ?>" >
            <span class="material-icons">add_shopping_cart</span> Add to Cart
          </button>
          <?php elseif($product['stocks'] <= 0): ?>
            <button class="btn btn-md bg-warning text-white" name="add-to-cart" data-pid="<?= $product['id']; ?>" <?= $btn_disabled = 'disabled'; ?>>
            <span class="material-icons">add_shopping_cart</span> Add to Cart
          </button>
          <?php endif; ?>
          <div class="lds-hourglass d-none"></div>
        </div>
      </div>
    </div> 
  </div>
  <?php endforeach; ?>
</div>
<div><?= $pager->links() ?></div>
<?php else: ?>
<div class="mt-5"><p>No Products Available.</p></div>
<?php endif; ?>