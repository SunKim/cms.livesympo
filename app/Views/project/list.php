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
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 검색</h6>

							<table class="table-list-search">
								<colgroup>
									<col width="10%"/>
									<col width="40%"/>
									<col width="10%"/>
									<col width="40%"/>
								</colgroup>
								<tbody>
									<tr>
										<th>프로젝트 타이틀</th>
										<td>
											<input type="text" id="search-prj-title" class="common-input w90" onchange="getList(1)" />
										</td>
										<th>프로젝트 URI</th>
										<td>
											<input type="text" id="search-prj-title-uri" class="common-input w90" onchange="getList(1)" />
										</td>
									</tr>
									<tr>
										<th>프로젝트 기간</th>
										<td colspan="3">
											<input type="text" id="search-st-date" class="common-input w10 datepicker" onchange="getList(1)" />
											<span class="inblock tc" style="width: 20px;">-</span>
											<input type="text" id="search-ed-date" class="common-input w10 datepicker" onchange="getList(1)" />
										</td>
									</tr>
									<!-- 검색버튼, 초기화버튼 영역 -->
									<tr>
										<td colspan="4" style="padding-bottom: 4px;">
											<div class="tc">
												<button class="btn-white btn-main" onclick="resetFilter();">초기화</button>
												<button class="btn-light-indigo btn-main ml10" onclick="getList(1);">검색</button>
											</div>
										</td>
									</tr>
									<!-- 검색버튼, 초기화버튼 영역 -->
								</tbody>
							</table>
						</div>
						<div class="card-body">
							<!-- START) 테이블 타이틀 영역 -->
							<div class="table-title f15 fw600 d-flex align-items-center justify-content-between">
								<!-- 좌측 총 건수 -->
								<h5 class="list-header">
									<p class="list-title">프로젝트 목록</p>
									<p class="list-totcnt">(총 <span id="cnt-all">0</span></span>개)</p>
								</h5>
								<!-- 좌측 총 건수 -->

								<!-- 우측 상태별 건수 -->
								<div class="list-added-info">
									<p>진행완료 <span class="cblue1" id="cnt-comp">0</span>건</p>
									<span class="vertical-separator">|</span>
									<p>진행중 <span class="cblue1" id="cnt-ing">0</span>건</p>
									<span class="vertical-separator">|</span>
									<p>향후진행 <span class="cblue1" id="cnt-coming">0</span>건</p>

									<button class="btn-blue btn-sub ml20" onclick="location.href='project/detail';">신규등록</button>
								</div>
								<!-- 우측 판매상태별 건수 -->
							</div>
							<!-- END) 테이블 타이틀 영역 -->

							<div style="width: 100%; overflow-x: scroll;">
								<!-- <table class="table-list" id="prj-list" style="min-width: 2000px;"> -->
								<table class="table-list" id="prj-list">
									<!-- <colgroup>
										<col width="34%" />
										<col width="33%" />
										<col width="33%" />
									</colgroup> -->
									<thead>
										<tr>
											<th rowspan="2">Seq.</th>
											<th colspan="5">프로젝트 일반</th>

											<!-- <th colspan="5">디자인</th> -->
											<th rowspan="2">메인이미지</th>
											<!-- <th rowspan="2">어젠다</th>
											<th rowspan="2">푸터</th> -->
											<!-- <th rowspan="2">메인색상</th>
											<th rowspan="2">버튼색상</th> -->

											<th rowspan="2">참여자</th>

											<!-- <th colspan="2">등록</th> -->
											<th rowspan="2">등록일</th>
											<th rowspan="2">등록ID</th>

											<th rowspan="2" style="min-width: 160px;">기능</th>
										</tr>
										<tr>
											<th>타이틀</th>
											<th>URI</th>

											<!-- <th>시작일시</th>
											<th>종료일시</th> -->
											<th>일시</th>

											<th>사전등록자</th>
											<th>시청자</th>

											<!-- <th>메인이미지</th>
											<th>어젠다</th>
											<th>푸터</th>
											<th>메인색상</th>
											<th>버튼색상</th> -->

											<!-- <th>등록일</th>
											<th>등록ID</th> -->
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="11" class="tc">데이터가 없습니다.</td>
										</tr>
									</tfoot>
								</table>
							</div>

							<!-- Pagination -->
							<div class="pagination-container">
								<!-- 처음페이지 버튼 -->
								<button class="page-button move-button first" id="btn-first" onclick="changePage('first')">
									<img src="/images/icon/icon_arrow_left2.png" alt="좌측화살표2" style="height: 10px;" />
								</button>
								<!-- 이전페이지 버튼 -->
								<button class="page-button move-button prev" id="btn-prev" onclick="changePage('prev')">
									<img src="/images/icon/icon_arrow_left.png" alt="좌측화살표" style="height: 10px;" />
								</button>

								<!-- 현재페이지 및 페이지버튼들 -->
								<div class="pages inblock">
								</div>

								<!-- 다음페이지 버튼 -->
								<button class="page-button move-button next" id="btn-next" onclick="changePage('next')">
									<img src="/images/icon/icon_arrow_right.png" alt="우측화살표" style="height: 10px;" />
								</button>
								<!-- 마지막페이지 버튼 -->
								<button class="page-button move-button last" id="btn-last" onclick="changePage('last')">
									<img src="/images/icon/icon_arrow_right2.png" alt="우측화살표2" style="height: 10px;" />
								</button>
							</div>

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

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">
// 보여줄 페이지번호 버튼들 갯수
const pageButtonCnt = 5;
// 한페이지에 보여줄 아이템 갯수
const itemsPerPage = 20;

// 맨 마지막 페이지번호
let pageMax = 0;
// 현재 페이지번호
let pageNo = 1;
// 클릭할 수 있는 페이지번호 버튼들
let pages = [];

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	// datepicker 설정
	const dpOption = {
		locale: 'ko'
	};
	$('.datepicker').flatpickr(dpOption);

	// 해당 line 클릭시 상세로 이동
	// $(document).on('click', '#prj-list tbody tr td', function(e) {
	// 	location.href = '/project/detail/' + $(this).parent('tr').attr('prj-seq');
	// });

	getList(pageNo);
}

// 페이지 변경
function changePage (gub) {
	if (gub === 'first') {
		getList(1);
	} else if (gub === 'last') {
		getList(pageMax);
	} else if (gub === 'next' && pageNo < pageMax) {
		getList(++pageNo);
	} else if (gub === 'prev' && pageNo > 1) {
		getList(--pageNo);
	} else if (gub >= 1 && gub <= pageMax) {
		getList(gub);
	} else {
		alert('페이지 변경도중 오류가 발생했습니다. 관리자에게 문의해주세요.\n\n메세지 : 페이지번호가 범위를 넘어갑니다.');
	}
}

// 검색필터 초기화
function resetFilter () {
	$('#search-prj-title').val('');
	$('#search-prj-title-uri').val('');
	$('#search-st-date').val('');
	$('#search-ed-date').val('');

	getList(1);
}

// 상세페이지
function goToDetail (prjSeq) {
	alert(prjSeq);
}

// 프로젝트 목록 가져오기
function getList (pageNo) {
	showSpinner();

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
					html += '	<td><a href="/project/detail/'+item.PRJ_SEQ+'">'+item.PRJ_TITLE+'</a></td>';
					html += '	<td>';
					html += '		<a href="<?= $livesympoUrl ?>/'+item.PRJ_TITLE_URI+'" target="_stream">'+item.PRJ_TITLE_URI+'</a>';
					html += '	</td>';

					// html += '	<td>'+item.ST_DTTM+'</td>';
					// html += '	<td>'+item.ED_DTTM+'</td>';
					html += '	<td>'+item.ST_DT+' '+item.ST_TM+'~'+item.ED_TM+'</td>';

					html += '	<td>'+item.REQR_CNT+'</td>';
					html += '	<td>0</td>';

					html += '	<td><img class="thumb" src="'+item.MAIN_IMG_THUMB_URI+'" /></td>';
					// html += '	<td><img class="thumb" src="'+item.AGENDA_IMG_THUMB_URI+'" /></td>';
					// html += '	<td><img class="thumb" src="'+item.FOOTER_IMG_THUMB_URI+'" /></td>';
					// html += '	<td><p class="color-box" style="background: '+item.ENT_THME_COLR+';"></p></td>';
					// html += '	<td><p class="color-box" style="background: '+item.APPL_BTN_BG_COLR+';"></p></td>';

					// html += '	<td>'+item.CNT_CHOICE+'</td>';
					// html += '	<td>'+item.CNT_SBJ+'</td>';
					html += '	<td>'+item.ASW_CNT+'</td>';

					html += '	<td>'+item.REG_DTTM+'</td>';
					html += '	<td>'+item.REGR_ID+'</td>';

					html += '	<td>';
					html += '		<p>';
					html += '			<a href="/project/requestor/'+item.PRJ_SEQ+'" class="">사전등록자</a>';
					html += '			<a href="/project/survey/'+item.PRJ_SEQ+'" class="ml20">설문관리</a>';
					html += '		</p>';
					html += '		<p>';
					html += '			<a href="/project/question/'+item.PRJ_SEQ+'" class="">질문관리</a>';
					html += '			<a href="/project/moderator/'+item.PRJ_SEQ+'" target="_moderator" class="ml20">모더레이터</a>';
					html += '		</p>';
					html += '	</td>';

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
			hideSpinner();
		}
	});
}

// 페이징에 보여줄 페이지번호 버튼들 설정
function setPages () {
	if (pageMax <= pageButtonCnt) {
		// 총 페이지가 페이지 버튼수 제한 이내면 그냥 다 뿌려줌.
		return generateInt(1, pageMax)
	} else {
		// 총 페이지가 페이지버튼수제한을 초과시
		if (pageNo - Math.floor((pageButtonCnt - 1) / 2) < 1) {
          // 1페이지부터 버튼수제한만큼
          return generateInt(1, pageButtonCnt)
        } else if (pageNo + Math.ceil((pageButtonCnt - 1) / 2) > pageMax) {
          // (마지막페이지-버튼수제한) 부터 마지막페이지까지
          return generateInt(pageMax - pageButtonCnt + 1, pageMax)
        } else {
          // 중간 페이지들.
          const _start = pageNo - Math.floor((pageButtonCnt - 1) / 2)
          const _end = pageNo + Math.ceil((pageButtonCnt - 1) / 2)
          return generateInt(_start, _end)
        }
	}
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
