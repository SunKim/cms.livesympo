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


<!-- START) 메인 css -->
<style type="text/css">
header { padding: 1rem 0; }
div { text-align: center; }
form { display: inline-block; width: 100%; max-width: 100%;}
p { margin: 0 !important; }

/* 로고 영역 */
div.logo-container { text-align: left; }

.question-container { width: 100%; }
ul.question-list p { margin: 0 !important; }
ul.question-list p.regr { color: #3f65ccbb; }
ul.question-list p.reg-dttm { font-size: 14px; color: #bbb; }
textarea { padding: 10px 14px; border: 1px solid #bbb; border-radius: 4px; color: #999; }

/* 768px 이하 -> 모바일 */
@media (max-width: 768px) {
    div.logo-container { padding: 0 1rem; }
    img.logo { width: 30%; }
}

/* 768~1200 -> 태블릿 */
@media (min-width: 769px) {
    img.logo { width: 20%; }
}

/* 1200px 이상 -> PC */
@media (min-width: 1200px) {
    img.logo { width: 20%; }
}

/* li.approved p.regr { color: #3f65cc; }
ul.question-list p.reg-dttm { font-size: 14px; color: #999; }
li.approved textarea { border: 1px solid #3f65cc99; border-radius: 4px; color: #666; } */
</style>
<!-- END) 메인 css -->

</head>

<body>
<div class="container">
    <header>
        <div class="logo-container">
            <img class="logo" src="/images/logo/logo_type1.png" />
        </div>
    </header>
</div>
<div class="container">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary tl"><?= $project['PRJ_TITLE'] ?> 질문 목록</h6>
		</div>
		<div class="card-body d-flex justify-content-between align-items-start">
			<div class="question-container">
				<div class="d-flex justify-content-between align-items-center">
					<p class="desc">
						* <span id="refresh-term">30</span>초에 한번씩 갱신됩니다.
					</p>
					<button class="btn-sub btn-blue" onclick="getQuestionList(<?= $project['PRJ_SEQ'] ?>);">수동 새로고침</button>
				</div>
				<hr />
				<ul class="question-list mt10">
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- 하단 footer -->
<?php include_once APPPATH.'Views/template/footer.php'; ?>

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
const REFRESH_TERM = 10 * 1000;

var refreshIntv;

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

	$.ajax({
		type: 'POST',
		url: '/project/getQuestionList',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq,
			aprvYn: 1,
			orderBy: 'APRV_DTTM'
		},

		success: function(data) {
			// console.log(data)
			if ( data.resCode == '0000' ) {
				const list = data.list;

				let html = '';
				list.forEach(item => {
					html += '<li class="mb20">';
					html += '	<div class="d-flex justify-content-between align-items-center">';
					html += '		<p class="regr">';
					html += '			<span>'+(item.FAKE_YN == 0 ? item.REQR_NM : item.FAKE_NM)+'</span>';
					// html += '			<span>('+maskPhone(formatMobile(item.MBILNO))+')</span>';
					// html += '			<span>'+item.HSPTL_NM+'</span>';
					// html += '			<span>'+item.SUBJ_NM+'</span>';
					html += '		</p>';
					html += '		<p class="reg-dttm">'+item.REG_DTTM+'</p>';
					html += '	</div>';
					html += '	<textarea maxlength="400" rows="4" class="w100 mt10 mb10" readonly>'+item.QST_DESC+'</textarea>';
					html += '</li>';
				});

				$('.question-list').empty();
				$('.question-list').append(html);
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

	// unload 되기 전 interval clear
	$(window).on('beforeunload', function() {
		clearInterval(refreshIntv);
	});
});

</script>

</body>

</html>
