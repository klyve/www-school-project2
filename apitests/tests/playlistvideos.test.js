import test from 'ava'
const { API } = require('../configs')
const { testDataIntegrity, axiosBearer, isEqualsShallow } = require('../methods')
const axios = require('axios')
const fs = require("fs")


// HTTP STATUS CODES
const HTTP_OK             = 200  // Success and returning content
const HTTP_CREATED        = 201  // Successfull creation
const HTTP_ACCEPTED       = 202  // Marked for  deletion, not deleted yet
const HTTP_NO_CONTENT     = 204  // Successfull update
const HTTP_NOT_IMPLMENTED = 501
const HTTP_BAD_REQUEST    = 400
const HTTP_NOT_FOUND      = 404

const credentials = {
    email: 'useremail0@kruskontroll.no',
    name: 'username0',
    password: 'password0',
}

let playlistvideo = {
    id: null,
    videoid: 2,
    playlistid: 2,
    position: 0
}

let userToken = null



// Log in user
test.before(async (t) => {
    t.plan(10)

    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t)
   
    userToken = res.data.token
    t.truthy(userToken)
})


test.serial('Add video to playlist', async (t) => {


    try {
        const res = await axios.post(`${API}/playlist/${playlistvideo.playlistid}/video`, playlistvideo, axiosBearer(userToken))

        console.log(res.data)

        t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.statusCode}`)

        playlistvideo.id = res.data.id
        t.truthy(playlistvideo.id)
    
    } catch (err) {
        console.log(err.response.data)
    }
})



/*
test.serial('Check if video was added to playlist', async (t) => {
    t.fail("Not implemented!")

})



test.serial('Remove video from playlist', async (t) => {
    t.fail("Not implemented!")

})


test.serial('Check if video was removed from playlist', async (t) => {
    t.fail("Not implemented!")

})


test.serial('Change playlist order', async (t) => {
    t.fail("Not implemented!")

})


test.serial('Check if playlist order changed correctly', async (t) => {
    t.fail("Not implemented!")

})

test.serial('Change playlist order back', async (t) => {
    t.fail("Not implemented!")

})


test.serial('Check if playlist order changed back again correctly', async (t) => {
    t.fail("Not implemented!")

})


*/
