$(document).ready(function(){

  $('button').on('click',function(){

          var jqxhr = $.get( "php/test.php",
          { x: "1", y: "2", z: "3" },
          function(data) {
            // alert( "success" + data );
            $('#wynik').append("<p>"+data+"</p>");
          })
            .done(function() {
              alert( "second success" );
            })
            .fail(function() {
              alert( "error" );
            })
            .always(function() {
              alert( "finished" );
            });

          jqxhr.always(function() {
            alert( "second finished" );
          });
  });//button
});
