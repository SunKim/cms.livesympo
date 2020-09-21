<!--
======== 사용 필요 조건
jQuery
-->

<!-- Toast 일반 -->
<span id="toast" class="toast"></span>

<!-- Toast용 style -->
<style type="text/css">
	span.toast { display:none;z-index:9000;position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);min-width:80%;padding:6px 20px;font-size:14px;background:#0009;color:#eee;border-radius:10px; }
</style>

<script language="javascript">

	function showToast(msg, intv) {
		$('#toast').text(msg);

		$('#toast').fadeIn(300);

		var dIntv = (typeof(intv) == 'number') ? intv : 2000;
		//console.log('dIntv : ' + dIntv);

		setTimeout(function () {
			$('#toast').fadeOut(300);
		}, dIntv);
	}

</script>
