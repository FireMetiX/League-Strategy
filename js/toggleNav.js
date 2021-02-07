
const navIcon = document.querySelector(".mobileNavigation i");
const navigationContainer = document.querySelector(".mobileNavigationContainer");

navIcon.addEventListener("click", openMobileNav);

function openMobileNav(){
    navigationContainer.classList.toggle("navIsOn");
    // console.log(navIcon.className);
    if( navIcon.className == "fas fa-bars" ) {
        navIcon.className = "fas fa-times";
    } else {
        navIcon.className = "fas fa-bars";
    }
}