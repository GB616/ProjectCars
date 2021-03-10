var slideIndex = 1;

showSlides(slideIndex);
slideNumber(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
  slideNumber(slideIndex);
}

function slideNumber(n)
{
    var slides = document.getElementsByClassName("slides");
    $( "#photoNumber" ).html(n + "/" + slides.length);
}


function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("slides");

  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  if(slides.length > 0)
   slides[slideIndex-1].style.display = "block";

}

function deletePicture(path)
{
    const request = new Request('/actionscar/delete/picture/' + path );   
    fetch(request)
    .then(reponse => {
    console.log('Photo deleted');
    }).catch(error => {
    console.error(`Error while deleting photo: ${error}`);
     });
    fetch(request, { credentials: 'same-origin' });
}