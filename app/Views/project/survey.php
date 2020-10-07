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

<title>Live Sympo 관리자 프로젝트 설문관리</title>

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
span.qst-no { width: 32px; height: 32px; line-height: 32px; background: #0274C3; color: #fff; border: 1px solid #0274C3; border-radius: 4px; text-align: center; vertical-align: middle; font-weight: 700; }

ul.qst-choice-list { min-height: 48px; padding: 10px; border: 1px solid #0274C355; border-radius: 10px; }
ul.qst-choice-list li { margin-top: 10px; }
ul.qst-choice-list li:first-child { margin-top: 0; }
span.choice-no { width: 20px; height: 20px; line-height: 20px; border: 1px solid #999; border-radius: 10px; text-align: center; vertical-align: middle; font-size: 14px; font-weight: 600; }
span.choice { margin-left: 4px; font-size: 14px; }
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
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 설문관리</h6>
						</div>
						<div style="padding: 20px 40px;">
							<p class="desc">* 설문항목은 총 10개까지 입력 가능합니다. </p>
							<ul class="survey-qst-list mt20">
								<li QST_NO="1" class="survey-qst-item d-flex align-items-start justify-content-around">
									<span class="qst-no">1</span>
									<div class="w90">
										<input type="text" class="common-input w100 qst-title" value="" placeholder="설문 질문항목을 입력하세요." />
										<div class="mt10">
											<select class="common-select w20 qst-tp">
												<option value="주관식" checked>주관식</option>
												<option value="객관식">객관식</option>
											</select>
											<select class="common-select w20 qst-multi-yn">
												<option value="0" checked>복수응답 불가</option>
												<option value="1">복수응답 가능</option>
											</select>
										</div>
										<div class="mt30">
											<h6>보기</h6>
											<ul class="qst-choice-list">
												<li>
													<span class="choice-no">1</span>
													<span class="choice">저는 그냥 보기일 뿐이에요.</span>
												</li>
											</ul>

											<div class="mt10">
												<input type="text" class="common-input w60 input-choice" value="" placeholder="보기를 입력하세요." />
												<button class="btn-sub btn-blue ml10">보기추가</button>
											</div>
										</div>
									</div>
								</li>
								<hr />
								<li QST_NO="2" class="survey-qst-item d-flex align-items-start justify-content-around">
									<span class="qst-no">2</span>
									<div class="w90">
										<input type="text" class="common-input w100 qst-title" value="" placeholder="설문 질문항목을 입력하세요." />
										<div class="mt10">
											<select class="common-select w20 qst-tp">
												<option value="주관식" checked>주관식</option>
												<option value="객관식">객관식</option>
											</select>
											<select class="common-select w20 qst-multi-yn">
												<option value="0" checked>복수응답 불가</option>
												<option value="1">복수응답 가능</option>
											</select>
										</div>
										<div class="mt30">
											<h6>보기</h6>
											<ul class="qst-choice-list">
												<li>
													<span class="choice-no">1</span>
													<span class="choice">저는 그냥 보기일 뿐이에요.</span>
												</li>
												<li>
													<span class="choice-no">2</span>
													<span class="choice">저는 그냥 보기라구요.</span>
												</li>
											</ul>

											<div class="mt10">
												<input type="text" class="common-input w60 input-choice" value="" placeholder="보기를 입력하세요." />
												<button class="btn-sub btn-blue ml10">보기추가</button>
											</div>
										</div>
									</div>
								</li>
								<hr />
							</ul>
						</div>

						<div class="d-flex align-items-center justify-content-between pa20">
							<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
							<span>&nbsp;</span>
							<button class="btn-main btn-light-indigo" onclick="save();">저장</button>
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

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	getSurveyList(<?= $project['PRJ_SEQ'] ?>);
}

// 질문목록 불러오기
function getSurveyList (prjSeq) {
	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/project/getSurveyList',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq
		},

		success: function(data) {
			console.log(data)
			if ( data.resCode == '0000' ) {
				const surveyQstList = data.surveyQstList;
				const surveyQstChoiceList = data.surveyQstChoiceList;

				let html = '';
				// surveyQstList.forEach(item => {
				// 	html += '<li class="mb20 '+(item.APRV_YN == 1 ? 'approved' : '')+'">';
				// 	html += '	<div class="d-flex justify-content-between align-items-center">';
				// 	html += '		<p class="regr">';
				// 	html += '			<span>'+(item.FAKE_YN == 0 ? item.REQR_NM : item.FAKE_NM)+'</span>';
				// 	html += '			<span>'+(item.FAKE_YN == 0 ? `(${formatMobile(item.MBILNO)})` : '')+'</span>';
				// 	html += '			<span>'+(item.FAKE_YN == 0 ? item.HSPTL_NM : '')+'</span>';
				// 	html += '			<span>'+(item.FAKE_YN == 0 ? item.SBJ_NM : '')+'</span>';
				// 	if (item.APRV_YN == 0) {
				// 		html += '			<button class="btn-sub btn-light-indigo ml5" onclick="approve('+item.QST_SEQ+', 1);">승인</button>';
				// 	} else {
				// 		html += '			<button class="btn-sub btn-white ml5" onclick="approve('+item.QST_SEQ+', 0);">승인취소</button>';
				// 	}
				// 	html += '		</p>';
				// 	html += '		<p class="reg-dttm">'+item.REG_DTTM+'</p>';
				// 	html += '	</div>';
				// 	html += '	<textarea maxlength="400" rows="4" class="w100 mt10 mb10" readonly>'+item.QST_DESC+'</textarea>';
				// 	html += '</li>';
				// });

				// $('.survey-qst-list').empty();
				// $('.survey-qst-list').append(html);
			} else {
				alert('프로젝트 설문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 설문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 설문 저장
function saveSurvey () {
	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/project/saveSurvey',
		dataType: 'json',
		cache: false,
		data: {
		},

		success: function(data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				alert('설문을 저장했습니다.');
				getSurveyList(<?= $project['PRJ_SEQ'] ?>);
			} else {
				alert('설문정보를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('설문정보를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
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
