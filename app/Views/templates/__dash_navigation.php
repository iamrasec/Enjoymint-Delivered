<!-- initialize uri service -->
<?php $uri = service('uri'); ?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="/dashboard" target="_blank">
        <img src="/assets/img/Enjoymint-Logo-Landscape-White-2.png" class="sidebar_logo" />
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto h-auto max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <!-- <li class="nav-item mb-2 mt-0">
          <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-white" aria-controls="ProfileNav" role="button" aria-expanded="false">
            <img src="../../assets/img/team-3.jpg" class="avatar">
            <span class="nav-link-text ms-2 ps-1">Brooklyn Alice</span>
          </a>
          <div class="collapse" id="ProfileNav">
            <ul class="nav ">
              <li class="nav-item">
                <a class="nav-link text-white" href="../../pages/pages/profile/overview.html">
                  <span class="sidenav-mini-icon"> MP </span>
                  <span class="sidenav-normal  ms-3  ps-1"> My Profile </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white " href="../../pages/pages/account/settings.html">
                  <span class="sidenav-mini-icon"> S </span>
                  <span class="sidenav-normal  ms-3  ps-1"> Settings </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white " href="../../pages/authentication/signin/basic.html">
                  <span class="sidenav-mini-icon"> L </span>
                  <span class="sidenav-normal  ms-3  ps-1"> Logout </span>
                </a>
              </li>
            </ul>
          </div>
        </li> -->
        <hr class="horizontal light mt-0">
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#dashboard" class="nav-link text-white active" aria-controls="dashboard" role="button" aria-expanded="false">
            <i class="material-icons-round opacity-10">dashboard</i>
            <span class="nav-link-text ms-2 ps-1">Products</span>
          </a>
          <div class="collapse  show " id="dashboard">
            <ul class="nav ">
              <li class="nav-item <?php if( $uri->getSegment(2) === 'products' AND $uri->getSegment(3) == "" ) { echo 'active'; }else { } ?>">
                <a class="nav-link text-white <?php if( $uri->getSegment(2) === 'products' AND $uri->getSegment(3) == "" ) { echo 'active'; }else { } ?>" href="<?php echo base_url('/admin/products'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Products List</span>
                </a>
              </li>
              <li class="nav-item <?= $uri->getSegment(3) == 'add_product' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(3) == 'add_product' ? 'active' : '' ?>" href="<?php echo base_url('/admin/products/add_product'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Add Product </span>
                </a>
              </li>
              <!--<li class="nav-item">
                <a class="nav-link text-white" href="/products/add_category">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Add Category </span>
                </a>
              </li>-->
              <li class="nav-item <?= $uri->getSegment(2) == 'categories' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(2) == 'categories' ? 'active' : '' ?>" href="<?php echo base_url('/admin/categories'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Manage Categories </span>
                </a>
              </li>
              <li class="nav-item <?= $uri->getSegment(2) == 'compounds' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(2) == 'compounds' ? 'active' : '' ?>" href="<?php echo base_url('/admin/compounds'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Manage Compounds </span>
                </a>
              </li>
              <li class="nav-item <?= $uri->getSegment(3) == 'strains' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(3) == 'strains' ? 'active' : '' ?>" href="<?php echo base_url('/admin/products/strains'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Manage Strains </span>
                </a>
              </li>
              <li class="nav-item <?= $uri->getSegment(3) == 'brands' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(3) == 'brands' ? 'active' : '' ?>" href="<?php echo base_url('/admin/products/brands'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Manage Brands </span>
                </a>
              </li>
              <li class="nav-item <?= $uri->getSegment(3) == 'measurements' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(3) == 'measurements' ? 'active' : '' ?>" href="<?php echo base_url('/admin/products/measurements'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Product Measurements </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white <?= $uri->getSegment(3) == 'reviews' ? 'active' : '' ?>"  href="<?php echo base_url('/admin/products/reviews'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Manage Reviews </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <hr class="horizontal light mt-0">
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#blog_pages" class="nav-link text-white" aria-controls="blog_pages" role="button" aria-expanded="false">
            <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">content_paste</i>
            <span class="nav-link-text ms-2 ps-1">Blogs / Pages</span>
          </a>
          <div class="collapse" id="blog_pages">
            <ul class="nav ">
              <li class="nav-item <?php if( $uri->getSegment(2) === 'blogs' AND $uri->getSegment(3) == "" ) { echo 'active'; }else { } ?>">
                <a class="nav-link text-white <?php if( $uri->getSegment(2) === 'blogs' AND $uri->getSegment(3) == "" ) { echo 'active'; }else { } ?>" href="<?php echo base_url('/admin/blogs'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> List Pages </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white " href="../../pages/dashboards/discover.html">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Add Page </span>
                </a>
              </li>
              <li class="nav-item <?= $uri->getSegment(3) == 'add_blog' ? 'active' : '' ?>">
                <a class="nav-link text-white <?= $uri->getSegment(3) == 'add_blog' ? 'active' : '' ?>" href="<?php echo base_url('/admin/blogs/add_blog'); ?>">
                  <span class="sidenav-mini-icon">  </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Add Blogss </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#ecommerce" class="nav-link text-white " aria-controls="ecommerce" role="button" aria-expanded="false">
            <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">shopping_basket</i>
            <span class="nav-link-text ms-2 ps-1">Ecommerce</span>
          </a>
          <div class="collapse " id="ecommerce">
            <ul class="nav ">
              <li class="nav-item ">
                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false" href="#productsExample">
                  <span class="sidenav-mini-icon"> P </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Products <b class="caret"></b></span>
                </a>
                <div class="collapse " id="productsExample">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/new-product.html">
                        <span class="sidenav-mini-icon"> N </span>
                        <span class="sidenav-normal  ms-2  ps-1"> New Product </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/edit-product.html">
                        <span class="sidenav-mini-icon"> E </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Edit Product </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/product-page.html">
                        <span class="sidenav-mini-icon"> P </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Product Page </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/products-list.html">
                        <span class="sidenav-mini-icon"> P </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Products List </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false" href="#ordersExample">
                  <span class="sidenav-mini-icon"> O </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Orders <b class="caret"></b></span>
                </a>
                <div class="collapse " id="ordersExample">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a class="nav-link text-white " href="<?= base_url('admin/order') ?>">
                        <span class="sidenav-mini-icon"> O </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Order List </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/orders/details.html">
                        <span class="sidenav-mini-icon"> O </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Order Details </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-white " href="../../pages/ecommerce/referral.html">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Referral </span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#integration" class="nav-link text-white " aria-controls="integration" role="button" aria-expanded="false">
            <i class="fa-solid fa-atom"></i>
            <span class="nav-link-text ms-2 ps-1">Integrations</span>
          </a>
          <div class="collapse " id="integration">
            <ul class="nav ">
              <li class="nav-item ">
                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false" href="#productsExample">
                  <span class="sidenav-mini-icon"> P </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Products <b class="caret"></b></span>
                </a>
                <div class="collapse " id="productsExample">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/new-product.html">
                        <span class="sidenav-mini-icon"> N </span>
                        <span class="sidenav-normal  ms-2  ps-1"> New Product </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/edit-product.html">
                        <span class="sidenav-mini-icon"> E </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Edit Product </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/product-page.html">
                        <span class="sidenav-mini-icon"> P </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Product Page </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/products/products-list.html">
                        <span class="sidenav-mini-icon"> P </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Products List </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false" href="#ordersExample">
                  <span class="sidenav-mini-icon"> O </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Orders <b class="caret"></b></span>
                </a>
                <div class="collapse " id="ordersExample">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/orders/list.html">
                        <span class="sidenav-mini-icon"> O </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Order List </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white " href="../../pages/ecommerce/orders/details.html">
                        <span class="sidenav-mini-icon"> O </span>
                        <span class="sidenav-normal  ms-2  ps-1"> Order Details </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-white " href="../../pages/ecommerce/referral.html">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal  ms-2  ps-1"> Referral </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        
      </ul>
    </div>
  </aside>