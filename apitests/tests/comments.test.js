/**
 * @file Tests all api endpoints associated with CommentsModel.php
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
	commentid: [1,2],
}

// Authentication token for user
let userToken;

// content to send with requsest body
var comment = {
	content: "First!",
}

// Comment posted by first test and used by successfull put and delete.
let postedComment;

test.before('Setting up user for all tests', async (t) =>{
	const res = await axios.post(`${API}/user/login`, credentials[0]);
	userToken = res.data.token;
});

test.serial('Post comment', async (t) =>{

	const res1 = await axios.post(	// Post new comment.
		`${API}/video/${urlVars.videoid[0]}/comment`, comment, axiosBearer(userToken)
	).catch( (res) => {
		t.fail();	// Comment not created, fail all following tests.
	});

	postedComment = res1.data.id;
	t.true(res1.status === 201 && res1.data.id != null); // Expect comment to be created.

});

test.serial('Edit comment', async (t) =>{

	const res1 = await axios.put(	// Edit comment belonging to the user.s
		`${API}/video/${urlVars.videoid[0]}/comment/${postedComment}`, comment, axiosBearer(userToken)
	);
	t.true(res1.status === 200);	// Expect successfull edit.

	const res2 = await axios.put(	// Edit comment belonging to another user.
		`${API}/video/${urlVars.videoid[1]}/comment/${urlVars.commentid[1]}`, comment, axiosBearer(userToken)
	).catch((err) =>{

		return err.response;
	});

	t.true(res2.status === 401);// Expect no change.
});

test.serial('Delete comment', async (t) =>{
	const res1 = await axios.delete(	// Delete comment belonging to the user.s
		`${API}/video/${urlVars.videoid[0]}/comment/${postedComment}`, axiosBearer(userToken)
	);
	t.true(res1.status === 202);	// Expect marked as deleted.

	const res2 = await axios.delete(	// Delete comment belonging to another user.
		`${API}/video/${urlVars.videoid[0]}/comment/${urlVars.commentid[1]}`, axiosBearer(userToken)
	).catch((err) =>{
		return err.response;
	});

	t.true(res2.status === 401);
});