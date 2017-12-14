<?php
/**
 * Class for our 'article' post type
 */

namespace Content;

class Article extends Post {

	use Lib\FeaturedVideo;

	public $PostClass = '\Content\Article';

}