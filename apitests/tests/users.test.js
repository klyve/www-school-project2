import test from 'ava';
const { API } = require('../configs');
const {testDataIntegrity, guid, axiosBearer} = require('../methods');
const axios = require('axios');
const crypto = require('crypto');

const credentials = {
    email: guid()+'@stud.ntnu.no',
    name: 'bjarte',
    password: 'somepassword',
    newpassword: 'hello123'
};

let userToken = null


const HTTP_NOT_IMPLMENTED = 501;
const HTTP_ACCEPTED       = 202;  // Marked for  deletion, not deleted yet
const HTTP_OK             = 200;  // Marked for  deletion, not deleted yet


test.serial('register a user', async (t) => {
    t.plan(17);
    const res = await axios.post(`${API}/user/register`, credentials)
    
    if(res.status !== 200)
        t.fail(`Expected status code 200 got ${res.status}`);
    t.pass();


    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t);


    try {
        const res2 = await axios.post(`${API}/user/register`, credentials)

    } catch(err) {
        if(err.response.status !== 409)
            t.fail(`Expected status code 409 got ${err.response.status}`);
        t.pass();


        testDataIntegrity(err.response.data, ['error', 'message', 'code'], t);
        t.pass();
    }
});



test.serial('Log in a user', async (t) => {
    t.plan(10);
    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, 200, `Expected status code 200 got ${res.status}`);

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t);
    
    userToken = res.data.token
    t.pass();
});


test.serial('Refresh token', async(t) => {
    t.plan(10);
    const res = await axios.post(`${API}/user/refresh`, {}, axiosBearer(userToken))
    t.is(res.status, 200, `Expected status code 200 got ${res.status}`);

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t);
    
    userToken = res.data.token
    t.pass();

});


test.todo('Log out')/*, async(t) => {
    t.fail("Test not implemeted")
});*/


test.todo('Check if logged out')/* async(t) => {
    t.fail("Test not implemeted")
});*/


test.todo('Log in again')/*async(t) => {
    t.fail("Test not implemeted")
});*/


test.serial('Change password of user', async (t) => {
    
    const res = await axios.put(`${API}/user/password`, credentials, axiosBearer(userToken));
    t.is(res.status, 204, `Expected status code 204 got ${res.status}`);
    console.log(res.data);

    try {
        const res2 = await axios.put(`${API}/user/password`, credentials, axiosBearer(userToken));
    } catch(err) {
        t.is(err.response.status, 400, `Expected status code 400 got ${err.response.status}`);
    }

    try {
        const res3 = await axios.put(`${API}/user/password`, credentials, axiosBearer('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOjUwLCJpc3MiOiJLcnVzS29udHJvbGwuY29tIiwiZXhwIjoiMjAxOC0wNC0xOSAxNzoxODo0MiIsInN1YiI6IiIsImF1ZCI6IiJ9.8UGWg_shKrgkQUPCuzcb-ewzvbAMMACLG2sfReIs6ao'));
    } catch(err) {
        t.is(err.response.status, 401, `Expected status code 401 got ${err.response.status}`);
    }

    t.pass();
});


test.serial('Update user email and name', async (t) => {
    const updatedCredentials = {
        email: guid()+'updated@stud.ntnu.no',
        name: 'Bjarte The Enhanced',
    };

    try {
        const res = await axios.put(`${API}/user`, updatedCredentials, axiosBearer(userToken))

        //console.log(res)
        t.is(res.status, HTTP_OK, `Expected status code ${HTTP_OK} got ${res.status}`);

    } catch(err) {
  //      console.log(err)
        t.is(err.response.status, HTTP_NOT_IMPLMENTED, `Expected status code ${HTTP_NOT_IMPLMENTED} got ${err.response.status}`);
    }
});


test.serial('Delete User', async (t) => {
    
    t.plan(1)
    //console.log(userToken)

    const res = await axios.delete(`${API}/user`, axiosBearer(userToken))
    t.is(res.status, HTTP_ACCEPTED, `Expected status code ${HTTP_ACCEPTED} got ${res.status}`);

});


