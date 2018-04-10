'use strict';

let fileInput = document.querySelector('.inputfile'),
    preview   = document.querySelector('.prevVid'),
    label	    = fileInput.nextElementSibling,
    inputs    = document.querySelector('.inputs');

function previewFile() {
  if (fileInput.files[0]) {
    let file = fileInput.files[0];

    if( file.name && file.type == "video/mp4") {
      videoSuccess(file);
		} else {
			videoError();
    }
  } else {
    videoError();
  }
}

function videoError() {
  preview.src = "";
  preview.controls = false;

  // Styles
  preview.style.height = "0px";

  label.querySelector( 'span' ).innerHTML = "Error, try again";
  label.style.background = "#D14C31";

  document.querySelector('.uploadVideoContainer').style.visibility = "hidden";

  // inputs.style.height = "0px";
  // inputs.style.visibility = "hidden";
}

function videoSuccess(file) {
  preview.src = URL.createObjectURL(file);

  preview.controls = true;

  label.querySelector( 'span' ).innerHTML = file.name;


  // Styles
  // label.style.background = "#3997D8";
  //
  // preview.style.height = "auto";
  //
  // inputs.style.height = "auto";
  // inputs.style.visibility = "visible";

  document.querySelector('.uploadVideoContainer').style.visibility = "visible";
}




fileInput.onchange = () => previewFile();
