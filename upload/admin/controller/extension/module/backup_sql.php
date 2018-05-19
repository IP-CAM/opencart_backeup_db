<?php
class ControllerExtensionModuleBackupSql extends Controller {
	private $error = array();
	const BACKUP_DIR = './myBackups';
	public function index() {
		$fileName = 'mysqlbackup-'. date('d-m-Y') . '@'.date('h.i.s').'.sql';
		$this->load->language('extension/module/backup_sql');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		/**
		 * getting data from db
		 */

		$this->load->model('catalog/backup_sql');


		if (!file_exists(self::BACKUP_DIR)) mkdir(self::BACKUP_DIR , 0700) ;
		if (!is_writable(self::BACKUP_DIR)) chmod(self::BACKUP_DIR , 0700) ;


		$content = 'Allow from all' ;
		$file = new SplFileObject(self::BACKUP_DIR . '/.htaccess', "w") ;
		$file->fwrite($content) ;

		if (isset($this->request->post['save_sql'])) {
			$return = $this->model_catalog_backup_sql->getsql();

			$zip = new ZipArchive();
			$resOpen = $zip->open(self::BACKUP_DIR . '/' . "back.zip", ZIPARCHIVE::CREATE);
			if ($resOpen) {
				$zip->addFromString($fileName, "$return");
			}
			$zip->close();

			$arr = [
				'link' => $this->request->server['SERVER_NAME'].'/admin/myBackups/back.zip',
				'size' => $this->get_file_size_unit(filesize(self::BACKUP_DIR . '/' . "back.zip"))
			];

			echo json_encode($arr);
			die();
		}

		if (file_exists( DIR_APPLICATION.'myBackups/back.zip'))
		{
			$data['file_link'] = '/admin/myBackups/back.zip';
			$data['file_size'] = $this->get_file_size_unit(filesize(self::BACKUP_DIR . '/' . "back.zip"));
			$data['button_save_sql'] = $this->language->get('button_save_sql');
		}

		$data['text_save_header'] = $this->language->get('text_save_header');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('category', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/category', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/backup_sql', 'token=' . $this->session->data['token'], true);
		$data['token'] = $this->session->data['token'];

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['category_status'])) {
			$data['category_status'] = $this->request->post['category_status'];
		} else {
			$data['category_status'] = $this->config->get('category_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/backup_sql', $data));
	}

	protected  function get_file_size_unit($file_size){
		switch (true) {
			case ($file_size/1024 < 1) :
				return intval($file_size ) ." Bytes" ;
				break;
			case ($file_size/1024 >= 1 && $file_size/(1024*1024) < 1)  :
				return intval($file_size/1024) ." KB" ;
				break;
			default:
				return intval($file_size/(1024*1024)) ." MB" ;
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}