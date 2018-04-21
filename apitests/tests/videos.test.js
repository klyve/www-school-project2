import test from 'ava';
const { API } = require('../configs');
const { testDataIntegrity, axiosBearer, axiosFile } = require('../methods');
const axios = require('axios');
const fs = require("fs");


// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet
const HTTP_NO_CONTENT     = 204;  // Successfull update
const HTTP_NOT_IMPLMENTED = 501;
const HTTP_NOT_FOUND     = 404;

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


let userid = 1
let videoid = null
let userToken  = null


test.before(async (t) => {
    t.plan(12);

    // @TODO Get 'userid' back from this route, and use it in the other tests.
    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`);

    testDataIntegrity(res, ['id', 'email', 'name', 'token', 'usergroup'], t);
   
    userid = res.data.id;
    userToken = res.data.token;
    t.pass();
});

/*
test.serial('Post video without files', async t => {
    t.plan(1);

    const res = await axios.post(`${API}/user/${userid}/video`, newVideodata, axiosBearer(userToken))
    t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.statusCode}`);

    videoid = res.data.videoid;
});


test.serial('Put video', async t => {
    t.plan(1);

    const res = await axios.put(`${API}/user/${userid}/video/${videoid}`, {
        userid,
        videoid,
        title: updateVideodata.title,
        description: updateVideodata.description
    }, axiosBearer(userToken))

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)
});


test.serial('Delete video', async t => {
    t.plan(1);

    const res = await axios.delete(`${API}/user/${userid}/video/${videoid}`, axiosBearer(userToken))
    t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`);
});

*/



test('Upload thumbnail file', async t => {

    t.plan(1)

    const filenameThumbnail = "files/thumbnail.png"
    const mimeThumbnail = "image/png"

    // UPLOAD THUMBNAIL FILE
    let dataThumbnail = fs.readFileSync(filenameThumbnail);
    if(dataThumbnail === null) t.fail();

    const fileSizeInBytes = fs.statSync(filenameThumbnail).size;

    try {

    const res = await axios.post(`${API}/file`, dataThumbnail, axiosFile(
                                                                        userToken, 
                                                                        fileSizeInBytes,
                                                                        filenameThumbnail,
                                                                        mimeThumbnail));

//    console.log(res);
    t.is(res.statusCode, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`);    

    } catch(err) {
        t.fail(`${err.response.status}: ${err.response.data}`);
    }

});

test('Upload video file', async t => {
    // UPLOAD VIDEO FILE

    const filenameVideo     = "files/video.mp4"
    const mimeVideo         = "video/mp4"
    
    let dataVideo = fs.readFileSync(filenameVideo);
    if(dataVideo === null) t.fail();

    const fileSizeInBytes = fs.statSync(filenameVideo).size;
   
    try {
        const res = await axios.post(`${API}/file`, dataVideo, axiosFile(
                                                                userToken, 
                                                                fileSizeInBytes,
                                                                filenameVideo,
                                                                mimeVideo));
  //      console.log(res);
        t.is(res.statusCode, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`);     

    } catch(err) {
        t.fail(`${err.response.status}: ${err.response.data}`);
    }


});
