import test from 'ava';
const { API } = require('../configs');
const {testDataIntegrity } = require('../methods');
const axios = require('axios');


// @note Insert this user into the database using 'php toolbox seed:up' 'php toolbox seed:refresh'
const credentials = {
    email: 'useremail1@kruskontroll.no',
    name: 'username1',
    password: 'password1',
};

const videodata = {
    userid: 1,
    title: '10 Javascript Frameworks you HAVE TO LEARN in 2018',
    description: 'Your life will be an absolute missery if you dont learn these super important frameworks!'
}

let userToken = null;

test.before(async (t) => {
    t.plan(10);
    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, 200, `Expected status code 200 got ${res.status}`);
   
    testDataIntegrity(res, ['email', 'name', 'token', 'usergroup'], t);
   
    userToken = res.data.token;
    t.pass();
});


test('Post new video without files', async t => {
    t.plan(1);
    t.pass()
});