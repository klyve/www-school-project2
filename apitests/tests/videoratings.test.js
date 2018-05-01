import test from 'ava'
const { API } = require('../configs')
const { testDataIntegrity, axiosBearer, isEqualsShallow } = require('../methods')
const axios = require('axios')


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

const DislikeType = 0
const LikeType    = 1
const likeDislikeVideoId = 2


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



test.serial('Like video', async (t) => {

    let likeData = {
        rating: LikeType   
    }

    const res = await axios.put(`${API}/video/${likeDislikeVideoId}/rate`, likeData, axiosBearer(userToken))
                           .catch(err => { console.log(err.response); t.fail("axios.put Cought error") })

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)
    t.truthy(res.data)
})
 
// @TODO This test depends on beiing able to select rating on userid. On the test server,
// we don't know the user yet.
test.serial('Check if video is liked exactly one time', async (t) => {

    const data = {
      query: `{
        video(id: ${likeDislikeVideoId}) {
          ratings {
            user {
              name
            }
            rating
          }
        }
      }`
    }
    const res = await axios.post(`${API}/graphql?query`, data, axiosBearer(userToken))

    t.truthy(res.data.data)

    let rating = res.data
                    .data
                    .video
                    .ratings
                    .filter(o => o.user.name == credentials.name)
    t.true(rating.length == 1)
    t.true(rating[0].rating == LikeType)
})

let beforeLikeCount = null;
let beforeDislikeCount = null;

let afterLikeCount  = null;
let afterDislikeCount  = null;

test.serial('Check like and dislike counts before', async (t) => {

    const data = {
      query: `{
        video (id: ${likeDislikeVideoId}) {
          ratings {
            rating 
          }
        }
      }`
    }
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))
                           .catch(err => { console.log(err.response); t.fail("axios.get Cought error") })


    t.truthy(res.data.data, "GrahpQL response error")
    beforeLikeCount = res.data
                         .data
                         .video
                         .ratings
                         .filter(e => e.rating == LikeType)
                         .length

    beforeDislikeCount = res.data
                            .data
                            .video
                            .ratings
                            .filter(e => e.rating == DislikeType)
                            .length

    t.true(beforeLikeCount >= 1, "Should be a number")
    t.true(beforeDislikeCount >= 0, "Should be a number")
})

 
test.serial('Dislike video', async (t) => {
    let dislikeData = {
        rating: DislikeType   
    }
    const res = await axios.put(`${API}/video/${likeDislikeVideoId}/rate`, dislikeData, axiosBearer(userToken))
                           .catch(err => { console.log(err.response); t.fail("axios.get Cought error") })

    t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.statusCode}`)
    t.truthy(res.data)                           
})

test.serial('Check if video is disliked exactly one time', async (t) => {

    const data = {
      query: `{
        video(id: ${likeDislikeVideoId}) {
          ratings {
            user {
              name
            }
            rating
          }
        }
      }`
    }
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))

    t.truthy(res.data.data)

    let rating = res.data
                    .data
                    .video
                    .ratings
                    .filter(o => o.user.name == credentials.name)

    t.true(rating.length == 1)
    t.true(rating[0].rating == DislikeType)
})

test.serial('Check like and dislike count after ', async (t) => {

    const data = {
      query: `{
        video (id: ${likeDislikeVideoId}) {
          ratings {
            rating 
          }
        }
      }`
    }
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))
                           .catch(err => { console.log(err.response); t.fail("axios.get Cought error") })


    t.truthy(res.data.data, "GrahpQL response error")
    afterLikeCount = res.data
                         .data
                         .video
                         .ratings
                         .filter(e => e.rating == LikeType)
                         .length

    afterDislikeCount = res.data
                            .data
                            .video
                            .ratings
                            .filter(e => e.rating == DislikeType)
                            .length

    t.true(afterLikeCount == (beforeLikeCount - 1), "Should be one less like than before")
    t.true(afterDislikeCount == (beforeDislikeCount + 1), "Should be one more dislike than before")
})

test.serial('Delete like',  async (t) => {
    const res = await axios.delete(`${API}/video/${likeDislikeVideoId}/rate`, axiosBearer(userToken))    
                           .catch(err => { console.log(err.response); t.fail("axios.get Cought error") })

    t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.statusCode}`)
    t.truthy(res.data)
})

test.serial('Check if like is Deleted', async (t) => {
    const data = {
      query: `{
        video(id: ${likeDislikeVideoId}) {
          ratings {
            user {
              name
            }
            deleted_at
          }
        }
      }`
    }

    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))

    t.truthy(res.data.data)

    let deletedRating = res.data
                           .data
                           .video
                           .ratings
                           .filter(o => o.user.name == credentials.name)

    t.truthy(deletedRating[0].deleted_at)
})

test.serial('Check if no like is registered by default', async (t) => {
    const notRatedVideoId = 5

    const data = {
      query: `{
        video(id: ${notRatedVideoId}) {
          ratings {
            user {
              name
            }
            rating
          }
        }
      }`
    }
    const res = await axios.post(`${API}/graphql`, data, axiosBearer(userToken))
    t.truthy(res.data.data)

    let deletedRating = res.data
                       .data
                       .video
                       .ratings
                       .filter(o => o.user.name == credentials.name)


    t.true(deletedRating.length == 0, "This video should never have been liked by this user")
})
