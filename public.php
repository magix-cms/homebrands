<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Magix CMS.
# Magix CMS, a CMS optimized for SEO
# Copyright (C) 2010 - 2011  Gerits Aurelien <aurelien@magix-cms.com>
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------
class plugins_homebrands_public extends plugins_homebrands_db {
	protected
        $template,
        $data,
        $getlang,
        $imagesComponent,
        $module,
        $mods;

	/**
	 * plugins_slideshow_public constructor.
	 */
    public function __construct($t = null){
        $this->template = $t ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->getlang = $this->template->lang;
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}
    /**
     *
     */
    private function initImageComponents()
    {
        if(!$this->imagesComponent instanceof component_files_images) $this->imagesComponent = new component_files_images($this->template);
    }

    /**
     * @param array $homebrands
     * @throws Exception
     */
    private function setHomeBrandsData(array $homebrands)
    {
        if(!empty($homebrands['img_slide'])) {
            $this->initImageComponents();
            $homebrands['img'] = $this->imagesComponent->setModuleImage([
                'type' => [
                    'module_img' => 'plugins',
                    'attribute_img' => 'homebrands'
                ],
                'dir' => 'homebrands',
                'subDir' => 'id_slide'
            ], $homebrands['img_slide'], $homebrands['id_slide']);
        }
        return $homebrands;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getHomeBrands(){
        $homebrands = $this->getItems('homeSlides',['lang' => $this->getlang],'all', false);
        if(!empty($homebrands)) {
            foreach ($homebrands as &$homebrand) {
                $homebrand = $this->setHomeBrandsData($homebrand);
            }
            return $homebrands;
        }
    }
}