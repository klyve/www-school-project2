/**
 * @apiDefine user
 * Require authentication.
 */

/**
 * @apiDefine teacher
 * Require teacher access or higher.
 */

 /**
 * @apiDefine admin
 * Require admin access.
 */

 /**
 * @apiDefine owner
 * Require ownership of resource.
 */

 /**
 * @apiDefine data
 * @apiSuccess {String} data Success message.
 */
 
 /**
 * @apiDefine errorCode
 * @apiError {String} code Http status code.
 * @apiError {String} error Internal error usefull for debug.
 * @apiError {String} message Error message.
 */