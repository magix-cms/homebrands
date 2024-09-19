<?php

/**
 * Class plugins_gmap_db
 */
class plugins_homebrands_db
{
    /**
     * @var debug_logger $logger
     */
    protected debug_logger $logger;
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 */
    public function fetchData(array $config,array $params = []) {
        if($config['context'] === 'all') {
            switch ($config['type']) {
                case 'slides':
                    $query = 'SELECT 
                                id_slide,
                                url_slide,
                                img_slide,
                                title_slide,
                                desc_slide
                            FROM mc_homebrands as sl
                            LEFT JOIN mc_homebrands_content as slc USING(id_slide)
                            LEFT JOIN mc_lang as l USING(id_lang)
                            WHERE id_lang = :default_lang
                            ORDER BY order_slide ASC';
                    break;
                case 'homeSlides':
                    $query = 'SELECT 
                                id_slide,
                                url_slide,
                                blank_slide,
                                img_slide,
                                title_slide,
                                desc_slide
                            FROM mc_homebrands as sl
                            LEFT JOIN mc_homebrands_content as slc USING(id_slide)
                            LEFT JOIN mc_lang as l USING(id_lang)
                            WHERE iso_lang = :lang
                            AND published_slide = 1
                            ORDER BY order_slide ASC';
                    break;
                case 'slide':
                    $query = 'SELECT a.*,c.*
                            FROM mc_homebrands AS a
                            JOIN mc_homebrands_content AS c USING(id_slide)
                            JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                            WHERE c.id_lang = :default_lang';
                    break;
                case 'slideContent':
                    $query = 'SELECT a.*,c.*
                            FROM mc_homebrands AS a
                            JOIN mc_homebrands_content AS c USING(id_slide)
                            JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                            WHERE c.id_slide = :id';
                    break;
                case 'img':
                    $query = 'SELECT s.id_slide, s.img_slide
                            FROM mc_homebrands AS s WHERE s.img_slide IS NOT NULL';
                    break;
                default:
                    return false;
            }

            try {
                return component_routing_db::layer()->fetchAll($query, $params);
            }
            catch (Exception $e) {
                if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
            }
        }
        elseif($config['context'] === 'one') {
            switch ($config['type']) {
                case 'slideContent':
                    $query = 'SELECT * FROM mc_homebrands_content WHERE id_slide = :id AND id_lang = :id_lang';
                    break;
                case 'lastSlide':
                    $query = 'SELECT * FROM mc_homebrands ORDER BY id_slide DESC LIMIT 0,1';
                    break;
                case 'img':
                    $query = 'SELECT * FROM mc_homebrands WHERE id_slide = :id';
                    break;
                default:
                    return false;
            }

            try {
                return component_routing_db::layer()->fetch($query, $params);
            }
            catch (Exception $e) {
                if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
            }
        }
        return false;
    }

    /**
     * @param array $config
     * @param array $params
     * @return bool|void
     */
    public function insert(array $config, array $params = []) {

		switch ($config['type']) {
			case 'slide':
                $query = 'INSERT INTO mc_homebrands(img_slide, order_slide) 
						SELECT img_slide, COUNT(id_slide) FROM mc_homebrands';
				break;
			case 'slideContent':
                $query = 'INSERT INTO mc_homebrands_content(id_slide, id_lang, title_slide, desc_slide, url_slide, blank_slide, published_slide)
						VALUES (:id_slide, :id_lang, :title_slide, :desc_slide, :url_slide, :blank_slide, :published_slide)';
				break;
			case 'img':
                $query = 'UPDATE mc_homebrands 
						SET img_slide = :img_slide
						WHERE id_slide = :id_slide';
				break;
            default:
                return false;
		}

        try {
            component_routing_db::layer()->insert($query,$params);
            return true;
        }
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
    }

    /**
     * @param array $config
     * @param array $params
     * @return bool|void
     */
    public function update(array $config, array $params = []) {
        switch ($config['type']) {
			case 'slideContent':
                $query = 'UPDATE mc_homebrands_content
						SET 
							title_slide = :title_slide,
							desc_slide = :desc_slide,
							url_slide = :url_slide,
							blank_slide = :blank_slide,
							published_slide = :published_slide
						WHERE id_slide_content = :id 
						AND id_lang = :id_lang';
				break;
			case 'img':
                $query = 'UPDATE mc_homebrands
						SET 
							img_slide = :img
						WHERE id_slide = :id';
				break;
			case 'order':
                $query = 'UPDATE mc_homebrands 
						SET order_slide = :order_slide
						WHERE id_slide = :id_slide';
				break;
            default:
                return false;
		}

        try {
            component_routing_db::layer()->update($query,$params);
            return true;
        }
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
    }

    /**
     * @param array $config
     * @param array $params
     * @return bool
     */
    public function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'slide':
                $query = 'DELETE FROM mc_homebrands
						WHERE id_slide = :id';
				break;
            default:
                return false;
        }

        try {
            component_routing_db::layer()->delete($query,$params);
            return true;
        }
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
	}
}