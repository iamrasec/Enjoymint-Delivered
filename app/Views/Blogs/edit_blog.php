<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>
<style>
  .breaker {
    margin-top: 10px;
  }
</style>
<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <form id="edit_blog" class="enjoymint-form" enctype="multipart/form-data">
      <?php foreach ($blog_data as $blog): ?>  
    <input type="hidden" value="<?= $blog->id; ?>" name="id" id="id">
    
      <div class="row">
        <div class="col-lg-6">
          <h4><?php echo $page_title; ?></h4>
        </div>
        <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
          <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12 col-xs-12 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">Blog Information</h5>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Title</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="title" class="form-control w-100 border px-2" value="<?= $blog->title; ?>" name="title" required>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-md-12 col-xs-12">
                  <div class="row">
                    <label class="form-label">Blog URL</label>
                    <div class="col-md-12 col-xs-12 mb-3">
                      <div class="input-group input-group-dynamic">
                        <p class="text-xs mt-3 float-end px-0"><?php echo base_url(); ?>/blogs/</p>&nbsp;
                        <input type="text" value="<?= $blog->url; ?>" id="burl" class="form-control border px-2"  name="burl" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-md-12 col-xs-12">
                  <label class="form-label">Author</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" class="form-control w-100 border px-2" id="author" name="author"  value="<?= $blog->author; ?>" required>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Description</label>
                  <div class="input-group input-group-dynamic">
                   <textarea id="description" class="form-control w-100 border px-2" name="description" value="" required><?= $blog->description; ?></textarea>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
 
              <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Content</label>
                  <div class="input-group input-group-dynamic">
                    <div id="quill_editor" class="w-100"><?= $blog->content; ?></div>
                    <input type="hidden" id="quill_content" value="<?= $blog->content; ?>"  name="content">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Main Image</label>
                  <div class="input-group input-group-dynamic">
                    <input type="file" id="blog_image" name="blog_image" accept="image/png, image/jpeg, image/jpg">
                  </div>
                </div>
              </div>

              <div class="row mt-3 mb-5">
                <div class="col-lg-12 text-right d-flex flex-column justify-content-end">
                  <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <?php endforeach ;?>
    </form>
  </div>
</main>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/edit_blog.js"></script>
<script>
  var toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'code-block'],

      [{ 'header': 1 }, { 'header': 2 }],               // custom button values
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
      [{ 'direction': 'rtl' }],                         // text direction

      [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      [ 'link', 'image', 'video', 'formula' ],          // add's image support
      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
      [{ 'font': [] }],
      [{ 'align': [] }],

      ['clean']                                         // remove formatting button
  ];

  var quill = new Quill('#quill_editor', {
    modules: {
      'toolbar': toolbarOptions
    },
    theme: 'snow'
  });

  quill.on('text-change', function(delta, oldDelta, source) {
    document.getElementById("quill_content").value = quill.root.innerHTML;
  });
</script>
<?php $this->endSection() ?>