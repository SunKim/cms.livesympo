<?php
/**
 * Login.php
 *
 * Livesympo CMS 로그인 Controller
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

use App\Models\AdminModel;

class Login extends BaseController {

	protected $bcryptOptions = ['cost' => 14];

	public function __construct() {
    	$this->adminModel = new AdminModel();
  	}

	public function index() {
		// if ($this->request->getMethod(TRUE) === "GET") {
		// 	return $this->login_form();
		// } else if ($this->request->getMethod(TRUE) === "POST") {
		// 	return $this->login_check();
		// }

		return $this->login_form();
	}

	// 로그인 화면
	public function login_form () {
		return view('login/login_form.php');
	}

	//ajax - 로그인 체크
	public function checkLogin() {
		$email = $this->request->getPost('email');
		$pwd = $this->request->getPost('pwd');

		//ID, PWD 체크 로직
		$adminData = $this->adminModel->checkLogin($email);

		//정상이면 세션처리 하고 ok
		if (isset($adminData)) {
			if ( $adminData['DEL_YN'] == 1 ) {
				$resData['resCode'] = '1020';
				$resData['resMsg'] = '삭제된 사용자입니다.';
			} else if (!password_verify($pwd, $adminData['PWD'])) {
				$resData['resCode'] = '1030';
				$resData['resMsg'] = '패스워드가 맞지 않습니다.';
			} else {
				$sessData = array(
					'email' => $adminData['EMAIL']
					, 'admSeq' => $adminData['ADM_SEQ']
					, 'admNm' => $adminData['ADM_NM']
					, 'lvl' => $adminData['LVL']
					, 'logged_in' => 1
				);

				$this->session->set($sessData);

				$resData['resCode'] = '0000';
				$resData['resMsg'] = '정상적으로 처리되었습니다.';
			}
		} else {
			$resData['resCode'] = '1010';
			$resData['resMsg'] = '이메일(아이디)가 존재하지 않습니다.';
		}


		// echo json_encode($resData);
		return $this->response->setJSON($resData);
	}

	//로그아웃 - 세션 종료
	public function logout() {
		$this->session->destroy();

		echo "<script>location.href='/login';</script>";
	}
}
