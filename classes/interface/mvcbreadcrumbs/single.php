<?php
/**
 * Declares MVCBreadcrumbs_Single interface
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
 * @link      https://github.com/emtou/kohana-mvcbreadcrumbs/tree/master/classes/interface/mvcbreadcrumbs/single.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Forces a controller to implement a single breadcrumb
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
 * @link      https://github.com/emtou/kohana-mvcbreadcrumbs/tree/master/classes/interface/mvcbreadcrumbs/single.php
 */
interface Interface_MVCBreadcrumbs_Single
{
  /**
   * Access to the single breadcrumbs
   *
   * @return MVCBreadcrumbs Breadcrumbs instance
   */
  public function breadcrumb();

  /**
   * Initialises the breadcrumbs for a given action
   *
   * @param array $extras extra parameters
   *
   * @return null
   */
  public function init_breadcrumbs(array $extras = array());
}