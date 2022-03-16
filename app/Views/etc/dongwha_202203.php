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
<meta name="author" content="Sun Kim">
<meta name="other agent" content="Sun Kim">
<meta name="reply-to(email)" content="sjmarine97@gmail.com">
<meta name="location" content="Seoul, Korea">
<meta name="distribution" content="Sun Kim">
<meta name="robots" content="noindex,nofollow">
<!-- meta name="robots" content="all" -->

<title>Live Sympo 관리자 동화약품 강의자료</title>

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
						<h1 class="h3 mb-0 text-gray-800">ETC</h1>

						<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
					</div>

					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">동화제약 강의자료(202203)</h6>
						</div>
						<div class="card-body">
							<form method="POST">
								<table class="table-detail">
									<colgroup>
										<col width="25%" />
										<col width="75%" />
									</colgroup>
									<tbody>
										<tr>
											<th rowspan="6" class="required">강의자료</th>
											<td class="tl">
												<p class="desc">How to approach itch, general consideration</p>
												<p class="mt10"> 기존파일 : <span id="lec1-nm"></span></p>
												<div class="mt10">
													<input type="file" id="lec1" name="lec1" class="common-input w50" accept="application/pdf" />
													<select class="common-select w20" id="DONGWHA_202203_LEC1_READY_YN" name="DONGWHA_202203_LEC1_READY_YN">
														<option value="1">준비중</option>
														<option value="0">자료완료</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td class="tl">
												<p class="desc">Treatment Paradigm in Itch</p>
												<p class="mt10"> 기존파일 : <span id="lec2-nm"></span></p>
												<div class="mt10">
													<input type="file" id="lec2" name="lec2" class="common-input w50" accept="application/pdf" />
													<select class="common-select w20" id="DONGWHA_202203_LEC2_READY_YN" name="DONGWHA_202203_LEC2_READY_YN">
														<option value="1">준비중</option>
														<option value="0">자료완료</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td class="tl">
												<p class="desc">Cases of Clinical Application of Intrinsic IB gel MD</p>
												<p class="mt10"> 기존파일 : <span id="lec3-nm"></span></p>
												<div class="mt10">
													<input type="file" id="lec3" name="lec3" class="common-input w50" accept="application/pdf" />
													<select class="common-select w20" id="DONGWHA_202203_LEC3_READY_YN" name="DONGWHA_202203_LEC3_READY_YN">
														<option value="1">준비중</option>
														<option value="0">자료완료</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td class="tl">
												<p class="desc">Panel Discussion: Focused on Case-Reviews of Clinical Application of Intrinsic IB gel MD</p>
												<p class="mt10"> 기존파일 : <span id="lec4-nm"></span></p>
												<div class="mt10">
													<input type="file" id="lec4" name="lec4" class="common-input w50" accept="application/pdf" />
													<select class="common-select w20" id="DONGWHA_202203_LEC4_READY_YN" name="DONGWHA_202203_LEC4_READY_YN">
														<option value="1">준비중</option>
														<option value="0">자료완료</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td class="tl">
												<p class="desc">Enhancement of Tight Junction Barrier Function by Intrinsic Lotion & Cream MD</p>
												<p class="mt10"> 기존파일 : <span id="lec5-nm"></span></p>
												<div class="mt10">
													<input type="file" id="lec5" name="lec5" class="common-input w50" accept="application/pdf" />
													<select class="common-select w20" id="DONGWHA_202203_LEC5_READY_YN" name="DONGWHA_202203_LEC5_READY_YN">
														<option value="1">준비중</option>
														<option value="0">자료완료</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td class="tl">
												<p class="desc">Panel Discussion: How to Apply Intrinsic IB Gel MD with Moisturizer</p>
												<p class="mt10"> 기존파일 : <span id="lec6-nm"></span></p>
												<div class="mt10">
													<input type="file" id="lec6" name="lec6" class="common-input w50" accept="application/pdf" />
													<select class="common-select w20" id="DONGWHA_202203_LEC6_READY_YN" name="DONGWHA_202203_LEC6_READY_YN">
														<option value="1">준비중</option>
														<option value="0">자료완료</option>
													</select>
												</div>
											</td>
										</tr>
									</tbody>
								</table>

								<input type="hidden" id="yyyymm" name="yyyymm" value="202203" />
							</form>
						</div>

						<div class="d-flex align-items-center justify-content-between pa20">
							<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
<?php
	// 레벨9만 보이도록 -> 일반관리자도 수정 가능
	if ($lvl == 9 || $lvl == 1) {
		$url = $_ENV['CI_ENVIRONMENT'] == 'production' ? 'https://livesympo.kr/dongwha' : 'http://localhost:9090/dongwha';
		echo '<button class="btn-main btn-sky" onclick="window.open(\''.$url.'\', \'_dongwha\')">미리보기</button>';
		echo '<button class="btn-main btn-light-indigo" onclick="save();">저장</button>';
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

<!-- MiniColors. https://www.jqueryscript.net/other/Color-Picker-Plugin-jQuery-MiniColors.html -->
<script src="/vendor/minicolors/jquery.minicolors.js"></script>

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	// 준비중 여부 설정
	$('#DONGWHA_202203_LEC1_READY_YN').val('<?= $DONGWHA_202203_LEC1_READY_YN ?>');
	$('#DONGWHA_202203_LEC2_READY_YN').val('<?= $DONGWHA_202203_LEC2_READY_YN ?>');
	$('#DONGWHA_202203_LEC3_READY_YN').val('<?= $DONGWHA_202203_LEC3_READY_YN ?>');
	$('#DONGWHA_202203_LEC4_READY_YN').val('<?= $DONGWHA_202203_LEC4_READY_YN ?>');
	$('#DONGWHA_202203_LEC5_READY_YN').val('<?= $DONGWHA_202203_LEC5_READY_YN ?>');
	$('#DONGWHA_202203_LEC6_READY_YN').val('<?= $DONGWHA_202203_LEC6_READY_YN ?>');

	$('#lec1-nm').text('<?= $DONGWHA_202203_LEC1_FILE_NM ?>');
	$('#lec2-nm').text('<?= $DONGWHA_202203_LEC2_FILE_NM ?>');
	$('#lec3-nm').text('<?= $DONGWHA_202203_LEC3_FILE_NM ?>');
	$('#lec4-nm').text('<?= $DONGWHA_202203_LEC4_FILE_NM ?>');
	$('#lec5-nm').text('<?= $DONGWHA_202203_LEC5_FILE_NM ?>');
	$('#lec6-nm').text('<?= $DONGWHA_202203_LEC6_FILE_NM ?>');
}

// 저장
function save () {
	// 강의자료 등록 체크
	// if (isEmpty( $('#lec1').val() )) {
	// 	alert('강의자료 1번을 선택해주세요.');
	// 	$('#lec1').focus();
	// 	return;
	// }
	// if (isEmpty( $('#lec2').val() )) {
	// 	alert('강의자료 2번을 선택해주세요.');
	// 	$('#lec2').focus();
	// 	return;
	// }
	// if (isEmpty( $('#lec3').val() )) {
	// 	alert('강의자료 3번을 선택해주세요.');
	// 	$('#lec3').focus();
	// 	return;
	// }
	// if (isEmpty( $('#lec4').val() )) {
	// 	alert('강의자료 4번을 선택해주세요.');
	// 	$('#lec4').focus();
	// 	return;
	// }

	const form = $('form')[0];
	const formData = new FormData(form);

	$.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/dongwha/save',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 10000,
        success: function (data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				alert('저장되었습니다.');
				location.reload();
			} else {
				alert('데이터를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
        },
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('데이터를 저장하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
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
