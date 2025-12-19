let browserPopup;
document.addEventListener("DOMContentLoaded", (e)=>{
    browserPopup=document.getElementById("browser-popup");
})

function hideBrowserPopup(){
    browserPopup.style.display = "none";
}