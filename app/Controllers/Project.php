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

use App\Models\ProjectModel;

class Project extends BaseController {

	const DEFAULT_ITEMS_PER_PAGE = 20;

	public function __construct() {
    	$this->projectModel = new ProjectModel();
  	}

	public function index() {
		return $this->list();
	}

	// 리스트 화면
	public function list () {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['cntItem'] = array(
			'CNT_ALL' => 57
			, 'CNT_COMP' => 40
			, 'CNT_ING' => 2
			, 'CNT_COMING' => 15
		);

		return view('project/list', $data);
	}

	// 상세 화면
	public function detail ($prjSeq = 0) {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['PRJ_SEQ'] = $prjSeq;

		return view('project/detail', $data);
	}

	//ajax - 프로젝트 리스트
	public function getList() {
		// param 받기
		// $param = $this->request->getJSON();
		// $param = $this->request->getPost('email');
		$param = (object) array (
			'itemsPerPage' => $this->request->getPost('itemsPerPage')
			, 'pageNo' => $this->request->getPost('pageNo')
			, 'prjTitle' => $this->request->getPost('prjTitle')
			, 'prjTitleUri' => $this->request->getPost('prjTitleUri')
			, 'stDttm' => $this->request->getPost('stDttm')
			, 'edDttm' => $this->request->getPost('edDttm')
		);

		$beginIndex = $this->getPagingIndex($param)['beginIndex'];
		$endIndex = $this->getPagingIndex($param)['endIndex'];

		// 프로젝트 리스트
		$prjList = $this->projectModel->list($param, $beginIndex, $endIndex);

		// 프로젝트 count
		$totCntItem = $this->projectModel->count($param);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $prjList;
		$data['totCnt'] = $totCntItem['CNT_ALL'];
		$data['item'] = $totCntItem;

		// 받은 parameter는 그대로 response
		$data['param'] = $param;

		return $this->response->setJSON($data);
	}

	//ajax - 프로젝트 상세
	public function getDetail() {
		// param 받기
		// $param = $this->request->getJSON();
		// $param = $this->request->getPost('email');
		$prjSeq = $this->request->getPost('prjSeq');

		// 프로젝트 아이템
		$prjList = $this->projectModel->detail($prjSeq);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['item'] = $prjList;

		return $this->response->setJSON($data);
	}

	// 저장
	public function save () {
		$uploadPath = $_ENV['UPLOAD_BASE_PATH'];

		// param들 받기
		$data['PRJ_TITLE'] = $this->request->getPost('PRJ_TITLE');
		$data['PRJ_TITLE_URI'] = $this->request->getPost('PRJ_TITLE_URI');
		$data['STREAM_URL'] = $this->request->getPost('STREAM_URL');
		$data['AGENDA_PAGE_YN'] = $this->request->getPost('AGENDA_PAGE_YN');

		$data['ST_DTTM'] = $this->request->getPost('ST_DATE').' '.$this->request->getPost('ST_TIME').':00';
		$data['ED_DTTM'] = $this->request->getPost('ED_DATE').' '.$this->request->getPost('ED_TIME').':00';

		$data['ENT_THME_COLOR'] = $this->request->getPost('ENT_THME_COLOR');
		$data['APPL_BTN_COLOR'] = $this->request->getPost('APPL_BTN_COLOR');
		// print_r($data);

		// https://cnpnote.tistory.com/entry/PHP-jQuery-%EB%B0%8F-AJAX%EB%A5%BC-%EC%82%AC%EC%9A%A9%ED%95%98%EC%97%AC-%EC%97%AC%EB%9F%AC-%ED%8C%8C%EC%9D%BC%EC%9D%84-%EC%97%85%EB%A1%9C%EB%93%9C%ED%95%98%EB%8A%94-%EB%B0%A9%EB%B2%95

		// http://ci4doc.cikorea.net/libraries/uploaded_files.html
		// 파일들 받기
		$files = $this->request->getFiles();

		// 파일 확장자 체크(이미지인지)
		try {
			if ($files) {
				foreach ($files as $key => $file) {
					// echo "key : $key, file : $file\n";
					// $key : form/input에서의 name
					// $file : 서버에 임시저장된 파일명. ex) /private/var/tmp/phpvnBwzw

					if ($file->isValid() && !$file->hasMoved()) {
						$src = $file->getRealPath();
						$ext = $file->getExtension();

						echo "src : $src, ext : $ext, uploadPath : $uploadPath\n";

					//
					// 	// 썸네일 생성 여부
					// 	if ($thumbYn) {
					// 		$thumbResult = self::generateThumbnail($src, $basePath, $path, $newFileName, $ext);
					// 		if ($thumbResult) {
					// 			$result['thumbName'] = $newFileName.'_thumb.'.$ext;
					// 		} else {
					// 			$result['thumbName'] = 'failed to generate thumbName';
					// 		}
					// 	}

					// // directory가 없으면 생성
					// if ( !is_dir($basePath.$path) ) {
					// 	// mkdir(path, mode, recursive). recursive는 꼭 true로!!
					// 	mkdir($basePath.$path, 0755, true);
					// }

					// 	// 새로운 파일명에 extension 붙여줌
					// 	$newFileName = $newFileName.'.'.$ext;
					// 	// result 설정
					// 	$result['orgFileName'] = $file->getName();
					// 	$result['baseUrl'] = $_ENV['CDN_BASE_URL'];
					// 	$result['path'] = $path;
					// 	$result['fileName'] = $newFileName;
					//
					// 	// 파일 이동
					// 	$file->move($basePath.$path, $newFileName);
					} else {
						throw new RuntimeException($file->getErrorString().'('.$file->getError().')');
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
		}

		// 파일들 정상이면 DB 처리(uri는 추후 update)

		// 파일 처리

		// DB update
	}

	// 페이지리스트의 param(itemsPerPage, pageNo을 포함한 obj)를 받아서 beginIndex, endIndex return
	public function getPagingIndex ($param) {
		$beginIndex = 0;
		$endIndex = 0;

		// 한페이지에 보여줄 리스트 수
		$itemsPerPage = (isset($param->itemsPerPage) && $param->itemsPerPage > 0) ? $param->itemsPerPage : self::DEFAULT_ITEMS_PER_PAGE;
		if (isset($param->pageNo)) {
			$beginIndex = $itemsPerPage * ($param->pageNo - 1);
	    	$endIndex = $itemsPerPage * $param->pageNo;
		} else {
			$beginIndex = 0;
	    	$endIndex = $itemsPerPage;
		}

		return [
			'beginIndex' => $beginIndex,
			'endIndex' => $endIndex,
		];
	}

	// 랜덤 파일명 생성
	protected static function getRandomName (int $limit = 6) {
		// return substr(md5(time()), 0, $limit);
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle($chars), 0, $limit);
	}
}
