<?php
/**
 * Dongwha.php
 *
 * 동화약품 1회성 교육자료 등록 Controller
 *
 * @package    App
 * @subpackage Controller
 * @author     20201208. SUN.
 * @copyright  Livesympo
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link
 * @see
 * @since      2020.12.08
 * @deprecated
 */
namespace App\Controllers;

use App\Models\SettingModel;

class Dongwha extends BaseController {

	public function __construct() {
		$this->settingModel = new SettingModel();
  	}

	public function index () {
		return $this->dh202105();
	}

	public function dh202105 () {
		$data['menu'] = 'dongwha_202105';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['DONGWHA_202105_LEC1_READY_YN'] = $this->settingModel->value('DONGWHA_202105_LEC1_READY_YN');
		$data['DONGWHA_202105_LEC2_READY_YN'] = $this->settingModel->value('DONGWHA_202105_LEC2_READY_YN');
		$data['DONGWHA_202105_LEC3_READY_YN'] = $this->settingModel->value('DONGWHA_202105_LEC3_READY_YN');
		$data['DONGWHA_202105_LEC4_READY_YN'] = $this->settingModel->value('DONGWHA_202105_LEC4_READY_YN');

		$data['DONGWHA_202105_LEC1_FILE_NM'] = $this->settingModel->value('DONGWHA_202105_LEC1_FILE_NM');
		$data['DONGWHA_202105_LEC2_FILE_NM'] = $this->settingModel->value('DONGWHA_202105_LEC2_FILE_NM');
		$data['DONGWHA_202105_LEC3_FILE_NM'] = $this->settingModel->value('DONGWHA_202105_LEC3_FILE_NM');
		$data['DONGWHA_202105_LEC4_FILE_NM'] = $this->settingModel->value('DONGWHA_202105_LEC4_FILE_NM');
		log_message('info', "Dongwha.php - dh202105. data: ".print_r($data, true));

		return view('etc/dongwha_202105.php', $data);
	}

	public function dh202012 () {
		$data['menu'] = 'dongwha_202012';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		$data['DONGWHA_202012_LEC1_READY_YN'] = $this->settingModel->value('DONGWHA_202012_LEC1_READY_YN');
		$data['DONGWHA_202012_LEC2_READY_YN'] = $this->settingModel->value('DONGWHA_202012_LEC2_READY_YN');
		$data['DONGWHA_202012_LEC3_READY_YN'] = $this->settingModel->value('DONGWHA_202012_LEC3_READY_YN');
		$data['DONGWHA_202012_LEC4_READY_YN'] = $this->settingModel->value('DONGWHA_202012_LEC4_READY_YN');

		$data['DONGWHA_202012_LEC1_FILE_NM'] = $this->settingModel->value('DONGWHA_202012_LEC1_FILE_NM');
		$data['DONGWHA_202012_LEC2_FILE_NM'] = $this->settingModel->value('DONGWHA_202012_LEC2_FILE_NM');
		$data['DONGWHA_202012_LEC3_FILE_NM'] = $this->settingModel->value('DONGWHA_202012_LEC3_FILE_NM');
		$data['DONGWHA_202012_LEC4_FILE_NM'] = $this->settingModel->value('DONGWHA_202012_LEC4_FILE_NM');

		return view('etc/dongwha_202012.php', $data);
	}

	// 강의자료 저장
	public function save () {
		$yyyymm = $this->request->getPost('yyyymm');
		// $test1 = $this->request->getPost("DONGWHA_202105_LEC1_READY_YN");
		// $test2 = $this->request->getPost("DONGWHA_".$yyyymm."_LEC1_READY_YN");
		// log_message('info', "Dongwha.php -save. yyyymm: $yyyymm, test1: $test1, test2: $test2");

		$data['SET_VAL'] = $this->request->getPost("DONGWHA_".$yyyymm."_LEC1_READY_YN");
		$this->settingModel->updateValue("DONGWHA_".$yyyymm."_LEC1_READY_YN", $data);

		$data['SET_VAL'] = $this->request->getPost("DONGWHA_".$yyyymm."_LEC2_READY_YN");
		$this->settingModel->updateValue("DONGWHA_".$yyyymm."_LEC2_READY_YN", $data);

		$data['SET_VAL'] = $this->request->getPost("DONGWHA_".$yyyymm."_LEC3_READY_YN");
		$this->settingModel->updateValue("DONGWHA_".$yyyymm."_LEC3_READY_YN", $data);

		$data['SET_VAL'] = $this->request->getPost("DONGWHA_".$yyyymm."_LEC4_READY_YN");
		$this->settingModel->updateValue("DONGWHA_".$yyyymm."_LEC4_READY_YN", $data);

		// 기본 업로드 path. ex) /Users/seonjungkim/workspace_php/cms.livesympo/public/uploads/project
		$uploadPath = $_ENV['UPLOAD_BASE_PATH'];

		// 파일들 받기
		// http://ci4doc.cikorea.net/libraries/uploaded_files.html
		$files = $this->request->getFiles();

		try {
			if ($files) {
				foreach ($files as $key => $file) {
					// echo "key : $key, file : $file\n";
					// $key : form/input에서의 name
					// $file : 서버에 임시저장된 파일명. ex) /private/var/tmp/phpvnBwzw
					log_message('info', "Dongwha.php - save(). key : $key, file : $file");

					if (isset($file) && $file->isValid() && !$file->hasMoved()) {
						$src = $file->getRealPath();
						$ext = $file->getExtension();

						// echo "src : $src, ext : $ext, uploadPath : $uploadPath\n";
						$path = $uploadPath.DIRECTORY_SEPARATOR.'etc'.DIRECTORY_SEPARATOR.'dongwha_'.$yyyymm;

						// directory가 없으면 생성
						if ( !is_dir($path) ) {
							// mkdir(path, mode, recursive). recursive는 꼭 true로!!
							mkdir($path, 0755, true);
							log_message('info', "Dongwha.php - save() 파일처리. 파일저장용 directory 생성 - $path");
						}

						// 새로운 파일명에 extension 붙여줌
						// $key : form의 input의 name. MAIN_IMG, AGENDA_IMG, FOOTER_IMG => MAIN_IMG_1.png 형태로
						// $newFileName = $key.'.'.$ext;

						// 원래 파일명 사용
						$newFileName = $file->getClientName();

						// 이미 파일이 있으면 삭제
						if (file_exists($path.DIRECTORY_SEPARATOR.$newFileName)) {
							log_message('info', "Dongwha.php - save() 파일처리. 기존파일 존재해서 삭제 필요. ".$path.DIRECTORY_SEPARATOR.$newFileName);
							unlink($path.DIRECTORY_SEPARATOR.$newFileName);
						}

						// 파일 이동
						$file->move($path, $newFileName);
						log_message('info', "Dongwha.php - save(). key : $key, file : $file, path: $path, newFileName: $newFileName");

						if ($key == 'lec1') {
							$data['SET_VAL'] = $newFileName;
							$this->settingModel->updateValue('DONGWHA_'.$yyyymm.'_LEC1_FILE_NM', $data);
						}
						if ($key == 'lec2') {
							$data['SET_VAL'] = $newFileName;
							$this->settingModel->updateValue('DONGWHA_'.$yyyymm.'_LEC2_FILE_NM', $data);
						}
						if ($key == 'lec3') {
							$data['SET_VAL'] = $newFileName;
							$this->settingModel->updateValue('DONGWHA_'.$yyyymm.'_LEC3_FILE_NM', $data);
						}
						if ($key == 'lec4') {
							$data['SET_VAL'] = $newFileName;
							$this->settingModel->updateValue('DONGWHA_'.$yyyymm.'_LEC4_FILE_NM', $data);
						}
					} else {
						// throw new RuntimeException($file->getErrorString().'('.$file->getError().')');
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', "exception - ".$e->getMessage());
		}

		$res['resCode'] = '0000';
		$res['resMsg'] = '정상적으로 처리되었습니다.';

		return $this->response->setJSON($res);
	}
}
