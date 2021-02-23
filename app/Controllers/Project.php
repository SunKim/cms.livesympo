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

		return view('project/requestor', $data);
	}

	// 스트리밍 방송자용 질문목록 화면
	public function moderator ($prjSeq = 0) {
		$data['project'] = $this->projectModel->detail($prjSeq);

		// return view('project/moderator', $data);
		return view('project/moderator2', $data);
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

		$admSeq = $this->request->getPost('admSeq');
		$lvl = $this->request->getPost('lvl');

		$beginIndex = $this->getPagingIndex($param)['beginIndex'];
		$endIndex = $this->getPagingIndex($param)['endIndex'];

		// 프로젝트 리스트
		$prjList = $this->projectModel->list($admSeq, $lvl, $param, $beginIndex, $endIndex);

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
		$data['ONAIR_ENT_TRM'] = $this->request->getPost('ONAIR_ENT_TRM');

		$data['ST_DTTM'] = $this->request->getPost('ST_DATE').' '.$this->request->getPost('ST_TIME').':00';
		$data['ED_DTTM'] = $this->request->getPost('ED_DATE').' '.$this->request->getPost('ED_TIME').':00';

		$data['EXT_SURVEY_YN'] = $this->request->getPost('EXT_SURVEY_YN');
		$data['EXT_SURVEY_URL'] = $this->request->getPost('EXT_SURVEY_URL');
		$data['NTC_DESC'] = $this->request->getPost('NTC_DESC');
		$data['QNA_TEXT'] = $this->request->getPost('QNA_TEXT');

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
		$data['ENT_THME_FONT_COLR'] = $this->request->getPost('ENT_THME_FONT_COLR');
		$data['ENT_THME_HEIGHT'] = $this->request->getPost('ENT_THME_HEIGHT');
		$data['ENT_BTN_BG_COLR'] = $this->request->getPost('ENT_BTN_BG_COLR');
		$data['ENT_BTN_FONT_COLR'] = $this->request->getPost('ENT_BTN_FONT_COLR');
		$data['ENT_BTN_ROUND_YN'] = $this->request->getPost('ENT_BTN_ROUND_YN');

		$data['STREAM_BODY_COLR'] = $this->request->getPost('STREAM_BODY_COLR');
		$data['STREAM_BTN_BG_COLR'] = $this->request->getPost('STREAM_BTN_BG_COLR');
		$data['STREAM_BTN_FONT_COLR'] = $this->request->getPost('STREAM_BTN_FONT_COLR');
		$data['STREAM_QA_BG_COLR'] = $this->request->getPost('STREAM_QA_BG_COLR');
		$data['STREAM_QA_FONT_COLR'] = $this->request->getPost('STREAM_QA_FONT_COLR');

		$data['MDRTOR_FONT_COLR'] = $this->request->getPost('MDRTOR_FONT_COLR');

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
					// $key : form/input에서의 name. ex) MAIN_IMG
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
		$prjSeq = $this->request->getPost('prjSeq');
		$search = $this->request->getPost('search');
		// log_message('info', "Project - getRequestorList. prjSeq: $prjSeq, searchReqrNm: ".$search['searchReqrNm'].", searchReqrMbilno: ".$search['searchReqrMbilno']);

		// 사전등록자, 참석자 목록
		$reqrList = $this->requestorModel->list($prjSeq, $search);
		// foreach($reqrList as $idx => $reqrItem) {
		// 	log_message('info', "Project - getRequestorList. reqrItem: ".$reqrItem['REQR_NM'].$reqrItem['MBILNO'].$reqrItem['CONN_ROUTE_VAL_NM']);
		// }

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $reqrList;

		return $this->response->setJSON($data);
	}

	// ajax - 프로젝트 참석자 리스트
	public function getAttendanceList () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$search = $this->request->getPost('search');

		// log_message('info', "Project - getAttendanceList. prjSeq: $prjSeq");

		// 사전등록자, 참석자 목록
		$attendanceList = $this->requestorModel->attendanceList($prjSeq, $search);
		// foreach($reqrList as $idx => $reqrItem) {
		// 	log_message('info', "Project - getRequestorList. reqrItem: ".$reqrItem['REQR_NM'].$reqrItem['MBILNO'].$reqrItem['CONN_ROUTE_VAL_NM']);
		// }

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $attendanceList;

		return $this->response->setJSON($data);
	}

	//ajax - 프로젝트 사전등록자 목록 전체 삭제
	public function deleteAllRequestor () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');

		$affectedRows = $this->requestorModel->deleteAllRequestor($prjSeq);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9997';
			$resData['resMsg'] = '프로젝트 사전등록자 목록을 삭제하는 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	//ajax - 프로젝트 사전등록자 선택 삭제
	public function deleteRequestor () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$reqrSeq = $this->request->getPost('reqrSeq');

		$affectedRows = $this->requestorModel->deleteRequestor($prjSeq, $reqrSeq);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9997';
			$resData['resMsg'] = '프로젝트 사전등록자를 삭제하는 도중 DB오류가 발생했습니다.';
		}

		return $this->response->setJSON($resData);
	}

	//ajax - 프로젝트 사전등록자 수정
	public function updateRequestor () {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$reqrSeq = $this->request->getPost('reqrSeq');
		$rowData = $this->request->getPost('rowData');

		$affectedRows = $this->requestorModel->updateRequestor($prjSeq, $reqrSeq, $rowData);

		if ($affectedRows > 0) {
			$resData['resCode'] = '0000';
			$resData['resMsg'] = '정상적으로 처리되었습니다.';
		} else {
			$resData['resCode'] = '9997';
			$resData['resMsg'] = '프로젝트 사전등록자를 수정하는 도중 DB오류가 발생했습니다.';
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

		// 해당 프로젝트 item (접속경로 text를 CONN_ROUTE_VAL로 변환하기 위해 필요)
		$prjItem = $this->projectModel->detail($prjSeq);

		$cntInsert = 0;
		$cntUpdate = 0;
		$logList = array();

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
								// print_r($lineData);

								// TB_PRJ_ENT_INFO_REQR_H 에 insert용 item
								$insertItem = array();

								// foreach로 하려했는데 그럴 필요가 없네;
								foreach($lineData as $idx => $colItem) {
									// echo "item : $idx - $colItem  ";
								}

								// TB_PRJ_ENT_INFO_REQR_H insert용 item 생성
								$insertItem['PRJ_SEQ'] = $prjSeq;
								if (isset($lineData[0]) && $lineData[0] != '') {
									$insertItem['REQR_NM'] = $lineData[0];
								}
								if (isset($lineData[1]) && $lineData[1] != '') {
									$insertItem['MBILNO'] = $lineData[1];
									$insertItem['MBILNO'] = str_replace('-', '', $insertItem['MBILNO']);
								}
								if (isset($lineData[2]) && $lineData[2] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_1'] = $lineData[2];
								}
								if (isset($lineData[3]) && $lineData[3] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_2'] = $lineData[3];
								}
								if (isset($lineData[4]) && $lineData[4] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_3'] = $lineData[4];
								}
								if (isset($lineData[5]) && $lineData[5] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_4'] = $lineData[5];
								}
								if (isset($lineData[6]) && $lineData[6] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_5'] = $lineData[6];
								}
								if (isset($lineData[7]) && $lineData[7] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_6'] = $lineData[7];
								}
								if (isset($lineData[8]) && $lineData[8] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_7'] = $lineData[8];
								}
								if (isset($lineData[9]) && $lineData[9] != '') {
									$insertItem['ENT_INFO_EXTRA_VAL_8'] = $lineData[9];
								}
								// 접속경로는 CONN_ROUTE_VAL로 변환 필요
								if (isset($lineData[10]) && $lineData[10] != '') {
									// $insertItem['CONN_ROUTE_RAW'] = $lineData[10];

									if ($lineData[10] === $prjItem['CONN_ROUTE_1']) {
										$insertItem['CONN_ROUTE_VAL'] = 1;
									} else if ($lineData[10] === $prjItem['CONN_ROUTE_2']) {
										$insertItem['CONN_ROUTE_VAL'] = 2;
									} else if ($lineData[10] === $prjItem['CONN_ROUTE_3']) {
										$insertItem['CONN_ROUTE_VAL'] = 3;
									}
								}
								if (isset($lineData[11]) && $lineData[11] != '') {
									$insertItem['REG_DTTM'] = $lineData[11];
								}

								// 우선 REQR_M에 없으면 insert, 있으면 REQR_SEQ 확인
								$reqrSeq = $this->requestorModel->checkReqr($insertItem['REQR_NM'], $insertItem['MBILNO']);

								// 존재하지 않으면 신청자마스터 (TB_REQR_M) insert
								if ($reqrSeq == 0) {
									$reqrData = array(
										'REQR_NM' => $insertItem['REQR_NM']
										, 'MBILNO' => $insertItem['MBILNO']
									);
									$reqrSeq = $this->requestorModel->insertReqr($reqrData);

									$log = "사전등록자 신규등록[".$insertItem['REQR_NM'].",".$insertItem['MBILNO']."] ";
								} else {
									$log = "사전등록자 이미존재[".$insertItem['REQR_NM'].",".$insertItem['MBILNO']."] ";
								}

								$insertItem['REQR_SEQ'] = $reqrSeq;

								// TB_PRJ_ENT_INFO_REQR_H insert
								// 우선 기존에 등록된게 있는지 체크($prjSeq, $reqrSeq)
								$existItem = $this->requestorModel->checkEntInfoReqr($prjSeq, $reqrSeq);
								if (isset($existItem)) {
									// 기존에 등록된게 있으면 update
									$affectedRows = $this->requestorModel->updateEntInfoReqr($insertItem);
									$cntUpdate += $affectedRows;

									$log = $log.'기존 사전신청정보 존재로 UPDATE ';
								} else {
									$entInfoReqrSeq = $this->requestorModel->insertEntInfoReqr($insertItem);

									// 성공횟수 설정
									if ($entInfoReqrSeq > 0) {
										$cntInsert++;
									}

									$log = $log.'사전신청정보 신규 INSERT ';
								}

								$logList[] = $log;
							}

							// 파일 닫기
							fclose($readFile);
						}

						$resData['resCode'] = '0000';
						$resData['resMsg'] = '정상적으로 처리되었습니다.';
						$resData['cntInsert'] = $cntInsert;
						$resData['cntUpdate'] = $cntUpdate;
						$resData['logList'] = $logList;

						return $this->response->setJSON($resData);
					} else {
						$resData['resCode'] = '9996';
						$resData['resMsg'] = '정상적인 파일이 아닙니다.';

						return $this->response->setJSON($resData);
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', "exception - ".$e->getMessage());

			$resData['resCode'] = '9995';
			$resData['resMsg'] = '파일 업로드 도중 문제가 발생했습니다. 메세지 : '.$e->getMessage();

			return $this->response->setJSON($resData);
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
		$surveyQstList = $this->surveyModel->surveyQstList($prjSeq);
		$surveyQstChoiceList = $this->surveyModel->surveyQstChoiceList($prjSeq);
		$surveyAswList = $this->surveyModel->surveyAswList($prjSeq);

		// 설문 객관식 보기에 대한 응답률 설정 (복수응답이 있고 설문응답은 1row라 쿼리로 설정하기 힘듦)
		foreach($surveyQstChoiceList as $idx => $surveyQstChoiceItem) {
			$qstNo = $surveyQstChoiceItem['QST_NO'];
			$choiceNo = $surveyQstChoiceItem['CHOICE_NO'];
			// log_message('info', "Project.php - getSurveyList. 객관식 choice 돌기. qstNo : $qstNo, choiceNo : $choiceNo");

			$choicedList = array_filter(
				$surveyAswList,
			    function ($e) use ($qstNo, $choiceNo) {
					// log_message('info', "Project.php - getSurveyList. 전체답변 돌기. qstNo : $qstNo, choiceNo : $choiceNo, REQR_SEQ : ".$e['REQR_SEQ'].", ASW_1 : ".$e['ASW_1'].", ASW_2".$e['ASW_2'].", ASW_3".$e['ASW_3'].", ASW_4".$e['ASW_4'].", ASW_5".$e['ASW_5']);

					// 설문응답의 몇번 답변이 해당 객관식 번호를 포함하고 있으면 return
					return substr_count($e['ASW_'.$qstNo], $choiceNo) > 0;
			    }
			);
			// log_message('info', "Project.php - getSurveyList. qstNo : $qstNo, choiceNo : $choiceNo, 찾은건수 : ".count($choicedList));

			// 총 응답자수, 선택자수 설정 (front에서 비율계산 가능)
			$surveyQstChoiceList[$idx]['CNT_ALL_ASW'] = count($surveyAswList);
			$surveyQstChoiceList[$idx]['CNT_SELECTED'] = count($choicedList);
		}

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['surveyQstList'] = $surveyQstList;
		$data['surveyQstChoiceList'] = $surveyQstChoiceList;
		$data['surveyAswList'] = $surveyAswList;

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
