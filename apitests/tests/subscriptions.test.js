/**
 * @file Tests all api endpoints associated with SubscriptionsModel.php
 */

import test from 'ava';
const { API } = require('../configs');
const {testDataIntegrity, guid, axiosBearer} = require('../methods');
const axios = require('axios');

/**
 * @brief user, expected to be in database, used for testing.
 */
const credentials = {
	//User with id 1 in database
    email: 'useremail1@kruskontroll.no',
    password: 'password1',
};

// Varibles sendt with the url
const urlVars = {
	playlistid: 4,
}

// Authentication token for user
let userToken;

// Content to send with requsest body
var tag = {
	name: null,
}

test.before('Setting up user for all tests', async (t) =>{
	const res = await axios.post(`${API}/user/login`, credentials);
	userToken = res.data.token;
});

test.serial('Subscribe to playlist', async (t) =>{
	t.plan(2);
	const res1 = await axios.post(	// Subscribe to a playlist the user has not subscribed to.
		`${API}/playlist/${urlVars.playlistid}/subscribe`, null, axiosBearer(userToken)
	);
	t.true(res1.status === 201);	// Expect beeing subscribed to playlist.

	try{							// Subscribe to a playlist already subscribed to.
		const res3 = await axios.post(	
			`${API}/playlist/${urlVars.playlistid}/subscribe`, null, axiosBearer(userToken)
		);
		t.true(res3.status === 409);
	}catch(err){					// Expect nothing to happen.
	
		t.true(err.response.status === 409);		
	}
});

test.serial('Unsubscribe from playlist', async (t) =>{
	t.plan(2);
	const res1 = await axios.delete(	// Unsubscribe to playlist
		`${API}/playlist/${urlVars.playlistid}/subscribe`, axiosBearer(userToken)
	);
	t.true(res1.status === 202);		// Expect accepted, marked as deleted.

	const res2 = await axios.delete(	// Unsubscribe to playlist the user is not subscribed to.
		`${API}/playlist/${urlVars.playlistid}/subscribe`, axiosBearer(userToken)
	);
	t.true(res2.status === 200 && res2.data === "Not subscribed");	// Expected no change.

});