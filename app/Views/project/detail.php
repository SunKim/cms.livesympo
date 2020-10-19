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

<title>Live Sympo 관리자 프로젝트</title>

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

<!-- flatpickr(date picker). https://flatpickr.js.org/getting-started/ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

<!-- MiniColors. https://www.jqueryscript.net/other/Color-Picker-Plugin-jQuery-MiniColors.html -->
<link rel="stylesheet" href="/vendor/minicolors/jquery.minicolors.css">

<!-- START) 메인 css -->
<style type="text/css">
ul { margin: 0; }
ul.enter-guide li { margin-bottom: 10px; }
ul.enter-guide li:last-child { margin-bottom: 0; }

span.input-title { display: inline-block; min-width: 140px; }
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

					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 상세</h6>
						</div>
						<div class="card-body">
							<form method="POST">
								<table class="table-detail">
									<colgroup>
										<col width="10%" />
										<col width="15%" />
										<col width="75%" />
									</colgroup>
									<tbody>
										<tr>
											<th rowspan="8">프로젝트</th>
											<th class="required">타이틀</th>
											<td class="tl">
												<input type="hidden" id="PRJ_SEQ" name="PRJ_SEQ" value="<?= $prjSeq ?>" />
												<input type="hidden" id="EMAIL" name="EMAIL" value="<?= $email ?>" />
												<input type="text" id="PRJ_TITLE" name="PRJ_TITLE" class="common-input w90" />
											</td>
										</tr>
										<tr>
											<th class="required">URI</th>
											<td class="tl">
												<input type="text" id="PRJ_TITLE_URI" name="PRJ_TITLE_URI" class="common-input w90" placeholder="drug-a-20200922 형식으로 입력해주세요." />
											</td>
										</tr>
										<tr>
											<th class="required">임베디드 코드</th>
											<td class="tl">
												<!-- <input type="text" id="STREAM_URL" name="STREAM_URL" class="common-input w90" placeholder="https://stream.com/xxxx 형식으로 입력해주세요." /> -->
												<textarea id="STREAM_URL" name="STREAM_URL" class="common-textarea w90 mt10 mb10" maxlength="1000" rows="4" placeholder="스트리밍 서비스의 임베디드 코드를 입력해주세요."></textarea>
											</td>
										</tr>
										<!-- <tr>
											<th class="required">아젠다 추가페이지</th>
											<td class="tl">
												<select class="common-select w90" id="AGENDA_PAGE_YN" name="AGENDA_PAGE_YN">
													<option value="0">N</option>
													<option value="1">Y</option>
												</select>
											</td>
										</tr> -->
										<tr>
											<th class="required">프로젝트 일시</th>
											<td class="tl">
												<input type="text" id="ST_DATE" name="ST_DATE" class="common-input w10 datepicker" />
												<input type="text" id="ST_TIME" name="ST_TIME" class="common-input w10" value="00:00" />
												<span class="inblock tc" style="width: 20px;">-</span>
												<input type="text" id="ED_DATE" name="ED_DATE" class="common-input w10 datepicker" />
												<input type="text" id="ED_TIME" name="ED_TIME" class="common-input w10" value="23:59" />
											</td>
										</tr>
										<tr>
											<th class="required">접속경로 설정</th>
											<td class="tl">
												<p class="desc">* 최대 3개까지 등록 가능합니다. 없으면 빈칸으로 두세요.</p>
												<ul>
													<li class="mt10"><input type="text" id="CONN_ROUTE_1" name="CONN_ROUTE_1" class="common-input w90" /></li>
													<li class="mt10"><input type="text" id="CONN_ROUTE_2" name="CONN_ROUTE_2" class="common-input w90" /></li>
													<li class="mt10"><input type="text" id="CONN_ROUTE_3" name="CONN_ROUTE_3" class="common-input w90" /></li>
												</ul>
											</td>
										</tr>
										<tr>
											<th class="required">사전등록 항목</th>
											<td class="tl">
												<p class="desc">* 6개까지 등록 가능합니다. 성명/연락처/병원명/과명은 기본항목입니다. Placeholder는 없으면 빈칸으로 두세요.</p>
												<div class="ent-info-container">
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" class="common-input w20" value="성명" readonly />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" class="common-input w20" value="" readonly />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" value="1" disabled>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" class="common-input w20" value="연락처" readonly />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" class="common-input w20" value="-는 제외하고 입력해주세요." readonly />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" value="1" disabled>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" class="common-input w20" value="병원명" readonly />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" class="common-input w20" value="" readonly />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" value="1" disabled>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" class="common-input w20" value="과명" readonly />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" class="common-input w20" value="" readonly />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" value="1" disabled>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" id="ENT_INFO_EXTRA_1" name="ENT_INFO_EXTRA_1" class="common-input w20" value="" />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" id="ENT_INFO_EXTRA_PHOLDER_1" name="ENT_INFO_EXTRA_PHOLDER_1" class="common-input w20" value="" />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" id="ENT_INFO_EXTRA_REQUIRED_1" name="ENT_INFO_EXTRA_REQUIRED_1">
															<option value="">선택</option>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" id="ENT_INFO_EXTRA_2" name="ENT_INFO_EXTRA_2" class="common-input w20" value="" />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" id="ENT_INFO_EXTRA_PHOLDER_2" name="ENT_INFO_EXTRA_PHOLDER_2" class="common-input w20" value="" />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" id="ENT_INFO_EXTRA_REQUIRED_2" name="ENT_INFO_EXTRA_REQUIRED_2">
															<option value="">선택</option>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" id="ENT_INFO_EXTRA_3" name="ENT_INFO_EXTRA_3" class="common-input w20" value="" />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" id="ENT_INFO_EXTRA_PHOLDER_3" name="ENT_INFO_EXTRA_PHOLDER_3" class="common-input w20" value="" />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" id="ENT_INFO_EXTRA_REQUIRED_3" name="ENT_INFO_EXTRA_REQUIRED_3">
															<option value="">선택</option>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" id="ENT_INFO_EXTRA_4" name="ENT_INFO_EXTRA_4" class="common-input w20" value="" />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" id="ENT_INFO_EXTRA_PHOLDER_4" name="ENT_INFO_EXTRA_PHOLDER_4" class="common-input w20" value="" />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" id="ENT_INFO_EXTRA_REQUIRED_4" name="ENT_INFO_EXTRA_REQUIRED_4">
															<option value="">선택</option>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" id="ENT_INFO_EXTRA_5" name="ENT_INFO_EXTRA_5" class="common-input w20" value="" />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" id="ENT_INFO_EXTRA_PHOLDER_5" name="ENT_INFO_EXTRA_PHOLDER_5" class="common-input w20" value="" />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" id="ENT_INFO_EXTRA_REQUIRED_5" name="ENT_INFO_EXTRA_REQUIRED_5">
															<option value="">선택</option>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
													<div class="mt10 mb10">
														<span class="ent-info-title">항목명</span>
														<input type="text" id="ENT_INFO_EXTRA_6" name="ENT_INFO_EXTRA_6" class="common-input w20" value="" />
														<span class="ent-info-title ml20">Placeholder</span>
														<input type="text" id="ENT_INFO_EXTRA_PHOLDER_6" name="ENT_INFO_EXTRA_PHOLDER_6" class="common-input w20" value="" />
														<span class="ent-info-title ml20">필수여부</span>
														<select class="common-select w20" id="ENT_INFO_EXTRA_REQUIRED_6" name="ENT_INFO_EXTRA_REQUIRED_6">
															<option value="">선택</option>
															<option value="1">Y</option>
															<option value="0">N</option>
														</select>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<th class="required">입장가이드 항목</th>
											<td class="tl">
												<ul class="enter-guide">
												</ul>
												<div class="mt10">
													<button type="button" class="btn-sub btn-blue ml10" onclick="javascript:addEnterGuide();">항목추가</button>
													<button type="button" class="btn-sub btn-white ml10" onclick="javascript:removeEnterGuide();">최근항목 삭제</button>
												</div>
											</td>
										</tr>
										<tr>
											<th class="required">스트리밍 페이지 버튼명</th>
											<td class="tl">
												<p>
													<span class="input-title">아젠다확인 버튼</span>
													<input type="text" id="AGENDA_BTN_TEXT" name="AGENDA_BTN_TEXT" class="common-input w30" value="아젠다확인" />
												</p>
												<p class="mt10">
													<span class="input-title">설문참여 버튼</span>
													<input type="text" id="SURVEY_BTN_TEXT" name="SURVEY_BTN_TEXT" class="common-input w30" value="설문참여" />
												</p>
												<p class="mt10">
													<span class="input-title">질문등록 버튼</span>
													<input type="text" id="QST_BTN_TEXT" name="QST_BTN_TEXT" class="common-input w30" value="질문등록" />
												</p>
											</td>
										</tr>
										<tr>
											<th rowspan="3">이미지</th>
											<th class="required">메인 이미지</th>
											<td class="tl">
												<p class="desc">* 가로는 1170px 세로는 적당한 비율로 올려주세요.</p>
												<img class="update-img-view" id="MAIN_IMG_URL" />
												<input type="file" id="MAIN_IMG" name="MAIN_IMG" class="common-input w50" accept="image/x-png,image/gif,image/jpeg" />
											</td>
										</tr>
										<tr>
											<th class="required">아젠다 이미지</th>
											<td class="tl">
												<p class="desc">* 가로는 1170px 세로는 556px로 올려주세요.</p>
												<img class="update-img-view" id="AGENDA_IMG_URL" />
												<input type="file" id="AGENDA_IMG" name="AGENDA_IMG" class="common-input w50" accept="image/x-png,image/gif,image/jpeg" />
											</td>
										</tr>
										<tr>
											<th class="required">푸터 이미지</th>
											<td class="tl">
												<p class="desc">* 가로는 1170px 세로는 적당한 비율로 올려주세요.</p>
												<img class="update-img-view" id="FOOTER_IMG_URL" />
												<input type="file" id="FOOTER_IMG" name="FOOTER_IMG" class="common-input w50" accept="image/x-png,image/gif,image/jpeg" />
											</td>
										</tr>
										<tr>
											<th rowspan="3"><p>사전등록</p><p>디자인</p></th>
											<th class="required">전체 배경색</th>
											<td class="tl">
												<input type="text" id="APPL_BODY_COLR" name="APPL_BODY_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
											</td>
										</tr>
										<tr>
											<th class="required">사전등록버튼</th>
											<td class="tl">
												<p>
													<span class="input-title">배경색상</span>
													<input type="text" id="APPL_BTN_BG_COLR" name="APPL_BTN_BG_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
												<p class="mt10">
													<span class="input-title">글씨색상</span>
													<input type="text" id="APPL_BTN_FONT_COLR" name="APPL_BTN_FONT_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
												<p class="mt10">
													<span class="input-title">배열</span>
													<select class="common-select w10" id="APPL_BTN_ALIGN" name="APPL_BTN_ALIGN">
														<option value="center" selected>중앙</option>
														<option value="left">좌측</option>
														<option value="right">우측</option>
													</select>
												</p>
											</td>
										</tr>
										<tr>
											<th class="required">입장정보 영역</th>
											<td class="tl">
												<p>
													<span class="input-title">배경색상</span>
													<input type="text" id="ENT_THME_COLR" name="ENT_THME_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
												<p class="mt10">
													<span class="input-title">높이</span>
													<input type="text" id="ENT_THME_HEIGHT" name="ENT_THME_HEIGHT" class="common-input w10" value="556" /> px
												</p>
											</td>
										</tr>
										<tr>
											<th rowspan="3"><p>스트리밍</p><p>디자인</p></th>
											<th class="required">전체 배경색</th>
											<td class="tl">
												<input type="text" id="STREAM_BODY_COLR" name="STREAM_BODY_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
											</td>
										</tr>
										<tr>
											<th class="required">전체 버튼</th>
											<td class="tl">
												<p>
													<span class="input-title">배경색상</span>
													<input type="text" id="STREAM_BTN_BG_COLR" name="STREAM_BTN_BG_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
												<p class="mt10">
													<span class="input-title">글씨색상</span>
													<input type="text" id="STREAM_BTN_FONT_COLR" name="STREAM_BTN_FONT_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
											</td>
										</tr>
										<tr>
											<th class="required">질문 Box</th>
											<td class="tl">
												<p>
													<span class="input-title">배경색상</span>
													<input type="text" id="STREAM_QA_BG_COLR" name="STREAM_QA_BG_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
												<p class="mt10">
													<span class="input-title">글씨색상</span>
													<input type="text" id="STREAM_QA_FONT_COLR" name="STREAM_QA_FONT_COLR" class="common-input w90 color-picker" value="#ffffff" style="height: 28px !important;" />
												</p>
											</td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>

						<div class="d-flex align-items-center justify-content-between pa20">
							<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
<?php
	// 레벨9만 보이도록
	if ($lvl == 9) {
		echo '<button class="btn-main btn-red" onclick="test()">테스트</button>';
		echo '<button class="btn-main btn-light-indigo" onclick="save();">저장</button>';
	}
?>
						</div>
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
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>

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

<!-- flatpickr(date picker). https://flatpickr.js.org/getting-started/ -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>

<!-- MiniColors. https://www.jqueryscript.net/other/Color-Picker-Plugin-jQuery-MiniColors.html -->
<script src="/vendor/minicolors/jquery.minicolors.js"></script>

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">
const ENT_INFO_CNT = 6;

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	// datepicker 설정
	const dpOption = {
		locale: 'ko'
	};
	$('.datepicker').flatpickr(dpOption);

	// 수정페이지면 데이터 가져오기
	if (<?= $prjSeq ?> > 0) {
		getDetail(<?= $prjSeq ?>);

		// update면 데이터 다 불러오고 나서 color-picker 설정.
	} else {
		// 신규등록이면 바로 color-picker 설정.
		// color picker 설정. https://www.jqueryscript.net/other/Color-Picker-Plugin-jQuery-MiniColors.html
		$('.color-picker').minicolors();

		// 입장가이드 default값 설정
		$('ul.enter-guide li:nth-child(1) input[name=enter_guide]').val('사전등록시 입력하신 선생님의 성명과 연락처를 입력해주세요.');
		$('ul.enter-guide li:nth-child(2) input[name=enter_guide]').val('현장등록시 사전등록이 안되신 분은 사전등록 후 이용해주세요.');
	}
}

// 입장가이드 항목 추가
function addEnterGuide() {
	let html = '';

	html += '<li>';
	html += '	- <input type="text" class="common-input w90" name="enter_guide" value="" />';
	html += '</li>';

	$('ul.enter-guide').append(html);
	$('ul.enter-guide li:last-child input').focus();
}

// 입장가이드 최근항목 삭제
function removeEnterGuide() {
	$('ul.enter-guide li:last-child').remove();
}

// 저장
function save () {
	if (isEmpty( $('#PRJ_TITLE').val() )) {
		alert('프로젝트 타이틀을 입력해주세요.');
		$('#PRJ_TITLE').focus();
		return;
	}
	if (isEmpty( $('#PRJ_TITLE_URI').val() )) {
		alert('프로젝트 URI를 입력해주세요.');
		$('#PRJ_TITLE_URI').focus();
		return;
	}
	// if (!checkUrl( $('#STREAM_URL').val() )) {
	// 	alert('임베디드 코드을 형식에 맞게 입력해주세요.');
	// 	$('#STREAM_URL').focus();
	// 	return;
	// }
	if (!checkDate( $('#ST_DATE').val() )) {
		alert('시작일자를 형식에 맞게 입력해주세요.(2020-01-01)');
		$('#ST_DATE').focus();
		return;
	}

	// 추가항목이 있을 경우 필수여부 체크
	if (!isEmpty( $('#ENT_INFO_EXTRA_1').val() )) {
		if ($('#ENT_INFO_EXTRA_REQUIRED_1').val() == '') {
			alert('사전등록항목('+$('#ENT_INFO_EXTRA_1').val()+')의 필수여부를 입력해주세요.');
			$('#ENT_INFO_EXTRA_REQUIRED_1').focus();
			return;
		}
	}
	if (!isEmpty( $('#ENT_INFO_EXTRA_2').val() )) {
		if ($('#ENT_INFO_EXTRA_REQUIRED_2').val() == '') {
			alert('사전등록항목('+$('#ENT_INFO_EXTRA_2').val()+')의 필수여부를 입력해주세요.');
			$('#ENT_INFO_EXTRA_REQUIRED_2').focus();
			return;
		}
	}
	if (!isEmpty( $('#ENT_INFO_EXTRA_3').val() )) {
		if ($('#ENT_INFO_EXTRA_REQUIRED_3').val() == '') {
			alert('사전등록항목('+$('#ENT_INFO_EXTRA_3').val()+')의 필수여부를 입력해주세요.');
			$('#ENT_INFO_EXTRA_REQUIRED_3').focus();
			return;
		}
	}
	if (!isEmpty( $('#ENT_INFO_EXTRA_4').val() )) {
		if ($('#ENT_INFO_EXTRA_REQUIRED_4').val() == '') {
			alert('사전등록항목('+$('#ENT_INFO_EXTRA_4').val()+')의 필수여부를 입력해주세요.');
			$('#ENT_INFO_EXTRA_REQUIRED_4').focus();
			return;
		}
	}
	if (!isEmpty( $('#ENT_INFO_EXTRA_5').val() )) {
		if ($('#ENT_INFO_EXTRA_REQUIRED_5').val() == '') {
			alert('사전등록항목('+$('#ENT_INFO_EXTRA_5').val()+')의 필수여부를 입력해주세요.');
			$('#ENT_INFO_EXTRA_REQUIRED_5').focus();
			return;
		}
	}
	if (!isEmpty( $('#ENT_INFO_EXTRA_6').val() )) {
		if ($('#ENT_INFO_EXTRA_REQUIRED_6').val() == '') {
			alert('사전등록항목('+$('#ENT_INFO_EXTRA_6').val()+')의 필수여부를 입력해주세요.');
			$('#ENT_INFO_EXTRA_REQUIRED_6').focus();
			return;
		}
	}

	// 신규등록일 경우 필수 이미지 체크
	if (<?= $prjSeq ?> == 0) {
		if (isEmpty( $('#MAIN_IMG').val() )) {
			alert('메인 이미지를 선택해주세요.');
			$('#MAIN_IMG').focus();
			return;
		}
		if (isEmpty( $('#AGENDA_IMG').val() )) {
			alert('어젠다 이미지를 선택해주세요.');
			$('#AGENDA_IMG').focus();
			return;
		}
		if (isEmpty( $('#FOOTER_IMG').val() )) {
			alert('푸터 이미지를 선택해주세요.');
			$('#FOOTER_IMG').focus();
			return;
		}
	}

	const form = $('form')[0];
	const formData = new FormData(form);

	// 입장가이드 입력목록 추가
	const entGuideList = []
	$('input[name=enter_guide]').each(function (item) {
		entGuideList.push($(this).val());
	});
	// console.log(`entGuideList - ${JSON.stringify(entGuideList)}`);

	formData.append('entGuideList', JSON.stringify(entGuideList));
	// console.log(`formData - ${JSON.stringify(formData)}`);

	$.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/project/save/<?= $prjSeq ?>',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 10000,
        success: function (data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				alert('저장되었습니다. 이전페이지로 이동합니다.');
				history.back();
			} else {
				alert('프로젝트 데이터를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
        },
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 데이터를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
        }
    });
}

// 프로젝트 상세정보
function getDetail (prjSeq) {
	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/project/getDetail',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq
		},

		success: function(data) {
			console.log(data)
			if ( data.resCode == '0000' ) {
				$('#PRJ_TITLE').val(data.item.PRJ_TITLE);
				$('#PRJ_TITLE_URI').val(data.item.PRJ_TITLE_URI);
				$('#STREAM_URL').val(data.item.STREAM_URL);
				// $('#AGENDA_PAGE_YN').val(data.item.AGENDA_PAGE_YN);
				$('#ST_DATE').val(data.item.ST_DATE);
				$('#ST_TIME').val(data.item.ST_TIME);
				$('#ED_DATE').val(data.item.ED_DATE);
				$('#ED_TIME').val(data.item.ED_TIME);

				$('#CONN_ROUTE_1').val(data.item.CONN_ROUTE_1);
				$('#CONN_ROUTE_2').val(data.item.CONN_ROUTE_2);
				$('#CONN_ROUTE_3').val(data.item.CONN_ROUTE_3);

				$('#ENT_INFO_EXTRA_1').val(data.item.ENT_INFO_EXTRA_1);
				$('#ENT_INFO_EXTRA_PHOLDER_1').val(data.item.ENT_INFO_EXTRA_PHOLDER_1);
				$('#ENT_INFO_EXTRA_REQUIRED_1').val(data.item.ENT_INFO_EXTRA_REQUIRED_1);
				$('#ENT_INFO_EXTRA_2').val(data.item.ENT_INFO_EXTRA_2);
				$('#ENT_INFO_EXTRA_PHOLDER_2').val(data.item.ENT_INFO_EXTRA_PHOLDER_2);
				$('#ENT_INFO_EXTRA_REQUIRED_2').val(data.item.ENT_INFO_EXTRA_REQUIRED_2);
				$('#ENT_INFO_EXTRA_3').val(data.item.ENT_INFO_EXTRA_3);
				$('#ENT_INFO_EXTRA_PHOLDER_3').val(data.item.ENT_INFO_EXTRA_PHOLDER_3);
				$('#ENT_INFO_EXTRA_REQUIRED_3').val(data.item.ENT_INFO_EXTRA_REQUIRED_3);
				$('#ENT_INFO_EXTRA_4').val(data.item.ENT_INFO_EXTRA_4);
				$('#ENT_INFO_EXTRA_PHOLDER_4').val(data.item.ENT_INFO_EXTRA_PHOLDER_4);
				$('#ENT_INFO_EXTRA_REQUIRED_4').val(data.item.ENT_INFO_EXTRA_REQUIRED_4);
				$('#ENT_INFO_EXTRA_5').val(data.item.ENT_INFO_EXTRA_5);
				$('#ENT_INFO_EXTRA_PHOLDER_5').val(data.item.ENT_INFO_EXTRA_PHOLDER_5);
				$('#ENT_INFO_EXTRA_REQUIRED_5').val(data.item.ENT_INFO_EXTRA_REQUIRED_5);
				$('#ENT_INFO_EXTRA_6').val(data.item.ENT_INFO_EXTRA_6);
				$('#ENT_INFO_EXTRA_PHOLDER_6').val(data.item.ENT_INFO_EXTRA_PHOLDER_6);
				$('#ENT_INFO_EXTRA_REQUIRED_6').val(data.item.ENT_INFO_EXTRA_REQUIRED_6);

				$('#AGENDA_BTN_TEXT').val(data.item.AGENDA_BTN_TEXT);
				$('#SURVEY_BTN_TEXT').val(data.item.SURVEY_BTN_TEXT);
				$('#QST_BTN_TEXT').val(data.item.QST_BTN_TEXT);

				$('#APPL_BODY_COLR').val(data.item.APPL_BODY_COLR);
				$('#APPL_BTN_BG_COLR').val(data.item.APPL_BTN_BG_COLR);
				$('#APPL_BTN_FONT_COLR').val(data.item.APPL_BTN_FONT_COLR);
				$('#APPL_BTN_ALIGN').val(data.item.APPL_BTN_ALIGN);

				$('#ENT_THME_COLR').val(data.item.ENT_THME_COLR);
				$('#ENT_THME_HEIGHT').val(data.item.ENT_THME_HEIGHT);

				$('#STREAM_BODY_COLR').val(data.item.STREAM_BODY_COLR);
				$('#STREAM_BTN_BG_COLR').val(data.item.STREAM_BTN_BG_COLR);
				$('#STREAM_BTN_FONT_COLR').val(data.item.STREAM_BTN_FONT_COLR);
				$('#STREAM_QA_BG_COLR').val(data.item.STREAM_QA_BG_COLR);
				$('#STREAM_QA_FONT_COLR').val(data.item.STREAM_QA_FONT_COLR);

				// 기존 등록된 이미지를 보여줌
				$('#MAIN_IMG_URL').attr('src', data.item.MAIN_IMG_URL);
				$('#AGENDA_IMG_URL').attr('src', data.item.AGENDA_IMG_URL);
				$('#FOOTER_IMG_URL').attr('src', data.item.FOOTER_IMG_URL);

				const entGuideList = data.entGuideList;
				entGuideList.forEach(item => {
					let html = '';

					html += '<li>';
					html += '	- <input type="text" class="common-input w90" name="enter_guide" value="'+item.GUIDE_DESC+'" />';
					html += '</li>';

					$('ul.enter-guide').append(html);
				});

				// update면 데이터 다 불러오고 나서 color-picker 설정. (바로하면 색상반영이 안됨)
				$('.color-picker').minicolors();

			} else {
				// modal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				// centerModal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				alert('프로젝트 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
				// hideSpinner();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			// modal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			// centerModal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			alert('프로젝트 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			// hideSpinner();
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 테스트용 데이터
function test () {
	$('#PRJ_TITLE').val('테스트 타이틀');
	$('#PRJ_TITLE_URI').val('test-title-20201001');
	$('#STREAM_URL').val('https://stream.com/test/stream?param=0');
	// $('#AGENDA_PAGE_YN').val('0');
	$('#ST_DATE').val('2020-10-14');
	$('#ST_TIME').val('10:30');
	$('#ED_DATE').val('2020-10-14');
	$('#ED_TIME').val('12:00');

	$('#CONN_ROUTE_1').val('온라인 광고');
	$('#CONN_ROUTE_2').val('영업사원');
	$('#CONN_ROUTE_2').val('어쩌다 마주친');

	$('#ENT_INFO_EXTRA_1').val('지역');
	$('#ENT_INFO_EXTRA_PHOLDER_1').val('지역명을 또박또박 입력해주세요.');
	$('#ENT_INFO_EXTRA_REQUIRED_1').val('1');
	$('#ENT_INFO_EXTRA_2').val('좌우명');
	$('#ENT_INFO_EXTRA_PHOLDER_2').val('좌우명을 입력해주세요.');
	$('#ENT_INFO_EXTRA_REQUIRED_2').val('0');

	$('#ENT_THME_COLR').val('#51633d');
	$('#APPL_BTN_BG_COLR').val('#e09238');
}

$(document).ready(function () {
	fnInit();

	//submit 되기 전 처리
	$('form').submit(function(e) {

	});
});

</script>

</body>

</html>
