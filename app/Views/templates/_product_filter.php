<div id="product_filter" class="product-filter-form card col-lg-2 mt-2 mt-lg-8 d-lg-flex">
  <h5>Filter By:</h5>
  <form method='get' action="<?= base_url('/shop/')?>" id="searchForm">
    <div class="row">

      <div class="select-box" >
        <label class="mt-3 py-0">Availability:</label>
        <select class="selected" name="availability" id="availability">
          <option value="0">All</option>
          <option value="1">Scheduled</option>
          <option value="2" <?= ((isset($_GET['availability']) && $_GET['availability'] == 2) ? 'selected' : ''); ?>>Fast-tracked</option>
        </select>

        <label class="mt-3 py-0">Category:</label>
        <select class="selected" name="category" id="category">
          <option value="0">All</option>
          <?php foreach($categories as $category): ?>
          <?php  echo '<option value="'.$category->id.'" '.((isset($_GET['category']) && $category->id == $_GET['category']) ? 'selected' : '').'>'.ucfirst($category->name).'</option>' ?>
          <?php endforeach; ?>
        </select>
  
        <label class="mt-3 py-0">Strain Type:</label>
        <select class="selected" name="strain" id="strain">
          <option value="0">All</option>
          <?php foreach($strains as $str): ?>
          <?php  echo '<option value="'.$str->id.'" '.((isset($_GET['strain']) && $str->id == $_GET['strain']) ? 'selected' : '').'>'.ucfirst($str->url_slug).'</option>' ?>
          <?php endforeach; ?>
        </select>
    
        <label class="mt-3 py-0">Brand:</label>
        <select class="selected" name="brands" id="brands">
          <option value="0">All</option>
          <?php foreach($brands as $brand): ?>
          <?php  echo '<option value="'.$brand->id.'" '.((isset($_GET['brands']) && $brand->id == $_GET['brands']) ? 'selected' : '').'>'.ucfirst(strtolower($brand->name)).'</option>' ?>
          <?php endforeach; ?>
        </select>
      
        <label class="mt-3 py-0 price-range-display">Price Range: $<?= (isset($current_filter['min_price'])) ? $current_filter['min_price'] : 0; ?> - $<?= (isset($current_filter['max_price'])) ? $current_filter['max_price'] : 300; ?></label>
        <input type="hidden" value="<?= (isset($current_filter['min_price'])) ? $current_filter['min_price'] : 0; ?>" name="min_price" id="min_price">
        <input type="hidden" value="<?= (isset($current_filter['max_price'])) ? $current_filter['max_price'] : 300; ?>" name="max_price" id="max_price">
        <div id="price-slider"></div>
  
        <label class="mt-3 py-0 thc-range-display">THC % Value: <?= (isset($current_filter['min_thc'])) ? $current_filter['min_thc'] : 0; ?>% - <?= (isset($current_filter['max_thc'])) ? $current_filter['max_thc'] : 100; ?>%</label>
        <input type="hidden" value="<?= (isset($current_filter['min_thc'])) ? $current_filter['min_thc'] : 0; ?>" name="min_thc" id="min_thc">
        <input type="hidden" value="<?= (isset($current_filter['max_thc'])) ? $current_filter['max_thc'] : 100; ?>" name="max_thc" id="max_thc">
        <div id="thc-slider"></div>

        <label class="mt-3 py-0 cbd-range-display">CBD % Value: <?= (isset($current_filter['min_cbd'])) ? $current_filter['min_cbd'] : 0; ?>% - <?= (isset($current_filter['max_cbd'])) ? $current_filter['max_cbd'] : 100; ?>%</label>
        <input type="hidden" value="<?= (isset($current_filter['min_cbd'])) ? $current_filter['min_cbd'] : 0; ?>" name="min_cbd" id="min_cbd">
        <input type="hidden" value="<?= (isset($current_filter['max_cbd'])) ? $current_filter['max_cbd'] : 100; ?>" name="max_cbd" id="max_cbd">
        <div id="cbd-slider"></div>
        
        <button id="searchFormSubmit" class="btn bg-primary-green mt-5">Search</button>
        <div class="text-center mb-5"><a href="<?= base_url('shop'); ?>" id="clear-product-filter">Clear Filter</a></div>
      </div>
    </div>
  </form>
</div>