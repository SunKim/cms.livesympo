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

<title>Live Sympo - <?= $project['PRJ_TITLE'] ?></title>

<!-- stylesheets -->
<link href="/css/bootstrap.min.css" rel="stylesheet">
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

<!-- Swiperjs. cf) https://swiperjs.com/get-started -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<!-- START) 메인 css -->
<style type="text/css">
body { padding: 20px; }
div.container-fluid { height: calc(100vh - 40px); background-image: url(<?= $project['MDRTOR_IMG_URL'] ?>); background-repeat: no-repeat; background-size: 100% }
div.no-qst h4 { position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); font-size: 48px; color: <?= $project['MDRTOR_FONT_COLR'] ?>; }

.swiper-container { width: 100%; height: 100%; }
.swiper-slide {
  text-align: center;
  font-size: 48px;
  background: #fff0;
  padding: 100px;
  color: <?= $project['MDRTOR_FONT_COLR'] ?>;

  /* Center slide text vertically */
  display: -webkit-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  -webkit-align-items: center;
  align-items: center;
}
.swiper-pagination-fraction { color: <?= $project['MDRTOR_PAGE_FONT_COLR'] ?>;font-size: <?= $project['MDRTOR_PAGE_FONT_SIZE'] ?>px; font-weight: bold; }
.swiper-button-prev, .swiper-button-next { --swiper-navigation-color: <?= $project['MDRTOR_ARROW_COLR'] ?>; }
</style>
<!-- END) 메인 css -->

</head>

<body>
<div class="container-fluid">
	<!-- <p class="desc">
		* <span id="refresh-term">30</span>초에 한번씩 갱신됩니다.
	</p> -->

	<!-- Slider main container -->
	<div class="swiper-container">
		<!-- Additional required wrapper -->
		<div class="swiper-wrapper">
			<!-- Slides -->
			<!--
			<div class="swiper-slide">Slide 1</div>
			<div class="swiper-slide">Slide 2</div>
			<div class="swiper-slide">Slide 3</div>
			 -->
		</div>
		<!-- If we need pagination -->
		<div class="swiper-pagination"></div>

		<!-- If we need navigation buttons -->
		<div class="swiper-button-prev"></div>
		<div class="swiper-button-next"></div>

		<!-- If we need scrollbar -->
		<!-- <div class="swiper-scrollbar"></div> -->
	</div>

	<div class="no-qst">
		<h4>현재 등록된 질문이 없습니다.</h4>
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

<!-- Swiperjs. cf) https://swiperjs.com/get-started -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">
// const REFRESH_TERM = 10 * 1000
const REFRESH_TERM = 10 * 1000

var refreshIntv
var swiper
var qstList = []

// 초기화
function fnInit () {

	getQuestionList(<?= $project['PRJ_SEQ'] ?>);

	refreshIntv = setInterval(function () {
        getQuestionList(<?= $project['PRJ_SEQ'] ?>);
    }, REFRESH_TERM);

	$('#refresh-term').text(REFRESH_TERM/1000);
}

// 질문목록 불러오기
function getQuestionList (prjSeq) {
	// showSpinner();
	// alert('getQuestionList')

	$.ajax({
		type: 'POST',
		url: '/project/getQuestionList',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq,
			aprvYn: 1,
			orderBy: 'APRV_DTTM ASC'
		},

		success: function(data) {
			// console.log(data)
			if ( data.resCode == '0000' ) {
				const latestQstList = data.list;

				if (latestQstList.length > 0) {
					$('div.no-qst').hide()
				}

				// 새로 추가된 것만 swiper에 append
				const cntNew = latestQstList.length - qstList.length
				for (let i = 0; i < cntNew; i++) {
					item = latestQstList[latestQstList.length - cntNew + i]

					swiper.appendSlide([
						`<div class="swiper-slide">${item.QST_DESC.replace(/\r?\n/g, '<br />')}</div>`
					])
				}

				qstList = latestQstList
			} else {
				alert('프로젝트 질문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 질문 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			// hideSpinner();
		}
	});
}

$(document).ready(function () {
	fnInit();

	//submit 되기 전 처리
	$('form').submit(function(e) {

	});

	// swiper init. cf) https://swiperjs.com/demos
	swiper = new Swiper('.swiper-container', {
		// Optional parameters
		// direction: 'vertical',
		// loop: true,

		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			type: 'fraction',
		},

		// Navigation arrows
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	});

	// unload 되기 전 interval clear
	$(window).on('beforeunload', function() {
		clearInterval(refreshIntv);
	});
});

</script>

</body>

</html>
