<?php
namespace Shim\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

/**
 * DRY Controller stuff
 */
class Controller extends CakeController {

	/**
	 * Add headers for IE8 etc to fix caching issues in those stupid browsers
	 *
	 * @return void
	 */
	public function disableCache() {
		$this->response->header([
			'Pragma' => 'no-cache',
		]);
		$this->response->disableCache();
	}

	/**
	 * @param Event $event
	 * @return void
	 */
	public function beforeRender(Event $event) {
		parent::beforeRender($event);

		// Automatically shim $this->request->data = $this->Model->find() etc which used to be of type array
		if (!empty($this->request->data) && $this->request->data instanceof Entity) {
			$this->request->data = $this->request->data->toArray();
		}
	}

	/**
	 * Hook to monitor headers being sent.
	 *
	 * This, if desired, adds a check if your controller actions are cleanly built and no headers
	 * or output is being sent prior to the response class, which should be the only one doing this.
	 *
	 * @param Event $event An Event instance
	 * @return void
	 */
	public function afterFilter(Event $event) {
		if (Configure::read('Shim.monitorHeaders') && $this->name !== 'Error' && php_sapi_name() !== 'cli') {
			if (headers_sent($filename, $linenum)) {
				$message = sprintf('Headers already sent in %s on line %s', $filename, $linenum);
				if (Configure::read('debug')) {
					throw new \Exception($message);
				}
				trigger_error($message);
			}
		}
	}

}
