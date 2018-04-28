const env = 'dev';
const API = {
    DEV: 'http://localhost:4000/',
    PROD: 'http://localhost:3000/',
};

const SERVER = (env !== 'production') ? API.DEV : API.PROD;