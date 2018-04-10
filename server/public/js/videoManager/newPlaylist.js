'use strict';

let activator = document.querySelector('.addPlaylistContainer'),
    content   = document.querySelector('.contentCreator')


activator.onclick = () => {
  content.style.height = "400px";
  content.style.visibility = "visible";
  content.style.overflow = "hidden";
  activator.style.visibility = "hidden";
}
