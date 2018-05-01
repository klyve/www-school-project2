require ('dotenv').load();

module.exports = {
    API: `http://${process.env.KRUS_API_HOST}:${process.env.KRUS_API_PORT}/index.php`,

    KRUS_ROOT: process.env.KRUS_ROOT,
}