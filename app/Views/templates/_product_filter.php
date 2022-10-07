<div id="product_filter" class="product-filter-form card col-lg-2 mt-2 mt-lg-8 d-lg-flex">
  <h5>Filter By:</h5>
  <form method='get' action="<?= base_url('/shop/')?>" id="searchForm">
    <div class="row">

      <div class="select-box" >
        <select class="selected" name="category">
          <option value="0">Category</option>
          <?php foreach($categories as $category): ?>
          <?php  echo '<option value="'.$category->id.'" '.((isset($_GET['category']) && $category->id == $_GET['category']) ? 'selected' : '').'>'.ucfirst($category->name).'</option>' ?>
          <?php endforeach; ?>
        </select>
  
        <select class="selected" id="strain" name="strain">
          <option value="0">Strain Type</option>
          <?php foreach($strains as $str): ?>
          <?php  echo '<option value="'.$str->id.'" '.((isset($_GET['strain']) && $str->id == $_GET['strain']) ? 'selected' : '').'>'.ucfirst($str->url_slug).'</option>' ?>
          <?php endforeach; ?>
        </select>
    
        <select class="selected" name="brands">
          <option value="0">Brand</option>
          <?php foreach($brands as $brand): ?>
          <?php  echo '<option value="'.$brand->id.'" '.((isset($_GET['brands']) && $brand->id == $_GET['brands']) ? 'selected' : '').'>'.ucfirst(strtolower($brand->name)).'</option>' ?>
          <?php endforeach; ?>
        </select>
      
        <label>Price Range:</label>
        <div slider id="slider-distance" class="mt-1">
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

          <input type="range" value="100" name="max_price" max="300" min="0" step="1" oninput="
          this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[3].style.width=(100-value)+'%';
          children[5].style.right=(100-value)+'%';
          children[9].style.left=value+'%';children[13].style.left=value+'%';
          children[13].childNodes[1].innerHTML=this.value;" />
        </div>
  
        <label class="mt-2">THC Value:</label>
        <div slider id="slider-distance" class="mt-1">
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
        </div>

        <label class="mt-2">CBD Value:</label>
        <div slider id="slider-distance" class="mt-1">
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

          <input type="range" value="0" name="min_cbd" max="200" min="0" step="1" oninput="
          this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[1].style.width=value+'%';
          children[5].style.left=value+'%';
          children[7].style.left=value+'%';children[11].style.left=value+'%';
          children[11].childNodes[1].innerHTML=this.value;" />

          <input type="range" value="100" name="max_cbd" max="200" min="0" step="1" oninput="
          this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
          let value = (this.value/parseInt(this.max))*100
          var children = this.parentNode.childNodes[1].childNodes;
          children[3].style.width=(100-value)+'%';
          children[5].style.right=(100-value)+'%';
          children[9].style.left=value+'%';children[13].style.left=value+'%';
          children[13].childNodes[1].innerHTML=this.value;" />
        </div>

        <label class="mt-2">Test Slider:</label>
        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">

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