(function($) {
    $(document).on('keyup', '#title', function() {
      $('#burl').val(slugify($(this).val()));
    });
  
    $(document).on('click', '.btn-modal', function(e) {
      e.preventDefault();
      
      return false;
    });
  
    /** this event will serve as adding of new image file input on clicking plus icon */
    $("#add_image").click(function () {
      var fileInputForm = 
      "<div class=\"row\"><div class=\"col-lg-10\">" +
        "<input type=\"file\" name=\"images[]\" accept=\"image/png, image/jpeg, image/jpg\" class=\"form-control\">" +
      "</div>" +
      "<div class=\"col-lg-2\">"+
      "  <button type=\"button\" class=\"btn bg-gradient-danger btn-sm remove_image\"><span class=\"material-icons\">delete</span></button>"+
      "</div></div>";
      $("#image_lists").append(fileInputForm); 
    });
  
    /** this event will serve as removing of new added file input */
    $("body").delegate(".remove_image", "click", function(){
      $(this).parent().parent().remove();
    });
  
    /** this event will serve as removing of new added file input */
    $("body").delegate(".remove_variant", "click", function(){
      $(this).parent().parent().remove();
    });
  
    $("#add_blog").submit(function(e) {
      e.preventDefault(); // avoid to execute the actual submit of the form.
  
      const formData = new FormData();
      const photos = document.querySelectorAll('input[type="file"]');

      console.log(photos);
    
      photos.forEach(function (item, field) {
        console.log(item.files);
        console.log(field);
        formData.append('blog_image', item.files[0]);
      });
  
      formData.append('title', $('#title').val());
      formData.append('url', $('#burl').val());
      formData.append('description', $('#description').val());
      formData.append('content', $('#quill_content').val());
      formData.append('author', $('#author').val());
  
      fetch('/api/blogs/add',  {
        method: 'POST',
        body: formData,
        headers : {
          'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
        }
      }) .then(response => response.json()).then(response => {
          var { message, success }  = response;
          success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/blogs') : enjoymintAlert('Sorry!', message, 'error', 0);
      }).catch((error) => {
          console.log('Error:', error);
      });
    });
  })(jQuery);