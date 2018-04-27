const testDataIntegrity = (res, expectedReturn, t) => {
    const user = res.data;
    Object.keys(user).map(key => {
        if(expectedReturn.indexOf(key) === -1)
            return t.fail(`${key} not found in expected data [${expectedReturn.join(',')}]`);

        return t.pass();
    });

    expectedReturn.map(key => {
        if(!user[key])
            return t.fail(`${key} not found or null in user return`);

        if(user[key] == '')
            return t.fail(`${key} is empty in user return`);
        
        return t.pass();
    });
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