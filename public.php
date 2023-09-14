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
    /**
     * @var frontend_model_template
     */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
    protected component_files_images $imagesComponent;

    /**
     * @var string $lang
     */
    public string $lang;

    /**
     * @param ?frontend_model_template $t
     */
    public function __construct(?frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->lang = $this->template->lang;
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param ?string $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }
    /**
     * @return void
     */
    private function initImageComponent(): void {
        if(!isset($this->imagesComponent)) $this->imagesComponent = new component_files_images($this->template);
    }

    /**
     * @param array $homebrands
     * @throws Exception
     */
    private function setHomeBrandsData(array $slides)
    {
        /*if(!empty($homebrands['img_slide'])) {
            $this->initImageComponents();

            $homebrands['img'] = $this->imagesComponent->setModuleImage('slideshow','slideshow', $homebrands['img_slide'], $homebrands['id_slide']);
        }
        return $homebrands;*/
        $arr = [];
        if(!empty($slides)) {
            $this->initImageComponent();
            foreach ($slides as $slide) {
                $arr[$slide['id_slide']] = [
                    'id_slide' => $slide['id_slide'],
                    'id_lang' => $slide['id_lang'],
                    'title_slide' => $slide['title_slide'],
                    'desc_slide' => $slide['desc_slide'],
                    'link_slide' => [
                        'url' => $slide['url_slide'],
                        'label' => $slide['title_slide'],
                        'title' => $slide['title_slide']
                    ],
                    'blank_slide' => $slide['blank_slide'],
                    'img' => $this->imagesComponent->setModuleImage('homebrands','homebrands',$slide['img_slide'],$slide['id_slide'])
                ];
            }
        }
        return $arr;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getHomeBrands(){
        $homebrands = $this->getItems('homeSlides',['lang' => $this->lang],'all', false);
        return $this->setHomeBrandsData($homebrands);
        /*if(!empty($homebrands)) {
            foreach ($homebrands as &$homebrand) {
                $homebrand = $this->setHomeBrandsData($homebrand);
            }
            return $homebrands;
        }*/
    }
}