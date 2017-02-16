$(document).ready(function(){

  function showAllFromDatabase() {

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  // document.getElementById("baza").innerHTML = this.responseText;

                  $("#baza").append(this.responseText);
                  // console.log(this.responseText);
                  $('p.currentProgramEvent').on('click',function(){
                    $(this).next().fadeToggle();
                  });
                  $('a.rezerwacje').on('click', function(event){
                    event.preventDefault();
                    $(this).next().fadeToggle();
                    console.log('rezerwacje');
                  });
              }
          };
          xmlhttp.open("GET", "php/showRow.php", true);
          xmlhttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
          xmlhttp.send();
  }
  showAllFromDatabase();

  console.log('jestem!');
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage'], a.galeria").on('click', function(event) {

  // Make sure this.hash has a value before overriding default behavior
  if (this.hash !== "") {

    // Prevent default anchor click behavior
    event.preventDefault();

    // Store hash 
    var hash = this.hash;

    // Using jQuery's animate() method to add smooth page scroll
    // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
    $('html, body').animate({
      scrollTop: $(hash).offset().top-80 //80 is a size of nav bar
    }, 900, function(){

      // Add hash (#) to URL when done scrolling (default click behavior)
      window.location.hash = hash;
      });
    } // End if
  });

  $('li a[href="#archieve"]').on('click',function(){
    $('#archievePanel').addClass('in');
  });
  $('li a[href="#gallery"]').on('click',function(){
    $('#galleryPanel').addClass('in');
  });
  $('li a[href="#theatres"]').on('click',function(){
    $('#theatresPanel').addClass('in');
  });
  $('li a[href="#workshops"]').on('click',function(){
    $('#workshopsPanel').addClass('in');
  });
  $('li a[href="#projects"]').on('click',function(){
    $('#projectsPanel').addClass('in');
  });
  $('li a[href="#aboutUs"]').on('click',function(){
    $('#aboutUsPanel').addClass('in');
  });
  $('li a[href="#contact"]').on('click',function(){
    $('#contactPanel').addClass('in');

  });

  $("a.newsletterLink").on('click',function(event){
    event.preventDefault();
    $('#newsletter').fadeToggle();
    if ( $('a.newsletterLink').text() == 'X') {
      $('a.newsletterLink').text('Zapisz się na newsletter');
    }
    else {
      $('a.newsletterLink').text('X');
    };
  });





});
