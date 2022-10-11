<div id="product_filter" class="product-filter-form card col-lg-2 mt-2 mt-lg-8 d-lg-flex">
  <h5>Filter By:</h5>
  <form method='get' action="<?= base_url('/shop/')?>" id="searchForm">
    <div class="row">

      <div class="select-box" >
        <label class="mt-3 py-0">Availability:</label>
        <select class="selected" name="availability">
          <option value="0">All</option>
          <option value="1">Scheduled</option>
          <option value="2">Fast-tracked</option>
        </select>

        <label class="mt-3 py-0">Category:</label>
        <select class="selected" name="category">
          <option value="0">All</option>
          <?php foreach($categories as $category): ?>
          <?php  echo '<option value="'.$category->id.'" '.((isset($_GET['category']) && $category->id == $_GET['category']) ? 'selected' : '').'>'.ucfirst($category->name).'</option>' ?>
          <?php endforeach; ?>
        </select>
  
        <label class="mt-3 py-0">Strain Type:</label>
        <select class="selected" id="strain" name="strain">
          <option value="0">All</option>
          <?php foreach($strains as $str): ?>
          <?php  echo '<option value="'.$str->id.'" '.((isset($_GET['strain']) && $str->id == $_GET['strain']) ? 'selected' : '').'>'.ucfirst($str->url_slug).'</option>' ?>
          <?php endforeach; ?>
        </select>
    
        <label class="mt-3 py-0">Brand:</label>
        <select class="selected" name="brands">
          <option value="0">All</option>
          <?php foreach($brands as $brand): ?>
          <?php  echo '<option value="'.$brand->id.'" '.((isset($_GET['brands']) && $brand->id == $_GET['brands']) ? 'selected' : '').'>'.ucfirst(strtolower($brand->name)).'</option>' ?>
          <?php endforeach; ?>
        </select>
      
        <label class="mt-3 py-0 price-range-display">Price Range: $<?= (isset($current_filter['min_price'])) ? $current_filter['min_price'] : 0; ?> - $<?= (isset($current_filter['max_price'])) ? $current_filter['max_price'] : 300; ?></label>
        <input type="hidden" value="<?= (isset($current_filter['min_price'])) ? $current_filter['min_price'] : 0; ?>" name="min_price" id="min_price">
        <input type="hidden" value="<?= (isset($current_filter['max_price'])) ? $current_filter['max_price'] : 300; ?>" name="max_price" id="max_price">
        <div id="price-slider"></div>

        <!-- <div slider id="slider-distance" class="mt-1">
          <div>
            <div inverse-left style="width:60%;"></div>
            <div inverse-right style="width:60%;"></div>
            <div range style="left:0%;right:0%;"></div>
            <span thumb style="left:0%;"></span>
            <span thumb style="left:100%;"></span>
            <div sign style="left:0%;">
              <span id="value">0</span>
            </div>
            <div sign style="left:100%;">
              <span id="value">300</span>
            </div>
          </div>
          <input type="range" value="0" name="min_price" max="300" min="0" step="1" oninput="
          this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[1].style.width=value+'%';
          children[5].style.left=value+'%';
          children[7].style.left=value+'%';children[11].style.left=value+'%';
          children[11].childNodes[1].innerHTML=this.value;" />

          <input type="range" value="300" name="max_price" max="300" min="0" step="1" oninput="
          this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[3].style.width=(100-value)+'%';
          children[5].style.right=(100-value)+'%';
          children[9].style.left=value+'%';children[13].style.left=value+'%';
          children[13].childNodes[1].innerHTML=this.value;" />
        </div> -->
  
        <label class="mt-3 py-0 thc-range-display">THC % Value: <?= (isset($current_filter['min_thc'])) ? $current_filter['min_thc'] : 0; ?>% - <?= (isset($current_filter['max_thc'])) ? $current_filter['max_thc'] : 100; ?>%</label>
        <input type="hidden" value="<?= (isset($current_filter['min_thc'])) ? $current_filter['min_thc'] : 0; ?>" name="min_thc" id="min_thc">
        <input type="hidden" value="<?= (isset($current_filter['max_thc'])) ? $current_filter['max_thc'] : 100; ?>" name="max_thc" id="max_thc">
        <div id="thc-slider"></div>

        <!-- <div slider id="slider-distance" class="mt-1">
          <div>
            <div inverse-left style="width:70%;"></div>
            <div inverse-right style="width:70%;"></div>
            <div range style="left:0%;right:0%;"></div>
            <span thumb style="left:0%;"></span>
            <span thumb style="left:100%;"></span>
            <div sign style="left:0%;">
              <span id="value">0</span>
            </div>
            <div sign style="left:100%;">
              <span id="value">100</span>
            </div>
          </div>

          <input type="range" value="0" name="min_thc" max="100" min="0" step="1" oninput="
          this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[1].style.width=value+'%';
          children[5].style.left=value+'%';
          children[7].style.left=value+'%';children[11].style.left=value+'%';
          children[11].childNodes[1].innerHTML=this.value;" />

          <input type="range" value="100" name="max_thc" max="100" min="0" step="1" oninput="
          this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[3].style.width=(100-value)+'%';
          children[5].style.right=(100-value)+'%';
          children[9].style.left=value+'%';children[13].style.left=value+'%';
          children[13].childNodes[1].innerHTML=this.value;" />
        </div> -->

        <label class="mt-3 py-0 cbd-range-display">CBD % Value: <?= (isset($current_filter['min_cbd'])) ? $current_filter['min_cbd'] : 0; ?>% - <?= (isset($current_filter['max_cbd'])) ? $current_filter['max_cbd'] : 100; ?>%</label>
        <input type="hidden" value="<?= (isset($current_filter['min_cbd'])) ? $current_filter['min_cbd'] : 0; ?>" name="min_cbd" id="min_cbd">
        <input type="hidden" value="<?= (isset($current_filter['max_cbd'])) ? $current_filter['max_cbd'] : 100; ?>" name="max_cbd" id="max_cbd">
        <div id="cbd-slider"></div>

        <!-- <div slider id="slider-distance" class="mt-1">
          <div>
            <div inverse-left style="width:70%;"></div>
            <div inverse-right style="width:70%;"></div>
            <div range style="left:0%;right:0%;"></div>
            <span thumb style="left:0%;"></span>
            <span thumb style="left:100%;"></span>
            <div sign style="left:0%;">
              <span id="value">0</span>
            </div>
            <div sign style="left:100%;">
              <span id="value">200</span>
            </div>
          </div>

          <input type="range" value="0" name="min_cbd" max="100" min="0" step="1" oninput="
          this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[1].style.width=value+'%';
          children[5].style.left=value+'%';
          children[7].style.left=value+'%';children[11].style.left=value+'%';
          children[11].childNodes[1].innerHTML=this.value;" />

          <input type="range" value="100" name="max_cbd" max="100" min="0" step="1" oninput="
          this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[3].style.width=(100-value)+'%';
          children[5].style.right=(100-value)+'%';
          children[9].style.left=value+'%';children[13].style.left=value+'%';
          children[13].childNodes[1].innerHTML=this.value;" />
        </div> -->

        <!-- <p>
        <label>CBD_value:</label>
        <input type="text" id="textInput1" value="0" style="border: 0;">
        </p>
        <input type="range" name="cbd_value" min="0" max="10" value="0" onchange="updateTextInput1(this.value);" >
        -->
        <!-- <input type="text" id="search" class="form-control w-20 border px-2" name="search" placeholder="Search here"> -->
        
        <button id="searchFormSubmit" class="btn bg-primary-green mt-5">Search</button>
        <div class="text-center mb-5"><a href="<?= base_url('shop'); ?>" id="clear-product-filter">Clear Filter</a></div>
      </div>
    </div>
  </form>
</div>