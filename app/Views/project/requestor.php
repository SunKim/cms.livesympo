<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="last-modified" content="mon,14 sep 2020 19:38:00">
<!-- <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> -->

<!-- Web Application. Independent Browser -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">

<meta name="subject" content="Live Sympo">
<!-- TODO title DB에서 -->
<meta name="author" content="Sun Kim">
<meta name="other agent" content="Sun Kim">
<meta name="reply-to(email)" content="sjmarine97@gmail.com">
<meta name="location" content="Seoul, Korea">
<meta name="distribution" content="Sun Kim">
<meta name="robots" content="noindex,nofollow">
<!-- meta name="robots" content="all" -->

<title>Live Sympo 사전등록자 관리</title>

<!-- stylesheets -->
<link href="/css/sun.common.20200914.css" rel="stylesheet">
<link href="/css/cms.livesympo.css" rel="stylesheet">

<!-- loading spinner를 위한 font-awesome. <span class="fa fa-spinner fa-spin fa-3x". ></span>. 아이콘 참고 - https://fontawesome.com/v4.7.0/icons/ -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Bootstrap-select. cf) https://silviomoreto.github.io/bootstrap-select -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<!-- Custom styles for this template-->
<link href="/css/sb-admin-2.css" rel="stylesheet">

<!-- Custom fonts for this template-->
<link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<!-- START) 메인 css -->
<style type="text/css">
table.tbl-reqr-list th, table.tbl-reqr-list td { padding: 4px !important; min-width: 50px !important; }

.scroll-to-bottom, .scroll-to-middle {
    position: fixed;
    right: 7rem;
    bottom: 1rem;
    display: none;
    width: 2.75rem;
    height: 2.75rem;
    text-align: center;
    color: #fff;
    background: rgba(90, 92, 105, 0.5);
    line-height: 46px;
}
.scroll-to-middle { right: 4rem; }

.scroll-to-middle:focus, .scroll-to-middle:hover, .scroll-to-bottom:focus, .scroll-to-bottom:hover { color: white; }
.scroll-to-middle:hover, .scroll-to-bottom:hover { background: #5a5c69; }
.scroll-to-middle i, .scroll-to-bottom i { font-weight: 800; }

div.search { margin-bottom: 10px; padding: 8px 20px; font-size: 14px; border: 1px solid #bbb; border-radius: 5px; }

table.tbl-reqr-popup th, td { padding: 4px; }
table.tbl-reqr-popup input { width: 90%; }
table.tbl-reqr-popup input:read-only { background: #ddd; }
</style>
<!-- END) 메인 css -->

</head>


<body id="page-top">
<!-- 세션체크 -->
<?php include_once APPPATH.'Views/template/check_session.php'; ?>

<?php
	$email = isset($session['email']) ? $session['email'] : '';
	$admSeq = isset($session['admSeq']) ? $session['admSeq'] : 0;
	$lvl = isset($session['lvl']) ? $session['lvl'] : 0;
?>

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- 좌측메뉴바 -->
		<?php include_once APPPATH.'Views/template/navigation.php'; ?>

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- top bar -->
				<?php include_once APPPATH.'Views/template/top.php'; ?>

				<!-- Begin Page Content -->
				<div class="container-fluid">

					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Project</h1>

						<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
					</div>
<?php
// 비사전등록이 아닐 경우만 보임
if ($project['ANONYM_USE_YN'] == 0) {
?>
					<!-- 사전등록자 목록 영역 -->
					<div id="reqr-container" class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 사전등록자 목록 - <?= $project['PRJ_TITLE'] ?> <?= $project['ANONYM_USE_YN'] == 1 ? '(비사전등록)' : '' ?></h6>
						</div>
						<div class="excel-container" style="padding: 20px 10px;">
                            <div class="search reqr align-items-center">
                                <label for="searchReqrNm">신청자명</label>
                                <input type="text" id="searchReqrNm" name="searchReqrNm" class="common-input w10 ml10" maxlength="10">
                                <label for="searchReqrMbilno" class="ml20">연락처</label>
                                <input type="text" id="searchReqrMbilno" name="searchReqrMbilno" class="common-input w20 ml10" maxlength="12" placeholder="숫자만 입력">
                                <button class="btn-sub btn-sky ml20" onclick="getRequestorList();">검색</button>
                                <button class="btn-sub btn-white ml4" onclick="clearSearch('Reqr');">초기화</button>
                            </div>
							<table class="table-list tbl-reqr-list" id="tbl-reqr-list">
								<thead>
									<tr>
										<th>Seq.</th>
										<th>성명</th>
										<th>연락처</th>
										<th class="extra-1"><?= $project['ENT_INFO_EXTRA_1'] ?></th>
										<th class="extra-2"><?= $project['ENT_INFO_EXTRA_2'] ?></th>
										<th class="extra-3"><?= $project['ENT_INFO_EXTRA_3'] ?></th>
										<th class="extra-4"><?= $project['ENT_INFO_EXTRA_4'] ?></th>
										<th class="extra-5"><?= $project['ENT_INFO_EXTRA_5'] ?></th>
										<th class="extra-6"><?= $project['ENT_INFO_EXTRA_6'] ?></th>
										<th class="extra-7"><?= $project['ENT_INFO_EXTRA_7'] ?></th>
										<th class="extra-8"><?= $project['ENT_INFO_EXTRA_8'] ?></th>
										<th class="conn-route"><?= $project['CONN_ROUTE_TEXT'] ?></th>
										<th>사전등록일시</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<!-- 사전등록자 목록 영역 -->

					<div class="d-flex align-items-center justify-content-between pa20">
						<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
<?php
    // 레벨9만 보이도록
    if ($lvl == 9 || $lvl == 1) {
    	echo '<div>';
    	echo '	<button class="btn-main btn-red" onclick="deleteAllRequestor();">전체삭제</button>';
    	echo '	<button class="btn-main btn-light-indigo ml10" onclick="openUploadExcel();">엑셀업로드</button>';
    	echo '	<button class="btn-main btn-light-indigo ml10" onclick="downloadExcel(\'tbl-reqr-list\');">엑셀저장</button>';
    	echo '</div>';
    } else if ($lvl == 2) {
    	echo '<div>';
    	echo '	<button class="btn-main btn-light-indigo ml10" onclick="downloadExcel(\'tbl-reqr-list\');">엑셀저장</button>';
    	echo '</div>';
    }
?>
					</div>
<?php
}
?>


					<!-- 참석자 목록 영역 -->
					<div id="att-container" class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 참석자 목록 - <?= $project['PRJ_TITLE'] ?> <?= $project['ANONYM_USE_YN'] == 1 ? '(비사전등록)' : '' ?></h6>
						</div>
<?php
// 비사전등록이 아닐 경우 신청정보
if ($project['ANONYM_USE_YN'] == 0) {
?>
						<div class="excel-container" style="padding: 20px 10px;">
                            <div class="search reqr align-items-center">
                                <label for="searchAttNm">참석자명</label>
                                <input type="text" id="searchAttNm" name="searchAttNm" class="common-input w10 ml10" maxlength="10">
                                <label for="searchAttMbilno" class="ml20">연락처</label>
                                <input type="text" id="searchAttMbilno" name="searchAttMbilno" class="common-input w20 ml10" maxlength="12" placeholder="숫자만 입력">
                                <button class="btn-sub btn-sky ml20" onclick="getAttendanceList();">검색</button>
                                <button class="btn-sub btn-white ml4" onclick="clearSearch('Att');">초기화</button>
                            </div>
							<table class="table-list tbl-att-list" id="tbl-att-list">
								<thead>
									<tr>
										<th>Seq.</th>
										<th>성명</th>
										<th>연락처</th>
										<th>IN</th>
										<th>OUT</th>
										<th>디바이스</th>
										<th class="extra-1"><?= $project['ENT_INFO_EXTRA_1'] ?></th>
										<th class="extra-2"><?= $project['ENT_INFO_EXTRA_2'] ?></th>
										<th class="extra-3"><?= $project['ENT_INFO_EXTRA_3'] ?></th>
										<th class="extra-4"><?= $project['ENT_INFO_EXTRA_4'] ?></th>
										<th class="extra-5"><?= $project['ENT_INFO_EXTRA_5'] ?></th>
										<th class="extra-6"><?= $project['ENT_INFO_EXTRA_6'] ?></th>
										<th class="extra-7"><?= $project['ENT_INFO_EXTRA_7'] ?></th>
										<th class="extra-8"><?= $project['ENT_INFO_EXTRA_8'] ?></th>
										<th class="conn-route"><?= $project['CONN_ROUTE_TEXT'] ?></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
<?php
} else {
?>
                        <div class="excel-container" style="padding: 20px 10px;">
                            <table class="table-list tbl-att-list" id="tbl-att-list">
                                <thead>
                                    <tr>
                                        <th>Seq.</th>
                                        <th>IN</th>
                                        <th>OUT</th>
                                        <th>디바이스</th>
                                        <th>IP주소</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
<?php
}
?>
					</div>
					<!-- 참석자 목록 영역 -->

					<div class="d-flex align-items-center justify-content-between pa20">
						<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
<?php
// 레벨9만 보이도록
if ($lvl == 9 || $lvl == 1) {
	echo '<div>';
	echo '	<button class="btn-main btn-light-indigo ml10" onclick="downloadExcel(\'tbl-att-list\');">엑셀저장</button>';
	echo '</div>';
}
?>
					</div>
				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

			<!-- 하단 footer -->
			<?php include_once APPPATH.'Views/template/footer.php'; ?>

		</div>
		<!-- End of Content Wrapper -->

	</div>
	<!-- End of Page Wrapper -->

	<!-- Scroll to Top Button-->
	<div>
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		<a class="scroll-to-middle rounded" href="#att-container">
			<i class="fas fa-align-justify"></i>
		</a>
		<a class="scroll-to-bottom rounded" href="#page-bottom">
			<i class="fas fa-angle-down"></i>
		</a>
	</div>

	<!-- 엑셀업로드 Modal -->
	<div id="uploadExcelModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title tl" id="uploadExcelModalTitle">사전등록자 엑셀(.csv) 파일 업로드</h4>
	                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<p>.csv 파일은 제목열은 빼고 프로젝트에서 설정한 입력항목 순서대로 아래 표와 같이 입력해주세요.</p>
					<p> - 칼럼1: 성명, 칼럼 2: 연락처, 칼럼 3: 추가입력1 ~ 칼럼 10: 추가입력8, 칼럼 11: 접속경로, 칼럼 12: 등록시간</p>
					<p> - 접속경로가 기존 입력값과 다를 경우 저장되지 않습니다. 예) 프로젝트 설정의 접속경로는 '영업담당자'인데 엑셀에는 '영업 담당자'라고 되어있는 경우</p>
					<p> - 기존 입력데이터 또는 엑셀업로드 데이터와 중복(이름, 연락처)되는 사전등록자가 있으면 최근 입력값으로 대체됩니다.</p>
                    <p> - 등록시간은 2020-01-01 14:50:30 형식으로 입력해야 합니다.</p>
					<table class="table-list mt10" id="tbl-reqr-list-sample">
	                    <tbody>
							<tr>
								<th>성명</th>
								<th>연락처</th>
								<th class="extra-1"><?= $project['ENT_INFO_EXTRA_1'] ?></th>
								<th class="extra-2"><?= $project['ENT_INFO_EXTRA_2'] ?></th>
								<th class="extra-3"><?= $project['ENT_INFO_EXTRA_3'] ?></th>
								<th class="extra-4"><?= $project['ENT_INFO_EXTRA_4'] ?></th>
								<th class="extra-5"><?= $project['ENT_INFO_EXTRA_5'] ?></th>
								<th class="extra-6"><?= $project['ENT_INFO_EXTRA_6'] ?></th>
								<th class="extra-7"><?= $project['ENT_INFO_EXTRA_7'] ?></th>
								<th class="extra-8"><?= $project['ENT_INFO_EXTRA_8'] ?></th>
								<th class="conn-route"><?= $project['CONN_ROUTE_TEXT'] ?></th>
                                <th class="reg-dttm">사전등록시간</th>
							</tr>
							<tr>
								<td>홍길동</td>
								<td>01011112222</td>
								<td class="extra-1">샘플1</td>
								<td class="extra-2">샘플1</td>
								<td class="extra-3">샘플1</td>
								<td class="extra-4">샘플1</td>
								<td class="extra-5">샘플1</td>
								<td class="extra-6">샘플1</td>
								<td class="extra-7">샘플1</td>
								<td class="extra-8">샘플1</td>
								<td>접속경로1</td>
                                <td>2020-01-01 14:50:30</td>
							</tr>
							<tr>
								<td>이순신</td>
								<td>01033334444</td>
								<td class="extra-1">샘플2</td>
								<td class="extra-2">샘플2</td>
								<td class="extra-3">샘플2</td>
								<td class="extra-4">샘플2</td>
								<td class="extra-5">샘플2</td>
								<td class="extra-6">샘플2</td>
								<td class="extra-7">샘플2</td>
								<td class="extra-8">샘플2</td>
								<td>접속경로2</td>
                                <td>2020-01-01 14:55:10</td>
							</tr>
	                    </tbody>
	                </table>

					<form method="POST">
						<h5 class="mt40">파일선택</h5>
						<input type="file" id="requestorFile" name="requestorFile" class="common-input w50" accept=".csv" />
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
					<button type="button" class="btn btn-primary" onclick="uploadExcel();">업로드</button>
				</div>
			</div>
		</div>
	</div>

    <div id="reqrModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reqrModalTitle" aria-hidden="true">
    	<div class="vertical-alignment-helper" style="width: 540px !important;">
    		<div class="modal-dialog vertical-align-center">
    			<div class="modal-content">
    				<div class="modal-header">
    					<h4 class="modal-title" id="reqrModalTitle">사전등록자 수정</h4>
    					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				</div>
    				<div class="modal-body">
    					<table class="tbl-reqr-popup">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">사전등록자명</th>
                                    <td><input id="REQR_NM" type="text" class="common-input" readonly></td>
                                </tr>
                                <tr>
                                    <th>연락처</th>
                                    <td><input id="MBILNO" type="text" class="common-input" readonly></td>
                                </tr>
    <?php
        if (isset($project['ENT_INFO_EXTRA_1']) && $project['ENT_INFO_EXTRA_1'] != '') {
            echo '              <tr>';
            echo '                  <th>'.$project['ENT_INFO_EXTRA_1'].'</th>';
            echo '                  <td><input id="ENT_INFO_EXTRA_VAL_1" type="text" class="common-input"></td>';
            echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_2']) && $project['ENT_INFO_EXTRA_2'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_2'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_2" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_3']) && $project['ENT_INFO_EXTRA_3'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_3'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_3" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_4']) && $project['ENT_INFO_EXTRA_4'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_4'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_4" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_5']) && $project['ENT_INFO_EXTRA_5'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_5'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_5" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_6']) && $project['ENT_INFO_EXTRA_6'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_6'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_6" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_7']) && $project['ENT_INFO_EXTRA_7'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_7'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_7" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
        if (isset($project['ENT_INFO_EXTRA_8']) && $project['ENT_INFO_EXTRA_8'] != '') {
        	echo '              <tr>';
        	echo '                  <th>'.$project['ENT_INFO_EXTRA_8'].'</th>';
        	echo '                  <td><input id="ENT_INFO_EXTRA_VAL_8" type="text" class="common-input"></td>';
        	echo '              </tr>';
        }
    ?>
                                <tr>
                                    <th><?= $project['CONN_ROUTE_TEXT'] ?></th>
                                    <td><input id="CONN_ROUTE_VAL" type="text" class="common-input"></td>
                                </tr>
                                <tr>
                                    <th>사전등록일시</th>
                                    <td><input id="REG_DTTM" type="text" class="common-input"></td>
                                </tr>
                            </tbody>
                        </table>
    				</div>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-danger" onclick="deleteRequestor()">삭제</button>
    					<button type="button" class="btn btn-primary" onclick="updateRequestor()">수정</button>
                        <button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>

<!-- 공통모달 -->
<?php include_once APPPATH.'Views/template/common_modal.php'; ?>

<!-- 토스트 -->
<?php include_once APPPATH.'Views/template/common_toast.php'; ?>


<!-- Bootstrap core JavaScript-->
<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="/js/sb-admin-2.min.js"></script>

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">
var selectedReqrSeq
var anonymUseYn = <?= $project['ANONYM_USE_YN'] ?>

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active')

    // 비사전등록입장이 아닐 경우만 사전등록자 불러오기
    if (anonymUseYn == 0) {
        getRequestorList()
    }
    getAttendanceList()
}

// 사전등록자 불러오기
function getRequestorList () {
	showSpinner();

    const data = {
        prjSeq: <?= $project['PRJ_SEQ'] ?>,
        search: {
            searchReqrNm: $('#searchReqrNm').val(),
            searchReqrMbilno: $('#searchReqrMbilno').val(),
        }
    }

	$.ajax({
		type: 'POST',
		url: '/project/getRequestorList/' + <?= $project['PRJ_SEQ'] ?>,
		dataType: 'json',
		cache: false,
		data,

		success: function(data) {
			// console.log(data)
			if ( data.resCode == '0000' ) {
                // console.log(data.list)

				// 사전등록자 목록
				const reqrList = data.list;

				$('table.tbl-reqr-list tbody').empty();

                let html = '';
				reqrList.forEach((item) => {
					html += '<tr>';
					html += '	<td>'+item.ROWNUM+'</td>';
					html += '	<td><a href="javascript:openReqrPopup(\''+encodeURI(JSON.stringify(item))+'\');">'+item.REQR_NM+'</a></td>';
					html += '	<td>'+formatMobile(simplifyMobile(item.MBILNO))+'</td>';
					html += '	<td class="extra-1">'+item.ENT_INFO_EXTRA_VAL_1+'</td>';
					html += '	<td class="extra-2">'+item.ENT_INFO_EXTRA_VAL_2+'</td>';
					html += '	<td class="extra-3">'+item.ENT_INFO_EXTRA_VAL_3+'</td>';
					html += '	<td class="extra-4">'+item.ENT_INFO_EXTRA_VAL_4+'</td>';
					html += '	<td class="extra-5">'+item.ENT_INFO_EXTRA_VAL_5+'</td>';
					html += '	<td class="extra-6">'+item.ENT_INFO_EXTRA_VAL_6+'</td>';
					html += '	<td class="extra-7">'+item.ENT_INFO_EXTRA_VAL_7+'</td>';
					html += '	<td class="extra-8">'+item.ENT_INFO_EXTRA_VAL_8+'</td>';
					html += '	<td class="conn-route">'+item.CONN_ROUTE_VAL_NM+'</td>';
					html += '	<td>'+item.REG_DTTM+'</td>';
					html += '</tr>';
				});
                $('table.tbl-reqr-list tbody').append(html);

				<?php
					// 접속경로 설정 없으면 숨김
					// if (!isset($project['CONN_ROUTE_1']) || $project['CONN_ROUTE_1'] == '') {
					// 	echo "$('table.tbl-reqr-list .conn-route').remove();";
					// }

					// 입력정보 없으면 테이블 column 삭제
					if (!isset($project['ENT_INFO_EXTRA_1']) || $project['ENT_INFO_EXTRA_1'] == '') {
						echo "$('table.tbl-reqr-list .extra-1').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_2']) || $project['ENT_INFO_EXTRA_2'] == '') {
						echo "$('table.tbl-reqr-list .extra-2').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_3']) || $project['ENT_INFO_EXTRA_3'] == '') {
						echo "$('table.tbl-reqr-list .extra-3').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_4']) || $project['ENT_INFO_EXTRA_4'] == '') {
						echo "$('table.tbl-reqr-list .extra-4').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_5']) || $project['ENT_INFO_EXTRA_5'] == '') {
						echo "$('table.tbl-reqr-list .extra-5').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_6']) || $project['ENT_INFO_EXTRA_6'] == '') {
						echo "$('table.tbl-reqr-list .extra-6').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_7']) || $project['ENT_INFO_EXTRA_7'] == '') {
						echo "$('table.tbl-reqr-list .extra-7').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_8']) || $project['ENT_INFO_EXTRA_8'] == '') {
						echo "$('table.tbl-reqr-list .extra-8').remove();";
					}
				?>

			} else {
				alert('사전등록자 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('사전등록자 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 참석자 불러오기
function getAttendanceList () {
	showSpinner();

    const data = {
        prjSeq: <?= $project['PRJ_SEQ'] ?>,
        search: {
            searchAttNm: $('#searchAttNm').val(),
            searchAttMbilno: $('#searchAttMbilno').val(),
        }
    }

	$.ajax({
		type: 'POST',
		url: '/project/getAttendanceList/' + <?= $project['PRJ_SEQ'] ?>,
		dataType: 'json',
		cache: false,
		data,

		success: function(data) {
			if ( data.resCode == '0000' ) {
				console.log(data.list)
				// 사전등록자, 참석자 목록
				const attList = data.list;

				$('table.tbl-att-list tbody').empty();

                let html = '';
				attList.forEach((item) => {

                    if (anonymUseYn == 0) {
                        html += '<tr>';
                        html += '	<td>'+item.ROWNUM+'</td>';
                        html += '	<td>'+item.REQR_NM+'</td>';
                        html += '	<td>'+formatMobile(simplifyMobile(item.MBILNO))+'</td>';
                        // html += '	<td>'+item.FIRST_ENTER_DTTM+'</td>';
                        // html += '	<td>'+item.LAST_LEAVE_DTTM+'</td>';

						// // IN
						// html += '	<td>';
						// item.ENTER_DTTM_ARR.split(',').forEach(arrItem => {
						// 	html += `<p>${arrItem}\n</p>`
						// })
						// html += '	</td>';
                        //
						// // OUT
						// html += '	<td>';
						// item.LEAVE_DTTM_ARR.split(',').forEach((arrItem, key, arr) => {
						// 	html += `<p>${arrItem}\n</p>`
                        //
						// 	// 마지막 아이템이고 ENTER보다 LEAVE 갯수가 적을때
						// 	if (Object.is(arr.length - 1, key)) {
						// 		if (item.ENTER_DTTM_ARR.split(',').length == item.LEAVE_DTTM_ARR.split(',').length+1) {
						// 			html += `<p>${item.ED_DTTM}</p>`
						// 		}
						// 	}
						// })
						// html += '	</td>';

                        html += '	<td>'+item.ENT_DTTM+'</td>';
                        html += '	<td>'+item.LEA_DTTM+'</td>';

                        html += '	<td>'+item.DVC_GB+'</td>';
                        html += '	<td class="extra-1">'+item.ENT_INFO_EXTRA_VAL_1+'</td>';
                        html += '	<td class="extra-2">'+item.ENT_INFO_EXTRA_VAL_2+'</td>';
                        html += '	<td class="extra-3">'+item.ENT_INFO_EXTRA_VAL_3+'</td>';
                        html += '	<td class="extra-4">'+item.ENT_INFO_EXTRA_VAL_4+'</td>';
                        html += '	<td class="extra-5">'+item.ENT_INFO_EXTRA_VAL_5+'</td>';
                        html += '	<td class="extra-6">'+item.ENT_INFO_EXTRA_VAL_6+'</td>';
                        html += '	<td class="extra-7">'+item.ENT_INFO_EXTRA_VAL_7+'</td>';
                        html += '	<td class="extra-8">'+item.ENT_INFO_EXTRA_VAL_8+'</td>';
                        html += '	<td class="conn-route">'+item.CONN_ROUTE_VAL_NM+'</td>';
                        html += '</tr>';
                    } else {
                        html += '<tr>';
                        html += '	<td>'+item.ROWNUM+'</td>';
                        html += '	<td>'+item.FIRST_ENTER_DTTM+'</td>';
                        html += '	<td>'+item.LAST_LEAVE_DTTM+'</td>';
                        html += '	<td>'+item.DVC_GB+'</td>';
                        html += '	<td>'+item.IP_ADDR+'</td>';
                        html += '</tr>';
                    }
				});
                $('table.tbl-att-list tbody').append(html);

				<?php
					// 접속경로 설정 없으면 숨김
					// if (!isset($project['CONN_ROUTE_1']) || $project['CONN_ROUTE_1'] == '') {
					// 	echo "$('table.tbl-reqr-list .conn-route').remove();";
					// }

					// 입력정보 없으면 테이블 column 삭제
					if (!isset($project['ENT_INFO_EXTRA_1']) || $project['ENT_INFO_EXTRA_1'] == '') {
						echo "$('table.tbl-att-list .extra-1').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_2']) || $project['ENT_INFO_EXTRA_2'] == '') {
						echo "$('table.tbl-att-list .extra-2').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_3']) || $project['ENT_INFO_EXTRA_3'] == '') {
						echo "$('table.tbl-att-list .extra-3').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_4']) || $project['ENT_INFO_EXTRA_4'] == '') {
						echo "$('table.tbl-att-list .extra-4').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_5']) || $project['ENT_INFO_EXTRA_5'] == '') {
						echo "$('table.tbl-att-list .extra-5').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_6']) || $project['ENT_INFO_EXTRA_6'] == '') {
						echo "$('table.tbl-att-list .extra-6').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_7']) || $project['ENT_INFO_EXTRA_7'] == '') {
						echo "$('table.tbl-att-list .extra-7').remove();";
					}
					if (!isset($project['ENT_INFO_EXTRA_8']) || $project['ENT_INFO_EXTRA_8'] == '') {
						echo "$('table.tbl-att-list .extra-8').remove();";
					}
				?>

			} else {
				alert('참석자 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('사전등록자 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 엑셀저장
function downloadExcel (tblNm) {
	const today = new Date();
	const todayShort = today.toJSON().slice(0, 10).split`-`.join``;
	// excelModalExwide1('엑셀저장', '엑셀저장 버튼을 클릭하세요.', 'tbl-reqr-list', '<?= $project['PRJ_TITLE'] ?>_사전등록자');
	downloadTableToCsv(tblNm, '<?= $project['PRJ_TITLE'] ?>_'+(tblNm == 'tbl-reqr-list' ? '사전등록자' : '참석자')+'_'+todayShort);
}

// 엑셀(.csv) 업로드 모달 열기
function openUploadExcel () {
	$('#uploadExcelModal').modal();
}
// 엑셀(.csv) 업로드
function uploadExcel () {
	// validation
	if (isEmpty( $('#requestorFile').val() )) {
		alert('업로드 파일을 선택해주세요.');
		$('#MAIN_IMG').focus();
		return;
	}

	showSpinner();

	const form = $('form')[0];
	const formData = new FormData(form);

	$.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/project/uploadRequestor/<?= $project['PRJ_SEQ'] ?>',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 10000,
        success: function (data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				console.log(data.logList);
				alert('프로젝트 사전등록자 목록 업로드가 완료되었습니다. (신규등록  '+data.cntInsert+'건, 갱신 '+data.cntUpdate+'건)');
				location.reload();
			} else {
				alert('프로젝트 사전등록자 목록 업로드 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
        },
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 사전등록자 목록 업로드 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
        },
		complete : function () {
			hideSpinner();
		}
    });
}

// 사전등록자 삭제
function deleteAllRequestor () {
	if (!confirm('해당 프로젝트의 사전등록자 데이터가 모두 삭제되며 복구할 수 없습니다. 사전등록자를 모두 삭제하시겠습니까?')) {
		return;
	}

	$.ajax({
		type: 'POST',
		url: '/project/deleteAllRequestor',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq: <?= $project['PRJ_SEQ'] ?>
		},

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				alert('프로젝트 사전등록자 목록을 삭제했습니다.');
				location.reload();
			} else {
				alert('프로젝트 사전등록자 목록을 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 사전등록자 목록을 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			// hideSpinner();
		}
	});
}

// 검색 초기화
function clearSearch (gb) {
    $(`#search${gb}Nm`).val('')
    $(`#search${gb}Mbilno`).val('')

    if (gb === 'Reqr') {
        getRequestorList()
    } else if (gb === 'Att') {
        getAttendanceList()
    }
}

// 사전등록자 1명에 대한 팝업 오픈
function openReqrPopup (jsonString) {
    const reqrItem = JSON.parse(jsonString)
    // console.log(reqrItem)

    selectedReqrSeq = reqrItem.REQR_SEQ
    // alert(`selectedReqrSeq: ${selectedReqrSeq}`)

    for (const [key, value] of Object.entries(reqrItem)) {
        $(`#${key}`).val(value)
    }

    $('#reqrModal').modal()
}

// 사전등록자 수정
function updateRequestor () {
    let rowData = {}
    $('.tbl-reqr-popup input:not([readonly])').each(function () {
        rowData[$(this).attr('id')] = $(this).val()
    })
    // console.log(rowData)

    const data = {
        prjSeq: <?= $project['PRJ_SEQ'] ?>,
        reqrSeq: selectedReqrSeq,
        rowData
    }

    $.ajax({
		type: 'POST',
		url: '/project/updateRequestor',
		dataType: 'json',
		cache: false,
		data,

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				alert('프로젝트 사전등록자를 수정했습니다.');

                selectedReqrSeq = null
                $('#reqrModal input').val('')
                $('#reqrModal').modal('hide')

                getRequestorList()
			} else {
				alert('프로젝트 사전등록자를 수정하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 사전등록자를 수정하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			// hideSpinner();
		}
	});
}

// 사전등록자 삭제
function deleteRequestor () {
    if (!confirm('사전등록자가 완전히 삭제되며, 복원할 수 없습니다. 그래도 삭제하시겠습니까?')) {
        return
    }

    const data = {
        prjSeq: <?= $project['PRJ_SEQ'] ?>,
        reqrSeq: selectedReqrSeq,
    }

    $.ajax({
		type: 'POST',
		url: '/project/deleteRequestor',
		dataType: 'json',
		cache: false,
		data,

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				alert('프로젝트 사전등록자를 삭제했습니다.');

                selectedReqrSeq = null
                $('#reqrModal input').val('')
                $('#reqrModal').modal('hide')

                getRequestorList()
			} else {
				alert('프로젝트 사전등록자를 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 사전등록자를 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			// hideSpinner();
		}
	});
}


$(document).ready(function () {
	fnInit();

	// scroll-to-top은 sb-admin-2.js에 있음.
	// 사전등록자는 목록이 매우 길고 참석자목록도 있으므로 별도 버튼 추가 구현
	$(document).on('scroll', function() {
		var scrollDistance = $(this).scrollTop();
		if (scrollDistance > 100) {
			$('.scroll-to-middle').fadeIn();
			$('.scroll-to-bottom').fadeIn();
		} else {
			$('.scroll-to-middle').fadeOut();
			$('.scroll-to-bottom').fadeOut();
		}
	});

	// Smooth scrolling using jQuery easing
	$(document).on('click', 'a.scroll-to-middle', function(e) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: ($($anchor.attr('href')).offset().top * 1 - 20)
		}, 1000, 'easeInOutExpo');
		e.preventDefault();
	});
	$(document).on('click', 'a.scroll-to-bottom', function(e) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: ($(document).height())
		}, 1000, 'easeInOutExpo');
		e.preventDefault();
	});

    // 검색 input에서 엔터 입력시 처리
    $('div.search input#searchReqrNm, div.search input#searchReqrMbilno').keyup(function (event) {
        if (event.keyCode == 13) {
            getRequestorList()
        }
    })
    $('div.search input#searchAttNm, div.search input#searchAttMbilno').keyup(function (event) {
        if (event.keyCode == 13) {
            getAttendanceList()
        }
    })

	//submit 되기 전 처리
	$('form').submit(function(e) {

	});

});

</script>

</body>

</html>
