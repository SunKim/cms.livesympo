<?php
	if ( !isset($session['logged_in']) || $session['logged_in'] != 1) {
		echo "<script>alert('로그인 정보가 없거나 만료되었습니다.\\n로그인 화면으로 이동합니다.');location.href='/login';</script>";
	} else {
		// echo "<script>alert('로그인 세션존재.');</script>";
		//echo "<script>console.log('Coil Donation Helper 관리자님 환영합니다.');</script>";
	}
?>
