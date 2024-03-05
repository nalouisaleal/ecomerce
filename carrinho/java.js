const header = document.querySelector("header");

window.addEventListener("scroll",function(){
    header.classList.toggle ("sticky",this.window.scrollY > 0);
});


function showMenu() {
    var $navbar = document.getElementById('navbar');
    var style = window.getComputedStyle($navbar);
    
    if(style.display == 'none') {
      $navbar.style.display = 'flex';
    } else {
      $navbar.style.display = 'none';
    }
  }


  locastyle.modal.open("#myBtn");