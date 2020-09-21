<?php
/**
 * Login.php
 *
 * Livesympo Home용 Controller
 *
 * @package    App
 * @subpackage Controller
 * @author     20200914. SUN.
 * @copyright  Livesympo
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link
 * @see
 * @since      2020.09.14
 * @deprecated
 */
namespace App\Controllers;

class Dashboard extends BaseController {

	public function __construct() {
    	// $this->adminModel = new AdminModel();
  	}

	public function index() {
		return $this->dashboard();
	}

	// 로그인 화면
	public function dashboard () {
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		return view('dashboard/detail', $data);
	}
}
