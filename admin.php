<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
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
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * MAGIX CMS
 * @category   Carousel
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2019 Gerits Aurelien,
 * http://www.magix-dev.be, http://www.magix-cms.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.0
 * @create    29-01-2019
 * @Update    29-01-2019
 * @author Salvatore Di Salvo
 * @name homebrands
 * Administration du module homebrands
 */
class plugins_homebrands_admin extends plugins_homebrands_db
{
    protected
        $controller,
        $message,
        $template,
        $plugins,
        $modelLanguage,
        $collectionLanguage,
        $data, $header, $upload, $imagesComponent, $routingUrl;
	/**
	 * GET
	 * @var $getlang,
	 * @var $edit
	 */
	public $getlang, $action, $edit, $tab;

	/**
	 * POST
	 * @var $slide
	 * @var $sliderorder
	 */
	public $slide, $homebrands, $img, $id;

	/**
	 * Constructor
	 */
    /**
     * Construct class
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->header = new http_header();
		$this->upload = new component_files_upload();
		$this->imagesComponent = new component_files_images($this->template);
		$this->routingUrl = new component_routing_url();
        $this->finder = new file_finder();
        $this->makeFiles = new filesystem_makefile();

		$formClean = new form_inputEscape();

		// --- Get
		if(http_request::isGet('controller')) {
			$this->controller = $formClean->simpleClean($_GET['controller']);
		}
		if (http_request::isGet('edit')) {
			$this->edit = $formClean->numeric($_GET['edit']);
		}
		if (http_request::isGet('action')) {
			$this->action = $formClean->simpleClean($_GET['action']);
		} elseif (http_request::isPost('action')) {
			$this->action = $formClean->simpleClean($_POST['action']);
		}
		if (http_request::isGet('tabs')) {
			$this->tab = $formClean->simpleClean($_GET['tabs']);
		}

		// --- Post
		if (http_request::isPost('slide')) {
			$this->slide = $formClean->arrayClean($_POST['slide']);
		}
		// --- Add or Edit
		if (http_request::isPost('id')) {
			$this->id = $formClean->simpleClean($_POST['id']);
		}
		// --- Image Upload
		if(isset($_FILES['img']["name"])){
			$this->img = http_url::clean($_FILES['img']["name"]);
		}
		// --- Order
		if (http_request::isPost('homebrands')) {
			$this->homebrands = $formClean->arrayClean($_POST['homebrands']);
		}
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('homebrands_plugin');
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
     * @return void
     */
    private function initImageComponent(): void {
        if(!isset($this->imagesComponent)) $this->imagesComponent = new component_files_images($this->template);
    }

    /**
     * Create and insert the address image
     * @param $img
     * @param $name
     * @param $id
     * @param $debug
     * @return array|void
     */
	private function insert_image($img, $name, $id, $debug = false){
        $logger = new debug_logger(MP_LOG_DIR);
        if($debug) {
            $logger->tracelog('resultUpload start');
        }
		if(isset($this->$img)) {
            //$resultUpload = [];
			$resultUpload = $this->upload->setImageUpload(
				'img',
				array(
					'name'            => filter_rsa::randMicroUI(),
					'edit'            => $name,
					'prefix'          => array('s_','m_','l_'),
					'module_img'      => 'homebrands',
					'attribute_img'   => 'homebrands',
					'original_remove' => false
				),
				array(
					'upload_root_dir' => 'upload/homebrands', //string
					'upload_dir'      => $id //string ou array
				),
				$debug
			);
            if($debug) {
                $logger->tracelog(json_encode($resultUpload));
            }
			$this->upd(array(
				'type' => 'img',
				'data' => array(
					'id_slide' => $id,
					'img_slide' => $resultUpload['file']
				)
			));
			return $resultUpload;
		}
	}

    /**
     * @param $name
     * @param $id
     * @return array|void|null
     */
	private function slide_image($name, $id){
        /*$logger = new debug_logger(MP_LOG_DIR);
        $logger->tracelog('name');
        $logger->tracelog(json_encode($name));*/
		if(isset($this->img) && !empty($id)) {
			return $this->insert_image(
				'img',
				$name,
				$id,
				false
			);
		}
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	private function delete_image($id)
	{
		/*$makeFiles = new filesystem_makefile();
		$path = $this->upload->dirImgUploadCollection(array(
			'upload_root_dir' => 'upload/slideshow', //string
			'upload_dir'      => $id //string ou array
		));

		$img = $this->getItems('img', $id, 'one', false);
		$sizes = array('s_', 'm_', 'l_');

		if (file_exists($path . $img['img_slide'])) {
			$makeFiles->remove($path . $img['img_slide']);
			foreach ($sizes as $prefix) {
				$makeFiles->remove($path . $prefix . $img['img_slide']);
			}
			return true;
		}
		else {
			throw new Exception('file: ' . $img['img_slide'] . ' is not found');
		}*/
        $setImgDirectory = $this->routingUrl->dirUpload('upload/homebrands/' . $id,true);

        if (file_exists($setImgDirectory)) {
            $setFiles = $this->finder->scanDir($setImgDirectory);
            $clean = '';
            if ($setFiles != null) {
                foreach ($setFiles as $file) {
                    $this->makeFiles->remove($setImgDirectory . $file);
                }
            }
            $this->makeFiles->remove($setImgDirectory);
            return true;
        }
	}

	/**
	 * @param array $data
	 * @return array
	 */
	private function setItemSlideData(array $data): array {
		$arr = array();
        $this->initImageComponent();
		foreach ($data as $slide) {
			if (!array_key_exists($slide['id_slide'], $arr)) {
                $arr[$slide['id_slide']] = [
                    'id_slide' => $slide['id_slide'],
                    'img' => $this->imagesComponent->setModuleImage('homebrands','homebrands',$slide['img_slide'],$slide['id_slide'])
                ];
			}

			$arr[$slide['id_slide']]['content'][$slide['id_lang']] = array(
				'id_lang' => $slide['id_lang'],
				'title_slide' => $slide['title_slide'],
				'desc_slide' => $slide['desc_slide'],
				'url_slide' => $slide['url_slide'],
				'blank_slide' => $slide['blank_slide'],
				'published_slide' => $slide['published_slide']
			);
		}
		return $arr;
	}

	/**
	 * @param $type
	 */
	public function order($type){
		switch ($type) {
			case 'home':
				$p = $this->homebrands;
				for ($i = 0; $i < count($p); $i++) {
					parent::update(
						array(
							'type' => 'order'
						),
						array(
							'id_slide'       => $p[$i],
							'order_slide'    => $i
						)
					);
				}
				break;
		}
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function add($config)
	{
		switch ($config['type']) {
			case 'slide':
				parent::insert(
					array('type' => $config['type'])
				);
				break;
			case 'slideContent':
				parent::insert(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param array $config
	 */
	private function upd($config)
	{
		switch ($config['type']) {
			case 'img':
			case 'slide':
			case 'slideContent':
				parent::update(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param $config
	 */
	private function del($config)
	{
		switch ($config['type']) {
			case 'slide':
				parent::delete(
					array('type' => $config['type']),
					$config['data']
				);
				$this->message->json_post_response(true,'delete',array('id' => $this->id));
				break;
		}
	}

    /**
     * Adds the plugin in resizing images
     * @return array
     */
    public function getItemsImages(){
        $data = $this->getItems('img',NULL,'all',false);
        $newArr = array();
        foreach($data as $key => $value){
            $newArr[$key]['id'] = $value['id_slide'];
            $newArr[$key]['img'] = $value['img_slide'];
        }
        return $newArr;
    }

	/**
	 * Affiche les pages de l'administration du plugin
	 * @access public
	 */
	public function run()
	{
		if(isset($this->action)) {
			switch ($this->action) {
				case 'add':
				case 'edit':
					if( isset($this->slide) && !empty($this->slide) ) {
                        $logger = new debug_logger(MP_LOG_DIR);
						$notify = 'update';
						$img = null;

						if(isset($this->slide['id']) && !empty($this->slide['id'])) {
							$img = $this->getItems('img',$this->slide['id'],'one',false);
							$img = $img['img_slide'];
						}

						if (!isset($this->slide['id'])) {
                            //$logger->tracelog('add');
							$this->add(array(
								'type' => 'slide'
							));

							$lastSlide = $this->getItems('lastSlide', null,'one',false);
							$this->slide['id'] = $lastSlide['id_slide'];
							$notify = 'add_redirect';
						}

						if(isset($this->img) && !empty($this->img)) {
							$img = $this->slide_image($img, $this->slide['id']);
							$img = $img['file'];
						}
                        //$logger->tracelog(json_encode($img));

						$this->upd(array(
							'type' => 'img',
							'data' => array(
								'id' => $this->slide['id'],
								'img' => $img
							)
						));

						foreach ($this->slide['content'] as $lang => $slide) {
							$slide['id_lang'] = $lang;
							$slide['blank_slide'] = (!isset($slide['blank_slide']) ? 0 : 1);
							$slide['published_slide'] = (!isset($slide['published_slide']) ? 0 : 1);
							$slideLang = $this->getItems('slideContent',array('id' => $this->slide['id'],'id_lang' => $lang),'one',false);

							if($slideLang) {
								$slide['id'] = $slideLang['id_slide_content'];
							}
							else {
								$slide['id_slide'] = $this->slide['id'];
							}

							$config = array(
								'type' => 'slideContent',
								'data' => $slide
							);

							$slideLang ? $this->upd($config) : $this->add($config);
						}
						$this->message->json_post_response(true,$notify);
					}
					else {
						$this->modelLanguage->getLanguage();

						if(isset($this->edit)) {
							$collection = $this->getItems('slideContent',$this->edit,'all',false);
							$setEditData = $this->setItemSlideData($collection);
							$this->template->assign('slide', $setEditData[$this->edit]);
						}

						$this->template->assign('edit',($this->action === 'edit' ? true : false));
						$this->template->display('edit.tpl');
					}
					break;
				case 'delete':
					if(isset($this->id) && !empty($this->id)) {
						if($this->delete_image($this->id)) {
							$this->del(
								array(
									'type' => 'slide',
									'data' => array(
										'id' => $this->id
									)
								)
							);
						}
					}
					break;
				case 'order':
					if (isset($this->homebrands) && is_array($this->homebrands)) {
						$this->order('home');
					}
					break;
			}
		}
		else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
			$this->getItems('slides',array('default_lang' => $defaultLanguage['id_lang']),'all');
			$assign = array(
				'id_slide',
				'url_slide' => array('title' => 'name'),
				'img_slide' => array('type' => 'bin', 'input' => null, 'class' => ''),
				'title_slide' => array('title' => 'name'),
				'desc_slide' => array('title' => 'name')
			);
			$this->data->getScheme(array('mc_homebrands', 'mc_homebrands_content'), array('id_slide', 'url_slide', 'img_slide','title_slide','desc_slide'), $assign);
			$this->template->display('index.tpl');
		}
	}
}