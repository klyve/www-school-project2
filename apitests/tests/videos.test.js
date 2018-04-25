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

let newVideodata = {
    title: '10 Javascript Frameworks you HAVE TO LEARN in 2018',
    description: 'Your life will be an absolute missery if you dont learn these super important frameworks!',
    fileThumbnail: null, // provided by return from tempfile upload
    fileVideo: null,     // provided by return from tempfile upload
}

let updateVideodata = {
    title: '10 Javascript Frameworks in 2018',
    description: 'Your life will be in RUINS if you dont learn these super important frameworks!'
}


let videoid = null
let userToken  = null

test.before(async (t) => {
    t.plan(11);

    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`);

    testDataIntegrity(res, ['email', 'name', 'token', 'usergroup'], t);
   
    userToken = res.data.token;
    t.truthy(userToken);
    t.pass();
});


test.serial('Upload thumbnail file', async t => {

    t.plan(2)

    const filenameThumbnail = "thumbnail.png"
    const mimeThumbnail = "image/png"

    // UPLOAD THUMBNAIL FILE
    let dataThumbnail = fs.readFileSync(`files/${filenameThumbnail}`);
    if(dataThumbnail === null) t.fail();

    const fileSizeInBytes = fs.statSync(`files/${filenameThumbnail}`).size;

    try {

        const res = await axios.post(`${API}/tempfile`, 
                                 dataThumbnail, 
                                 axiosFile(userToken, 
                                           fileSizeInBytes,
                                           filenameThumbnail,
                                           mimeThumbnail));


    t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`);    

        newVideodata.fileThumbnail = res.data.fname;
        t.truthy(newVideodata.fileThumbnail, "File thumbnail has to be defined");

    } catch(err) {
        t.fail(`${err.response.status}: ${err.response.data}`);
    }

});

test.serial('Upload video file', async t => {
    // UPLOAD VIDEO FILE
    t.plan(2)

    const filenameVideo     = "video.mp4"
    const mimeVideo         = "video/mp4"
    
    let dataVideo = fs.readFileSync(`files/${filenameVideo}`);
    if(dataVideo === null) t.fail();

    const fileSizeInBytes = fs.statSync(`files/${filenameVideo}`).size;
   
    try {
        const res = await axios.post(`${API}/tempfile`, 
                                     dataVideo, 
                                     axiosFile(userToken, 
                                               fileSizeInBytes,
                                               filenameVideo,
                                               mimeVideo));

        t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`)

        newVideodata.fileVideo = res.data.fname;
        t.truthy(newVideodata.fileVideo, "File video has to be defined");

    } catch(err) {
        t.fail(`${err.response.status}: ${err.response.data}`);
    }
});

test.serial('Post video', async t => {
    t.plan(2);
    

    try {
        const res = await axios.post(`${API}/video`, newVideodata, axiosBearer(userToken))
        t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`)
        
        videoid = res.data.videoid;

        t.truthy(res.data.videoid);

    } catch (err) {
        t.fail()
    }

});



test.serial('Put video', async t => {
    t.plan(1);

    const res = await axios.put(`${API}/video/${videoid}`, {
        videoid,
        title: updateVideodata.title,
        description: updateVideodata.description
    }, axiosBearer(userToken))


    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)
});


test.serial('Delete video', async t => {
    t.plan(1);

    const res = await axios.delete(`${API}/video/${videoid}`, axiosBearer(userToken))
    t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`)
});
