<!--
======== 사용 필요 조건
jQuery
Bootstrap
FontAwesome

<!-- loading spinner를 위한 font-awesome. <span class="fa fa-spinner fa-spin fa-3x"></span>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
-->

<!-- Modal 일반 -->
<div id="commonModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="commonModal1Title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="commonModal1Title">알림</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="commonModal1Content">메세지</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-primary">액션</button> -->
				<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Wide -->
<div id="commonModalWide1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="commonModalWide1Title" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="commonModalWide1Title">알림</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="commonModalWide1Content">메세지</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-primary">액션</button> -->
				<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Extra Wide -->
<div id="commonModalExwide1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="commonModalExwide1Title" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="commonModalExwide1Title">알림</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="commonModalExwide1Content">메세지</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-primary">액션</button> -->
				<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>

<!-- Vertical Center Modal 일반 -->
<div id="centerModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="centerModal1Title" aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog vertical-align-center">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="centerModal1Title">알림</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="centerModal1Content">메세지</div>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-primary">액션</button> -->
					<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Vertical Center Modal - 확인/취소 버튼. 확인 클릭시 callback -->
<div id="centerModalConfirm1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="centerModalConfirm1Title" aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog vertical-align-center">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="centerModalConfirm1Title">알림</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="centerModalConfirm1Content">메세지</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default confirm-cancel" data-dismiss="modal">취소</button>
					<button type="button" class="btn btn-primary confirm-agree">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Vertical Center Modal - 확인버튼만 있지만 확인 클릭시 callback  -->
<div id="centerModalConfirm2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="centerModalConfirm2Title" aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog vertical-align-center">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="centerModalConfirm2Title">알림</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="centerModalConfirm2Content">메세지</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary confirm-agree">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Vertical Center Modal용 style -->
<style type="text/css">
	.vertical-alignment-helper { display:table;height:100%;padding:10px;margin:0 auto;width:90%; }
	.vertical-align-center { display:table-cell;vertical-align: middle; }
</style>


<!-- Modal Extra Wide. 엑셀다운로드버튼 -->
<div id="excelModalExwide1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="excelModalExwide1Title" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="excelModalExwide1Title">알림</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="excelModalExwide1Content">메세지</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="downloadCsv();">엑셀 다운로드</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>


<!-- Loading Spinner Modal -->
<div id="spinnerModal" class="modal fade spinner-modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<i class="fa fa-spinner fa-spin fa-3x" style="color:#fff"></i>
			<p class="mt20" style="color:#fff;font-size:16px;">잠시만 기다려주세요.</p>
		</div>
	</div>
</div>

<!-- spinnerModal용 style -->
<style type="text/css">
	.spinner-modal .modal-dialog{ display:table;position:relative;margin:0 auto;top:calc(50% - 60px); }
	.spinner-modal .modal-dialog .modal-content{
		background-color:#fff0;border:none;text-align:center;background:transparent;
		-webkit-box-shadow: 0 5px 15px rgba(0,0,0,0);
		-moz-box-shadow: 0 5px 15px rgba(0,0,0,0);
		-o-box-shadow: 0 5px 15px rgba(0,0,0,0);
		box-shadow: 0 5px 15px rgba(0,0,0,0);
	}
	.spinner-modal .modal-dialog .modal-content i.fa-spinner { margin:0px auto; }

	.modal-xl { width: 90%;max-width:1200px; }
</style>


<script language="javascript">

	//엑셀(csv) 다운로드용 파일명 및 table id. 모달에서 바로 access가 불가능하므로 global로 선언.
	var _csvTableId = '';
	var _csvFileName = '';

	//일반모달 - 확인버튼
	function modal1(title, msg) {
		$('#commonModal1Title').text(title);
		$('#commonModal1Content').html(msg);
		$('#commonModal1').modal();
	}

	//Wide 모달 - 확인버튼. Wide
	function modalWide1(title, msg) {
		$('#commonModalWide1Title').text(title);
		$('#commonModalWide1Content').html(msg);
		$('#commonModalWide1').modal();
	}

	//Extra Wide 모달 - 확인버튼.
	function modalExwide1(title, msg) {
		$('#commonModalExwide1Title').text(title);
		$('#commonModalExwide1Content').html(msg);
		$('#commonModalExwide1').modal();
	}

	//Extra Wide 모달 - 확인버튼. 엑셀다운로드 버튼
	function excelModalExwide1(title, msg, tableId, fileName) {
		$('#excelModalExwide1Title').text(title);
		$('#excelModalExwide1Content').html(msg);
		$('#excelModalExwide1').modal();

		_csvTableId = tableId;
		_csvFileName = fileName;
	}

	//Vertical Center 모달 - 확인버튼
	function centerModal1(title, msg) {
		$('#centerModal1Title').text(title);
		$('#centerModal1Content').html(msg);
		$('#centerModal1').modal();
	}

	//vertical Center 모달 - 확인/취소 버튼. 확인 클릭시 callback
	function centerModalConfirm1(title, msg, cb) {

		if ( typeof cb === 'function') {

			//기존에 이미 등록되어있던 callback 해제
			$('#centerModalConfirm1 .confirm-agree').off('click');

			$('#centerModalConfirm1Title').text(title);
			$('#centerModalConfirm1Content').html(msg);
			$('#centerModalConfirm1').modal();

			//확인버튼 클릭시 callback 수행
			$('#centerModalConfirm1 .confirm-agree').on('click', function() {
				cb();
				$('#centerModalConfirm1').modal('hide');
			});
		} else {
			alert('Callback이 지정되지 않았습니다.');
		}
	}

	//vertical Center 모달 - 확인버튼만 있지만 확인 클릭시 callback
	function centerModalConfirm2(title, msg, cb) {

		if ( typeof cb === 'function') {

			//기존에 이미 등록되어있던 callback 해제
			$('#centerModalConfirm2 .confirm-agree').off('click');

			$('#centerModalConfirm2Title').text(title);
			$('#centerModalConfirm2Content').html(msg);
			$('#centerModalConfirm2').modal();

			//확인버튼 클릭시 callback 수행
			$('#centerModalConfirm2 .confirm-agree').on('click', function() {
				cb();
				$('#centerModalConfirm2').modal('hide');
			});
		} else {
			alert('Callback이 지정되지 않았습니다.');
		}
	}

	//스피너 모달 보여주기. param이 숫자로 넘어올경우 해당 시간(ms) 이후 없어짐.
	function showSpinner(intv) {

		$('#spinnerModal').modal('show');

		if ( typeof(intv) == 'number') {
			setTimeout(function () {
				$('#spinnerModal').modal('hide');
			}, intv);
		}
	}

	//스피너 모달 숨기기.
	function hideSpinner() {
		$('#spinnerModal').modal('hide');
	}

	//엑셀 다운로드 버튼 클릭
	function downloadCsv() {
		//참고) https://codepen.io/kostas-krevatas/pen/mJyBwp

		var tableId = _csvTableId;
		var fileName = _csvFileName;

		var _tableToCSV = function(table) {
			// We'll be co-opting `slice` to create arrays
			var slice = Array.prototype.slice;

			return slice
				.call(table.rows)
				.map(function(row) {
					return slice
						.call(row.cells)
						.map(function(cell) {
							return '"t"'.replace("t", cell.textContent);
						})
						.join(",");
				})
				.join("\r\n");
		};

		var _downloadAnchor = function(content, ext) {
			var anchor = document.createElement("a");
			anchor.style = "display:none !important";
			anchor.id = "downloadanchor";
			document.body.appendChild(anchor);

			// If the [download] attribute is supported, try to use it

			if ("download" in anchor) {
				anchor.download = fileName + "." + ext;
			}
			anchor.href = content;
			anchor.click();
			anchor.remove();
		}

		// Generate our CSV string from out HTML Table
		var csv = _tableToCSV(document.getElementById(tableId));
		// Create a CSV Blob
		var blob = new Blob(['\ufeff', csv], { type: "text/csv" });

		// Determine which approach to take for the download
		if (navigator.msSaveOrOpenBlob) {
			// Works for Internet Explorer and Microsoft Edge
			navigator.msSaveOrOpenBlob(blob, fileName + ".csv");
		} else {
			_downloadAnchor(URL.createObjectURL(blob), 'csv');
		}
	}


</script>
