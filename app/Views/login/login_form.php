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

<title>Live Sympo 관리자 로그인</title>

<!-- stylesheets -->
<link href="/css/sun.common.20200914.css" rel="stylesheet">
<link href="/css/cms.livesympo.css" rel="stylesheet">

<!-- loading spinner를 위한 font-awesome. <span class="fa fa-spinner fa-spin fa-3x". ></span>. 아이콘 참고 - https://fontawesome.com/v4.7.0/icons/ -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Bootstrap-select. cf) https://silviomoreto.github.io/bootstrap-select -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<!-- Custom styles for this template-->
<link href="/css/sb-admin-2.css" rel="stylesheet">

<!-- START) 메인 css -->
<style type="text/css">
</style>
<!-- END) 메인 css -->

</head>

<body>

<!-- 세션체크 -->
<!-- ?php include_once APPPATH.'Views/template/check_session.php'; ?> -->

<?php
    // $reqrSeq =  $session['reqrSeq'];
    // $reqrNm =  $session['reqrNm'];
?>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">LiveSympo CMS</h1>
                  </div>
                  <form class="user">
                    <div class="form-group">
                      <?php
                        // <input type="email" class="form-control form-control-user" id="email" aria-describedby="emailHelp" placeholder="Email" value="admin@livesympo.kr" />
                      ?>
                      <input type="email" class="form-control form-control-user" id="email" aria-describedby="emailHelp" placeholder="Email" value="" />
                    </div>
                    <div class="form-group">
                      <?php
                        // <input type="password" class="form-control form-control-user" id="pwd" placeholder="Password" value="1234" />
                      ?>
                      <input type="password" class="form-control form-control-user" id="pwd" placeholder="Password" value="" />
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <!-- <label class="custom-control-label" for="customCheck">Remember Me</label> -->
                      </div>
                    </div>
                    <a href="javascript:login();" class="btn btn-primary btn-user btn-block">
                      Login
                    </a>
                    <hr>
                    <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->
                  </form>
                  <!-- <hr> -->
                  <!-- <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.html">Create an Account!</a>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
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
    // showSpinner(1000);

    // modal1('타이틀', '메세지랑께');
}

// 로그인 버튼
function login () {
	// validation
	if ( !checkEmail($('#email').val()) ) {
		alert('이메일을 확인해주세요.');
		return false;
	}

	if ( $('#pwd').val().length < 4 ) {
		alert('패스워드는 4자리 이상 입력해주세요.');
		return false;
	}

	showSpinner();

	$.ajax({
		type: 'POST',
		url: '/login/checkLogin',
		dataType: 'json',
		cache: false,
		data: {
			email : $('#email').val()
			, pwd : $('#pwd').val()
		},

		success: function(data) {
			console.log(data);
			if ( data.resCode == '0000' ) {
				// home으로 이동
				location.href='/dashboard';
			} else {
				modal1('경고', '로그인 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				// centerModal1('경고', '로그인 도중 오류가 발생했습니다. 관리자에게 문의해주세요.<br><br>코드(resCode):'+data.resCode+'<br>메세지(resMsg):'+data.resMsg);
				// alert('로그인 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드(resCode):'+data.resCode+'\n메세지(resMsg):'+data.resMsg);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			// centerModal1('경고', '로그인 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
			alert('로그인 도중 오류가 발생했습니다.\n관리자에게 문의해주세요.\n\n코드:'+xhr.status+'\n메세지:'+thrownError);
		},
		complete : function () {
			hideSpinner();
		}
	});
}

$(document).ready(function () {
    fnInit();

    //submit 되기 전 처리
    $('form').submit(function( event ) {

    });
});

</script>

</body>

</html>
