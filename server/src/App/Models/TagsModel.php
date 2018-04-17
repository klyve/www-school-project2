<?php namespace App\Models;


/**
 * 	Class to represent a video or playlist tag.
 * 	A tag contains a string the user can search
 * 	for to get video or playlist
 */
class TagsModel extends \MVC\Core\Model {


	public $table = 'tags';				// Name of table.

    public $id;
	public $text;			       // search word for the tag.

	public $exclude = ['id'];	// Everything is given by the user.
}

