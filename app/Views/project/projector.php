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

	<title>Live Sympo(Projector) - <?= $project['PRJ_TITLE'] ?></title>

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
	html,
	body {
		height: 100%;
		margin: 0;
		padding: 0;
	}

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
		height: 100vh;
		display: flex;
		flex-direction: column;
	}

	/* 로고 영역 */
	div.logo-container {
		display: flex;
		justify-content: center;
		align-items: center;
		margin-bottom: 24px;
		flex-shrink: 0;
	}

	.question-container {
		display: flex;
		justify-content: center;
		align-items: center;
		flex: 1;
		width: 100%;
		background-color: #fff;
	}

	#selected-question {
		white-space: pre-wrap;
		word-wrap: break-word;
		max-width: 90%;
		line-height: 1.6;
		font-size: 48px;
		color: #333;
		text-align: center;
		font-weight: 700;
		font-family: 'Nunito', sans-serif;
		text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
		letter-spacing: 1px;
		text-transform: uppercase;
		text-decoration: none;
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
		<section class="question-container">
			<h4 id="selected-question">No Questions at the moment</h4>
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
	var selectedQuestion = ''

	// 초기화
	function fnInit() {
		getSelectedQuestion(<?= $project['PRJ_SEQ'] ?>);

		refreshIntv = setInterval(function() {
			getSelectedQuestion(<?= $project['PRJ_SEQ'] ?>);
		}, REFRESH_TERM);

	}

	// 선택된 질문 1건 가져오기
	function getSelectedQuestion(prjSeq) {
		// showSpinner();
		console.log(`Getting selected question`);

		$.ajax({
			type: 'POST',
			url: '/project/getSelectedQuestion',
			dataType: 'json',
			cache: false,
			data: {
				prjSeq
			},

			success: function(data) {
				console.log(data)
				if (data.resCode == '0000') {
					const selectedQuestion = data.selectedQuestion;
					if (selectedQuestion) {
						$('#selected-question').text(selectedQuestion.QST_DESC);
					} else {
						$('#selected-question').text('No Questions at the moment');
					}
				} else {
					alert('선택된 질문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):' + data.resCode +
						'\n메세지(resMsg):' + data.resMsg);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.error(xhr);
				alert('선택된 질문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:' + xhr.status + '\n메세지:' +
					thrownError);
			},
			complete: function() {
				// hideSpinner();
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