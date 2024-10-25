/***********************************
Курсор
************************************/

let curs = document.querySelector(".cursor");

document.addEventListener("mousemove", (e) => {
 let x = e.pageX;
 let y = e.pageY;
 curs.style.left = x - 15 + "px";
 curs.style.top = y - 15 + "px";
}); 

let menuEls = document.querySelectorAll(".nav-title a, #logo-codepen");
menuEls.forEach((el) => {
 el.addEventListener("mouseenter", () => {
  setTimeout(() => {
   curs.classList.add("cursor-fade");
  }, 300);
 });

 el.addEventListener("mouseleave", () => {
  curs.classList.remove("cursor-fade");
 });
});





