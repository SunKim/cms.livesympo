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

	<title>Live Sympo(설문핸들러) - <?= $project['PRJ_TITLE'] ?></title>

	<!-- stylesheets -->
	<link href="/css/sun.common.20200914.css" rel="stylesheet">
	<link href="/css/cms.livesympo.css" rel="stylesheet">

	<!-- loading spinner를 위한 font-awesome. <span class="fa fa-spinner fa-spin fa-3x". ></span>. 아이콘 참고 - https://fontawesome.com/v4.7.0/icons/ -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Custom styles for this template-->
	<link href="/css/sb-admin-2.css" rel="stylesheet">

	<!-- Custom fonts for this template-->
	<link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<!-- START) 메인 css -->
	<style type="text/css">
	header {
		padding: 1rem 0;
	}

	button {
		border: 0;
		color: #fff;
	}

	div.container {
		padding-left: 0 !important;
		padding-right: 0 !important;
		text-align: center;
	}

	/* 로고 영역 */
	div.logo-container {
		display: flex;
		justify-content: center;
		align-items: center;
		margin-bottom: 24px;
	}

	input::placeholder {
		color: #ddd;
	}

	span.qst-no {
		width: 32px;
		height: 32px;
		line-height: 32px;
		background: #0274C3;
		color: #fff;
		border: 1px solid #0274C3;
		border-radius: 4px;
		text-align: center;
		vertical-align: middle;
		font-weight: 700;
	}

	ul.qst-choice-list {
		min-height: 48px;
		padding: 10px;
		border: 1px solid #0274C355;
		border-radius: 10px;
	}

	ul.qst-choice-list li {
		margin-top: 10px;
	}

	ul.qst-choice-list li:first-child {
		margin-top: 0;
	}

	span.choice-no {
		width: 20px;
		height: 20px;
		line-height: 20px;
		border: 1px solid #999;
		border-radius: 10px;
		text-align: center;
		vertical-align: middle;
		font-size: 14px;
		font-weight: 600;
	}

	span.choice {
		margin-left: 4px;
		font-size: 14px;
	}

	/* 설문 form 영역 좌측정렬 */
	section.tl {
		text-align: left;
	}

	.survey-qst-item {
		text-align: left;
	}

	.survey-qst-item div {
		text-align: left;
	}

	/* 768px 이하 -> 모바일 */
	@media (max-width: 768px) {
		div.logo-container {
			padding: 0 1rem;
		}

		img.logo {
			width: 100%;
		}
	}

	/* 768~1200 -> 태블릿 */
	@media (min-width: 769px) {
		img.logo {
			width: 100%;
		}
	}

	/* 1200px 이상 -> PC */
	@media (min-width: 1200px) {
		img.logo {
			width: 100%;
		}
	}
	</style>
	<!-- END) 메인 css -->

</head>


<body>
	<div class="container">
		<div class="logo-container">
			<img class="logo" src="<?= $project['MDRTOR_IMG_URL'] ?>" />
		</div>
		<section class="tl">
			<div style="padding: 20px 40px;">
				<p class="desc">* 설문항목이 없거나 삭제할 경우 사용자에게 설문참여 버튼이 보이지 않습니다.</p>
				<p class="desc">* 설문항목은 총 10개까지 입력 가능합니다. (질문항목은 100자, 보기는 40자 입력 가능)</p>
				<ul class="survey-qst-list mt20">
				</ul>
			</div>

			<div class="d-flex align-items-center justify-content-between pa20">
				<button class="btn-main btn-red mr10" onclick="deleteSurvey();">설문삭제</button>
				<button class="btn-main btn-light-indigo btn-save" onclick="saveSurvey();">저장</button>
			</div>
		</section>
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

	<script src="/js/sun.common.20200914.js"></script>

	<!-- 메인 script -->
	<script language="javascript">
	// 초기화
	function fnInit() {
		setBaseForm();

		$('body').on('change', 'select.qst-tp', function() {
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
	function setBaseForm() {
		let html = '';

		for (let i = 1; i <= 10; i++) {
			html += '<li QST_NO="' + i +
				'" class="survey-qst-item d-flex align-items-start justify-content-around mt30 mb30">';
			html += '	<span class="qst-no">' + i + '</span>';
			html += '	<div class="w90">';
			html +=
				'		<input type="text" class="common-input w100 qst-title" value="" placeholder="설문 질문항목을 입력하세요." maxlength="100" />';
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
			html +=
				'				<input type="text" class="common-input w60 input-choice" value="" placeholder="보기를 입력하세요." maxlength="40" />';
			html += '				<button class="btn-sub btn-blue ml10" onclick="javascript:addChoice(' + i + ');">보기추가</button>';
			html += '				<button class="btn-sub btn-white ml10" onclick="javascript:removeChoice(' + i +
				');">최근보기 삭제</button>';
			html += '			</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</li>';
			html += '<hr />';
		}

		$('.survey-qst-list').empty();
		$('.survey-qst-list').append(html);
	}

	// 보기 추가
	function addChoice(qstNo) {
		const choiceInputObj = $('li.survey-qst-item[QST_NO=' + qstNo + '] input.input-choice');
		const lastChoiceNo = $('li.survey-qst-item[QST_NO=' + qstNo + '] ul.qst-choice-list li:last-child span.choice-no')
			.text() * 1;

		if (isEmpty($(choiceInputObj).val())) {
			alert('보기 내용을 입력해주세요.');
			$(choiceInputObj).focus();
			return;
		}

		let html = '';

		html += '<li>';
		html += '	<span class="choice-no">' + (lastChoiceNo + 1) + '</span>';
		html += '	<span class="choice">' + $(choiceInputObj).val() + '</span>';
		html += '</li>';

		$('li.survey-qst-item[QST_NO=' + qstNo + '] ul.qst-choice-list').append(html);
		$(choiceInputObj).val('');
		$(choiceInputObj).focus();
	}

	// 최근보기 삭제
	function removeChoice(qstNo) {
		const lastChoiceNo = $('li.survey-qst-item[QST_NO=' + qstNo + '] ul.qst-choice-list li:last-child span.choice-no')
			.text() * 1;

		if (confirm(`${lastChoiceNo}번 보기를 삭제하시겠습니까?`)) {
			$('li.survey-qst-item[QST_NO=' + qstNo + '] ul.qst-choice-list li:last-child').remove();
		}
	}

	// 설문목록 (설문 질문목록, 보기목록) 불러오기
	function getSurveyList(prjSeq) {
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
				if (data.resCode == '0000') {
					// 설문항목(질문) 목록
					const surveyQstList = data.surveyQstList;
					// 설문항목(질문) 객관식 보기 목록
					const surveyQstChoiceList = data.surveyQstChoiceList;

					// 우선 다 비워줌
					$('.survey-qst-list input.qst-title').val('');
					$('.survey-qst-list select.qst-tp').val('객관식');
					$('.survey-qst-list select.qst-multi-yn').val('0');
					$('.survey-qst-list ul.qst-choice-list').empty();

					// 설문항목 설정
					surveyQstList.forEach(item => {
						$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] input.qst-title').val(item.QST_TITLE);
						$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] select.qst-tp').val(item.QST_TP);
						$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] select.qst-multi-yn').val(item.QST_MULTI_YN);

						if (item.QST_TP == '주관식') {
							$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] div.qst-choice-container').hide();
							$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] ul.qst-choice-list').hide();
						} else {
							$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] div.qst-choice-container').show();
						}
					});

					// 설문항목에 딸린 객관식 보기 설정
					surveyQstChoiceList.forEach(item => {
						let html = '';

						html += '<li>';
						html += '	<span class="choice-no">' + item.CHOICE_NO + '</span>';
						html += '	<span class="choice">' + item.CHOICE + '</span>';
						html += '</li>';

						$('.survey-qst-list li[QST_NO=' + item.QST_NO + '] ul.qst-choice-list').append(html);
					});
				} else {
					alert('프로젝트 설문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):' + data.resCode +
						'\n메세지(resMsg):' + data.resMsg);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.error(xhr);
				alert('프로젝트 설문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:' + xhr.status + '\n메세지:' + thrownError);
			},
			complete: function() {
				hideSpinner();
			}
		});
	}

	// 설문 저장
	function saveSurvey() {
		// validation
		let valMsg = '';
		$('li.survey-qst-item').each(function() {
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
		$('li.survey-qst-item').each(function() {
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
					REGR_ID: 'survey_handler'
				}
				surveyQstList.push(surveyQstItem);

				// 설문항목에 딸린 객관식 보기 설정
				if ($(this).find('select.qst-tp').val() === '객관식') {
					$(this).find('ul.qst-choice-list li').each(function() {
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

		showSpinner();

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
				if (data.resCode == '0000') {
					alert('설문을 저장했습니다.');
					getSurveyList(<?= $project['PRJ_SEQ'] ?>);
				} else {
					alert('설문정보를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):' + data.resCode + '\n메세지(resMsg):' + data
						.resMsg);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.error(xhr);
				alert('설문정보를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:' + xhr.status + '\n메세지:' + thrownError);
			},
			complete: function() {
				hideSpinner();
			}
		});
	}

	// 설문 삭제
	function deleteSurvey() {
		if (confirm('모든 설문 문항 및 보기를 삭제하시겠습니까?\n기존 답변이 있을 경우 답변도 삭제되며 사용자에게 설문참여 버튼이 보이지 않게 됩니다.\n삭제하시겠습니까?')) {
			showSpinner();
			
			$.ajax({
				type: 'POST',
				url: '/project/deleteSurvey/<?= $project['PRJ_SEQ'] ?>',
				dataType: 'json',
				cache: false,
				success: function(data) {
					if (data.resCode == '0000') {
						alert('설문을 삭제했습니다.');
						getSurveyList(<?= $project['PRJ_SEQ'] ?>);
					} else {
						alert('설문을 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):' + data.resCode + '\n메세지(resMsg):' + data
							.resMsg);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.error(xhr);
					alert('설문을 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:' + xhr.status + '\n메세지:' + thrownError);
				},
				complete: function() {
					hideSpinner();
				}
			});
		}
	}

	$(document).ready(function() {
		fnInit();

		//submit 되기 전 처리
		$('form').submit(function(e) {

		});
	});
	</script>

</body>

</html>