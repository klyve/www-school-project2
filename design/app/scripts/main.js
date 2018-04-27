console.log('\'Allo \'Allo!');

const body = document.body;

const toggleMenu = (e) => {
    if(body.classList.contains('expanded-menu'))
        body.classList.remove('expanded-menu');
    else
        body.classList.add('expanded-menu');
}


// document.querySelector('.content-container').addEventListener('click', () => {
//     if(body.classList.contains('expanded-menu')) {
//         body.classList.remove('expanded-menu');
//     }
// })





const menu = document.querySelector('.toggle-menu');
menu.addEventListener('click', toggleMenu);