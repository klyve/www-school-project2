'use strict';
let open = true;
let deg;


function toggleNav() {
  if (open === true) {
    closeNav();
    open = false;
  } else {
    openNav();
    open = true;
  }
}

function openNav() {
  deg = 0;
  document.querySelector(".sidebar").style.width = "270px";
  document.querySelector(".main").style.marginLeft = "270px";
  document.querySelector(".main").style.width = "calc(100vw - 270px)";
  document.querySelector(".list").style.marginLeft = "0";
  document.querySelector(".open").style.transform = `rotate(${deg}deg)`;

}

function closeNav() {
  deg = 90;
  document.querySelector(".sidebar").style.width = "0";
  document.querySelector(".main").style.marginLeft = "0";
  document.querySelector(".main").style.width = "100vw";
  document.querySelector(".list").style.marginLeft = "-270px";
  document.querySelector(".open").style.transform = `rotate(${deg}deg)`;

}

  // 'use strict';
  // let show = true;
  // let degrees;
  //
  //
  // function toggleNotes() {
  //   if (show === true) {
  //     closeNotes();
  //     show = false;
  //   } else {
  //     openNotes();
  //     show = true;
  //   }
  // }
  //
  // function openNotes() {
  //   degrees = 180;
  //   console.log("open");
  //   document.querySelector(".notes").style.width = "270px";
  //   // document.querySelector(".comments").style.marginLeft = "0";
  //   document.querySelector(".btn").style.transform = `rotate(${degrees}deg)`;
  //
  // }
  //
  // function closeNotes() {
  //   console.log("close");
  //
  //   degrees = 0;
  //   document.querySelector(".notes").style.width = "23px";
  //   // document.querySelector(".comments").style.marginLeft = "270px";
  //   document.querySelector(".btn").style.transform = `rotate(${degrees}deg)`;
  //
  // }
