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

const Dislike = 0
const Like    = 1
const likeDislikeVideoId = 2
const neutralVideoId     = 4

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

test.todo('Check like count')

test.serial('Like video', async (t) => {

    const res = await axios.put(`${API}/video/${likeDislikeVideoId}/rate`, axiosBearer(userToken))
})

test.serial('Check if video is liked', async (t) => {
    const query = ``
    const res = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken))
})

test.todo('Check if like count increased')


test.serial('Dislike video', async (t) => {
    const res = await axios.put(`${API}/video/${likeDislikeVideoId}/rate`, axiosBearer(userToken))
})

test.serial('Check if video is disliked', async (t) => {
    const query = ``
    const res = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken))
})

test.serial('Delete like',  async (t) => {
    const res = await axios.delete(`${API}/video/${likeDislikeVideoId}/rate`, axiosBearer(userToken))    
})

test.serial('Check if like is Deleted', async (t) => {
    const query = ``
    const res = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken))
})

test('Check if no like is registered by default', async (t) => {
    const query = ``
    const res = await axios.post(`${API}/graphql?query=${query}`, axiosBearer(userToken))
})
