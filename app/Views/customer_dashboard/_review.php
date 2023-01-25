<div class="card mt-3">
  <div class="card-header pb-0"><h6>To Review Orders</h6></div>
  <div class="card-body pt-1">
    <div class="row mb-5">
      <div class="col-12 col-md-12 col-xs-12">
        <?php foreach($orders as $review_orders): ?>
        <pre><?php //print_r($active_orders); ?></pre>
        <div class="row border mb-3"  >
          <div class="col-12 col-md-12 col-xs-12 mx-0 px-0">
            <div class="order-list-header bg-primary-green d-flex flex-column flex-md-row text-white px-2 py-2">
              <div class="px-2 py-0 py-md-2">
                <strong>Order # <?= $review_orders['id']; ?></strong>
              </div>
              <div class="px-2 px-md-4 py-0 py-md-2">
                <?php 
                $date = $review_orders['created'];
                $timestamp = strtotime($date);
                $display_date = date("F j, Y, g:i a", $timestamp);
                ?>
                <strong><?= $display_date; ?></strong>
              </div>
              <div class="px-2 px-md-4 py-0 py-md-2">
                <strong>Total Cost: $<?= $review_orders['total']; ?></strong>
              </div>
              <?php if($review_orders['delivery_schedule'] != '' && $review_orders['delivery_time'] != ''): ?>
                <?php
                $del_time = explode("-", $review_orders['delivery_time']);
                $del_time_from = $del_time[0];
                $del_time_to = $del_time[1];

                if($del_time_from > 1200) {
                  $del_time_from = ($del_time_from - 1200) . ' PM';
                }
                else {
                  $del_time_from = $del_time_from . ' AM';
                }

                if($del_time_to > 1200) {
                  $del_time_to = ($del_time_to - 1200) . ' PM';
                }
                else {
                  $del_time_to = $del_time_to . ' AM';
                }

                $del_time_from = substr_replace($del_time_from, ':', -5, 0);
                $del_time_to = substr_replace($del_time_to, ':', -5, 0);
                ?>
                <div class="px-2 px-md-4 py-0 py-md-2">
                  <strong>Delivery Schedule: <?= $review_orders['delivery_schedule']; ?> @ <?= $del_time_from; ?> - <?= $del_time_to; ?></strong>
                </div>
              <?php endif; ?>
            </div>
            <?php foreach($review_orders['products'] as $products): ?>
              <?php if(isset($products->images[0])): ?>
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
              <div class="row" id="myform"  >
              <h6>Rate Here</6>   
              <form role="form" method="post" action="/products/rating">
              <div class="col-sm-6" >
                      <div class="input-group input-group-outline mb-3">
                        <!-- <?php for($y=5;$y>0;$y--): ?>
                          <i class="material-icons text-lg">star_outline</i>
                          <input type="text" name="result" hidden>
                          <?php endfor; ?> -->
                          <i class="material-icons text-lg stars" data-id="1" id="star_1">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="2" id="star_2">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="3" id="star_3">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="4" id="star_4">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="5" id="star_5">star_outline</i>
                          <input type="hidden" name="ratings" id="ratings" value="">
                      </div>
                      <div class="input-group input-group-outline mb-3">
                        <label class="form-label">Comment</label>
                        
                        <input type="text" name="message" class="form-control"> 
                        <input type="hidden" value="<?= $review_orders['id']; ?>" name="id">
                        <input type="hidden" name="customer_id" value="<?= $user_data['id']; ?>" class="form-control">                  
                      </div>                                  
                  </div>
                  <button type="submit" class="login-btn btn btn-lg bg-gradient-primary btn-sm mt-4 mb-0">Submit</button>
              </form>
                </div>
            </div>
              <?php else: ?>
               <img class="prod_image" src="" /></a>
              <?php endif; ?>
            <?php endforeach; ?>
            <div class="row p-2 d-none d-md-block">
            <?php foreach($review_orders['products'] as $products): ?>
              <?php if(isset($products->images[0])): ?>
              <table>
                <thead>
                  <td></td>
                  <td><strong>Product Title</strong></td>
                  <td><strong>Qty</strong></td>
                  <td><strong>Unit Price</strong></td>
                  <td><strong>Total</strong></td>
                </thead>
                <tbody>
                 
                  <tr>
                    <td class="px-2 text-center"><img class="prod_image" src="<?= base_url('products/images/'.$products->images[0]->filename); ?>" /></td>
                    <td><?= $products->product_name; ?></td>
                    <td><?= $products->qty; ?></td>
                    <td>$<?= $products->unit_price; ?></td>
                    <td>$<?= $products->total; ?></td>
                    
                  </tr>
                  
                  
               
                </tbody>
                 
              </table>
              <div class="row" id="myform"  style=" margin-left:290px;">
              <h6>Rate Here</6>   
              <form role="form" method="post" action="/products/rating">
              <div class="col-sm-6" >
                      <div class="input-group input-group-outline mb-3">
                        <!-- <?php for($y=5;$y>0;$y--): ?>
                          <i class="material-icons text-lg">star_outline</i>
                          <input type="text" name="result" hidden>
                          <?php endfor; ?> -->
                          <i class="material-icons text-lg stars" data-id="1" id="star_1">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="2" id="star_2">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="3" id="star_3">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="4" id="star_4">star_outline</i>
                          <i class="material-icons text-lg stars" data-id="5" id="star_5">star_outline</i>
                          <input type="hidden" name="ratings" id="ratings" value="">
                      </div>
                      <div class="input-group input-group-outline mb-3">
                        <label class="form-label">Comment</label>
                        
                        <input type="text" name="message" class="form-control"> 
                        <input type="hidden" value="<?= $review_orders['id']; ?>" name="id">
                        <input type="hidden" name="customer_id" value="<?= $user_data['id']; ?>" class="form-control">                  
                      </div>                                  
                  </div>
                  <button type="submit" class="login-btn btn btn-lg bg-gradient-primary btn-sm mt-4 mb-0">Submit</button>
              </form>
                </div>  
                <hr> 
                  <?php else: ?>
                  <img class="prod_image" src="" /></a>
                  <?php endif; ?>
                <?php endforeach; ?>
                
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div><?= $pager->links() ?></div>
  </div>
  
  
     

      

<?php $this->section('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
 $(function(){
   $('#show').on('click',function(){  
      $('#myform').show();
   });
});

    $("body").delegate(".stars", "click", function(){
    let count = $(this).data('id');
    for(var x=1;x<=5;x++){
      count >= x ?  $('#star_'+x).html('grade') : $('#star_'+x).html('star_outline');
      
    }
    document.getElementById('ratings').value= count;
  });

</script>
<?php $this->endSection(); ?>