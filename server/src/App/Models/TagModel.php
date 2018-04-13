<?php namespace App\Models;


/**
 * 	Class to represent a video or playlist tag.
 * 	A tag contains a string the user can search
 * 	for to get video or playlist
 */
class TagModel extends \MVC\Core\Model {


	public $table = 'Tag';				// Name of table.
	public $name;						// search word for the tag.

	public $exclude = [];				// Everything is given by the user.
}
