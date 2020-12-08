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

class Dongwha extends BaseController {

	public function __construct() {
  	}

	public function index () {
		$data['menu'] = 'project';
		// get('session name') 에서 session name을 안주면 전체 session정보.
		$data['session'] = $this->session->get();

		return view('etc/dongwha_202012.php', $data);
	}

	// 강의자료 저장
	public function save () {
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
						$path = $uploadPath.DIRECTORY_SEPARATOR.'etc'.DIRECTORY_SEPARATOR.'dongwha_202012';

						// directory가 없으면 생성
						if ( !is_dir($path) ) {
							// mkdir(path, mode, recursive). recursive는 꼭 true로!!
							mkdir($path, 0755, true);
							log_message('info', "Dongwha.php - save() 파일처리. 파일저장용 directory 생성 - $path");
						}

						// 새로운 파일명에 extension 붙여줌
						// $key : form의 input의 name. MAIN_IMG, AGENDA_IMG, FOOTER_IMG => MAIN_IMG_1.png 형태로
						$newFileName = $key.'.'.$ext;

						// 이미 파일이 있으면 삭제
						if (file_exists($path.DIRECTORY_SEPARATOR.$newFileName)) {
							log_message('info', "Dongwha.php - save() 파일처리. 기존파일 존재해서 삭제 필요. ".$path.DIRECTORY_SEPARATOR.$newFileName);
							unlink($path.DIRECTORY_SEPARATOR.$newFileName);
						}

						// 파일 이동
						$file->move($path, $newFileName);
						log_message('info', "Dongwha.php - save(). key : $key, file : $file, path: $path, newFileName: $newFileName");
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
