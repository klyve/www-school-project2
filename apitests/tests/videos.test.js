import test from 'ava';
const { KRUS_ROOT, API } = require('../configs');
const { testDataIntegrity, axiosBearer, axiosFile, isEqualsShallow } = require('../methods');
const axios = require('axios');
const fs = require("fs");

// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet
const HTTP_NO_CONTENT     = 204;  // Successfull update
const HTTP_NOT_IMPLMENTED = 501;
const HTTP_NOT_FOUND     = 404;






let userToken  = null

test.before(async (t) => {
    t.plan(11);

    const credentials = {
        email: 'useremail1@kruskontroll.no',
        name: 'username1',
        password: 'password1',
    };

    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`);

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t);
   
    userToken = res.data.token;
    t.truthy(userToken);
    t.pass();
});










let temp_videoid = null;

test.serial('Upload video file', async t => {
    // UPLOAD VIDEO FILE
    t.plan(2)


    let dataVideo = fs.readFileSync(`${KRUS_ROOT}/server/public/media/videos/example.mp4`);
    if(dataVideo === null) 
        t.fail();

    const fileSizeInBytes = fs.statSync(`${KRUS_ROOT}/server/public/media/videos/example.mp4`)
                              .size;


    const config = {
        headers: {
            "Content-Type": "video/mp4",
            ...axiosBearer(userToken).headers,
           // "maxContentLength:": fileSizeInBytes
        },
        maxContentLength: fileSizeInBytes
    }


    const res = await axios.post(`${API}/tempfile`, dataVideo, config)
                           .catch(err => { console.log(err); t.fail("Caught error"); })

    t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`)

    temp_videoid = res.data.id;
    t.truthy(temp_videoid, "File video has to be defined")
})











const FormData = require('form-data');
let    videoid = null
const newVideodata = {
    title: '10 Javascript Frameworks you HAVE TO LEARN in 2018',
    description: 'Your life will be an absolute missery if you dont learn these super important frameworks!',
}

test.serial('Post video', async t => {
    t.plan(2)
    
    var data = new FormData();

    data.append('title', newVideodata.title);
    data.append('description', newVideodata.description);
    data.append('temp_videoid', temp_videoid);
    data.append('thumbnail', fs.createReadStream(`${KRUS_ROOT}/server/public/media/thumbnails/example.png`));
    data.append("subtitle", fs.createReadStream(`${KRUS_ROOT}/server/public/media/subtitles/example.srt`));

    const config = {
        headers: {
            ...axiosBearer(userToken).headers,
            ...data.getHeaders()
        }
    }

    const res = await axios.post(`${API}/video`, data, config)
                           .catch(err => { console.log(err); t.fail("Caught error") })



    t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`)
    
    videoid = res.data.videoid

    t.truthy(res.data.videoid)
})











test.serial('Check if video was created', async t => {

    let data = {
      query: `{
        video(id: ${videoid}) {
            title,
            description
        }
      }
    `}
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))
    t.truthy(res.data.data)

    t.true(isEqualsShallow(res.data.data.video, {title: newVideodata.title, description: newVideodata.description}), "updated object does not match intended")
})

let updateVideodata = {
    title: '10 Javascript Frameworks in 2018',
    description: 'Your life will be in RUINS if you dont learn these super important frameworks!'
}


test.serial('Put video', async t => {
    t.plan(1);

    const res = await axios.put(`${API}/video/${videoid}`, {
        videoid,
        title: updateVideodata.title,
        description: updateVideodata.description
    }, axiosBearer(userToken))

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)
});

test.serial('Check if video was updated', async t => {

    let data = {
      query: `{
        video(id: ${videoid}) {
          title,
          description
        }
      }`
    }
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))
    t.truthy(res.data.data)
    t.true(isEqualsShallow(res.data.data.video, updateVideodata), "updated object does not match intended")
})



test.serial('Delete video', async t => {
    t.plan(1);

    const res = await axios.delete(`${API}/video/${videoid}`, axiosBearer(userToken))

    t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`)
});



const deleteFolderRecursive = path => {
    var files = [];
    if( fs.existsSync(path) ) {
        files = fs.readdirSync(path);
        let keep = false;
        files.forEach(function(file,index){
            
            var curPath = path + "/" + file;
            if(fs.lstatSync(curPath).isDirectory()) { // recurse
                deleteFolderRecursive(curPath);
            } else { // delete file
                if (file != ".gitkeep" && file.indexOf("example") == -1) {
                    fs.unlinkSync(curPath);
                } else {
                    keep = true;
                }
            }
        });
        if(!keep) {
            fs.rmdirSync(path);
        }
    }
};


test.after.always(async t => {

    // Clean out testdata
    const PUBLIC_MEDIA_VIDEOS    = `${KRUS_ROOT}/server/public/media/subtitles/`
    const PUBLIC_MEDIA_SUBTITLES = `${KRUS_ROOT}/server/public/media/videos/`
    const PUBLIC_MEDIA_THUMBNAILS = `${KRUS_ROOT}/server/public/media/thumbnails/`
    const PUBLIC_TEMP = `${KRUS_ROOT}/server/public/temp/`

    deleteFolderRecursive(PUBLIC_MEDIA_VIDEOS)
    deleteFolderRecursive(PUBLIC_MEDIA_SUBTITLES)
    deleteFolderRecursive(PUBLIC_MEDIA_THUMBNAILS)
    deleteFolderRecursive(PUBLIC_TEMP)

})






