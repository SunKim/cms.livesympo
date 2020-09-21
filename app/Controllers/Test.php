<?php
 /**
  * Test.php
  *
  * Linkedmarket test용 controller
	* cf) Query Builder : http://ci4doc.cikorea.net/database/query_builder.html
  * cf) Model/Entity : http://ci4doc.cikorea.net/models/model.html
  *
  * @package    App
  * @subpackage Controllers
  * @author     20200527. SUN.
  * @copyright  Linkedmarket
  * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
  * @link
  * @see        \App\Config\Filters : filter 등록
  * @since      2020.05.27
  * @deprecated
  */

namespace App\Controllers;

use App\Models\TestModel;
use App\Libraries\Custom;
use App\Linkedmarket\Rescode;
use App\Linkedmarket\AppSetting;
use App\Linkedmarket\Category;

class Test extends BaseController {

	public function __construct() {
    	$this->testModel = new TestModel();
	}

	public function index () {
		// echo "<p>Not enough URL. Please specify a method.</p>";
		// return view('welcome_message');
		customView();
	}

	// default helper(Array Helper) 테스트
	public function arrayHelper () {
		$data = [
	    'foo' => [
			  'buzz' => ['fizz' => 11],
			  'bar' => ['baz' => 23],
			],
		];

		$fizz = dot_array_search('foo.buzz.fizz', $data);

		return $this->response->setJSON($fizz);
	}

	// Custom Constants 테스트 (링크드마켓 전용 app/Linkedmarket/Rescode.php)
	public function globalValiables () {
		$data['resCode'] = Rescode::OK[0];
		$data['resMsg'] = Rescode::OK[1];
		$data['item'] = 'global variables from Rescode';
		$data['APPPATH'] = APPPATH;
		// $data['homedir'] = $this->homedir;

		return $this->response->setJSON($data);
	}

	// Custom Library 테스트
	// app/Libraries/Custom.php 생성
	// BaseController에 $custom 설정
	public function customLibrary () {
		$data['customConstant'] = $this->custom::CUSTOM_LIBRARY_TEXT;
		$data['customMethod'] = $this->custom->customLibraryTest();
		$data['customVariable'] = $this->custom->customLibraryText;

		$data['resCode'] = Rescode::OK[0];
		$data['resMsg'] = Rescode::OK[1];

		return $this->response->setJSON($data);
	}

	// Custom View 테스트
	// 기본 view가 아닌 app/Frontend/dist/spa/index.html를 load
	public function customView () {
		// 방법 1) Service::renderer() 사용 - 어차피 renderer도 방법 2의 view를 생성해서 return함
		// $view = \Config\Services::renderer();
		// $options['debug'] = false;
		// return $view->render('welcome_message', $options, '1');

		// 방법 2) View를 수동 생성
		$config = new \Config\View();
		// $viewPath = "/Users/seonjungkim/workspace_linkedmarket/admin/app/Config/../Views";
		$viewPath = "/Users/seonjungkim/workspace_linkedmarket/admin/public/frontend/dist/spa/";
		$view = new \CodeIgniter\View\View($config, $viewPath);

		$options['debug'] = false;
		return $view->render('index.html', $options);
		// return $view->render('pages/test/home', $options, '1');
	}

	// ajax request로 전달된 parameter 테스트
	public function ajaxRequestParam () {
		// param 받기
		$param = $this->request->getJSON();

		$data['params'] = $param;

		return $this->response->setJSON($data);
	}

	// DB insert 테스트 (plain query)
	public function dbInsertQuery () {
		// param 받기
		$param = $this->request->getJSON();
		$colKey = isset($param->colKey) ? $param->colKey : '';
		$colValue = isset($param->colValue) ? $param->colValue : '';

		// param 검증
		if (!$this->validator->isPassed($colKey)) {
			return $this->resEmptyParam('키');
		}
		if (!$this->validator->isPassed($colValue)) {
			return $this->resEmptyParam('값');
		}
		// response에 request parameter도 넘겨줌
		$data['params'] = $param;

		$result = $this->testModel->dbInsertQuery($colKey, $colValue);

		return $this->resCrud('insert', $result, $param);
	}

	// DB insert 테스트 (query builder)
	public function dbInsertBuilder () {
		// param 받기
		$param = $this->request->getJSON();
		$colKey = isset($param->colKey) ? $param->colKey : '';
		$colValue = isset($param->colValue) ? $param->colValue : '';

		// param 검증
    	if (!$this->validator->isPassed($colKey)) {
			return $this->resEmptyParam('키');
		}
		if (!$this->validator->isPassed($colValue)) {
			return $this->resEmptyParam('값');
		}
		// response에 request parameter도 넘겨줌
		$data['params'] = $param;

		$result = $this->testModel->dbInsertBuilder($colKey, $colValue);

		return $this->resCrud('insert', $result, $param);
	}

	// DB select(single row) 테스트 - sql 작성
	public function dbSelectItemQuery () {
		// param 받기
		$param = $this->request->getJSON();
		$seq = isset($param->seq) ? $param->seq : 0;

		// param 검증
		if (!$this->validator->isPassed($seq)) {
			return $this->resEmptyParam('키');
		}

    	$item = $this->testModel->dbSelectItemQuery($seq);

		return $this->resItem($item, $param);
	}

	// DB select(single row) 테스트 - builder 사용
	public function dbSelectItemBuilder () {
		// param 받기
		$param = $this->request->getJSON();
		$seq = isset($param->seq) ? $param->seq : 0;

		// param 검증
    	if (!$this->validator->isPassed($seq)) {
			return $this->resEmptyParam('키');
		}

    	$item = $this->testModel->dbSelectItemBuilder($seq);

		return $this->resItem($item, $param);
	}

	// DB select(list) 테스트 - sql 작성
	public function dbSelectListQuery () {
		// param 받기
		$param = $this->request->getJSON();

    	$list = $this->testModel->dbSelectListQuery();

		return $this->resList($list, $param);
	}

	// DB select(list) 테스트 - builder 사용
	public function dbSelectListBuilder () {
		// param 받기
		$param = $this->request->getJSON();

    	$list = $this->testModel->dbSelectListBuilder();

		return $this->resList($list, $param);
	}

	// DB update 테스트 - sql 작성
	public function dbUpdateQuery () {
		// param 받기
		$param = $this->request->getJSON();
		$colKey = isset($param->colKey) ? $param->colKey : '';
		$colValue = isset($param->colValue) ? $param->colValue : '';

		// param 검증
		if (!$this->validator->isPassed($colKey)) {
			return $this->resEmptyParam('키');
		}
		if (!$this->validator->isPassed($colValue)) {
			return $this->resEmptyParam('값');
		}

		$result = $this->testModel->dbUpdateQuery($colKey, $colValue);

		return $this->resCrud('update', $result, $param);
	}

	// DB update 테스트 - builder 사용
	public function dbUpdateBuilder () {
		// param 받기
		$param = $this->request->getJSON();
		$colKey = isset($param->colKey) ? $param->colKey : '';
		$colValue = isset($param->colValue) ? $param->colValue : '';

		// param 검증
		if (!$this->validator->isPassed($colKey)) {
			return $this->resEmptyParam('키');
		}
		if (!$this->validator->isPassed($colValue)) {
			return $this->resEmptyParam('값');
		}

		$result = $this->testModel->dbUpdateBuilder($colKey, $colValue);

		return $this->resCrud('update', $result, $param);
	}

	// DB delete 테스트 - sql 작성
	public function dbDeleteQuery () {
		// param 받기
		$param = $this->request->getJSON();
		$seq = isset($param->seq) ? $param->seq : 0;

		// param 검증
    	if (!$this->validator->isPassed($seq)) {
			return $this->resEmptyParam('키');
		}

		$result = $this->testModel->dbDeleteQuery($seq);

		return $this->resCrud('delete', $result, $param);
	}

	// DB delete 테스트 - builder 사용
	public function dbDeleteBuilder () {
		// param 받기
		$param = $this->request->getJSON();
		$seq = isset($param->seq) ? $param->seq : 0;

		// param 검증
		if (!$this->validator->isPassed($seq)) {
			return $this->resEmptyParam('키');
		}

		$result = $this->testModel->dbDeleteBuilder($seq);

		return $this->resCrud('delete', $result, $param);
	}

	// header 정보 가져오기
	public function getRequestHeader () {
		// header에 Authorization가 없을경우
		if (!$this->request->hasHeader('Authorization')) {
			return false;
		}

		// header에서 Authorization 항목의 값
		$token = $this->request->getHeader('Authorization')->getValue();

		return $this->resItem((array)$token, []);
	}

	// header의 Authorization에서 token 추출후 adminSeq 가져오기
	public function getAdminSeqFromToken() {
		$tokenData = $this->getTokenData();

		if (!$tokenData) {
			return $this->resTokenExpired();
		}

		$adminSeq = $tokenData->adminSeq;
		return $this->resItem((array)$tokenData, $adminSeq);
	}

	//
	public function validationFilter() {
		return $this->resItem([]);
	}

	// bcrypt 암호화
	public function bcrypt () {
		$password = '11111111';
		echo password_hash($password, PASSWORD_DEFAULT)."\n";

		echo "=== encrypt\n";
		// cost : 4~31
		$options = ['cost' => 11];
		echo password_hash($password, PASSWORD_BCRYPT, $options)."\n";

		$options = ['cost' => 12];
		echo password_hash($password, PASSWORD_BCRYPT, $options)."\n";

		$options = ['cost' => 13];
		echo password_hash($password, PASSWORD_BCRYPT, $options)."\n";

		$options = ['cost' => 14];
		echo password_hash($password, PASSWORD_BCRYPT, $options)."\n";

		echo "\n\n=== decrypt";

		// bcrypt encoded password를 PHP에서 verify
		if (password_verify($password, '$2y$11$wAmUSHBWgjtChGEB3zDmGewd96i4uMWVuFLi9g3mMj4vyGVyml.ZS')) {
			echo "\n password verified";
		} else {
			echo "\n password verify failed";
		}

		if (password_verify($password, '$2y$12$ckWabkuinmZoceg7YRx53OAvmLXUFMqBvz8j88vfYLIZuefGxZrYq')) {
			echo "\n password verified";
		} else {
			echo "\n password verify failed";
		}

		if (password_verify($password, '$2y$13$xvzyhCF5qbBig7FZ/okgte1RtEef4qp9P30e4eMD6IxLaRM05j11q')) {
			echo "\n password verified";
		} else {
			echo "\n password verify failed";
		}

		if (password_verify($password, '$2y$14$sxoeQoSvS/VSrSmpyV2Jb.RUhEw8Xu09iBTq19RVqfJ8OsDSlu0OW')) {
			echo "\n password verified";
		} else {
			echo "\n password verify failed";
		}
	}

	// 상품고시유형 AppSetting 설정후 해당 고시유형 불러오기
	public function prdNtfc () {
		// param 받기
		$param = $this->request->getJSON();
		$ntcTpNo = isset($param->ntcTpNo) ? $param->ntcTpNo : '';

		$prdNtfcList = AppSetting::PRODUCT_NOTIFICATION_LIST;
		// print_r($prdNtfcList);

		$key = array_search($ntcTpNo, array_column($prdNtfcList, 'NTFC_TP_NO'));
		$prdNtfcItem = $prdNtfcList[$key];

		return $this->resItem($prdNtfcItem, []);
	}

	public function testReplace () {
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"> <html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><p>한글을 넣어보자</p> <p>마구마구 넣어보자</p> </body></html> ';
		// $html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"> <html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>', '', $html);
		// $html = str_replace('</body></html>', '', $html);
		echo "html : \n".$html."\n";
		$html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $html);
		$html = str_replace('<html>', '', $html);
		$html = str_replace('<head>', '', $html);
		$html = str_replace('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">', '', $html);
		$html = str_replace('</head>', '', $html);
		$html = str_replace('<body>', '', $html);
		$html = str_replace('</body>', '', $html);
		$html = str_replace('</html>', '', $html);
		echo "replaced : \n".$html."\n";
	}

	// 카테고리 insert 각각 상위 catg_seq select 후 상위 카테고리 시퀀스가 없으면 상위 카테고리부터 insert 후 진행
	public function insertCatg () {
		// print_r(Category::CATG_ARRAY);

		for ($i = 0; $i < sizeOf(Category::CATG_ARRAY); $i++ ) {
			$one_depth = $this->testModel->selectOneDepthCatg(Category::CATG_ARRAY[$i][0]);

			if (!$one_depth) {
				// $this->testModel->insertOneDepthCatg(Category::CATG_ARRAY[$i][0]);
				$one_depth = $this->testModel->selectOneDepthCatg(Category::CATG_ARRAY[$i][0]);
			}

			$two_depth = $this->testModel->selectTwoDepthCatg($one_depth['CATG_SEQ'], Category::CATG_ARRAY[$i][1]);

			if (!$two_depth) {
				// $this->testModel->insertTwoDepthCatg($one_depth['CATG_SEQ'], Category::CATG_ARRAY[$i][1]);
				$two_depth = $this->testModel->selectTwoDepthCatg($one_depth['CATG_SEQ'], Category::CATG_ARRAY[$i][1]);
			}

			// $this->testModel->insertThreeDepthCatg($two_depth['CATG_SEQ'], Category::CATG_ARRAY[$i][2]);
		}
	}
}
