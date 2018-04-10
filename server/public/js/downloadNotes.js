'use strict';

let toggled = false;

let btn = document.querySelector(".saveNotes");
let comments = document.querySelector(".comments");

let descBtn = document.querySelector(".descBtn");
let desc = document.querySelector(".desc");

descBtn.onclick = (evt) => {
  evt.preventDefault();
  toggleDescription();
}

const toggleDescription = () => {

  if (toggled) {
    desc.style.transition = ".5s ease-out"
    desc.style.maxHeight = "32px";
    descBtn.innerHTML = "Show more";
    toggled = false;
  } else {
    toggled = true;
    desc.style.maxHeight = "300px";
    descBtn.innerHTML = "Show less";
  }
}

btn.onclick = (evt) => {
  evt.preventDefault();
  download(comments.value, 'notes.pdf', 'application/pdf');
  console.log(comments.value);
}

function download(data, filename, type) {
  var file = new Blob([data], {type: type});

  if (window.navigator.msSaveOrOpenBlob) {
    window.navigator.msSaveOrOpenBlob(file, filename);
  } else {
    let a = document.createElement("a"),
            url = URL.createObjectURL(file);
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    setTimeout(function() {
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }, 0);
}
}
