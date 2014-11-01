<?php
defined('_JEXEC') or die( 'Restricted access' );
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

jimport('joomla.user.user');
jimport( 'simpleschema.category' );
jimport( 'simpleschema.person' );
jimport( 'simpleschema.blog.post' );

require_once( EBLOG_HELPERS . '/date.php' );
require_once( EBLOG_HELPERS . '/string.php' );
require_once( EBLOG_CLASSES . '/adsense.php' );

class EasyblogApiResourceLatest extends ApiResource
{

	public function __construct( &$ubject, $config = array()) {
		parent::__construct( $ubject, $config = array() );

	}
	
	public function get() {
		$input = JFactory::getApplication()->input;
		$model 		= EasyBlogHelper::getModel( 'Blog' );
		$id = $input->get('id', null, 'INT');
		$search = $input->get('search', null, 'STRING');
		$posts = array();

		// If we have an id try to fetch the user
		$blog 		= EasyBlogHelper::getTable( 'Blog' );
		$blog->load( $id );
		
		if ($id) {
			if(!$blog->id) {
				$this->plugin->setResponse( $this->getErrorResponse(404, 'Blog not found') );
				return;
			}

			$this->plugin->setResponse( $blog );
		} else {
			
			$sorting	= $this->plugin->params->get( 'sorting' , 'latest' );
			$rows 		= $model->getBlogsBy( $sorting , '' , $sorting , 0, EBLOG_FILTER_PUBLISHED, $search );
			
			foreach ($rows as $k => $v) {
				$item = EasyBlogHelper::getHelper( 'SimpleSchema' )->mapPost($v, '', 100, array('text'));
				$posts[] = $item;
			}
			
			$this->plugin->setResponse( $posts );
		}
	}
	
	public static function getName() {
		
	}
	
	public static function describe() {
		
	}
	
}
