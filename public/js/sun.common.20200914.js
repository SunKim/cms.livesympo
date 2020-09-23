// 공통함수. 20200914. SUN.

// 빈값 체크 - '', null, undefined, NaN, [], {} 모두 true로 return
function isEmpty (val) {
  return !val || val === '' || val === null || val === undefined || Number.isNaN(val) || (val !== null && typeof val === 'object' && !Object.keys(val).length)
}

// 숫자에 콤마
function setComma (num) {
  if (typeof (num * 1) !== 'number') {
    return 0
  }
  return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

// 콤마 표시, 값이 없거나 0일경우 - 로 반환 an
function setCommaDash (num) {
  if (typeof (num * 1) !== 'number' || num * 1 === 0) {
    return '-'
  }
  return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

// 문자열 입력받아서 숫자만 return
function numberfy (num) {
  return num.replace(/[^0-9.]/g, '')
}

// 범위 내의 숫자 생성
function generateInt (start, end) {
  return Array(end - start + 1).fill().map((_, idx) => start + idx)
}

// validation 관련
// 숫자 체크
function checkInt (num, min, max) {
  // console.log(`num : ${num}`)
  if (!num) {
    return false
  }
  if (num === '' || typeof (num * 1) !== 'number' || !Number.isInteger(num * 1) || Number.isNaN(num)) {
    return false
  }
  if ((min && num < min) || (max && num > max)) {
    return false
  }

  return true
}

// 금액 체크
function checkCurrency (num, min, max) {
  return this.checkInt(num, min, max)
}

// 날짜 체크
function checkDate (date) {
  /* eslint-disable-next-line */
  const re = /^(19|20)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/

  return re.test(date)
}

// 시간 체크
function checkTime (time) {
  /* eslint-disable-next-line */
  const re = /^(0[1-9]|[01][0-9]|2[0-3]):([0-5][0-9])$/

  return re.test(time)
}

// 전화번호/휴대폰번호 형식 체크
function checkMobile (mobile) {
  /* eslint-disable-next-line */
  const re = /(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/

  return re.test(mobile)
}

// 계좌번호 형식 체크
function checkAcnutNo (acnutNo) {
  /* eslint-disable-next-line */
  const re = /^(\d+-?)+\d+$/

  return re.test(acnutNo)
}

// 이메일 주소 체크
function checkEmail (email) {
  /* eslint-disable-next-line */
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  // const re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  return re.test(String(email).toLowerCase())
}

// URL 체크
function checkUrl(url) {
  /* eslint-disable-next-line */
  const re = /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/

  return re.test(url);
}

// 퍼센트인지 체크. 소수점 1자리 (0.0 ~ 100.0)
function checkPercent (num, scale) {
  // alert(`num : =${num}=`)
  if (num === '' || typeof (num) === 'undefined' || typeof (num * 1) !== 'number' || num * 1 < 0 || num * 1 > 100) {
    return false
  }
  if (scale && Number.isInteger(scale) && (num + '').indexOf('.') > 0) {
    const tLength = (num + '').substring((num + '').indexOf('.') + 1)
    if (tLength.length > scale) {
      alert('소숫점 ' + scale + '자리만 입력가능. tLength : ' + tLength)
      return false
    }
  }

  return true
}

// 문자열 관련
// 문자열에 특수문자 포함 여부
function hasSpecialCharacter (str) {
  const re = /[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/

  return re.test(str)
}

function trim (str) {
  return str.replace(/^\s+|\s+$/g, '')
}

// 값이 없을 경우 -로 변환
// 숫자에 자릿수에 맞춰 0을 붙여줌. pad(5, 3) => '005'
function pad (n, width) {
  n = n + ''
  return n.length >= width
    ? n
    : new Array(width - n.length + 1).join('0') + n
}

// null값을 dash로 바꿔줌
function replaceNullToDash (text) {
  if (text === '' || text === null) {
    return '-'
  }
  return text
}

// 전화번호/휴대폰번호에 숫자 외의 다른 문자가 들어가있으면 제거 (이렇게 하고 다시 -를 넣으려고)
function simplifyMobile (mobile) {
  return mobile.replace(/[^0-9.]/g, '')
}

// 전화번호/휴대폰번호 format (즉 01011112222 받아서 010-1111-2222 리턴. 우선 숫자만 남기려면 simplifyMobile)
function formatMobile (orgMobile) {
  const mobile = this.simplifyMobile(orgMobile)
  if (typeof (mobile * 1) !== 'number' || mobile.length > 11 || mobile.length < 9) {
    return '전화번호가 형식에 맞지 않습니다.(숫자 9~11자리)'
  } else {
    return mobile.replace(/(\d{2,3})(\d{3,4})(\d{4})/, '$1-$2-$3')
  }
}

// Masking 관련
// 이름 마스킹
function maskName (name) {
  // console.log('maskName. name : ' + name)
  if (typeof (name) !== 'string') {
    return ''
  }

  if (name.length === 1) {
    return name
  } else if (name.length === 2) {
    return name.substr(0, 1) + '*'
  } else {
    return name.substr(0, 1) + '*'.repeat(name.length - 2) + name.substr(name.length - 1, 1)
  }
}

// 전화번호/휴대폰번호 가운데자리 마스킹 (010-1111-2222 => 010-****-2222)
// https://rios.tistory.com/entry/JS-Javascirpt-를-이용한-각종-정규식마스킹-방법
function maskPhone (mobile) {
  if (typeof (mobile) !== 'string') {
    return '문자열이 아닙니다. (' + mobile + ')'
  }

  if (mobile === '') {
    return ''
  }

  if (!mobile.match(/\d{2,3}-\d{3,4}-\d{4}/gi)) {
    return '전화번호/휴대폰 형식이 올바르지 않습니다. (' + mobile + ')'
  }

  let _mobile = ''
  // 00-000-0000 / 000-000-0000 형태
  if (/-[0-9]{3}-/.test(mobile)) {
    _mobile = mobile.replace(/-[0-9]{3}-/g, '-***-')
  }
  // 00-0000-0000 / 000-0000-0000 형태
  if (/-[0-9]{4}-/.test(mobile)) {
    _mobile = mobile.replace(/-[0-9]{4}-/g, '-****-')
  }

  // console.log('maskMobile(' + mobile + ') : ' + _mobile.replace(/(\d{3})(\d{4})(\d{4})/gi, '$1-****-$3'))
  return _mobile.replace(/(\d{3})(\d{3,4})(\d{4})/gi, '$1-****-$3')
}

// 숫자체크
function checkNumber (number) {
  const re = /^[0-9]*$/
  return re.test(number)
}

// keep-alive에서 화면갱신용 key(랜덤 문자열) 생성
function generateKey () {
  const _key = Math.random().toString(36).slice(2)
  // console.log('generateKey : ' + _key)
  return _key
}

// 임시 encryption (자동로그인 패스워드 저장등에 사용)
// cf) https://stackoverflow.com/questions/18279141/javascript-string-encryption-and-decryption
function cipher (salt) {
  const textToChars = text => text.split('').map(c => c.charCodeAt(0))
  const byteHex = n => ('0' + Number(n).toString(16)).substr(-2)
  const applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code)

  return text => text.split('').map(textToChars).map(applySaltToChar).map(byteHex).join('')
}

// 임시 decryption
function decipher (salt) {
  const textToChars = text => text.split('').map(c => c.charCodeAt(0))
  // let saltChars = textToChars(salt)
  const applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code)
  return encoded => encoded.match(/.{1,2}/g).map(hex => parseInt(hex, 16)).map(applySaltToChar).map(charCode => String.fromCharCode(charCode)).join('')
}

// q-uploader 사용시 이미지 넓이 체크하기 : (업로드파일, qUploader ref, 가로, 세로)
function checkImgSize (files, uploader, width = '', height = '') {
  files.map(file => {
    const img = new Image()
    img.src = window.URL.createObjectURL(file)
    img.onload = () => {
      if ((width && img.width !== width) || (height && img.height !== height)) {
        uploader.abort()
        uploader.removeFile(file)
        alert(`지정된 크기에 맞춰 업로드해주세요. \n${width ? `가로 : ${width}px` : ''} ${height ? `, 세로 : ${height}px` : ''}`)
      }
    }
  })
}

// input 박스 클릭시 0일 때 null ex) <input @foucs="emptyNumberInput" />
function emptyNumberInput (e) {
  // 입력된 데이터 타입이 문자열이고 0일 때
  if (typeof e.target.value === 'string' && e.target.value === '0') {
    e.target.value = null
  }
  // 입력된 데이터 타입이 숫자이고 0일 때
  if (typeof e.target.value === 'number' && e.target.value === 0) {
    e.target.value = null
  }
}
