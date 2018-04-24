import test from 'ava';
const { API } = require('../configs');
const { testDataIntegrity, axiosBearer } = require('../methods');
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

let playlistdata = {
    title: 'PLAYLIST 10 Javascript Frameworks',
    description: 'A playlist about javascript frameworks ',
}

let newPlaylistdata = {
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

    testDataIntegrity(res, ['email', 'name', 'token', 'usergroup'], t);
   
    userToken = res.data.token;
    t.pass();
});


test.serial('Create playlist', async (t) => {

    t.fail("Not implemented!")
});


test.serial('Check if playlist was created', async (t) => {
    t.fail("Not implemented!")
});


test.serial('Update playlist', async (t) => {
    t.fail("Not implemented!")

});


test.serial('Check if playlist updated correctly', async (t) => {
    t.fail("Not implemented!")

});



test.serial('Add video to playlist', async (t) => {
    t.fail("Not implemented!")

});


test.serial('Check if video was added to playlist', async (t) => {
    t.fail("Not implemented!")

});



test.serial('Remove video from playlist', async (t) => {
    t.fail("Not implemented!")

});


test.serial('Check if video was removed from playlist', async (t) => {
    t.fail("Not implemented!")

});


test.serial('Change playlist order', async (t) => {
    t.fail("Not implemented!")

});


test.serial('Check if playlist order changed correctly', async (t) => {
    t.fail("Not implemented!")

});


test.serial('Delete playlist', async (t) => {
    t.fail("Not implemented!")

});

test.serial('Check if playlist was deleted', async (t) => {
    t.fail("Not implemented!")

});