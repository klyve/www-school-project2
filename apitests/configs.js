require ('dotenv').load();

module.exports = {
    API: `http://${process.env.KRUS_WEB_HOST}:${process.env.KRUS_WEB_PORT}/index.php`,
}