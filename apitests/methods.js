

const testDataIntegrity = (data, expectedReturn, t) => {

    Object.keys(data).map(key => {
        if(expectedReturn.indexOf(key) === -1)
            return t.fail(`${key} not found in expected data [${expectedReturn.join(',')}]`);

        return t.pass();
    });

    expectedReturn.map(key => {
        if(!data[key])
            return t.fail(`${key} not found or null in user return`);

        if(data[key] == '')
            return t.fail(`${key} is empty in user return`);
        
        return t.pass();
    });
}


// @doc inspired by --> http://adripofjavascript.com/blog/drips/object-equality-in-javascript.html - 24.04.18
const isEqualsShallow = (recievedObject, expectedObject) => {

    // Create arrays of property names
    let recieveProps = Object.getOwnPropertyNames(recievedObject);
    let expectProps = Object.getOwnPropertyNames(expectedObject);

    // If number of properties is different,
    // objects are not equivalent
    if (recieveProps.length != expectProps.length) {
        return false;
    }

    for (var i = 0; i < recieveProps.length; i++) {
        let recievePropName = recieveProps[i];
        let expectPropName  = expectProps[i];

        // If properties does not have the same name in the key 
        if (recievePropName !== expectPropName) {
            return false;
        }

        // If values of same property are not equal,
        // objects are not equivalent
        if (recievedObject[recievePropName] !== expectedObject[recievePropName]) {
            return false;
        }
    }

    // If we made it this far, objects
    // are considered equivalent
    return true;
}


const guid = () => {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}
  
module.exports.axiosBearer = (token) => ({
    headers: {
        'Authorization': 'Bearer ' + token
    }
})

module.exports.axiosFile = (token, size, filename, mimetype) => ({
    headers: { 
        'Content-Type':   mimetype,
        'Authorization':  'Bearer ' + token,
        'X-OriginalFilename': filename,
        'X-OriginalFilesize': size,
        'X-OriginalMimetype': mimetype,
    },
    onUploadProgress: progressEvent => console.log(progressEvent.loaded)
});



module.exports.guid = guid;
module.exports.testDataIntegrity = testDataIntegrity;
module.exports.isEqualsShallow = isEqualsShallow;