<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {widget_slideshow_data} function plugin
 *
 * Type:     function
 * Name:     widget_homebrands_data
 * Date:     07/09/2011
 * Update    07/10/2020
 * Purpose:  homebrands
 * @link
 * @author   Gerits Aurelien
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_widget_homebrands_data($params, $template){
    $homebrands = new plugins_homebrands_public();

    $assign = isset($params['assign']) ? $params['assign'] : 'brands';

    $template->assign($assign,$homebrands->getHomeBrands());
}