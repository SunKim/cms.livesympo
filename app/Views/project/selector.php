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

	<title>Live Sympo(Selector) - <?= $project['PRJ_TITLE'] ?></title>

	<!-- stylesheets -->
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/sun.common.20200914.css" rel="stylesheet">
	<link href="/css/cms.livesympo.css" rel="stylesheet">

	<!-- loading spinner를 위한 font-awesome. <span class="fa fa-spinner fa-spin fa-3x". ></span>. 아이콘 참고 - https://fontawesome.com/v4.7.0/icons/ -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Bootstrap-select. cf) https://silviomoreto.github.io/bootstrap-select -->
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

	<!-- Custom styles for this template-->
	<link href="/css/sb-admin-2.css" rel="stylesheet">

	<!-- Custom fonts for this template-->
	<link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<!-- Swiperjs. cf) https://swiperjs.com/get-started -->
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

	<!-- START) 메인 css -->
	<style type="text/css">
	header {
		padding: 1rem 0;
	}

	div {
		text-align: center;
	}

	button {
		border: 0;
		color: #fff;
	}

	div.container {
		padding-left: 0 !important;
		padding-right: 0 !important;
	}

	/* 로고 영역 */
	div.logo-container {
		display: flex;
		justify-content: center;
		align-items: center;
		margin-bottom: 24px;
	}

	ul.question-list p {
		margin: 0 !important;
	}

	ul.question-list p.regr {
		color: #999;
	}

	ul.question-list .reg-dttm {
		font-size: 14px;
		color: #bbb;
	}

	ul.question-list span.selected {
		font-size: 14px;
		font-weight: 700;
		color: #eee;
		background: #ad3213;
		padding: 8px 12px;
		border-radius: 8px;
	}

	#cnt {
		font-weight: 700;
		color: #c2133399;
	}

	textarea {
		border: 1px solid #eee;
		color: #bbb;
	}

	textarea.selected {
		border: 1px solid #5B76E2;
		color: #666;
	}

	span#refresh-term {
		display: none;
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
			<!-- <img class="logo" src="<?= $project['MDRTOR_IMG_URL'] ?>" /> -->
			<img class="logo" src="https://cms.livesympo.kr/uploads/project/62/MDRTOR_IMG_62_lRQuvs.jpg" />
		</div>
		<section class="tl">
			<p class="cnt-desc">총 <span id="cnt">0</span>개의 질문이 있습니다. <span id="refresh-term">(10초에 한번씩 자동갱신)</span></p>
			<ul class="question-list mt10">
			</ul>
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

	<!-- Swiperjs. cf) https://swiperjs.com/get-started -->
	<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

	<script src="/js/sun.common.20200914.js"></script>

	<!-- 메인 script -->
	<script language="javascript">
	// const REFRESH_TERM = 10 * 1000
	const REFRESH_TERM = 4 * 1000

	var refreshIntv
	var swiper
	var qstList = []

	// 초기화
	function fnInit() {
		$('#refresh-term').text(REFRESH_TERM / 1000);

		getQuestionList(<?= $project['PRJ_SEQ'] ?>);

		refreshIntv = setInterval(function() {
			getQuestionList(<?= $project['PRJ_SEQ'] ?>);
		}, REFRESH_TERM);

		$('#refresh-term').text(REFRESH_TERM / 1000);
	}

	// 질문목록 불러오기
	function getQuestionList(prjSeq) {
		// showSpinner();
		console.log(`Refreshing`);

		$.ajax({
			type: 'POST',
			url: '/project/getQuestionList',
			dataType: 'json',
			cache: false,
			data: {
				prjSeq,
				aprvYn: 0,
				orderBy: 'REG_DTTM'
			},

			success: function(data) {
				console.log(data)
				if (data.resCode == '0000') {
					const list = data.list;
					let cnt = 0

					let html = '';
					list.forEach(item => {
						if (item.APRV_YN == 1) {
							cnt++

							html += `<li class="mb20 approved">`;
							html += `	<div class="d-flex justify-content-between align-items-center">`;
							html += `		<p class="regr">`;

							// 							html += `			<span>${item.FAKE_YN == 0 ? item.REQR_NM : item.FAKE_NM}</span>`;
							// 							html +=
							// 								`			<span>${item.FAKE_YN == 0 && item.MBILNO ? `(${formatMobile(simplifyMobile(item.MBILNO))})` : ''}</span>`;
							// 							html += `			<span>${item.FAKE_YN == 0 && item.ENT_INFO_EXTRA_VAL_1 ? item.ENT_INFO_EXTRA_VAL_1 :
							// ''}</span>`;
							// 							html += `			<span>${item.FAKE_YN == 0 && item.ENT_INFO_EXTRA_VAL_2 ? item.ENT_INFO_EXTRA_VAL_2 :
							// ''}</span>`;

							if (item.SEL_YN == 0) {
								html +=
									`			<button class="btn-light-indigo" onclick="selectQst(${item.QST_SEQ});">Select</button>`;
							}
							html += `		</p>`;
							html += `		<div>`;
							html += `			${item.SEL_YN == 1 ? '<span class="selected mr10">Selected</span>' : ''}`;
							// html += `			<span class="reg-dttm">${item.REG_DTTM}</span>`;
							html += `		</div>`;
							html += `	</div>`;
							html +=
								`	<textarea maxlength="400" rows="4" class="w100 mt10 mb10 ${item.SEL_YN == 1 ? 'selected': ''}" readonly>${item.QST_DESC}</textarea>`;
							html += `</li>`;
						}
					});

					$('.question-list').empty();
					$('.question-list').append(html);
					$('#cnt').text(cnt);
				} else {
					alert('프로젝트 질문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):' + data.resCode +
						'\n메세지(resMsg):' + data.resMsg);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.error(xhr);
				alert('프로젝트 질문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:' + xhr.status + '\n메세지:' +
					thrownError);
			},
			complete: function() {
				// hideSpinner();
			}
		});
	}

	// 질문 선택
	function selectQst(qstSeq) {
		showSpinner();

		$.ajax({
			type: 'POST',
			url: '/project/selectQuestion',
			dataType: 'json',
			cache: false,
			data: {
				prjSeq: <?= $project['PRJ_SEQ'] ?>,
				qstSeq
			},

			success: function(data) {
				// console.log(data);
				if (data.resCode == '0000') {
					alert('해당 질문을 선택처리 처리했습니다.');
					getQuestionList(<?= $project['PRJ_SEQ'] ?>);
				} else {
					alert('질문 선택을 처리하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):' + data.resCode + '\n메세지(resMsg):' +
						data.resMsg);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.error(xhr);
				alert('질문 선택을 처리하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:' + xhr.status + '\n메세지:' + thrownError);
			},
			complete: function() {
				hideSpinner();
			}
		});
	}

	$(document).ready(function() {
		fnInit();

		//submit 되기 전 처리
		$('form').submit(function(e) {});

		// unload 되기 전 interval clear
		$(window).on('beforeunload', function() {
			clearInterval(refreshIntv);
		});
	});
	</script>

</body>

</html>