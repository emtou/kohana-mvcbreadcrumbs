<?php
/**
 * Declares Charougna_MVCBreadcrumbs helper
 *
 * PHP version 5
 *
 * @group mvcbreadcrumbs
 *
 * @category  MVCBreadcrumbs
 * @package   MVCBreadcrumbs
 * @author    mtou <mtou@charougna.com>
 * @copyright 2011 mtou
 * @license   http://www.debian.org/misc/bsd.license BSD License (3 Clause)
 * @link      https://github.com/emtou/kohana-mvcbreadcrumbs/tree/master/classes/charougna/mvcbreadcrumbs.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Charougna_MVCBreadcrumbs helper
 *
 * PHP version 5
 *
 * @group mvcbreadcrumbs
 *
 * @category  MVCBreadcrumbs
 * @package   MVCBreadcrumbs
 * @author    mtou <mtou@charougna.com>
 * @copyright 2011 mtou
 * @license   http://www.debian.org/misc/bsd.license BSD License (3 Clause)
 * @link      https://github.com/emtou/kohana-mvcbreadcrumbs/tree/master/classes/charougna/mvcbreadcrumbs.php
 */
class Charougna_MVCBreadcrumbs
{
  protected $_config      = array();
  protected $_breadcrumbs = NULL;

  public $controller_name = 'UNKNOWN_CONTROLLER';


  /**
   * Extracts the parent function name
   *
   * @return string parent function name
   */
  public function extract_parent_function_name()
  {
    try
    {
      $backtrace = debug_backtrace();
      return $backtrace[2]['function'];
    }
    catch (Exception $exception)
    {
      unset($exception);
      return '';
    }
  }


  /**
   * Creates the inside Breadcrumbs intance
   *
   * @return null
   */
  public function __construct()
  {
    $this->_breadcrumbs = new Breadcrumbs;
  }


  /**
   * Adds a breadcrumb to the breadcrumbs stack
   *
   * The $params array can contain :
   * - link (bool) to force a link on the breadcrumb (default TRUE)
   * - extras (array) to pass to the generation process
   *
   * @param string $route  breadcrumb route
   * @param array  $params extra parameters
   *
   * @return null
   */
  public function add($route, array $params = array())
  {
    $breadcrumb = Breadcrumb::factory();

    $extras = array();
    if (array_key_exists('extras', $params))
    {
      $extras = $params['extras'];
    }
    $breadcrumb->set_title($this->_breadcrumb_title($route, $extras));

    if ((array_key_exists('link', $params) and $params['link'])
        or ! array_key_exists('link', $params))
    {
      $breadcrumb->set_url($this->_breadcrumb_url($route, $extras));
    }

    $this->_breadcrumbs->add($breadcrumb);
  }


  /**
   * Breadcrumb title of the requested route
   *
   * @param string $route  breadcrumb route
   * @param array  $extras extra parameters
   *
   * @return string breadcrumb title
   */
  protected function _breadcrumb_title($route, $extras)
  {
    try
    {
      $title = $this->_config[$route]['title'];

      foreach ($extras as $extra_name => $extra_value)
      {
        $title = preg_replace('/%'.$extra_name.'%/', $extra_value, $title);
      }
    }
    catch (Exception $exception)
    {
      throw new Kohana_Exception('Can\'t find breadcrumb title '.
                                 'for route «'.$route.'» '.
                                 'of controller «'.$this->controller_name.'».');
    }

    $not_replaced_extras = array();
    preg_match_all('/%(.+)%/', $title, $not_replaced_extras);

    if (sizeof($not_replaced_extras[0]) > 0)
    {
      throw new Kohana_Exception('There are not replaced extras '.
                                 'in breadcrumb title '.
                                 'for route «'.$route.'» '.
                                 'of controller «'.$this->controller_name.'» : '.
                                 implode(', ', $not_replaced_extras[1])
                                );
    }
    return $title;
  }


  /**
   * Breadcrumb URL of the requested route
   *
   * @param string $route  breadcrumb route
   * @param array  $extras extra parameters
   *
   * @return string breadcrumb url
   */
  protected function _breadcrumb_url($route, $extras)
  {
    try
    {
      $route_name = $this->_config[$route]['route'];

      $route_params = array();
      if (array_key_exists('route_params', $this->_config[$route]))
      {
        $route_params = $this->_config[$route]['route_params'];
      }
      $route_params = array_merge($route_params, $extras);

      $url = Route::url($route_name, $route_params);

      return $url;
    }
    catch (Exception $exception)
    {
      throw $exception;
      throw new Kohana_Exception('Can\'t find breadcrumb url '.
                                 'for route «'.$route.'» '.
                                 'of controller «'.$this->controller_name.'».');
    }
  }


  /**
   * Add a breadcrumb configuration definition
   *
   * @param string $label        configuration label
   * @param string $title        breadcrumb title
   * @param string $route        breadcrumb route link
   * @param array  $route_params optional breadcrumb route parameters
   *
   * @return null
   */
  public function config_add($label, $title, $route, array $route_params = array())
  {
    $this->_config[$label] = array(
      'title' => $title,
      'route' => $route,
    );

    if (sizeof($route_params) > 0)
    {
      $this->_config[$label]['route_params'] = $route_params;
    }
  }


  /**
   * Renders the breadcrumb to standard output
   *
   * @param string $template template path
   *
   * @return null
   */
  public function render($template = "breadcrumbs/layout")
  {
    $this->_breadcrumbs->render($template);
  }

}