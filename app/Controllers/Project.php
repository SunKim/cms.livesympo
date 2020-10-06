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
  	}

	public function index() {
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

	// 스트리밍 방송자용 질문목록 화면
	public function moderator ($prjSeq = 0) {
		$data['project'] = $this->projectModel->detail($prjSeq);

		return view('project/moderator', $data);
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
		// 전체건수
		$data['totCnt'] = $totCntItem['CNT_ALL'];
		// 건수들 아이템(진행완료, 진행중, 향후진행)
		$data['item'] = $totCntItem;

		// 받은 parameter는 그대로 response
		$data['param'] = $param;

		return $this->response->setJSON($data);
	}

	//ajax - 프로젝트 상세
	public function getDetail() {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$lvl = $this->request->getPost('lvl');

		// 프로젝트 아이템
		$prjItem = $this->projectModel->detail($prjSeq);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['item'] = $prjItem;

		return $this->response->setJSON($data);
	}

	//ajax - 프로젝트 질문 리스트
	public function getQuestionList() {
		// param 받기
		$prjSeq = $this->request->getPost('prjSeq');
		$aprvYn = $this->request->getPost('aprvYn');
		$orderBy = $this->request->getPost('orderBy');
		// log_message('info', "Project.php - getQuestionList. prjSeq: $prjSeq, aprvYn: $aprvYn");

		// 프로젝트 아이템
		$questionList = $this->questionModel->list($prjSeq, $aprvYn, $orderBy);

		$data['resCode'] = '0000';
		$data['resMsg'] = '정상적으로 처리되었습니다.';
		$data['list'] = $questionList;

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
		$data['AGENDA_PAGE_YN'] = $this->request->getPost('AGENDA_PAGE_YN') !== null ? $this->request->getPost('AGENDA_PAGE_YN') : 0;

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

		$data['ENT_THME_COLOR'] = $this->request->getPost('ENT_THME_COLOR');
		$data['APPL_BTN_COLOR'] = $this->request->getPost('APPL_BTN_COLOR');
		// print_r($data);

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
						// log_message('info', "Project.php - save(). prjSeq: $prjSeq, path: $path");

						// directory가 없으면 생성
						if ( !is_dir($path) ) {
							// mkdir(path, mode, recursive). recursive는 꼭 true로!!
							mkdir($path, 0755, true);
							// log_message('info', "Project.php - save(). 파일저장용 directory 생성 - $path");
						}

						// 새로운 파일명에 extension 붙여줌
						// $key : form의 input의 name. MAIN_IMG, AGENDA_IMG, FOOTER_IMG => MAIN_IMG_1.png 형태로
						$newFileName = $key.'_'.$prjSeq.'.'.$ext;
						$thumbNm = $key.'_'.$prjSeq.'_THUMB.'.$ext;

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
