/**
 * @file Tests all api endpoints associated with VideoTagsModel.php
 */

import test from 'ava';
const { API } = require('../configs');
const {testDataIntegrity, guid, axiosBearer} = require('../methods');
const axios = require('axios');

/**
 * @brief user, expected to be in database, used for testing.
 */
const credentials = [{
		//User with id 1 in database
    	email: 'useremail0@kruskontroll.no',
    	password: 'password0',
	},{
		//User with id 2 in database
		email: 'useremail1@kruskontroll.no',
		password: 'password1',
	}
];

// Varibles sendt with the url
const urlVars = {
	videoid: [1,2],
}

// Authentication token for user
let userToken;

// content to send with requsest body
var tag = {
	name: null,
}

test.before('Setting up user for all tests', async (t) =>{
	tag.name = Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
	const res = await axios.post(`${API}/user/login`, credentials[0]);
	userToken = res.data.token;
});

test.serial('Add tag to playlist', async (t) =>{
	t.plan(4);
	const res1 = await axios.post(	// Send a non existing tag  to a video.
		`${API}/video/${urlVars.videoid[0]}/tag`, tag, axiosBearer(userToken)
	);
	t.true(res1.status === 201);	// Expect a new tag to be created and added to the video.

	const res2 = await axios.post(	// Sends an existing tag to a video without the tag.
		`${API}/video/${urlVars.videoid[1]}/tag`, tag, axiosBearer(userToken)
	);
	t.true(res2.status === 200);	// Expect no new tag to be created, but for the video to get the tag.
	
	try{							// Sends a tag to a video that has the tag already.
		const res3 = await axios.post(	
			`${API}/video/${urlVars.videoid[0]}/tag`, tag, axiosBearer(userToken)
		);
		t.true(res3.status === 409);
	}catch(err){					// Expect no new tag to be created nor for the video to get more tags.
		//console.log(err);
		t.true(err.response.status === 409);		
	}

	try{							// Sends a tag to a video that does not belong to user.
		const res = await axios.post(`${API}/user/login`, credentials[1]);
		let userToken2 = res.data.token;

		const res3 = await axios.post(	
			`${API}/video/${urlVars.videoid[0]}/tag`, tag, axiosBearer(userToken2)
		);
		t.true(res3.status === 401);

	}catch(err){					// Expect no new tag to be created nor for the video to get more tags.
		t.true(err.response.status === 401);		
	}
});

test.serial('Delete tag from playlist', async (t) =>{
	t.plan(3);
	const res1 = await axios.delete(	// Delete a tag from a video containing tag.
		`${API}/video/${urlVars.videoid[0]}/tag/${tag.name}`, axiosBearer(userToken)
	);
	t.true(res1.status === 202)			// Expect accepted, marked as deleted.


	try{							// Sends a tag to a video that does not belong to user.
		const res = await axios.post(`${API}/user/login`, credentials[1]);
		let userToken2 = res.data.token;
		
		const res3 = await axios.delete(	
			`${API}/video/${urlVars.videoid[0]}/tag/${tag.name}`, axiosBearer(userToken2)
		);
		t.true(res3 === 401);
	}catch(err){					// Expect no new tag to be created nor for the video to get more tags.
		t.true(err.response.status === 401);	
	}

	try{
		const res2 = await axios.delete(	// Delete a tag from a video without tag.
			`${API}/video/${urlVars.videoid[0]}/tag/${tag.name}`, axiosBearer(userToken)
		);
		t.true(res2.status === 404)
	}catch(err){						// Expected no content to be found
		t.true(err.response.status === 404)
	}

});