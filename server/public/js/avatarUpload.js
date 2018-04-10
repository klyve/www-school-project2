'use strict';

let fileInput = document.querySelector('.inputfile'),
    preview   = document.querySelector('.avatarPicture'),
    label	    = fileInput.nextElementSibling;

function previewFile() {
  if (fileInput.files[0]) {
    let file = fileInput.files[0];

    if( file.name && (file.type == "image/jpeg" || file.type == "image/png")) {
      success(file);
		} else {
			error();
    }
  } else {
    error();
  }
}

function error() {
  // Styles

  label.querySelector( 'span' ).innerHTML = "Error, try again";
  label.style.background = "#D14C31";

}

function success(file) {
  preview.src = URL.createObjectURL(file);

  label.querySelector( 'span' ).innerHTML = file.name;


  // Styles
  label.style.background = "#3997D8";
}




fileInput.onchange = () => previewFile();
