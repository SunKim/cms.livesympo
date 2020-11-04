<?php
/**
 * Login.php
 *
 * Livesympo Admin용 Controller
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

class Admin extends BaseController {

	public function __construct() {
    	$this->adminModel = new AdminModel();
  	}

	public function index() {
		return $this->list();
	}

	// 리스트 화면
	public function list () {
		$data['menu'] = 'admin';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		return view('admin/list', $data);
	}

	//ajax - 관리자 리스트
	public function getList() {
		// 관리자 리스트
		$admList = $this->adminModel->list();

		// 프로젝트 count
		$totCntItem = $this->adminModel->count();

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $admList;
		$data['totCnt'] = $totCntItem['CNT_ALL'];
		$data['item'] = $totCntItem;

		return $this->response->setJSON($data);
	}

	// 관리자 등록
	public function save () {
		// param들 받기
		$email = $this->request->getPost('email');
		$pwd = $this->request->getPost('pwd');
		$admNm = $this->request->getPost('admNm');
		$lvl = $this->request->getPost('lvl');
		$orgNm = $this->request->getPost('orgNm');
		$regrId = $this->request->getPost('regrId');

		// 기존 등록된 이메일 있는지 확인
		$adminData = $this->adminModel->getDetailByEmail($email);
		if (isset($adminData)) {
			$resData['resCode'] = '7004';
			$resData['resMsg'] = '이미 등록된 관리자 이메일 입니다.';
			return $this->response->setJSON($resData);
		}

		$data = array(
			'EMAIL' => $email
			, 'ADM_NM' => $admNm
			, 'LVL' => $lvl
			, 'ORG_NM' => $orgNm
			, 'REGR_ID' => $regrId
		);
		// 패스워드 암호화
		$options = ['cost' => 14];
		$data['PWD'] = password_hash($pwd, PASSWORD_BCRYPT, $options);

		$admSeq = $this->adminModel->insertAdmin($data);

		if ($admSeq > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '7005';
			$resData['resMsg'] = '관리자를 등록하는 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	// 패스워드 변경
	public function changePwd () {
		// param들 받기
		$admSeq = $this->request->getPost('admSeq');
		$oldPwd = $this->request->getPost('oldPwd');
		$newPwd = $this->request->getPost('newPwd');

		// 기존 패스워드가 맞는지 체크
		$adminData = $this->adminModel->getDetail($admSeq);
		if (!isset($adminData)) {
			$resData['resCode'] = '7001';
			$resData['resMsg'] = '해당 관리자가 존재하지 않습니다.';
			return $this->response->setJSON($resData);
		} else if ( !password_verify($oldPwd, $adminData['PWD']) ) {
			$resData['resCode'] = '7002';
			$resData['resMsg'] = '기존 패스워드가 일치하지 않습니다.';
			return $this->response->setJSON($resData);
		}

		// 패스워드 변경 (암호화)
		$options = ['cost' => 14];
		$data['PWD'] = password_hash($newPwd, PASSWORD_BCRYPT, $options);
		$affectedRows = $this->adminModel->updateAdmin($admSeq, $data);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '7003';
			$resData['resMsg'] = '패스워드 변경중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	// 관리자 삭제 (DEL_YN)
	public function deleteAdmin () {
		// param들 받기
		$admSeq = $this->request->getPost('admSeq');

		$data['DEL_YN'] = 1;
		$affectedRows = $this->adminModel->updateAdmin($admSeq, $data);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '7004';
			$resData['resMsg'] = '관리자 삭제 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}
}
