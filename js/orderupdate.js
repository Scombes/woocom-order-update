/**
*
* Javascript for Order Update
*
**/
jQuery(document).ready(function($) {

  //Click function to update and create new schedule
  $( "#OrderUpdateSCsubmit" ).on( "click", function(event) {
      //Variables
      var oldTime = $('#oldTime').val();
      var newTime = $('#newTime').val();
      var freq = $('#frequency').val();
      //If there is a new schedule time, use AJAX to update schedule
      if(oldTime!==newTime){
        var data = {
                    'action': 'create_cronjob_sc',
                    'newTime': newTime,
                    'oldTime': oldTime,
                    'frequency': freq
                  };
        //Ajax             
        jQuery.post(ajaxurl, data, function(response) {
          $('#saveResutls').html('Settings saved. Schedule updated.')
        });
      } else{
        $('#saveResutls').html('Settings saved.')
      }
  });//End submit function

  $('#orderupdate-FTPTest').on('click', function(event){
    event.preventDefault();
    $('#ftpTestResults').html('');
    $('#order_update_loading_gif').toggle();
    var order_host = $('#ftp_host_sc').val();
    var order_user = $('#ftp_user_sc').val();
    var order_password = $('#ftp_pass_sc').val();
    var data = {
                'action': 'order_test_ftp',
                'order_host': order_host,
                'order_user': order_user,
                'order_password' : order_password
               };
    jQuery.post(ajaxurl, data, function(response) {
      $('#order_update_loading_gif').toggle();
      $('#ftpTestResults').html(response);

    });
  });//End FTP Test

}); //End Document Ready function
