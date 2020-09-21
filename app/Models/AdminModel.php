<?php
 /**
  * AdminModel.php
  *
  * Livesympo Admin 계정용 Model
  *
  * @package    App
  * @subpackage Models
  * @author     20200914. SUN.
  * @copyright  Livesympo
  * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
  * @link
  * @see
  * @since      2020.09.14
  * @deprecated
  */

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model {
	// 로그인 체크
    public function checkLogin($email) {
      $strQry  = "";

      $strQry .= "SELECT ADM_SEQ, EMAIL, PWD, LVL, DEL_YN \n";
      $strQry .= "FROM TB_ADMIN_M \n";
      $strQry .= "WHERE 1=1 \n";
	  $strQry .= "	AND EMAIL = ".$this->db->escape($email)." \n";
	  // $strQry .= "	AND DEL_YN = 0	\n";

      $strQry .= ";";

      return $this->db->query($strQry)->getRowArray();
    }
}
