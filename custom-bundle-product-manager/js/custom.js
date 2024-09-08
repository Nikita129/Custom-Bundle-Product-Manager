jQuery(function()
{
        new DataTable('#tbl-product');

        jQuery("#bundle-product-form").validate();

// add media model
jQuery("#btn-upload-image").on("click", function(event){
      
      event.preventDefault();

      // create media instance

      let mediaUploader = wp.media({
          title: "Select Profile Image",
          multiple: false
      })

      // select image handle

      mediaUploader.on("select", function(){

          let attachment = mediaUploader.state().get("selection").first().toJSON();
          console.log(attachment);
          jQuery("#image-url").val(attachment.url);
      });

      // open media model

      mediaUploader.open();

      
  });



});

