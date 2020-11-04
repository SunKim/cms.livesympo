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

<title>Live Sympo 사전등록자 관리</title>

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

<!-- START) 메인 css -->
<style type="text/css">
table.tbl-reqr-list th, table.tbl-reqr-list td { padding: 4px !important; min-width: 50px !important; }
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

					<!-- 사전등록자 목록 영역 -->
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">프로젝트 사전등록자 목록</h6>
						</div>
						<div id="excel-container" style="padding: 20px 10px;">
							<table class="table-list tbl-reqr-list" id="tbl-reqr-list">
								<thead>
									<tr>
										<th>Seq.</th>
										<th>성명</th>
										<th>연락처</th>
										<th class="extra-1"><?= $project['ENT_INFO_EXTRA_1'] ?></th>
										<th class="extra-2"><?= $project['ENT_INFO_EXTRA_2'] ?></th>
										<th class="extra-3"><?= $project['ENT_INFO_EXTRA_3'] ?></th>
										<th class="extra-4"><?= $project['ENT_INFO_EXTRA_4'] ?></th>
										<th class="extra-5"><?= $project['ENT_INFO_EXTRA_5'] ?></th>
										<th class="extra-6"><?= $project['ENT_INFO_EXTRA_6'] ?></th>
										<th class="extra-7"><?= $project['ENT_INFO_EXTRA_7'] ?></th>
										<th class="extra-8"><?= $project['ENT_INFO_EXTRA_8'] ?></th>
										<th class="conn-route">접속경로</th>
										<th>사전등록일시</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<!-- 사전등록자 목록 영역 -->

					<div class="d-flex align-items-center justify-content-between pa20">
						<button class="btn-main btn-white mr15" onclick="history.back();">뒤로</button>
<?php
// 레벨9만 보이도록
if ($lvl == 9 || $lvl == 1) {
	echo '<div>';
	echo '	<button class="btn-main btn-red" onclick="deleteRequestor();">데이터삭제</button>';
	echo '	<button class="btn-main btn-light-indigo ml10" onclick="openUploadExcel();">엑셀업로드</button>';
	echo '	<button class="btn-main btn-light-indigo ml10" onclick="downloadExcel();">엑셀저장</button>';
	echo '</div>';
}
?>
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

	<!-- 엑셀업로드 Modal -->
	<div id="uploadExcelModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title tl" id="uploadExcelModalTitle">사전등록자 엑셀(.csv) 파일 업로드</h4>
	                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<p>.csv 파일은 제목열은 빼고 프로젝트에서 설정한 입력항목 순서대로 아래 표와 같이 입력해주세요.</p>
					<p> - 칼럼1: 성명, 칼럼 2: 연락처, 칼럼 3: 추가입력1 ~ 칼럼 10: 추가입력8, 칼럼 11: 접속경로</p>
					<p> - 접속경로가 기존 입력값과 다를 경우 저장되지 않습니다. 예) 프로젝트 설정의 접속경로는 '영업담당자'인데 엑셀에는 '영업 담당자'라고 되어있는 경우</p>
					<p> - 기존 입력데이터 또는 엑셀업로드 데이터와 중복(이름, 연락처)되는 사전등록자가 있으면 최근 입력값으로 대체됩니다.</p>
					<table class="table-list mt10" id="tbl-reqr-list-sample">
	                    <tbody>
							<tr>
								<th>성명</th>
								<th>연락처</th>
								<th class="extra-1"><?= $project['ENT_INFO_EXTRA_1'] ?></th>
								<th class="extra-2"><?= $project['ENT_INFO_EXTRA_2'] ?></th>
								<th class="extra-3"><?= $project['ENT_INFO_EXTRA_3'] ?></th>
								<th class="extra-4"><?= $project['ENT_INFO_EXTRA_4'] ?></th>
								<th class="extra-5"><?= $project['ENT_INFO_EXTRA_5'] ?></th>
								<th class="extra-6"><?= $project['ENT_INFO_EXTRA_6'] ?></th>
								<th class="extra-7"><?= $project['ENT_INFO_EXTRA_7'] ?></th>
								<th class="extra-8"><?= $project['ENT_INFO_EXTRA_8'] ?></th>
								<th class="conn-route">접속경로</th>
							</tr>
							<tr>
								<td>홍길동</td>
								<td>01011112222</td>
								<td class="extra-1">샘플1</td>
								<td class="extra-2">샘플1</td>
								<td class="extra-3">샘플1</td>
								<td class="extra-4">샘플1</td>
								<td class="extra-5">샘플1</td>
								<td class="extra-6">샘플1</td>
								<td class="extra-7">샘플1</td>
								<td class="extra-8">샘플1</td>
								<td>접속경로1</td>
							</tr>
							<tr>
								<td>이순신</td>
								<td>01033334444</td>
								<td class="extra-1">샘플2</td>
								<td class="extra-2">샘플2</td>
								<td class="extra-3">샘플2</td>
								<td class="extra-4">샘플2</td>
								<td class="extra-5">샘플2</td>
								<td class="extra-6">샘플2</td>
								<td class="extra-7">샘플2</td>
								<td class="extra-8">샘플2</td>
								<td>접속경로2</td>
							</tr>
	                    </tbody>
	                </table>

					<form method="POST">
						<h5 class="mt40">파일선택</h5>
						<input type="file" id="requestorFile" name="requestorFile" class="common-input w50" accept=".csv" />
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
					<button type="button" class="btn btn-primary" onclick="uploadExcel();">업로드</button>
				</div>
			</div>
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

<!-- Custom scripts for all pages-->
<script src="/js/sb-admin-2.min.js"></script>

<script src="/js/sun.common.20200914.js"></script>

<!-- 메인 script -->
<script language="javascript">

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	getRequestorList(<?= $project['PRJ_SEQ'] ?>);
}

// 설문목록 (설문 질문목록, 보기목록, 답변목록) 불러오기
function getRequestorList (prjSeq) {
	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/project/getRequestorList/' + prjSeq,
		dataType: 'json',
		cache: false,
		data: {
			prjSeq
		},

		success: function(data) {
			console.log(data)
			if ( data.resCode == '0000' ) {
				// 설문항목(질문) 목록
				const reqrList = data.list;

				$('table.tbl-reqr-list tbody').empty();

				reqrList.forEach((item) => {
					let html = '';

					html += '<tr>';
					html += '	<td>'+item.REQR_SEQ+'</td>';
					html += '	<td>'+item.REQR_NM+'</td>';
					html += '	<td>'+formatMobile(item.MBILNO)+'</td>';
					html += '	<td class="extra-1">'+item.ENT_INFO_EXTRA_VAL_1+'</td>';
					html += '	<td class="extra-2">'+item.ENT_INFO_EXTRA_VAL_2+'</td>';
					html += '	<td class="extra-3">'+item.ENT_INFO_EXTRA_VAL_3+'</td>';
					html += '	<td class="extra-4">'+item.ENT_INFO_EXTRA_VAL_4+'</td>';
					html += '	<td class="extra-5">'+item.ENT_INFO_EXTRA_VAL_5+'</td>';
					html += '	<td class="extra-6">'+item.ENT_INFO_EXTRA_VAL_6+'</td>';
					html += '	<td class="extra-7">'+item.ENT_INFO_EXTRA_VAL_7+'</td>';
					html += '	<td class="extra-8">'+item.ENT_INFO_EXTRA_VAL_8+'</td>';
					html += '	<td class="conn-route">'+item.CONN_ROUTE_VAL_NM+'</td>';
					html += '	<td>'+item.REG_DTTM+'</td>';
					html += '</tr>';

					$('table.tbl-reqr-list tbody').append(html);

<?php
	// 접속경로 설정 없으면 숨김
	// if (!isset($project['CONN_ROUTE_1']) || $project['CONN_ROUTE_1'] == '') {
	// 	echo "$('table.tbl-reqr-list .conn-route').remove();";
	// }

	// 입력정보 없으면 테이블 column 숨김
	if (!isset($project['ENT_INFO_EXTRA_1']) || $project['ENT_INFO_EXTRA_1'] == '') {
		echo "$('table.tbl-reqr-list .extra-1').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_2']) || $project['ENT_INFO_EXTRA_2'] == '') {
		echo "$('table.tbl-reqr-list .extra-2').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_3']) || $project['ENT_INFO_EXTRA_3'] == '') {
		echo "$('table.tbl-reqr-list .extra-3').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_4']) || $project['ENT_INFO_EXTRA_4'] == '') {
		echo "$('table.tbl-reqr-list .extra-4').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_5']) || $project['ENT_INFO_EXTRA_5'] == '') {
		echo "$('table.tbl-reqr-list .extra-5').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_6']) || $project['ENT_INFO_EXTRA_6'] == '') {
		echo "$('table.tbl-reqr-list .extra-6').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_7']) || $project['ENT_INFO_EXTRA_7'] == '') {
		echo "$('table.tbl-reqr-list .extra-7').remove();";
	}
	if (!isset($project['ENT_INFO_EXTRA_8']) || $project['ENT_INFO_EXTRA_8'] == '') {
		echo "$('table.tbl-reqr-list .extra-8').remove();";
	}
?>
				});
			} else {
				alert('사전등록자 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('사전등록자 데이터를 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
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
	downloadTableToCsv('tbl-reqr-list', '<?= $project['PRJ_TITLE'] ?>_사전등록자_'+todayShort);
}

// 엑셀(.csv) 업로드 모달 열기
function openUploadExcel () {
	$('#uploadExcelModal').modal();
}
// 엑셀(.csv) 업로드
function uploadExcel () {
	// validation
	if (isEmpty( $('#requestorFile').val() )) {
		alert('업로드 파일을 선택해주세요.');
		$('#MAIN_IMG').focus();
		return;
	}

	showSpinner();

	const form = $('form')[0];
	const formData = new FormData(form);

	$.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/project/uploadRequestor/<?= $project['PRJ_SEQ'] ?>',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 10000,
        success: function (data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				console.log(data.logList);
				alert('프로젝트 사전등록자 목록 업로드가 완료되었습니다. (신규등록  '+data.cntInsert+'건, 갱신 '+data.cntUpdate+'건)');
				location.reload();
			} else {
				alert('프로젝트 사전등록자 목록 업로드 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
        },
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 사전등록자 목록 업로드 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
        },
		complete : function () {
			hideSpinner();
		}
    });
}

// 사전등록자 삭제
function deleteRequestor () {
	if (!confirm('해당 프로젝트의 사전등록자 데이터가 모두 삭제되며 복구할 수 없습니다. 사전등록자를 모두 삭제하시겠습니까?')) {
		return;
	}

	$.ajax({
		type: 'POST',
		url: '/project/deleteRequestor',
		dataType: 'json',
		cache: false,
		data: {
			prjSeq: <?= $project['PRJ_SEQ'] ?>
		},

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				alert('프로젝트 사전등록자 목록을 삭제했습니다.');
				location.reload();
			} else {
				alert('프로젝트 사전등록자 목록을 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('프로젝트 사전등록자 목록을 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
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
