$(document).ready(function(){




  function showAllFromDatabase() {

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  $("#baza").append(this.responseText);
                  console.log('title',$('div.title'));




                  $("[data-target='#myModal']").on('click', function(){

                    var idEvent=$(this).parent().parent().data('id');
                    var dateEvent=$(this).parent().parent().data('date');
                    var titleEvent=$(this).parent().prev().find('h2 a').text();
                    $('#myModalLabel').html(dateEvent+"<br>"+titleEvent);
                    $("#modalForm input[name='id']").val(idEvent);
                    $('div.modal-footer').text("");
                    $('div.modal-body-button').show();

                  });

                  $("[data-target='#modalBiletInfo']").on('click', function(event){
                    console.log($(this).attr('href'));
                    $( "#modalBiletInfo" ).css('display','none');
                    // event.stopPropagation();
                    // var idEvent=$(this).parent().parent().data('id');
                    // var dateEvent=$(this).parent().parent().data('date');
                    // var titleEvent=$(this).parent().prev().find('h2 a').text();
                    // $('#modalBiletInfo').text(dateEvent+" : "+titleEvent);
                    if ($(this).attr('href') == "#") {
                      console.log($('#modalBiletInfo div.modal-body p'));
                      $('#modalBiletInfo div.modal-body p').html('Brak możliwości zakupu biletów on-line. <br> Skorzystaj z opcji rezerwacji.');
                      // $('#modalBiletInfo').show();
                      // $( "#modalBiletInfo" ).addClass('in');
                      // $('body').addClass('modal-open');
                      // $('body').append("<div class='modal-backdrop fade in'></div>");
                      $( "#modalBiletInfo" ).css('display','block');

                      // $(this).trigger('click');
                    } else {
                      $('#modalBiletInfo div.modal-body p').text('');
                      window.location.href = $(this).attr('href');
                    };
                    //
                    // };

                    // $("#modalForm input[name='id']").val(idEvent);
                    // $('div.modal-footer').text("");
                    // $('div.modal-body-button').show();

                  });

                  $('a.eventTitle').on('click',function(){
                    console.log($('a.eventTitle'));
                    var $eventDescription = $(this).parent().parent().parent().next();
                    if ($eventDescription.hasClass('hiddenDescription') == false) $(".descriptionProgramEvent").addClass('hiddenDescription');
                    else {
                      $(".descriptionProgramEvent").addClass('hiddenDescription');
                      $eventDescription.toggleClass('hiddenDescription');
                    }
                    // $(this).next().fadeOut();
                  });
              }
          };
          xmlhttp.open("GET", "php/showRowArchiwum.php", true);
          xmlhttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
          xmlhttp.send();
  }
  showAllFromDatabase();




  $('#modalForm div').on('click', function(){
    console.log('reserw');
    $inputs = $(this).parent().find('input');
    var email = $inputs.eq(0).val();
    var count = $inputs.eq(1).val();
    var id    = $inputs.eq(2).val();

    function validateemail(emailToValidate) {

        if(!/^[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*@([a-zA-Z0-9_-]+)(\.[a-zA-Z0-9_-]+)*(\.[a-zA-Z]{2,4})$/i.test(emailToValidate)) {
          return false;
        }
        else {
          return true;
        }
      };
    var $footer = $(this).parent().parent().next();
    if (email == "") {
      $footer.text('Pole email jest puste...');
    }
    else if (validateemail(email) == false) {
      $footer.text('Nieprawidłowy email : '+email);
    }
    else if (count == ""){
      $footer.text('A jaką ilość biletów zarezerwować? (min:1 || max:6)');
    }
    else if ((parseInt(count) < 1) || (parseInt(count) > 6)){
      $footer.text('Ilość biletów musi być od 1 do 6...');
    }
    else {
      var $rezerwacja = this;
      count = parseInt(count);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // $("#baza").append(this.responseText);
                      // if (this.responseText == 'OK')
                      {
                        $('div.modal-footer').text("Email z potwierdzeniem został wysłany!");
                        $($rezerwacja).hide();
                      }
                    };

            };
            xmlhttp.open("GET", "php/makeReservation.php?id="+id+"&email="+email+"&count="+count, true);
            xmlhttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
            xmlhttp.send();
    };
  });

  $('#modalNewsletterForm div').on('click', function(){
    console.log('reserw');
    $inputs = $(this).parent().find('input');
    var name       = $inputs.eq(0).val();
    var familyName = $inputs.eq(1).val();
    var email      = $inputs.eq(2).val();

    function validateemail(emailToValidate) {

        if(!/^[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*@([a-zA-Z0-9_-]+)(\.[a-zA-Z0-9_-]+)*(\.[a-zA-Z]{2,4})$/i.test(emailToValidate)) {
          return false;
        }
        else {
          return true;
        }
      };
    var $footer = $(this).parent().parent().next();
    if (email == "") {
      $footer.text('Pole email jest puste...');
    }
    else if (validateemail(email) == false) {
      $footer.text('Nieprawidłowy email : '+email);
    }
    else {
      var $potwierdzenie = this;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // $("#baza").append(this.responseText);
                      // if (this.responseText == 'OK')
                      {
                        $footer.text("Email z potwierdzeniem został wysłany!");
                        console.log($(this).parent().next());
                        $($potwierdzenie).hide();
                      }
                    };

            };
            xmlhttp.open("GET", "php/newsletterSave.php?name="+name+"&email="+email+"&familyName="+familyName, true);
            xmlhttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
            xmlhttp.send();
    };
  });



// google map
  var myCenter = new google.maps.LatLng(52.4080771, 16.9211261);

  function initialize() {
  var mapProp = {
  center:myCenter,
  zoom:17,
  scrollwheel:false,
  draggable:false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

  var marker = new google.maps.Marker({
  position:myCenter,

  });

  marker.setMap(map);
  }

  google.maps.event.addDomListener(window, 'load', initialize);


});
