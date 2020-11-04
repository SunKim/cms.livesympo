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

<title>Live Sympo 질문관리</title>

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
.question-container { width: calc(100% - 420px - 40px); }
ul.question-list p { margin: 0 !important; }
ul.question-list p.regr { color: #999; }
ul.question-list p.reg-dttm { font-size: 14px; color: #bbb; }

textarea { border: 1px solid #eee; color: #bbb; }

li.approved p.regr { color: #3f65cc; }
ul.question-list p.reg-dttm { font-size: 14px; color: #999; }
li.approved textarea { border: 1px solid #3f65cc99; border-radius: 4px; color: #666; }
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
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 질문관리</h6>
						</div>
						<div class="card-body d-flex justify-content-between align-items-start">
							<iframe src="<?= $livesympoUrl ?>/<?= $project['PRJ_TITLE_URI'] ?>"></iframe>
							<div class="question-container">
								<div class="d-flex justify-content-between align-items-center">
									<p class="desc">
										* <span id="refresh-term">30</span>초에 한번씩 갱신됩니다.
									</p>
									<button class="btn-sub btn-blue" onclick="getQuestionList(<?= $project['PRJ_SEQ'] ?>);">수동 새로고침</button>
								</div>
								<hr />
								<div>
									<div class="d-flex justify-content-between align-items-center">
										<p>
											질문자명 : <input type="text" id="fakeNm" name="fakeNm" class="common-input w60" />
										</p>
										<span>&nbsp;</span>
										<button class="btn-sub btn-sky" onclick="fakeQuestion(<?= $project['PRJ_SEQ'] ?>);">질문등록</button>
									</div>
									<textarea id="qstDesc" name="qstDesc" maxlength="400" rows="4" class="w100 mt10 mb10"></textarea>
								</div>
								<hr />
								<ul class="question-list mt10">
								</ul>
							</div>
						</div>

						<div class="d-flex align-items-center justify-content-between pa20">
							<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
							<span>&nbsp;</span>
							<button class="btn-main btn-light-indigo ml10" onclick="downloadExcel();">엑셀저장</button>
						</div>
					</div>

					<!-- 엑셀다운로드용 테이블 -->
					<table style="display: none;" id="tbl-excel">
					<!-- <table id="tbl-excel"> -->
						<thead>
							<tr>
								<th>Seq.</th>
								<th>질문내용</th>
								<th>이름</th>
								<th>승인여부</th>
								<th>등록일시</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>

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
const ENT_INFO_CNT = 8;
const REFRESH_TERM = 10 * 1000;

var refreshIntv;

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

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
			aprvYn: 0,
			orderBy: 'REG_DTTM'
		},

		success: function(data) {
			// console.log(data)
			if ( data.resCode == '0000' ) {
				const list = data.list;

				let html = '';
				let html2 = '';
				list.forEach(item => {
					html += '<li class="mb20 '+(item.APRV_YN == 1 ? 'approved' : '')+'">';
					html += '	<div class="d-flex justify-content-between align-items-center">';
					html += '		<p class="regr">';
					html += '			<span>'+(item.FAKE_YN == 0 ? item.REQR_NM : item.FAKE_NM)+'</span>';
					html += '			<span>'+(item.FAKE_YN == 0 && item.MBILNO ? `(${formatMobile(item.MBILNO)})` : '')+'</span>';
					html += '			<span>'+(item.FAKE_YN == 0 && item.ENT_INFO_EXTRA_VAL_1 ? item.ENT_INFO_EXTRA_VAL_1 : '')+'</span>';
					html += '			<span>'+(item.FAKE_YN == 0 && item.ENT_INFO_EXTRA_VAL_2 ? item.ENT_INFO_EXTRA_VAL_2 : '')+'</span>';
					if (item.APRV_YN == 0) {
						html += '			<button class="btn-sub btn-light-indigo ml5" onclick="approve('+item.QST_SEQ+', 1);">승인</button>';
					} else {
						html += '			<button class="btn-sub btn-white ml5" onclick="approve('+item.QST_SEQ+', 0);">승인취소</button>';
					}
					html += '		</p>';
					html += '		<p class="reg-dttm">'+item.REG_DTTM+'</p>';
					html += '	</div>';
					html += '	<textarea maxlength="400" rows="4" class="w100 mt10 mb10" readonly>'+item.QST_DESC+'</textarea>';
					html += '</li>';

					// 엑셀다운로드용 테이블에도
					html2 += '<tr>';
					html2 += '	<td>'+item.QST_SEQ+'</td>';
					html2 += '	<td>'+item.QST_DESC+'</td>';
					html2 += '	<td>'+(item.FAKE_YN == 0 ? item.REQR_NM : item.FAKE_NM)+'</td>';
					html2 += '	<td>'+(item.APRV_YN == 1 ? 'Y' : 'N')+'</td>';
					html2 += '	<td>'+item.REG_DTTM+'</td>';
					html2 += '</tr>';
				});

				$('.question-list').empty();
				$('.question-list').append(html);

				$('#tbl-excel tbody').empty();
				$('#tbl-excel tbody').append(html2);
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

// 질문 승인
function approve (qstSeq, aprvYn) {
	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/project/approveQuestion',
		dataType: 'json',
		cache: false,
		data: {
			qstSeq,
			aprvYn
		},

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				alert('질문 승인여부를 처리했습니다.');
				getQuestionList(<?= $project['PRJ_SEQ'] ?>);
			} else {
				alert('질문 승인여부를 처리하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('질문 승인여부를 처리하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 페이크질문 등록
function fakeQuestion (prjSeq) {
	// validation
	if (isEmpty($('#fakeNm').val())) {
		alert('질문자명을 입력해주세요.');
		$('#fakeNm').focus();
		return;
	}
	if (isEmpty($('#qstDesc').val())) {
		alert('질문내용을 입력해주세요.');
		$('#qstDesc').focus();
		return;
	}

	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/project/fakeQuestion',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq,
			fakeNm: $('#fakeNm').val(),
			qstDesc: $('#qstDesc').val(),
		},

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				alert('질문을 등록했습니다.');
				getQuestionList(<?= $project['PRJ_SEQ'] ?>);
			} else {
				alert('질문 정보를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('질문 정보를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 엑셀저장
function downloadExcel () {
	const today = new Date();
	const todayShort = today.toJSON().slice(0, 10).split`-`.join``;
	// excelModalExwide1('엑셀저장', '엑셀저장 버튼을 클릭하세요.', 'tbl-reqr-list', '<?= $project['PRJ_TITLE'] ?>_사전등록자');
	downloadTableToCsv('tbl-excel', '<?= $project['PRJ_TITLE'] ?>_QnA_'+todayShort);
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
