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

<title>Live Sympo 관리자 관리</title>

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
table.table-admin-reg { font-size: 14px; }
table.table-admin-reg th { font-weight: 500; }
table.table-admin-reg td { padding: 4px 10px; }
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
						<h1 class="h3 mb-0 text-gray-800">Admin</h1>

						<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
					</div>

					<div class="card shadow mb-4">
						<div class="card-body">
							<!-- START) 테이블 타이틀 영역 -->
							<div class="table-title f15 fw600 d-flex align-items-center justify-content-between">
								<!-- 좌측 총 건수 -->
								<h5 class="list-header">
									<p class="list-title">관리자 목록</p>
									<p class="list-totcnt">(총 <span id="cnt-all">0</span></span>명)</p>
								</h5>
								<!-- 좌측 총 건수 -->

								<!-- 우측 상태별 건수 -->
								<div class="list-added-info">
									<p>최고관리자 <span class="cblue1" id="cnt-9">0</span>명</p>
									<span class="vertical-separator">|</span>
									<p>일반관리자 <span class="cblue1" id="cnt-1">0</span>명</p>
									<span class="vertical-separator">|</span>
									<p>데이터관리자 <span class="cblue1" id="cnt-2">0</span>명</p>

									<button type="button" class="btn-blue btn-sub ml20" onclick="openRegPopup();">신규등록</button>
								</div>
								<!-- 우측 판매상태별 건수 -->
							</div>
							<!-- END) 테이블 타이틀 영역 -->

							<div style="width: 100%; overflow-x: scroll;">
								<table class="table-list" id="prj-list">
									<!-- <colgroup>
										<col width="34%" />
										<col width="33%" />
										<col width="33%" />
									</colgroup> -->
									<thead>
										<tr>
											<th>Seq.</th>
											<th>이메일</th>
											<th>관리자명</th>
											<th>관리자레벨</th>
											<th>소속</th>
											<th>데이터관리</th>
											<th>등록일시</th>
											<th>등록자이메일</th>
											<th>버튼</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="7" class="tc">데이터가 없습니다.</td>
										</tr>
									</tfoot>
								</table>
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

	<!-- 패스워드 변경 Modal -->
	<div id="changePwdModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="changePwdModalTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title tl" id="changePwdModalTitle">패스워드 변경</h4>
	                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<table class="table-password">
	                    <tbody>
							<tr>
								<th>기존 패스워드</th>
								<td>
									<input type="hidden" id="pwdAdminSeq" />
									<input type="password" id="oldPwd" class="common-input w100" />
								</td>
							</tr>
							<tr>
								<th>신규 패스워드</th>
								<td>
									<input type="password" id="newPwd" class="common-input w100" />
								</td>
							</tr>
							<tr>
								<th>신규 패스워드 확인</th>
								<td>
									<input type="password" id="newPwd2" class="common-input w100" />
								</td>
							</tr>
	                    </tbody>
	                </table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
					<button type="button" class="btn btn-primary" onclick="changePwd();">확인</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 관리자 신규등록 Modal-->
	<div class="modal fade" id="adminRegModal" tabindex="-1" role="dialog" aria-labelledby="adminRegModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="adminRegModalLabel">관리자 등록</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table-admin-reg">
						<colgroup>
							<col style="width: 30%;" />
							<col style="width: 70%;" />
						</colgroup>
                    	<tbody>
							<tr>
								<th>이메일</th>
								<td>
									<input type="text" id="email" class="common-input w100" autocomplete="none" />
								</td>
							</tr>
							<tr>
								<th>패스워드</th>
								<td>
									<input type="password" id="pwd" class="common-input w100" autocomplete="none" />
								</td>
							</tr>
							<tr>
								<th>패스워드확인</th>
								<td>
									<input type="password" id="pwd2" class="common-input w100" autocomplete="none" />
								</td>
							</tr>
							<tr>
								<th>관리자명</th>
								<td>
									<input type="text" id="admNm" class="common-input w100" autocomplete="none" />
								</td>
							</tr>
							<tr>
								<th>관리자레벨</th>
								<td>
									<select id="lvl" class="common-select w100">
										<option value="1">일반관리자</option>
										<option value="2">데이터관리자</option>
										<option value="9">최고관리자</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>소속</th>
								<td>
									<input type="text" id="orgNm" class="common-input w100" autocomplete="none" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">취소</button>
					<a class="btn btn-blue" href="javascript: testAdmin();">테스트</a>
					<a class="btn btn-primary" href="javascript: saveAdmin();">확인</a>
				</div>
			</div>
		</div>
	</div>
	<!-- 관리자 신규등록 Modal-->

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

// 초기화
function fnInit () {
	// 해당 메뉴에 active
	$('.nav-item.<?= $menu ?>').addClass('active');

	getList();
}

// 패스워드 변경창 오픈
function openChangePwd (admSeq) {
	$('#pwdAdminSeq').val(admSeq);
	$('#changePwdModal').modal('show');
}

// 관리자 등록창 오픈
function openRegPopup () {
	$('#adminRegModal').modal('show');
}

// 관리자 테스트데이터 입력
function testAdmin () {
	$('#email').val('test1@livesympo.kr');
	$('#pwd').val('1234');
	$('#pwd2').val('1234');
	$('#admNm').val('테스트1');
	$('#lvl').val('1');
}

// 관리자 등록
function saveAdmin () {
	// validation
	if (!checkEmail($('#email').val())) {
		alert('관리자 이메일을 형식에 맞게 입력해주세요.');
		$('#email').focus();
		return;
	}
	if (isEmpty($('#pwd').val())) {
		alert('패스워드를 입력해주세요.');
		$('#newPwd').focus();
		return;
	}
	if ($('#pwd').val() != $('#pwd2').val()) {
		alert('패스워드와 패스워드 확인을 동일하게 입력해주세요.');
		$('#pwd2').focus();
		return;
	}
	if (isEmpty($('#admNm').val())) {
		alert('관리자명을 입력해주세요.');
		$('#admNm').focus();
		return;
	}

	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/admin/save',
		dataType: 'json',
		cache: false,
		data: {
			email: $('#email').val(),
			pwd: $('#pwd').val(),
			admNm: $('#admNm').val(),
			lvl: $('#lvl').val(),
			regrId: '<?= $email ?>'
		},

		success: function(data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				alert('신규 관리자를 등록했습니다.');

				getList();
			} else {
				alert('신규 관리자를 등록하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('신규 관리자를 등록하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();

			$('#email').val('');
			$('#pwd').val('');
			$('#pwd2').val('');
			$('#admNm').val('');
			$('#lvl').val('1');
			$('#adminRegModal').modal('hide');
		}
	});
}

// 패스워드 변경
function changePwd () {
	// validation
	if (isEmpty($('#oldPwd').val())) {
		alert('기존 패스워드를 입력해주세요.');
		$('#oldPwd').focus();
		return;
	}
	if (isEmpty($('#newPwd').val())) {
		alert('신규 패스워드를 입력해주세요.');
		$('#newPwd').focus();
		return;
	}
	if ($('#newPwd').val() != $('#newPwd2').val()) {
		alert('신규 패스워드와 패스워드 확인을 동일하게 입력해주세요.');
		$('#newPwd2').focus();
		return;
	}

	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/admin/changePwd',
		dataType: 'json',
		cache: false,
		data: {
			admSeq: $('#pwdAdminSeq').val(),
			oldPwd: $('#oldPwd').val(),
			newPwd: $('#newPwd').val()
		},

		success: function(data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				alert('패스워드를 변경했습니다.');
			} else {
				alert('패스워드를 변경하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('패스워드를 변경하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
			$('#oldPwd').val('');
			$('#newPwd').val('');
			$('#newPwd2').val('');
			$('#changePwdModal').modal('hide');
		}
	});
}

// 삭제
function deleteAdmin (admSeq) {
	if (!confirm('정말 삭제하시겠습니까?')) {
		return;
	}

	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/admin/deleteAdmin',
		dataType: 'json',
		cache: false,
		data: {
			admSeq
		},

		success: function(data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				alert('관리자를 삭제했습니다.');
				getList();
			} else {
				alert('관리자를 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			alert('관리자를 삭제하는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

// 관리자 목록 가져오기
function getList () {
	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/admin/getList',
		dataType: 'json',
		cache: false,
		data: {
		},

		success: function(data) {
			// console.log(data);
			if ( data.resCode == '0000' ) {
				$('span#cnt-all').text(data.item.CNT_ALL);
				$('span#cnt-9').text(data.item.CNT_9);
				$('span#cnt-1').text(data.item.CNT_1);

				const list = data.list;

				if (list.length > 0) {
					$('.table-list tfoot').hide();
				} else {
					$('.table-list tfoot').show();
				}

				let html = '';
				list.forEach(item => {
					html += '<tr adm-seq="'+item.ADM_SEQ+'">';
					html += '	<td>'+item.ADM_SEQ+'</td>';
					html += '	<td>'+item.EMAIL+'</td>';
					html += '	<td>'+item.ADM_NM+'</td>';
					html += '	<td>'+item.LVL_NM+'</td>';
					html += '	<td>'+item.ORG_NM+'</td>';
					html += '	<td title="'+item.PRJ_TITLE_ARR+'">';
					html += '		<div style="max-width: 200px; max-height: 42px; text-overflow: ellipsis; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; word-wrap:break-word;">';
					html += '			'+item.PRJ_TITLE_ARR+' ';
					html += '		</div>';
					html += '	</td>';
					html += '	<td>'+item.REG_DTTM+'</td>';
					html += '	<td>'+item.REGR_ID+'</td>';
					html += '	<td>';
					html += '		<button type="button" class="btn-white btn-sub" onclick="openChangePwd('+item.ADM_SEQ+');">패스워드변경</button>';
					html += '		<button type="button" class="btn-red btn-sub ml10" onclick="deleteAdmin('+item.ADM_SEQ+');">삭제</button>';
					html += '	</td>';

					html += '</tr>';
					// console.log(html);
				});
				$('.table-list tbody').empty();
				$('.table-list tbody').append(html);
			} else {
				// modal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				// centerModal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				alert('관리자 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
				// hideSpinner();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.error(xhr);
			// modal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			// centerModal1('경고', '프로젝트 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			alert('관리자 목록을 가져오는 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			// hideSpinner();
		},
		complete : function () {
			hideSpinner();
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
