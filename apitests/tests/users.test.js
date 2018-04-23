import test from 'ava';
const { API } = require('../configs');
const {testDataIntegrity, guid, axiosBearer} = require('../methods');
const axios = require('axios');
const crypto = require('crypto');

const credentials = {
    email: guid()+'@stud.ntnu.no',
    name: 'bjarte',
    password: 'somepassword',
    newpassword: 'hello123',
};
let userToken = null;


test.serial('register a user', async (t) => {
    t.plan(17);
    const res = await axios.post(`${API}/user/register`, credentials)
    
    if(res.status !== 200)
        t.fail(`Expected status code 200 got ${res.status}`);
    t.pass();
    
    testDataIntegrity(res, ['email', 'name', 'token', 'usergroup'], t);


    try {
        const res2 = await axios.post(`${API}/user/register`, credentials)
    }catch(err) {
        if(err.response.status !== 409)
            t.fail(`Expected status code 409 got ${err.response.status}`);
        t.pass();

        testDataIntegrity(err.response, ['error', 'message', 'code'], t);
        t.pass();
    }
});



test.serial('Log in a user', async (t) => {
    t.plan(10);
    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, 200, `Expected status code 200 got ${res.status}`);

    testDataIntegrity(res, ['email', 'name', 'token', 'usergroup'], t);
    
    userToken = res.data.token;
    t.pass();
});



test.serial('Change password of user', async (t) => {
    
    const res = await axios.put(`${API}/user/password`, credentials, axiosBearer(userToken));
    t.is(res.status, 204, `Expected status code 204 got ${res.status}`);

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