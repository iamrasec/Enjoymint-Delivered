<?php $this->extend("templates/base"); ?>

<?php $this->section("styles") ?>
<link id="pagestyle" href="<?php echo base_url('assets/css/faqs.css'); ?>" rel="stylesheet" />
<?php $this->endSection() ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">
  <section class="pt-3 pb-4" id="faqs-header">
    <div id="intro" class="container text-center p-6">
      <div class="row">
        <div class="col-12 col-md-12">
          <h3 class="page-title mb-5"><?= strtoupper($page_title); ?></h3>
          
          <div class="row">
            <div class="col-6 col-md-6 col-xs-12">
              <div id="how-it-works-faqs" class="faqs-controller">
              <i class="material-icons opacity-6 me-2" style="font-size: 30px;">sticky_note_2</i> How It Works
              </div>
            </div>
            <div class="col-6 col-md-6 col-xs-12">
              <div id="services-faqs" class="faqs-controller">
              <i class="material-icons opacity-6 me-2" style="font-size: 30px;">local_shipping</i> Services
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="pt-3 pb-4" id="faqs-content">
    <div class="hiw-content row d-none">
      <div class="col-10 col-md-10 mx-auto">
        <h3>How It Works</h3>
        <div class="accordion" id="faqs-content-accordion">
          <div class="accordion-item mb-3">
            <h5 class="accordion-header" id="hiwHeadingOne">
              <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hiwCollapseOne" aria-expanded="false" aria-controls="hiwCollapseOne">
                Does your menu prices include taxes?
                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
              </button>
            </h5>
            <div id="hiwCollapseOne" class="accordion-collapse collapse" aria-labelledby="hiwHeadingOne" data-bs-parent="#faqs-content-accordion" style="">
              <div class="accordion-body text-sm opacity-8">
                No. Prices displayed in our menu are pretax price.
              </div>
            </div>
          </div>
          <div class="accordion-item mb-3">
            <h5 class="accordion-header" id="hiwHadingTwo">
              <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hiwCollapseTwo" aria-expanded="false" aria-controls="hiwCollapseTwo">
                Can I schedule a Pre-Order?
                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
              </button>
            </h5>
            <div id="hiwCollapseTwo" class="accordion-collapse collapse" aria-labelledby="hiwHadingTwo" data-bs-parent="#faqs-content-accordion" style="">
              <div class="accordion-body text-sm opacity-8">
                To ensure that specific stock is available, you may schedule pre-orders hours or days in advance. Any same-day pre-order placed 4-6 hours in advance is eligible for a 5% discount. Any pre-order placed for next day delivery is eligible for a 10% discount.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="services-content row d-none">
      <div class="col-10 col-md-10 mx-auto">
        <h3>Services</h3>
        <div class="accordion" id="services-content-accordion">
          <div class="accordion-item mb-3">
            <h5 class="accordion-header" id="servicesHeadingOne">
              <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#servicesCollapseOne" aria-expanded="false" aria-controls="servicesCollapseOne">
                Do you only deliver to where I live?
                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
              </button>
            </h5>
            <div id="servicesCollapseOne" class="accordion-collapse collapse" aria-labelledby="servicesHeadingOne" data-bs-parent="#services-content-accordion" style="">
              <div class="accordion-body text-sm opacity-8">
                We can deliver to your home and many other places! As long as you possess a valid ID and meet the legal requirements for cannabis delivery, we will deliver to you at nearly any physical address. We must be able to find the street number to your delivery address in our navigation platform in order to dispatch a delivery. We do not deliver to Federal Buildings, State Parks, Airports, or Schools.
              </div>
            </div>
          </div>
          <div class="accordion-item mb-3">
            <h5 class="accordion-header" id="servicesHadingTwo">
              <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#servicesCollapseTwo" aria-expanded="false" aria-controls="servicesCollapseTwo">
                What are your delivery hours?
                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
              </button>
            </h5>
            <div id="servicesCollapseTwo" class="accordion-collapse collapse" aria-labelledby="servicesHadingTwo" data-bs-parent="#services-content-accordion" style="">
              <div class="accordion-body text-sm opacity-8">
                Hours of operation (Holidays are Subject to Change):<br>Everyday 10am-10pm
              </div>
            </div>
          </div>
          <div class="accordion-item mb-3">
            <h5 class="accordion-header" id="servicesHadingThree">
              <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#servicesCollapseThree" aria-expanded="false" aria-controls="servicesCollapseThree">
                Do you deliver in my area?
                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
              </button>
            </h5>
            <div id="servicesCollapseThree" class="accordion-collapse collapse" aria-labelledby="servicesHadingThree" data-bs-parent="#services-content-accordion" style="">
              <div class="accordion-body text-sm opacity-8">
                We currently serve: Daly City, Colma, Brisbane, South San Francisco, San Bruno, Millbrae, Atherton, Pacifica, San Mateo, Belmont, San Carlos, Foster City, Redwood City, Redwood Shores Palo Alto, East Palo Alto, Menlo Park Atherton, Stanford, Half Moon Bay, Miramar, El Granada, Moss Beach, Montara, Woodside, Portola Valley, Los Altos, Mountain View, Sunnyvale, Cupertino, Saratoga, San Jose, Santa Clara, Los Gatos, Monte Sereno, Milpitas, Morgan Hill, San Martin, Gilroy, Fremont, Newark, Union City, Sunol, Hayward, San Leandro, San Lorenzo, Castro Valley, Pleasanton, Dublin, Livermore, San Ramon, Danville, Alamo, Diablo, Blackhawk, Lafayette, Orinda, Moraga, Walnut Creek, Pleasant Hill, Clayton, Concord, Pacheco, Martinez, Bay Point, Pittsburg, Antioch
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php echo $this->include('sections/got_questions.php'); ?>

<?php $this->endSection() ?>

<?php $this->section("scripts") ?>
<script>
  $(document).ready(function() {
    $('#how-it-works-faqs').click(function(e) {
      e.preventDefault;
      $('.hiw-content').removeClass('d-none');
      $('.services-content').addClass('d-none');
      $('#services-faqs').removeClass('active');
      $(this).addClass('active');
    });

    $('#services-faqs').click(function(e) {
      e.preventDefault;
      $('.services-content').removeClass('d-none');
      $('.hiw-content').addClass('d-none');
      $('#how-it-works-faqs').removeClass('active');
      $(this).addClass('active');
    });
  });
</script>
<?php $this->endSection() ?>