<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <!-- <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-3 text-dark" href="javascript:;">
                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <title>shop </title>
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                      <g transform="translate(1716.000000, 291.000000)">
                        <g transform="translate(0.000000, 148.000000)">
                          <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                          <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                        </g>
                      </g>
                    </g>
                  </g>
                </svg>
              </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Sales</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Sales</h6>
        </nav> -->
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
          <a href="javascript:;" class="nav-link text-body p-0">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </div>
        <!-- <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Search here</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item">
              <a href="../../pages/authentication/signin/illustration.html" class="nav-link text-body p-0 position-relative" target="_blank">
                <i class="material-icons me-sm-1">
              account_circle
            </i>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3">
              <a href="javascript:;" class="nav-link text-body p-0">
                <i class="material-icons fixed-plugin-button-nav cursor-pointer">
              settings
            </i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2">
              <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-icons cursor-pointer">
              notifications
            </i>
                <span class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2">
                  <span class="small">11</span>
                  <span class="visually-hidden">unread notifications</span>
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex align-items-center py-1">
                      <span class="material-icons">email</span>
                      <div class="ms-2">
                        <h6 class="text-sm font-weight-normal my-auto">
                          Check new messages
                        </h6>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex align-items-center py-1">
                      <span class="material-icons">podcasts</span>
                      <div class="ms-2">
                        <h6 class="text-sm font-weight-normal my-auto">
                          Manage podcast session
                        </h6>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex align-items-center py-1">
                      <span class="material-icons">shopping_cart</span>
                      <div class="ms-2">
                        <h6 class="text-sm font-weight-normal my-auto">
                          Payment successfully completed
                        </h6>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div> -->
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-3">
      <div class="row">
        <div class="col-sm-3">
          <div class="card">
            <div class="card-body p-3 position-relative">
              <di v class="row">
                <div class="col-7 text-start">
                  <p class="text-sm mb-1 text-capitalize font-weight-bold">Sales</p>
                  <h5 class="font-weight-bolder mb-0">
                    $230,220
                  </h5>
                  <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">+55% <span class="font-weight-normal text-secondary">since last month</span></span>
                </div>
                <div class="col-5">
                  <div class="dropdown text-end">
                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers1" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="text-xs text-secondary">6 May - 7 May</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUsers1">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
        <div class="col-sm-3 mt-sm-0 mt-3">
          <div class="card">
            <div class="card-body p-3 position-relative">
              <div class="row">
                <div class="col-7 text-start">
                  <p class="text-sm mb-1 text-capitalize font-weight-bold">Customers</p>
                  <h5 class="font-weight-bolder mb-0">
                    3.200
                  </h5>
                  <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">+12% <span class="font-weight-normal text-secondary">since last month</span></span>
                </div>
                <div class="col-5">
                  <div class="dropdown text-end">
                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers2" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="text-xs text-secondary">6 May - 7 May</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUsers2">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3 mt-sm-0 mt-3">
          <div class="card">
            <div class="card-body p-3 position-relative">
              <div class="row">
                <div class="col-7 text-start">
                  <p class="text-sm mb-1 text-capitalize font-weight-bold">Avg. Revenue</p>
                  <h5 class="font-weight-bolder mb-0">
                    $1.200
                  </h5>
                  <span class="font-weight-normal text-secondary text-sm"><span class="font-weight-bolder text-success">+$213</span> since last month</span>
                </div>
                <div class="col-5">
                  <div class="dropdown text-end">
                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers3" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="text-xs text-secondary">6 May - 7 May</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUsers3">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3 mt-sm-0 mt-3">
          <div class="card">
            <div class="card-body p-3 position-relative">
              <div class="row">
                <div class="col-7 text-start">
                  <p class="text-sm mb-1 text-capitalize font-weight-bold">Active Users</p>
                  <h5 class="font-weight-bolder mb-0">
                    34
                  </h5>
                  <p class="text-sm mb-1 text-capitalize font-weight-bold">Pending Users</p>
                  <h5 class="font-weight-bolder mb-0">
                  5
                  </h5>
                </div>
                <div class="col-5">
                  <div class="dropdown text-end">
                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers3" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="text-xs text-secondary">6 May - 7 May</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUsers3">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-4 col-sm-6">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-0">Channels</h6>
                <button type="button" class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="See traffic channels">
                  <i class="material-icons text-sm">priority_high</i>
                </button>
              </div>
            </div>
            <div class="card-body pb-0 p-3 mt-4">
              <div class="row">
                <div class="col-7 text-start">
                  <div class="chart">
                    <canvas id="chart-pie" class="chart-canvas" height="200"></canvas>
                  </div>
                </div>
                <div class="col-5 my-auto">
                  <span class="badge badge-md badge-dot me-4 d-block text-start">
                    <i class="bg-info"></i>
                    <span class="text-dark text-xs">Facebook</span>
                  </span>
                  <span class="badge badge-md badge-dot me-4 d-block text-start">
                    <i class="bg-primary"></i>
                    <span class="text-dark text-xs">Direct</span>
                  </span>
                  <span class="badge badge-md badge-dot me-4 d-block text-start">
                    <i class="bg-dark"></i>
                    <span class="text-dark text-xs">Organic</span>
                  </span>
                  <span class="badge badge-md badge-dot me-4 d-block text-start">
                    <i class="bg-secondary"></i>
                    <span class="text-dark text-xs">Referral</span>
                  </span>
                </div>
              </div>
            </div>
            <div class="card-footer pt-0 pb-0 p-3 d-flex align-items-center">
              <div class="w-60">
                <p class="text-sm">
                  More than <b>1,200,000</b> sales are made using referral marketing, and <b>700,000</b> are from social media.
                </p>
              </div>
              <div class="w-40 text-end">
                <a class="btn bg-light mb-0 text-end" href="javascript:;">Read more</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8 col-sm-6 mt-sm-0 mt-4">
          <div class="card">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-0">Revenue</h6>
                <button type="button" class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="See which ads perform better">
                  <i class="material-icons text-sm">priority_high</i>
                </button>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge badge-md badge-dot me-4">
                  <i class="bg-primary"></i>
                  <span class="text-dark text-xs">Facebook Ads</span>
                </span>
                <span class="badge badge-md badge-dot me-4">
                  <i class="bg-dark"></i>
                  <span class="text-dark text-xs">Google Ads</span>
                </span>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-8">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-0">Sales by Age</h6>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-bar" class="chart-canvas" height="340"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-0">Sales by Area</h6>
              </div>
            </div>
            <div class="card-body p-3">
              <ul class="list-group list-group-flush list my--3">
                <li class="list-group-item px-0 border-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Country flag -->
                      <img src="../../assets/img/icons/flags/US.png" alt="Country flag">
                    </div>
                    <div class="col">
                      <p class="text-xs font-weight-bold mb-0">City:</p>
                      <h6 class="text-sm font-weight-normal mb-0">Los Angeles</h6>
                    </div>
                    <div class="col text-center">
                      <p class="text-xs font-weight-bold mb-0">Sales:</p>
                      <h6 class="text-sm font-weight-normal mb-0">2500</h6>
                    </div>
                    <div class="col text-center">
                      <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                      <h6 class="text-sm font-weight-normal mb-0">29.9%</h6>
                    </div>
                  </div>
                  <hr class="horizontal dark mt-3 mb-1">
                </li>
                <li class="list-group-item px-0 border-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Country flag -->
                      <img src="../../assets/img/icons/flags/US.png" alt="Country flag">
                    </div>
                    <div class="col">
                      <p class="text-xs font-weight-bold mb-0">City:</p>
                      <h6 class="text-sm font-weight-normal mb-0">San Francisco</h6>
                    </div>
                    <div class="col text-center">
                      <p class="text-xs font-weight-bold mb-0">Sales:</p>
                      <h6 class="text-sm font-weight-normal mb-0">2500</h6>
                    </div>
                    <div class="col text-center">
                      <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                      <h6 class="text-sm font-weight-normal mb-0">29.9%</h6>
                    </div>
                  </div>
                  <hr class="horizontal dark mt-3 mb-1">
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Top Selling Products</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Average Qty</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

<?php $this->endSection() ?>

<?php $this->section("scripts") ?>
  <!-- Kanban scripts -->
  <script src="<?php echo base_url(); ?>/assets/js/plugins/dragula/dragula.min.js"></script>
  <script src="<?php echo base_url(); ?>/assets/js/plugins/jkanban/jkanban.js"></script>
  <script src="<?php echo base_url(); ?>/assets/js/plugins/chartjs.min.js"></script>

  <script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");
    var ctx2 = document.getElementById("chart-pie").getContext("2d");
    var ctx3 = document.getElementById("chart-bar").getContext("2d");

    // Line chart
    new Chart(ctx1, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Facebook Ads",
            tension: 0,
            pointRadius: 5,
            pointBackgroundColor: "#e91e63",
            pointBorderColor: "transparent",
            borderColor: "#e91e63",
            borderWidth: 4,
            backgroundColor: "transparent",
            fill: true,
            data: [50, 100, 200, 190, 400, 350, 500, 450, 700],
            maxBarThickness: 6
          },
          {
            label: "Google Ads",
            tension: 0,
            borderWidth: 0,
            pointRadius: 5,
            pointBackgroundColor: "#3A416F",
            pointBorderColor: "transparent",
            borderColor: "#3A416F",
            borderWidth: 4,
            backgroundColor: "transparent",
            fill: true,
            data: [10, 30, 40, 120, 150, 220, 280, 250, 280],
            maxBarThickness: 6
          }
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: '#c1c4ce5c'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#9ca2b7',
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: true,
              borderDash: [5, 5],
              color: '#c1c4ce5c'
            },
            ticks: {
              display: true,
              color: '#9ca2b7',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });


    // Pie chart
    new Chart(ctx2, {
      type: "pie",
      data: {
        labels: ['Facebook', 'Direct', 'Organic', 'Referral'],
        datasets: [{
          label: "Projects",
          weight: 9,
          cutout: 0,
          tension: 0.9,
          pointRadius: 2,
          borderWidth: 1,
          backgroundColor: ['#17c1e8', '#e91e63', '#3A416F', '#a8b8d8'],
          data: [15, 20, 12, 60],
          fill: false
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              color: '#c1c4ce5c'
            },
            ticks: {
              display: false
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              color: '#c1c4ce5c'
            },
            ticks: {
              display: false,
            }
          },
        },
      },
    });

    // Bar chart
    new Chart(ctx3, {
      type: "bar",
      data: {
        labels: ['16-20', '21-25', '26-30', '31-36', '36-42', '42-50', '50+'],
        datasets: [{
          label: "Sales by age",
          weight: 5,
          borderWidth: 0,
          borderRadius: 4,
          backgroundColor: '#3A416F',
          data: [15, 20, 12, 60, 20, 15, 25],
          fill: false
        }],
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: '#c1c4ce5c'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#c1c4ce5c',
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: true,
              drawTicks: true,
              color: '#9ca2b7'
            },
            ticks: {
              display: true,
              color: '#9ca2b7',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    if (document.getElementById('edit-deschiption')) {
      var quill = new Quill('#edit-deschiption', {
        theme: 'snow' // Specify theme in configuration
      });
    };

    if (document.getElementById('choices-category')) {
      var element = document.getElementById('choices-category');
      const example = new Choices(element, {
        searchEnabled: false
      });
    };

    if (document.getElementById('choices-sizes')) {
      var element = document.getElementById('choices-sizes');
      const example = new Choices(element, {
        searchEnabled: false
      });
    };

    if (document.getElementById('choices-currency')) {
      var element = document.getElementById('choices-currency');
      const example = new Choices(element, {
        searchEnabled: false
      });
    };

    if (document.getElementById('choices-tags')) {
      var tags = document.getElementById('choices-tags');
      const examples = new Choices(tags, {
        removeItemButton: true
      });

      examples.setChoices(
        [{
            value: 'One',
            label: 'Expired',
            disabled: true
          },
          {
            value: 'Two',
            label: 'Out of Stock',
            selected: true
          }
        ],
        'value',
        'label',
        false,
      );
    }
  </script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
<?php $this->endSection() ?>