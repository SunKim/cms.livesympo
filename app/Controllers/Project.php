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
use App\Models\QuestionModel;
use App\Models\SurveyModel;
use App\Models\RequestorModel;
use App\Models\AdminModel;

class Project extends BaseController {

	const DEFAULT_ITEMS_PER_PAGE = 20;

	// 썸네일 생성용 php 내장 function 정의
	const IMAGE_HANDLERS = [
	    IMAGETYPE_JPEG => [
	        'load' => 'imagecreatefromjpeg',
	        'save' => 'imagejpeg',
	        'quality' => 100
	    ],
	    IMAGETYPE_PNG => [
	        'load' => 'imagecreatefrompng',
	        'save' => 'imagepng',
	        'quality' => 0
	    ],
	    IMAGETYPE_GIF => [
	        'load' => 'imagecreatefromgif',
	        'save' => 'imagegif'
	    ]
	];
	// 기본 썸네일 width
	const THUMBNAIL_WIDTH = 200;


	public function __construct() {
    	$this->projectModel = new ProjectModel();
		$this->questionModel = new QuestionModel();
		$this->surveyModel = new SurveyModel();
		$this->requestorModel = new RequestorModel();
		$this->adminModel = new AdminModel();
  	}

	public function index () {
		return $this->list();
	}

	// 리스트 화면
	public function list () {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['livesympoUrl'] = $_ENV['app.livesympoBaseUrl'];

		return view('project/list', $data);
	}

	// 상세 화면
	public function detail ($prjSeq = 0) {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['prjSeq'] = $prjSeq;

		return view('project/detail', $data);
	}

	// 질문관리 화면
	public function question ($prjSeq = 0) {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['prjSeq'] = $prjSeq;
		$data['project'] = $this->projectModel->detail($prjSeq);
		$data['livesympoUrl'] = $_ENV['app.livesympoBaseUrl'];

		return view('project/question', $data);
	}

	// 설문관리 화면
	public function survey ($prjSeq = 0) {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['prjSeq'] = $prjSeq;
		$data['project'] = $this->projectModel->detail($prjSeq);

		return view('project/survey', $data);
	}

	// 사전등록자 관리 화면
	public function requestor ($prjSeq = 0) {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['prjSeq'] = $prjSeq;
		$data['project'] = $this->projectModel->detail($prjSeq);
		$data['reqrList'] = $this->requestorModel->list($prjSeq);

		return view('project/requestor', $data);
	}

	// 스트리밍 방송자용 질문목록 화면
	public function moderator ($prjSeq = 0) {
		$data['project'] = $this->projectModel->detail($prjSeq);

		return view('project/moderator', $data);
	}

	//ajax - 프로젝트 리스트
	public function getList () {
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
		// 전체건수
		$data['totCnt'] = $totCntItem['CNT_ALL'];
		// 건수들 아이템(진행완료, 진행중, 향후진행)
		$data['item'] = $totCntItem;

		// 받은 parameter는 그대로 response
		$data['param'] = $param;

		return $this->response->setJSON($data);
	}

	//ajax - 프로젝트 상세
	public function getDetail () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$lvl = $this->request->getPost('lvl');

		// 프로젝트 아이템
		$prjItem = $this->projectModel->detail($prjSeq);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['item'] = $prjItem;
		$data['entGuideList'] = $this->projectModel->enterGuideList($prjSeq);
		$data['dataAdmList'] = $this->adminModel->list(2);

		return $this->response->setJSON($data);
	}

	// ajax - 프로젝트 저장
	public function save ($prjSeq = 0) {
		$insOrUpd = $prjSeq > 0 ? 'upd' : 'ins';

		// 기본 업로드 path. ex) /Users/seonjungkim/workspace_php/cms.livesympo/public/uploads/project
		$uploadPath = $_ENV['UPLOAD_BASE_PATH'];

		// param들 받기
		$data['PRJ_TITLE'] = $this->request->getPost('PRJ_TITLE');
		$data['PRJ_TITLE_URI'] = $this->request->getPost('PRJ_TITLE_URI');
		$data['STREAM_URL'] = $this->request->getPost('STREAM_URL');
		// $data['AGENDA_PAGE_YN'] = $this->request->getPost('AGENDA_PAGE_YN') !== null ? $this->request->getPost('AGENDA_PAGE_YN') : 0;
		$data['ONAIR_YN'] = $this->request->getPost('ONAIR_YN');

		$data['ST_DTTM'] = $this->request->getPost('ST_DATE').' '.$this->request->getPost('ST_TIME').':00';
		$data['ED_DTTM'] = $this->request->getPost('ED_DATE').' '.$this->request->getPost('ED_TIME').':00';

		$data['CONN_ROUTE_1'] = $this->request->getPost('CONN_ROUTE_1');
		$data['CONN_ROUTE_2'] = $this->request->getPost('CONN_ROUTE_2');
		$data['CONN_ROUTE_3'] = $this->request->getPost('CONN_ROUTE_3');

		$data['ENT_INFO_EXTRA_1'] = $this->request->getPost('ENT_INFO_EXTRA_1');
		$data['ENT_INFO_EXTRA_PHOLDER_1'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_1');
		$data['ENT_INFO_EXTRA_REQUIRED_1'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_1');
		$data['ENT_INFO_EXTRA_2'] = $this->request->getPost('ENT_INFO_EXTRA_2');
		$data['ENT_INFO_EXTRA_PHOLDER_2'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_2');
		$data['ENT_INFO_EXTRA_REQUIRED_2'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_2');
		$data['ENT_INFO_EXTRA_3'] = $this->request->getPost('ENT_INFO_EXTRA_3');
		$data['ENT_INFO_EXTRA_PHOLDER_3'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_3');
		$data['ENT_INFO_EXTRA_REQUIRED_3'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_3');
		$data['ENT_INFO_EXTRA_4'] = $this->request->getPost('ENT_INFO_EXTRA_4');
		$data['ENT_INFO_EXTRA_PHOLDER_4'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_4');
		$data['ENT_INFO_EXTRA_REQUIRED_4'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_4');
		$data['ENT_INFO_EXTRA_5'] = $this->request->getPost('ENT_INFO_EXTRA_5');
		$data['ENT_INFO_EXTRA_PHOLDER_5'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_5');
		$data['ENT_INFO_EXTRA_REQUIRED_5'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_5');
		$data['ENT_INFO_EXTRA_6'] = $this->request->getPost('ENT_INFO_EXTRA_6');
		$data['ENT_INFO_EXTRA_PHOLDER_6'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_6');
		$data['ENT_INFO_EXTRA_REQUIRED_6'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_6');
		$data['ENT_INFO_EXTRA_7'] = $this->request->getPost('ENT_INFO_EXTRA_7');
		$data['ENT_INFO_EXTRA_PHOLDER_7'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_7');
		$data['ENT_INFO_EXTRA_REQUIRED_7'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_7');
		$data['ENT_INFO_EXTRA_8'] = $this->request->getPost('ENT_INFO_EXTRA_8');
		$data['ENT_INFO_EXTRA_PHOLDER_8'] = $this->request->getPost('ENT_INFO_EXTRA_PHOLDER_8');
		$data['ENT_INFO_EXTRA_REQUIRED_8'] = $this->request->getPost('ENT_INFO_EXTRA_REQUIRED_8');

		$data['AGENDA_BTN_TEXT'] = $this->request->getPost('AGENDA_BTN_TEXT');
		$data['SURVEY_BTN_TEXT'] = $this->request->getPost('SURVEY_BTN_TEXT');
		$data['QST_BTN_TEXT'] = $this->request->getPost('QST_BTN_TEXT');

		$data['APPL_BODY_COLR'] = $this->request->getPost('APPL_BODY_COLR');
		$data['APPL_BTN_BG_COLR'] = $this->request->getPost('APPL_BTN_BG_COLR');
		$data['APPL_BTN_FONT_COLR'] = $this->request->getPost('APPL_BTN_FONT_COLR');
		$data['APPL_BTN_ALIGN'] = $this->request->getPost('APPL_BTN_ALIGN');
		$data['APPL_BTN_ROUND_YN'] = $this->request->getPost('APPL_BTN_ROUND_YN');

		$data['ENT_THME_COLR'] = $this->request->getPost('ENT_THME_COLR');
		$data['ENT_THME_HEIGHT'] = $this->request->getPost('ENT_THME_HEIGHT');
		$data['ENT_BTN_BG_COLR'] = $this->request->getPost('ENT_BTN_BG_COLR');
		$data['ENT_BTN_FONT_COLR'] = $this->request->getPost('ENT_BTN_FONT_COLR');
		$data['ENT_BTN_ROUND_YN'] = $this->request->getPost('ENT_BTN_ROUND_YN');

		$data['STREAM_BODY_COLR'] = $this->request->getPost('STREAM_BODY_COLR');
		$data['STREAM_BTN_BG_COLR'] = $this->request->getPost('STREAM_BTN_BG_COLR');
		$data['STREAM_BTN_FONT_COLR'] = $this->request->getPost('STREAM_BTN_FONT_COLR');
		$data['STREAM_QA_BG_COLR'] = $this->request->getPost('STREAM_QA_BG_COLR');
		$data['STREAM_QA_FONT_COLR'] = $this->request->getPost('STREAM_QA_FONT_COLR');

		$data['DATA_ADM_SEQ_1'] = $this->request->getPost('DATA_ADM_SEQ_1');
		$data['DATA_ADM_SEQ_2'] = $this->request->getPost('DATA_ADM_SEQ_2');
		// print_r($data);

		$entGuideList = json_decode($this->request->getPost('entGuideList'));
		// print_r($entGuideList);
		// return;

		// 프로젝트 URI 체크 (프로젝트 저장시 기존에 입력된 동일한 URI 존재여부 체크)
		if (count($this->projectModel->checkTitleUri($data['PRJ_TITLE_URI'], $prjSeq)) > 0) {
			$res['resCode'] = '9998';
			$res['resMsg'] = '이미 동일한 URI가 존재합니다. 다른 URI를 입력해주세요.';

			return $this->response->setJSON($res);
		}

		/************************************
		* START) Transaction 처리
		************************************/
		$db = \Config\Database::connect();
		$db->transStart();

		// 0이면 신규등록. 프로젝트 insert 해서 PRJ_SEQ 받아옴
		if ($insOrUpd === 'ins') {
			$data['REGR_ID'] = $this->request->getPost('EMAIL');

			$prjSeq = $this->projectModel->insertProject($data);
		} else {
			$data['MODR_ID'] = $this->request->getPost('EMAIL');
			$data['MOD_DTTM'] = date('Y-m-d H:i:s');

			$affectedRows = $this->projectModel->updateProject($prjSeq, $data);
		}

		if (($insOrUpd === 'ins' && $prjSeq == 0) || ($insOrUpd === 'upd' && $affectedRows == 0)) {
			$resData['resCode'] = '9999';
			$resData['resMsg'] = '프로젝트 저장에 실패했습니다.';

			return $this->response->setJSON($resData);
		}

		// 파일들 받기
		// http://ci4doc.cikorea.net/libraries/uploaded_files.html
		$files = $this->request->getFiles();

		try {
			if ($files) {
				foreach ($files as $key => $file) {
					// echo "key : $key, file : $file\n";
					// $key : form/input에서의 name
					// $file : 서버에 임시저장된 파일명. ex) /private/var/tmp/phpvnBwzw

					if (isset($file) && $file->isValid() && !$file->hasMoved()) {
						$src = $file->getRealPath();
						$ext = $file->getExtension();

						// echo "src : $src, ext : $ext, uploadPath : $uploadPath\n";
						$path = $uploadPath.DIRECTORY_SEPARATOR.'project'.DIRECTORY_SEPARATOR.$prjSeq;

						// directory가 없으면 생성
						if ( !is_dir($path) ) {
							// mkdir(path, mode, recursive). recursive는 꼭 true로!!
							mkdir($path, 0755, true);
							// log_message('info', "Project.php - save() 파일처리. 파일저장용 directory 생성 - $path");
						}

						// 새로운 파일명에 extension 붙여줌
						// $key : form의 input의 name. MAIN_IMG, AGENDA_IMG, FOOTER_IMG => MAIN_IMG_1.png 형태로
						$newFileName = $key.'_'.$prjSeq.'_'.$this->getRandomName().'.'.$ext;
						$thumbNm = $key.'_'.$prjSeq.'_THUMB'.'_'.$this->getRandomName().'.'.$ext;

						// log_message('info', "Project.php - save() 파일처리. prjSeq: $prjSeq, path: $path, key: $key, file : $file, newFileName: $newFileName, thumbNm: $thumbNm");

						// 이미 파일이 있으면 삭제
						if (file_exists($path.DIRECTORY_SEPARATOR.$newFileName)) {
							// log_message('info', "Project.php - save() 파일처리. 기존파일 존재해서 삭제 필요. ".$path.DIRECTORY_SEPARATOR.$thumbNm);
							unlink($path.DIRECTORY_SEPARATOR.$newFileName);
						}
						if (file_exists($path.DIRECTORY_SEPARATOR.$thumbNm)) {
							// log_message('info', "Project.php - save() 파일처리. 기존파일 존재해서 삭제 필요. ".$path.DIRECTORY_SEPARATOR.$thumbNm);
							unlink($path.DIRECTORY_SEPARATOR.$thumbNm);
						}

						// 썸네일 생성
						$thumbResult = self::generateThumbnail($src, $path, $thumbNm);

						// 파일 이동
						$file->move($path, $newFileName);
						// log_message('info', "Project.php - save(). key : $key, file : $file, path: $path, newFileName: $newFileName");

						$updateUriData[$key.'_URI'] = '/uploads/project/'.$prjSeq.'/'.$newFileName;
						$updateUriData[$key.'_THUMB_URI'] = '/uploads/project/'.$prjSeq.'/'.$thumbNm;
						// DB update (파일이 있을때만)
						$this->projectModel->updateProject($prjSeq, $updateUriData);
					} else {
						// 프로젝트 수정시 파일을 안건드리면 파일이 안올라오므로 에러를 띄우면 안됨
						// log_message('error', $file->getErrorString().'('.$file->getError().')');
						// throw new RuntimeException($file->getErrorString().'('.$file->getError().')');
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', "exception - ".$e->getMessage());
		}

		// 입장가이드 delete 후 insert
		$this->projectModel->deleteEnterGuide($prjSeq);

		for ($i = 0; $i < count($entGuideList); $i++) {
			$entGuideData = array(
				'PRJ_SEQ' => $prjSeq
				, 'SERL_NO' => $i+1
				, 'GUIDE_DESC' => $entGuideList[$i]
				, 'REGR_ID' => $this->request->getPost('EMAIL')
				, 'REG_DTTM' => date('Y-m-d H:i:s')
				, 'DEL_YN' => 0
			);

			$this->projectModel->insertEnterGuide($entGuideData);
		}

		$db->transComplete();
		/************************************
		* END) Transaction 처리
		************************************/

		if ($db->transStatus() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			log_message('error', 'Project.php - save : 트랜잭션 처리 에러');

			$res['resCode'] = '9999';
			$res['resMsg'] = '프로젝트 저장에 실패했습니다.';
		} else {
			$res['resCode'] = '0000';
			$res['resMsg'] = '정상적으로 처리되었습니다.';
		}

		return $this->response->setJSON($res);
	}

	//ajax - 프로젝트 삭제
	public function delete () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');

		$data['DEL_YN'] = 1;
		$affectedRows = $this->projectModel->updateProject($prjSeq, $data);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9997';
			$resData['resMsg'] = '프로젝트 삭제 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	// ajax - 프로젝트 사전등록자 리스트
	public function getRequestorList () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		// log_message('info', "Project - getRequestorList. prjSeq: $prjSeq");

		// 질문목록
		$reqrList = $this->requestorModel->list($prjSeq);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $reqrList;

		return $this->response->setJSON($data);
	}

	//ajax - 프로젝트 사전등록자 목록 삭제
	public function deleteRequestor () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');

		$affectedRows = $this->requestorModel->deleteRequestor($prjSeq);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9997';
			$resData['resMsg'] = '프로젝트 사전등록자 목록을 삭제하는 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	//ajax - 프로젝트 사전등록자 목록 .csv 파일 업로드
	public function uploadRequestor ($prjSeq = 0) {
		// 기본 업로드 path. ex) /Users/seonjungkim/workspace_php/cms.livesympo/public/uploads/project
		$uploadPath = $_ENV['UPLOAD_BASE_PATH'];
		$path = $uploadPath.DIRECTORY_SEPARATOR.'temp';
		$fileNm = 'uploaded_csv.txt';

		// 파일들 받기
		// http://ci4doc.cikorea.net/libraries/uploaded_files.html
		$files = $this->request->getFiles();

		try {
			if ($files) {
				foreach ($files as $key => $file) {
					// echo "key : $key, file : $file\n";
					// $key : form/input에서의 name
					// $file : 서버에 임시저장된 파일명. ex) /private/var/tmp/phpvnBwzw

					if (isset($file) && $file->isValid() && !$file->hasMoved()) {

						// directory가 없으면 생성
						if ( !is_dir($path) ) {
							// mkdir(path, mode, recursive). recursive는 꼭 true로!!
							mkdir($path, 0755, true);
						}

						// 이미 기존에 업로드한 파일(uploaded_csv.txt)이 있으면 삭제
						if (file_exists($path.DIRECTORY_SEPARATOR.$fileNm)) {
							unlink($path.DIRECTORY_SEPARATOR.$fileNm);
						}

						// 파일 이동
						$file->move($path, $fileNm);

						// 파일 열기 성공하면
						if ($readFile = fopen($path.DIRECTORY_SEPARATOR.$fileNm , 'r')) {
							// 한줄씩 읽음
							while (($lineData = fgetcsv($readFile, 1000, ",")) !== FALSE) {
								print_r($lineData);
							}

							// 파일 닫기
							fclose($readFile);
						}
					} else {
						$resData['resCode'] = '9996';
						$resData['resMsg'] = '정상적인 파일이 아닙니다.';

						return $this->response->setJSON($resData);
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', "exception - ".$e->getMessage());
		}
	}

	// ajax - 프로젝트 질문 리스트
	public function getQuestionList () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$aprvYn = $this->request->getPost('aprvYn');
		$orderBy = $this->request->getPost('orderBy');
		// log_message('info', "Project.php - getQuestionList. prjSeq: $prjSeq, aprvYn: $aprvYn");

		// 질문목록
		$questionList = $this->questionModel->list($prjSeq, $aprvYn, $orderBy);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $questionList;

		return $this->response->setJSON($data);
	}

	// ajax - 질문 승인(APRV_YN)
	public function approveQuestion () {
		// param들 받기
		$qstSeq = $this->request->getPost('qstSeq');
		$aprvYn = $this->request->getPost('aprvYn');

		$data['APRV_YN'] = $aprvYn;
		$data['APRV_DTTM'] = date('Y-m-d H:i:s');
		$affectedRows = $this->questionModel->updateQuestion($qstSeq, $data);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9001';
			$resData['resMsg'] = '질문 승인 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	// ajax - 관리자 페이크질문 입력
	public function fakeQuestion () {
		// param들 받기
		$data['PRJ_SEQ'] = $this->request->getPost('prjSeq');
		$data['FAKE_YN'] = 1;
		$data['REQR_SEQ'] = 0;
		$data['FAKE_NM'] = $this->request->getPost('fakeNm');
		$data['QST_DESC'] = $this->request->getPost('qstDesc');

		$qstSeq = $this->questionModel->insertQuestion($data);

		if ($qstSeq > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9001';
			$resData['resMsg'] = '질문 저장 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	// ajax - 프로젝트에 딸린 설문 질문 및 보기 목록
	public function getSurveyList () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		// log_message('info', "Project.php - getSurveyList. prjSeq: $prjSeq");

		// 프로젝트 아이템
		$data['surveyQstList'] = $this->surveyModel->surveyQstList($prjSeq);
		$data['surveyQstChoiceList'] = $this->surveyModel->surveyQstChoiceList($prjSeq);
		$data['surveyAswList'] = $this->surveyModel->surveyAswList($prjSeq);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';

		return $this->response->setJSON($data);
	}

	// ajax - 설문 질문 및 객관식 보기 등록
	public function saveSurvey ($prjSeq = 0) {
		if ($prjSeq == 0) {
			$res['resCode'] = '9980';
			$res['resMsg'] = '프로젝트 시퀀스가 올바르지 않습니다.';
			return $this->response->setJSON($res);
		}

		// param들 받기
		$surveyQstList = $this->request->getPost('surveyQstList');
		$surveyQstChoiceList = $this->request->getPost('surveyQstChoiceList');

		/************************************
		* START) Transaction 처리
		************************************/
		$db = \Config\Database::connect();
		$db->transStart();

		// update 없이 delete 후 insert. 우선 기존 등록된걸 지움.
		$this->surveyModel->deleteSurveyQst($prjSeq);
		$this->surveyModel->deleteSurveyChoice($prjSeq);

		for ($i = 0; $i < count($surveyQstList); $i++) {
			$surveyQstItem = $surveyQstList[$i];

			// 보기들을 넣으려면 $surveyQstSeq를 알아야함
			$surveyQstSeq = $this->surveyModel->insertSurveyQst($surveyQstItem);

			// 해당 설문항목에 맞는 보기만 insert
			for ($j = 0; $j < count($surveyQstChoiceList); $j++) {
				$surveyQstChoiceItem = $surveyQstChoiceList[$j];

				// 같은 설문항목(질문)이면 보기를 해당 SURVE_QST_SEQ로 저장
				if ($surveyQstChoiceItem['QST_NO'] === $surveyQstItem['QST_NO']) {
					$surveyQstChoiceItem['SURVEY_QST_SEQ'] = $surveyQstSeq;

					$surveyQstChoiceSeq = $this->surveyModel->insertSurveyChoice($surveyQstChoiceItem);
				}
			}
		}

		$db->transComplete();
		/************************************
		* END) Transaction 처리
		************************************/

		if ($db->transStatus() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			log_message('error', 'Project.php - saveSurvey : 트랜잭션 처리 에러');

			$res['resCode'] = '9999';
			$res['resMsg'] = '프로젝트 저장에 실패했습니다.';
		} else {
			$res['resCode'] = '0000';
			$res['resMsg'] = '정상적으로 처리되었습니다.';
		}

		return $this->response->setJSON($res);
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

	// 썸네일 생성
	// cf) https://pqina.nl/blog/creating-thumbnails-with-php/
	protected static function generateThumbnail ($src, $path, $thumbNm) {
		// log_message('info', "Project.php - generateThumbnail. src: $src, path: $path, thumbNm: $thumbNm");
		$type = exif_imagetype($src);
		// log_message('info', "Project.php - generateThumbnail. type: $type");

		if (!$type || !self::IMAGE_HANDLERS[$type]) {
			return null;
		}

		$image = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $src);

		// no image found at supplied location -> exit
		if (!$image) {
			return null;
		}

		// 원본 사이즈 및 resize용 사이즈
		$orgWidth = imagesx($image);
		$orgHeight = imagesy($image);
		$ratio = $orgWidth / $orgHeight;
		$targetWidth = self::THUMBNAIL_WIDTH;
		$targetHeight = floor(self::THUMBNAIL_WIDTH / $ratio);

		// create duplicate image based on calculated target size
		$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

		// gif, png용 옵션 적용(투명도)
		if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {
			// 투명도 적용
			imagecolortransparent(
				$thumbnail,
				imagecolorallocate($thumbnail, 0, 0, 0)
			);

			// PNG용 추가 설정
			if ($type == IMAGETYPE_PNG) {
				imagealphablending($thumbnail, false);
				imagesavealpha($thumbnail, true);
			}
		}

		// 원본 이미지 복사 및 resize
		imagecopyresampled(
			$thumbnail,
			$image,
			0, 0, 0, 0,
			$targetWidth, $targetHeight,
			$orgWidth, $orgHeight
		);

		// 이미지 저장
		$dest = $path.DIRECTORY_SEPARATOR.$thumbNm;

		return call_user_func(
			self::IMAGE_HANDLERS[$type]['save'],
			$thumbnail,
			$dest,
			self::IMAGE_HANDLERS[$type]['quality']
		);
	}
}
