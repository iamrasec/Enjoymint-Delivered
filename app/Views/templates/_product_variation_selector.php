<div class="product-variants dropdown">
  <a class="btn dropdown-toggle variant-selector w-100 fs-6 fw-bold" href="#" role="button" id="variant-selector-<?= $product['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="price fs-6 fw-bold">$<?= $product['price']; ?></span> - <span class="unit fs-6 text-lowercase fw-normal"><?= $base_product_unit; ?></span>
  </a>

  <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuLink">
    <li class="d-none">
      <a class="dropdown-item" href="#" data-variation-id="0" data-pid="<?= $product['id']; ?>" data-price="<?= $product['price']; ?>" data-unit="<?= $base_product_unit; ?>" data-stock="<?= $product['stocks']; ?>">
        <div class="price fw-bold">$<span><?= $product['price']; ?></span> - <span class="unit text-lowercase fw-normal"><?= $base_product_unit; ?></span></div>
      </a>
    </li>
    <?php foreach($product['variants'] as $variant): ?>
    <?php 
    switch(trim($variant->unit)) {
      case 'mg':
        $variant_unit = $variant->unit_value . " mg.";
        break;
      case 'g':
        if($variant->unit_value > 1) {
          $variant_unit = $variant->unit_value . " grams";
        }
        else {
          $variant_unit = $variant->unit_value . " gram";
        }
        break;
      case 'oz':
        $variant_unit = $variant->unit_value . "ounces";
        break;
      case 'piece':
        if($variant->unit_value == 1) {
          // $variant_unit = "each";
          $variant_unit = round($variant->unit_value) . " piece";
        }
        else {
          $variant_unit = round($variant->unit_value) . " pieces";
        }
        break;
      case 'pct':
        $variant_unit = $variant->unit_value . "%";
        // if($variant->unit_value == 1) {
        //   $variant_unit = "each";
        // }
        // else {
        //   $variant_unit = round($variant->unit_value) . " pieces";
        // }
        break;
    } 
    ?>
    <li>
      <a class="dropdown-item" href="#" data-variation-id="<?= $variant->id; ?>" data-pid="<?= $product['id']; ?>" data-price="<?= $variant->price; ?>" data-unit="<?= $variant_unit; ?>" data-stock="<?= $variant->stock; ?>">
        <div class="price fw-bold">$<span><?= $variant->price; ?></span> - <span class="unit fw-normal"><?= $variant_unit; ?></span></div>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
  
</div>