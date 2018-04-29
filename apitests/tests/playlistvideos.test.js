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
test.beforeEach(async (t) => {
    t.plan(10)

    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t)
   
    userToken = res.data.token
    t.truthy(userToken)
})


test.serial('Add video to playlist', async (t) => {

    const res = await axios.post(`${API}/playlist/${playlistvideo.playlistid}/video`, playlistvideo, axiosBearer(userToken))

    t.is(res.status, HTTP_CREATED, `Expected status code ${HTTP_CREATED} got ${res.status}`)

    playlistvideo.id = res.data.id
    t.truthy(playlistvideo.id)
    
})



test.serial('Check if video was added to playlist', async (t) => {
   
    t.plan(2)

    const query =  `{ playlist(id: ${playlistvideo.playlistid}) {  videos{  id  }   }}`;
    const res   = await axios.post(`${API}/graphql?query=${query}`, {}, axiosBearer(userToken));

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
        const res = await axios.delete(query, axiosBearer(userToken))
        t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.status}`)

    } catch(err) {
        t.fail()
    }
})

// @TODO have a way to delete entries in link tables. This would be OK as we absolutely dont want to store links
//        for future references... as they are not data at all - JSolsvik 27.04.2018
test.serial('Check if video was removed from playlist', async (t) => {
    t.plan(2)
    let query = `{
      playlist(id:${playlistvideo.playlistid}) {
        nodes {
          id
          deleted_at
        }
      }
    }`

    const res   = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken));


    let filtered = res.data
                      .data
                      .playlist
                      .nodes
                      .filter(obj => obj.id == playlistvideo.id && !obj.deleted_at)

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
    t.true( filtered.length === 0, `Playlistvideo not marked for deletion 
                                  playlistid: ${playlistvideo.playlistid}
                                  videoid:    ${playlistvideo.videoid}
                                  id:         ${playlistvideo.id}`)
})

let reorderDataBefore = {
    playlistid: playlistvideo.playlistid,
    reordering : [
       {id: 5, position: 1},
       {id: 6, position: 2}
    ]
}

let reorderData = {
    playlistid: playlistvideo.playlistid,
    reordering : [
       {id: 5, position: 2},
       {id: 6, position: 1}
    ]
}


test.serial('Change playlist order', async (t) => {
    
    const query = `${API}/playlist/${reorderData.playlistid}/reorder`
    const res   = await axios.post(query, reorderData, axiosBearer(userToken));
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
});

test.serial('Check if playlist order changed correctly', async (t) => {
    const query = `{
      playlist(id:${playlistvideo.playlistid}) {
        nodes {
          id
          position
        }
      }
    }`
    
    const res = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken))

    t.truthy(res.data.data.playlist.nodes, "Graphql query did not return results")

    reorderData.reordering.map(wanted => {

        let actual = res.data
                        .data
                        .playlist
                        .nodes
                        .find(node => node.id == wanted.id)

        t.truthy(actual, "Result actual node should be defined")
        t.true(wanted.position == actual.position, "Wanted position differs from actual position")
    })
})



test.serial('Change playlist order back', async (t) => {
    const query = `${API}/playlist/${reorderDataBefore.playlistid}/reorder`
    const res   = await axios.post(query, reorderDataBefore, axiosBearer(userToken));
    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`)
});


test.serial('Check if playlist order changed back again correctly', async (t) => {
    const query = `{
        playlist(id:${playlistvideo.playlistid}) {
          nodes {
            id
            position
          }
        }
      }`
      
      const res = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken))
  
      t.truthy(res.data.data.playlist.nodes, "Graphql query did not return results")
  
      reorderDataBefore.reordering.map(wanted => {
  
          let actual = res.data
                          .data
                          .playlist
                          .nodes
                          .find(node => node.id == wanted.id)
  
          t.truthy(actual, "Result actual node should be defined")
          t.true(wanted.position == actual.position, "Wanted position differs from actual position")
      })  
})



