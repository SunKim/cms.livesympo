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
input::placeholder { color: #ddd; }
span.qst-no { width: 32px; height: 32px; line-height: 32px; background: #0274C3; color: #fff; border: 1px solid #0274C3; border-radius: 4px; text-align: center; vertical-align: middle; font-weight: 700; }

ul.qst-choice-list { min-height: 48px; padding: 10px; border: 1px solid #0274C355; border-radius: 10px; }
ul.qst-choice-list li { margin-top: 10px; }
ul.qst-choice-list li:first-child { margin-top: 0; }
span.choice-no { width: 20px; height: 20px; line-height: 20px; border: 1px solid #999; border-radius: 10px; text-align: center; vertical-align: middle; font-size: 14px; font-weight: 600; }
span.choice { margin-left: 4px; font-size: 14px; }

table.tbl-survey-asw th, table.tbl-survey-asw td { padding: 4px !important; min-width: 50px !important; }
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

					<div class="card shadow mb-4 survey-container">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 설문관리</h6>
						</div>
						<div style="padding: 20px 40px;">
							<p class="desc">* 설문항목은 총 10개까지 입력 가능하며 참여자가 한명이라도 있으면 수정은 불가합니다. (질문항목은 100자, 보기는 40자 입력 가능)</p>
							<ul class="survey-qst-list mt20">
								<li QST_NO="1" class="survey-qst-item d-flex align-items-start justify-content-around">
									<span class="qst-no">1</span>
									<div class="w90">
										<input type="text" class="common-input w100 qst-title" value="" placeholder="설문 질문항목을 입력하세요." maxlength="100" />
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
												<input type="text" class="common-input w60 input-choice" value="" placeholder="보기를 입력하세요." maxlength="40" />
												<button class="btn-sub btn-blue ml10">보기추가</button>
											</div>
										</div>
									</div>
								</li>
								<hr />
								<li QST_NO="2" class="survey-qst-item d-flex align-items-start justify-content-around">
									<span class="qst-no">2</span>
									<div class="w90">
										<input type="text" class="common-input w100 qst-title" value="" placeholder="설문 질문항목을 입력하세요." maxlength="100" />
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
												<input type="text" class="common-input w60 input-choice" value="" placeholder="보기를 입력하세요." maxlength="40" />
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
							<button class="btn-main btn-light-indigo btn-save" onclick="saveSurvey();">저장</button>
						</div>

					</div>

					<!-- 설문답변 영역 -->
					<div class="card shadow mb-4 answer-container">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 설문답변</h6>
						</div>
						<div style="padding: 20px 10px;">
							<table class="table-list tbl-survey-asw">
								<thead>
									<tr>
										<th>Seq.</th>
										<th>참여자명</th>
										<th>병원명</th>
										<th>과명</th>
										<!-- <th>연락처</th> -->
										<th>답변1</th>
										<th>답변2</th>
										<th>답변3</th>
										<th>답변4</th>
										<th>답변5</th>
										<th>답변6</th>
										<th>답변7</th>
										<th>답변8</th>
										<th>답변9</th>
										<th>답변10</th>
										<th>답변일시</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
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

	setBaseForm();

	$('body').on('change', 'select.qst-tp', function () {
		// alert($(this).val());
		if ($(this).val() === '주관식') {
			$(this).closest('li.survey-qst-item').find('div.qst-choice-container').hide();
			$(this).closest('li.survey-qst-item').find('ul.qst-choice-list').empty();
		} else {
			$(this).closest('li.survey-qst-item').find('div.qst-choice-container').show();
		}
	});

	getSurveyList(<?= $project['PRJ_SEQ'] ?>);
}

// 기본 설문항목 10개 추가
function setBaseForm () {
	let html = '';

	for (let i=1; i <= 10; i++) {
		html += '<li QST_NO="'+i+'" class="survey-qst-item d-flex align-items-start justify-content-around mt30 mb30">';
		html += '	<span class="qst-no">'+i+'</span>';
		html += '	<div class="w90">';
		html += '		<input type="text" class="common-input w100 qst-title" value="" placeholder="설문 질문항목을 입력하세요." maxlength="100" />';
		html += '		<div class="mt10">';
		html += '			<select class="common-select w20 qst-tp">';
		html += '				<option value="객관식" checked>객관식</option>';
		html += '				<option value="주관식">주관식</option>';
		html += '			</select>';
		html += '			<select class="common-select w20 qst-multi-yn">';
		html += '				<option value="0" checked>복수응답 불가</option>';
		html += '				<option value="1">복수응답 가능</option>';
		html += '			</select>';
		html += '		</div>';
		html += '		<div class="qst-choice-container mt30">';
		html += '			<h6>보기</h6>';
		html += '			<ul class="qst-choice-list">';
		html += '			</ul>';
		html += '			<div class="mt10">';
		html += '				<input type="text" class="common-input w60 input-choice" value="" placeholder="보기를 입력하세요." maxlength="40" />';
		html += '				<button class="btn-sub btn-blue ml10" onclick="javascript:addChoice('+i+');">보기추가</button>';
		html += '			</div>';
		html += '		</div>';
		html += '	</div>';
		html += '</li>';
		html += '<hr />';

		$('.survey-qst-list').empty();
		$('.survey-qst-list').append(html);
	}
}

// 보기 추가
function addChoice (qstNo) {
	// alert('addChoice');
	const choiceInputObj = $('li.survey-qst-item[QST_NO='+qstNo+'] input.input-choice');
	const lastChoiceNo = $('li.survey-qst-item[QST_NO='+qstNo+'] ul.qst-choice-list li:last-child span.choice-no').text() * 1;

	let html = '';

	html += '<li>';
	html += '	<span class="choice-no">'+(lastChoiceNo+1)+'</span>';
	html += '	<span class="choice">'+$(choiceInputObj).val()+'</span>';
	html += '</li>';

	$('li.survey-qst-item[QST_NO='+qstNo+'] ul.qst-choice-list').append(html);
	$(choiceInputObj).val('');
	$(choiceInputObj).focus();
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
				// 설문항목(질문) 목록
				const surveyQstList = data.surveyQstList;
				// 설문항목(질문) 객관식 보기 목록
				const surveyQstChoiceList = data.surveyQstChoiceList;
				// 설문 참여자 답변 목록
				const surveyAswList = data.surveyAswList;

				// 답변이 하나라도 있으면 수정버튼 disable
				if (surveyAswList.length > 0) {
					$('button.btn-save').attr('disabled', true);
				}

				// 우선 다 비워줌
				$('.survey-qst-list input.qst-title').val('');
				$('.survey-qst-list select.qst-tp').val('객관식');
				$('.survey-qst-list select.qst-multi-yn').val('0');
				$('.survey-qst-list ul.qst-choice-list').empty();
				$('table.tbl-survey-asw tbody').empty();

				surveyQstList.forEach(item => {
					$('.survey-qst-list li[QST_NO='+item.QST_NO+'] input.qst-title').val(item.QST_TITLE);
					$('.survey-qst-list li[QST_NO='+item.QST_NO+'] select.qst-tp').val(item.QST_TP);
					$('.survey-qst-list li[QST_NO='+item.QST_NO+'] select.qst-multi-yn').val(item.QST_MULTI_YN);

					if (item.QST_TP == '주관식') {
						$('.survey-qst-list li[QST_NO='+item.QST_NO+'] div.qst-choice-container').hide();
						$('.survey-qst-list li[QST_NO='+item.QST_NO+'] ul.qst-choice-list').hide();
					} else {
						$('.survey-qst-list li[QST_NO='+item.QST_NO+'] div.qst-choice-container').show();
					}
				});

				surveyQstChoiceList.forEach(item => {
					let html = '';

					html += '<li>';
					html += '	<span class="choice-no">'+item.CHOICE_NO+'</span>';
					html += '	<span class="choice">'+item.CHOICE+'</span>';
					html += '</li>';

					$('.survey-qst-list li[QST_NO='+item.QST_NO+'] ul.qst-choice-list').append(html);
				});

				surveyAswList.forEach((item) => {
					let html = '';

					html += '<tr>';
					html += '	<td>'+item.REQR_SEQ+'</td>';
					html += '	<td>'+item.REQR_NM+'</td>';
					html += '	<td>'+item.HSPTL_NM+'</td>';
					html += '	<td>'+item.SBJ_NM+'</td>';
					// html += '	<td>'+formatMobile(item.MBILNO)+'</td>';

					html += '	<td>'+item.AWS_1+'</td>';
					html += '	<td>'+item.AWS_2+'</td>';
					html += '	<td>'+item.AWS_3+'</td>';
					html += '	<td>'+item.AWS_4+'</td>';
					html += '	<td>'+item.AWS_5+'</td>';
					html += '	<td>'+item.AWS_6+'</td>';
					html += '	<td>'+item.AWS_7+'</td>';
					html += '	<td>'+item.AWS_8+'</td>';
					html += '	<td>'+item.AWS_9+'</td>';
					html += '	<td>'+item.AWS_10+'</td>';

					html += '	<td>'+item.ASW_DTTM+'</td>';
					html += '</tr>';

					$('table.tbl-survey-asw tbody').append(html);
				});


				if (surveyAswList.length > 0) {
					$('div.answer-container').show();
				} else {
					$('div.answer-container').hide();
				}
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
	// validation
	let valMsg = '';
	$('li.survey-qst-item').each(function () {
		// 객관식인데 보기가 없는거 체크
		if (!isEmpty($(this).find('input.qst-title').val())) {
			const qstNo = $(this).attr('QST_NO');
			if ($(this).find('select.qst-tp').val() === '객관식') {
				if ($(this).find('ul.qst-choice-list li').length === 0) {
					valMsg += `${qstNo}번 질문의 보기를 최소 1개 이상 입력해주세요.\n`;
					return;
				}
			}
		}
	});

	if (valMsg !== '') {
		alert(valMsg);
		return;
	}

	const surveyQstList = [];
	const surveyQstChoiceList = [];
	$('li.survey-qst-item').each(function () {
		// 설문항목(질문)에 내용이 있으면
		if (!isEmpty($(this).find('input.qst-title').val())) {
			const qstNo = $(this).attr('QST_NO');

			// 설문항목 설정
			const surveyQstItem = {
				PRJ_SEQ: <?= $project['PRJ_SEQ'] ?>,
				QST_NO: qstNo,
				QST_TITLE: $(this).find('input.qst-title').val(),
				QST_TP: $(this).find('select.qst-tp').val(),
				QST_MULTI_YN: $(this).find('select.qst-multi-yn').val(),
				REGR_ID: '<?= $email ?>'
			}
			surveyQstList.push(surveyQstItem);

			// 설문항목에 딸린 객관식 보기 설정
			if ($(this).find('select.qst-tp').val() === '객관식') {
				$(this).find('ul.qst-choice-list li').each(function () {
					const surveyQstChoiceItem = {
						PRJ_SEQ: <?= $project['PRJ_SEQ'] ?>,
						QST_NO: qstNo,
						CHOICE_NO: $(this).find('span.choice-no').text(),
						CHOICE: $(this).find('span.choice').text()
					}

					surveyQstChoiceList.push(surveyQstChoiceItem);
				});
			}
		}
	});
	// showSpinner();
	// console.log(`surveyQstList - ${JSON.stringify(surveyQstList)}`);
	// console.log(`surveyQstChoiceList - ${JSON.stringify(surveyQstChoiceList)}`);

	$.ajax({
		type: 'POST',
		url: '/project/saveSurvey/<?= $project['PRJ_SEQ'] ?>',
		dataType: 'json',
		cache: false,
		data: {
			surveyQstList,
			surveyQstChoiceList
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
