import test from 'ava';
const { API } = require('../configs');
const { testDataIntegrity, axiosBearer } = require('../methods');
const axios = require('axios');
const fs = require("fs");


// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet
const HTTP_NOCONTENT     = 204;  // Successfull update
const HTTP_NOTIMPLMENTED = 501;


// @note Insert this user into the database using 'php toolbox seed:up' 'php toolbox seed:refresh'
const credentials = {
    email: 'useremail1@kruskontroll.no',
    name: 'username1',
    password: 'password1',
};

const newVideodata = {
    title: '10 Javascript Frameworks you HAVE TO LEARN in 2018',
    description: 'Your life will be an absolute missery if you dont learn these super important frameworks!'
}

const updateVideodata = {
    title: '10 Javascript Frameworks in 2018',
    description: 'Your life will be in RUINS if you dont learn these super important frameworks!'
}

let userid     = 1;
let videoid    = 1;
let userToken  = null;


test.before(async (t) => {
    t.plan(10);

    // @TODO Get 'userid' back from this route, and use it in the other tests.
    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`);

    testDataIntegrity(res, ['email', 'name', 'token', 'usergroup'], t);
   
    userToken = res.data.token;

    //console.log(userToken);
    t.pass();
});


test.serial('Post video without files', async t => {
    t.plan(1);

    try {
        const res = await axios.post(`${API}/user/${userid}/video`, newVideodata, axiosBearer(userToken))
        t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`);

        videoid = res.data.videoid;
    } catch(err) {
        t.is(err.response.status, HTTP_NOTIMPLMENTED, `Expected status code ${HTTP_NOTIMPLMENTED} got ${err.response.status}`);
    }
});


test.serial('Put video', async t => {
    t.plan(1);

    try {
        const res = await axios.put(`${API}/user/${userid}/video/${videoid}`, updateVideodata, axiosBearer(userToken))
        //t.is(res.status, HTTP_NOCONTENT, `Expected status code ${HTTP_NOCONTENT} got ${res.status}`)
        console.log(res.data);


    } catch(err) {
        // @TODO understand error occuring sometimes here - JSolsvik 19.04.2018
        /*
          Rejected promise returned by test. Reasonkey: "value", 
              TypeError {
                message: 'Cannot read property \'status\' of undefined',
              }
        */
    //    console.log(err)
        t.fail()

      //  t.is(err, HTTP_NOTIMPLMENTED, `Expected status code ${HTTP_NOTIMPLMENTED} got ${err.response.status}`);
    }
});


test.serial('Delete video', async t => {
    t.plan(1);

    try {
        const res = await axios.delete(`${API}/user/${userid}/video/${videoid}`, axiosBearer(userToken))
        t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.status}`);

    } catch(err) {
       
        t.is(err.response.status, HTTP_NOTIMPLMENTED, `Expected status code ${HTTP_NOTIMPLMENTED} got ${err.response.status}`);
    }
});


test('Post video with file', async t => {


    t.plan(2);

    let error = null;
    await fs.readFile("files/thumbnail.png", function(err, data) {
    
        console.log(data);
        error = err;
    });
    await fs.readFile("files/video.mp4", function(err, data) {
            
            console.log(data);
            error = err;
    });


console.log(error);
    t.is(error, null, `Error ${error}`);

console.log(error);
    t.is(error, null, `Error ${error}`);

    
    /*
    var formData = new FormData();
    var imagefile = document.querySelector('#file');
    formData.append("image", imagefile.files[0]);
    axios.post('upload_file', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
    })
    */


});