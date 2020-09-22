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
							<form method="POST">
								<table class="table-list">
									<colgroup>
										<col width="10%" />
										<col width="15%" />
										<col width="75%" />
									</colgroup>
									<tbody>
										<tr>
											<th rowspan="5">프로젝트</th>
											<th>타이틀</th>
											<td class="tl">
												<input type="hidden" id="PRJ_SEQ" name="PRJ_SEQ" />
												<input type="text" id="PRJ_TITLE" name="PRJ_TITLE" class="common-input w90" />
											</td>
										</tr>
										<tr>
											<th>URI</th>
											<td class="tl">
												<input type="text" id="PRJ_TITLE_URI" name="PRJ_TITLE_URI" class="common-input w90" placeholder="drug-a-20200922 형식으로 입력해주세요." />
											</td>
										</tr>
										<tr>
											<th>스트리밍 URL</th>
											<td class="tl">
												<input type="text" id="STREAM_URL" name="STREAM_URL" class="common-input w90" placeholder="https://stream.com/xxxx 형식으로 입력해주세요." />
											</td>
										</tr>
										<tr>
											<th>아젠다 추가페이지</th>
											<td class="tl">
												<select class="common-select w90" id="AGENDA_PAGE_YN" name="AGENDA_PAGE_YN">
													<option value="0">N</option>
													<option value="1">Y</option>
												</select>
											</td>
										</tr>
										<tr>
											<th>프로젝트 일시</th>
											<td class="tl">
												<input type="text" id="ST_DATE" name="ST_DATE" class="common-input w10 datepicker" />
												<input type="text" id="ST_TIME" name="ST_TIME" class="common-input w10" value="00:00" />
												<span class="inblock tc" style="width: 20px;">-</span>
												<input type="text" id="ED_DATE" name="ED_DATE" class="common-input w10 datepicker" />
												<input type="text" id="ED_TIME" name="ED_TIME" class="common-input w10" value="23:59" />
											</td>
										</tr>
										<tr>
											<th rowspan="3">이미지</th>
											<th>메인 이미지</th>
											<td class="tl">
												<input type="file" id="MAIN_IMG_URI" name="MAIN_IMG_URI" class="common-input w90" />
											</td>
										</tr>
										<tr>
											<th>아젠다 이미지</th>
											<td class="tl">
												<input type="file" id="AGENDA_IMG_URI" name="AGENDA_IMG_URI" class="common-input w90" />
											</td>
										</tr>
										<tr>
											<th>푸터 이미지</th>
											<td class="tl">
												<input type="file" id="FOOTER_IMG_URI" name="FOOTER_IMG_URI" class="common-input w90" />
											</td>
										</tr>
										<tr>
											<th rowspan="2">색상</th>
											<th>테마 색상</th>
											<td class="tl">
												<input type="text" id="ENT_THME_COLOR" name="ENT_THME_COLOR" class="common-input w90" />
											</td>
										</tr>
										<tr>
											<th>사전등록버튼 색상</th>
											<td class="tl">
												<input type="text" id="APPL_BTN_COLOR" name="APPL_BTN_COLOR" class="common-input w90" />
											</td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>

						<div class="d-flex align-items-center justify-content-between pa20">
							<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
<?php
	// 레벨9만 보이도록
	if ($lvl == 9) {
		echo '<button class="btn-main btn-light-indigo" onclick="save()">저장</button>';
	}
?>
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

	// if ()
}

// 저장
function save () {
	const form = $('form')[0];
	const formData = new FormData(form);

	$.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "/project/save",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 10000,
        success: function (res) {
			console.log(res);
        	alert('complete');
        },
        error: function (e) {
            console.log('ERROR : ', e);
            alert('fail');
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
