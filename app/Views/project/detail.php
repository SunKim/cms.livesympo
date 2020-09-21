<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="last-modified" content="mon,14 sep 2020 19:38:00">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />

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

<!-- START) 메인 css -->
<style type="text/css">
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
							<table class="table-list">
								<colgroup>
									<col width="20%" />
									<col width="80%" />
								</colgroup>
								<tbody>
									<tr>
										<th>프로젝트 타이틀</th>
										<td class="tl">
											<input type="text" id="prj-title" class="common-input w90" />
										</td>
									</tr>
									<tr>
										<th>프로젝트 URI</th>
										<td class="tl">
											<input type="text" id="prj-title-uri" class="common-input w90" placeholder="drug-a-20200922 형식으로 입력해주세요." />
										</td>
									</tr>
									<tr>
										<th>스트리밍 URL</th>
										<td class="tl">
											<input type="text" id="stream-url" class="common-input w90" placeholder="https://stream.com/xxxx 형식으로 입력해주세요." />
										</td>
									</tr>
									<tr>
										<th>메인 이미지</th>
										<td class="tl">
											<input type="file" id="main-img" class="common-input w90" />
										</td>
									</tr>
									<tr>
										<th>아젠다 이미지</th>
										<td class="tl">
											<input type="file" id="agenda-img" class="common-input w90" />
										</td>
									</tr>
									<tr>
										<th>푸터 이미지</th>
										<td class="tl">
											<input type="file" id="footer-img" class="common-input w90" />
										</td>
									</tr>
									<tr>
										<th>테마 색상</th>
										<td class="tl">
											<input type="text" id="ent-thme-color" class="common-input w90" />
										</td>
									</tr>
									<tr>
										<th>사전등록버튼 색상</th>
										<td class="tl">
											<input type="text" id="appl-btn-color" class="common-input w90" />
										</td>
									</tr>
									<tr>
										<th>아젠다 추가페이지</th>
										<td class="tl">
											<select class="common-select w90">
												<option value="0">N</option>
												<option value="1">Y</option>
											</select>
										</td>
									</tr>
									<tr>
										<th>프로젝트 일시</th>
										<td class="tl">
											<input type="text" id="st-date" class="common-input w10 datepicker" />
											<input type="text" id="st-time" class="common-input w10" value="00:00" />
											<span class="inblock tc" style="width: 20px;">-</span>
											<input type="text" id="ed-date" class="common-input w10 datepicker" />
											<input type="text" id="ed-time" class="common-input w10" value="23:59" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="d-flex align-items-center justify-content-between pa20">
							<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
							<button class="btn-main btn-light-indigo" onclick="save()" <?= $lvl < 9 ? 'disabled' : '' ?>>저장</button>
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

<script src="/js/sum.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	// datepicker 설정
	const dpOption = {
		locale: 'ko'
	};
	$('.datepicker').flatpickr(dpOption);
}

// 프로젝트 목록 가져오기
function getList (pageNo) {
	// showSpinner(1000);

	$.ajax({
		type: 'POST',
		url: '/project/getList',
		dataType: 'json',
		cache: false,
		data: {
			pageNo,
			prjTitle: $('#search-prj-title').val(),
			prjTitleUri: $('#search-prj-title-uri').val(),
			stDttm: !isEmpty($('#search-st-date').val()) ? $('#search-st-date').val()+' 00:00:00' : '',
			edDttm: !isEmpty($('#search-ed-date').val()) ? $('#search-ed-date').val()+' 23:59:59' : ''
		},

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				$('span#cnt-all').text(data.item.CNT_ALL);
				$('span#cnt-comp').text(data.item.CNT_COMP);
				$('span#cnt-ing').text(data.item.CNT_ING);
				$('span#cnt-coming').text(data.item.CNT_COMING);

				const list = data.list;

				if (list.length > 0) {
					$('.table-list tfoot').hide();
				} else {
					$('.table-list tfoot').show();
				}

				let html = '';
				list.forEach(item => {
					html += '<tr prj-seq="'+item.PRJ_SEQ+'">';
					html += '	<td>'+item.PRJ_SEQ+'</td>';
					html += '	<td>'+item.PRJ_TITLE+'</td>';
					html += '	<td>'+item.ST_DTTM+'</td>';
					html += '	<td>'+item.ED_DTTM+'</td>';
					html += '	<td>'+item.PRJ_TITLE_URI+'</td>';
					html += '	<td><img class="thumb" src="'+item.MAIN_IMG_URI+'" /></td>';
					html += '	<td><img class="thumb" src="'+item.AGENDA_IMG_URI+'" /></td>';
					html += '	<td><p class="color-box" style="background: '+item.ENT_THME_COLOR+';"></p></td>';
					html += '	<td><p class="color-box" style="background: '+item.APPL_BTN_COLOR+';"></p></td>';
					html += '	<td>'+item.REG_DTTM+'</td>';
					html += '	<td>'+'0'+'</td>';
					html += '	<td>'+'0'+'</td>';
					html += '	<td>'+item.REGR_ID+'</td>';
					html += '</tr>';
					// console.log(html);
				});
				$('.table-list tbody').empty();
				$('.table-list tbody').append(html);

				// 페이징 관련 설정
				pageMax = Math.ceil(data.totCnt / itemsPerPage);
				pages = setPages();
				// console.log(`pages : ${pages}, pageMax : ${pageMax}`);
				// 이전페이지 버튼
				if (pageNo === 1 || pages.length === 0) {
					$('.pagination-container #btn-prev').attr('disabled', true);
				} else {
					$('.pagination-container #btn-prev').attr('disabled', false);
				}
				// 다음페이지 버튼
				if (pageNo === pageMax || pages.length === 0) {
					$('.pagination-container #btn-next').attr('disabled', true);
				} else {
					$('.pagination-container #btn-next').attr('disabled', false);
				}
				// 첫페이지 버튼
				if (pageNo === 1 || pages.length === 0) {
					$('.pagination-container #btn-first').attr('disabled', true);
				} else {
					$('.pagination-container #btn-first').attr('disabled', false);
				}
				// 마지막페이지 버튼
				if (pageNo === pageMax || pages.length === 0) {
					$('.pagination-container #btn-last').attr('disabled', true);
				} else {
					$('.pagination-container #btn-last').attr('disabled', false);
				}

				let btnHtml = '';
				pages.forEach(item => {
					btnHtml += '<button class="page-button '+(pageNo == item ? 'active-page' : '')+'" onclick="changePage('+item+')" '+(pageNo == item ? 'disabled' : '')+'>'+item+'</button>';
				});
				if (btnHtml === '') {
					btnHtml += '<button class="page-button" disabled>1</button>';
				}
				$('.pagination-container .pages').empty();
				$('.pagination-container .pages').append(btnHtml);
			} else {
				// modal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				// centerModal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				alert('프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);

				// hideSpinner();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			// modal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			// centerModal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			alert('프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);

			// hideSpinner();
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
});

</script>

</body>

</html>
