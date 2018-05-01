import test from 'ava';
const { API } = require('../configs');
const { testDataIntegrity, axiosBearer, isEqualsShallow } = require('../methods');
const axios = require('axios');
const fs = require("fs");


// HTTP STATUS CODES
const HTTP_OK             = 200;  // Success and returning content
const HTTP_CREATED        = 201;  // Successfull creation
const HTTP_ACCEPTED       = 202;  // Marked for  deletion, not deleted yet
const HTTP_NO_CONTENT     = 204;  // Successfull update
const HTTP_NOT_IMPLMENTED = 501;
const HTTP_BAD_REQUEST    = 400;
const HTTP_NOT_FOUND      = 404;

// @note Insert this user into the database using 'php toolbox seed:up' 'php toolbox seed:refresh'
const credentials = {
    email: 'useremail1@kruskontroll.no',
    name: 'username1',
    password: 'password1',
};

let playlistdata = {
    id: null,
    title: 'PLAYLIST 10 Javascript Frameworks',
    description: 'A playlist about javascript frameworks ',
}

let updatedPlaylistdata = {
    id: null,
    title: 'PLAYLIST 10 Javascript Frameworks NEW 2018 UPDATE',
    description: 'A playlist about javascript frameworks NEW 2018 UPDATED',
}


let playlistid = null
let userToken  = null

// Log in user
test.before(async (t) => {
    t.plan(10);

    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`);

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t);
   
    userToken = res.data.token;
    t.pass();
});


test.serial('Create playlist', async (t) => {

    t.plan(6)

    try {
        const res = await axios.post(`${API}/playlist`, playlistdata, axiosBearer(userToken))
        t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`)


        testDataIntegrity(res.data, ['id', 'message'], t)

        playlistid = res.data.id
        playlistdata.id = res.data.id
        updatedPlaylistdata.id = res.data.id
        
        t.truthy(playlistdata.id)

    } catch(err) {
        console.log(err.response)
        t.fail();
    }

});

test.serial('Check if playlist was created', async (t) => {
   
    t.plan(2)
    let data = {
        query:`{ 
            playlist(id: ${playlistdata.id}) {  
                id, 
                title, 
                description 
            }
        }`
    };
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    t.true( isEqualsShallow(res.data.data.playlist, playlistdata), "Shallow equal unsuccessful")
});


test.serial('Update playlist', async (t) => {

    t.plan(3)
    try {
        const res = await axios.put(`${API}/playlist/${playlistid}`, updatedPlaylistdata, axiosBearer(userToken))
        testDataIntegrity(res.data, ['message'], t)
        t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    

    } catch(err) {
    }
});

test.serial('Check if playlist updated correctly', async (t) => {


    t.plan(2)
    const data = {query: `{ playlist(id: ${updatedPlaylistdata.id}) {  id, title, description }}`}
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))


    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    //console.log(res.data.data.playlist, updatedPlaylistdata)
    t.true( isEqualsShallow(res.data.data.playlist, updatedPlaylistdata), "Shallow equal unsuccessful")

});



test.serial('Delete playlist', async (t) => {
    t.plan(3)
    
    try {
        const res = await axios.delete(`${API}/playlist/${playlistid}`, axiosBearer(userToken))
        testDataIntegrity(res.data, ['message'], t)
        t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.status}`)   

    } catch(err) {

    }
});

test.serial('Check if playlist was deleted', async (t) => {
    t.plan(2)


    const data = {query: `{ playlist(id: ${updatedPlaylistdata.id}) {  id, deleted_at }}`}
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    //console.log(res.data.data.playlist, updatedPlaylistdata)
    t.truthy( res.data.data.playlist.deleted_at, "playlist.deleted_at should be null, because not found" );
});


