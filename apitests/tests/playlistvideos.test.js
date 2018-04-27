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

    const res = await axios.post(`${API}/playlist/${playlistvideo.playlistid}/video`, playlistvideo, axiosBearer(userToken))


    t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.statusCode}`)

    playlistvideo.id = res.data.id
    t.truthy(playlistvideo.id)
    
})



test.serial('Check if video was added to playlist', async (t) => {
   
    t.plan(2)

    const query =  `{ playlist(id: ${playlistvideo.playlistid}) {  videos{  id  }   }}`;
    const res   = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken));

    let filtered = res.data
                      .data
                      .playlist
                      .videos
                      .filter(obj => obj.id == playlistvideo.videoid)

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    t.true( filtered.length > 0, `No results found 
                                 playlistid: ${playlistvideo.playlistid}
                                 videoid:    ${playlistvideo.videoid}`)
})



test.serial('Remove video from playlist', async (t) => {

    t.plan(1)

    const query = `${API}/playlist/${playlistvideo.playlistid}/video/${playlistvideo.id}`;
    try {
        const res = await axios.delete(query, playlistvideo, axiosBearer(userToken))
        t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`)
    
    } catch(err) {
        t.fail()
    }
})

// @TODO have a way to delete entries in link tables. This would be OK as we absolutely dont want to store links
//        for future references... as they are not data at all - JSolsvik 27.04.2018
test.skip('Check if video was removed from playlist', async (t) => {
  /*
    t.plan(2)

    const query =  `{ playlist(id: ${playlistvideo.playlistid}) {  videos{  id, deleted_at  }   }}`;
    const res   = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken));

    console.log(res.data)

    let filtered = res.data
                      .data
                      .playlist
                      .videos
                      .filter(obj => obj.id == playlistvideo.videoid)

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    console.log(filtered.length)
    t.true( filtered.length === 0, `Found playlistvideos, should have been deleted 
                                  playlistid: ${playlistvideo.playlistid}
                                  videoid:    ${playlistvideo.videoid}`)
*/
})


test.todo('Change playlist order')/*, async (t) => {
    
})*/


test.todo('Check if playlist order changed correctly') /*async (t) => {
    
})*/

test.todo('Change playlist order back')/* async (t) => {
    
})*/


test.todo('Check if playlist order changed back again correctly') /*async (t) => {
    
})*/



