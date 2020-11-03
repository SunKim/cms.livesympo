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
	protected $table      = 'TB_ADMIN_M';
	protected $primaryKey = 'ADM_SEQ';

	// 로그인 체크용 (이메일로 관리자 데이터 가져오기)
    public function checkLogin($email) {
      $strQry  = "";

      $strQry .= "SELECT ADM_SEQ, EMAIL, ADM_NM, PWD, LVL, ORG_NM, DEL_YN \n";
      $strQry .= "FROM TB_ADMIN_M \n";
      $strQry .= "WHERE 1=1 \n";
	  $strQry .= "	AND EMAIL = ".$this->db->escape($email)." \n";
	  // $strQry .= "	AND DEL_YN = 0	\n";

      $strQry .= ";";

      return $this->db->query($strQry)->getRowArray();
    }

	// 관리자 목록
    public function list($lvl = 0) {
      $strQry  = "";

	  $strQry .= "SELECT A.ADM_SEQ, A.EMAIL, A.ADM_NM, IFNULL(A.ORG_NM, '') AS ORG_NM, A.REGR_ID, A.REG_DTTM	\n";
	  $strQry .= "	, CASE WHEN A.LVL = 9 THEN '최고관리자'	\n";
	  $strQry .= "		WHEN A.LVL = 1 THEN '일반관리자'	\n";
	  $strQry .= "		WHEN A.LVL = 2 THEN '데이터관리자'	\n";
	  $strQry .= "		END AS LVL_NM	\n";
	  $strQry .= "	, IFNULL(GROUP_CONCAT(P.PRJ_TITLE), '') AS PRJ_TITLE_ARR	\n";
	  $strQry .= "FROM TB_ADMIN_M AS A	\n";
	  $strQry .= "LEFT OUTER JOIN TB_PRJ_M AS P	\n";
	  $strQry .= "		ON (A.ADM_SEQ = P.DATA_ADM_SEQ_1 OR A.ADM_SEQ = P.DATA_ADM_SEQ_2)	\n";
	  $strQry .= "WHERE 1=1	\n";
	  $strQry .= "	AND A.DEL_YN = 0	\n";
	  $strQry .= "GROUP BY A.ADM_SEQ, A.EMAIL, A.ADM_NM, A.ORG_NM, A.REGR_ID, A.REG_DTTM, A.LVL	\n";

	  if ($lvl > 0) {
		  $strQry .= "	AND A.LVL = ".$this->db->escape($lvl)."	\n";
	  }

	  $strQry .= "ORDER BY LVL DESC, ADM_SEQ	\n";

      $strQry .= ";";

      return $this->db->query($strQry)->getResultArray();
    }

	// 관리자 count
    public function count () {
        $strQry    = "";

		$strQry .= "SELECT 	\n";
		$strQry .= "	IFNULL(SUM(1), 0) AS CNT_ALL	\n";
		$strQry .= "	, IFNULL(SUM(IF (LVL = 9, 1, 0)), 0) AS CNT_9	\n";
		$strQry .= "        , IFNULL(SUM(IF (LVL = 1, 1, 0)), 0) AS CNT_1	\n";
		$strQry .= "        , IFNULL(SUM(IF (LVL = 2, 1, 0)), 0) AS CNT_2	\n";
		$strQry .= "FROM TB_ADMIN_M	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND DEL_YN = 0	\n";

        $strQry .= ";";

        return $this->db->query($strQry)->getRowArray();
    }

	// 관리자 상세
    public function getDetail($admSeq) {
      $strQry  = "";

	  $strQry .= "SELECT A.ADM_SEQ, A.EMAIL, A.ADM_NM, A.REGR_ID, A.REG_DTTM, A.LVL, A.ORG_NM, A.PWD	\n";
	  $strQry .= "	, CASE WHEN A.LVL = 9 THEN '최고관리자'	\n";
	  $strQry .= "		WHEN A.LVL = 1 THEN '일반관리자'	\n";
	  $strQry .= "		WHEN A.LVL = 2 THEN '데이터관리자'	\n";
	  $strQry .= "	END AS LVL_NM	\n";
	  $strQry .= "FROM TB_ADMIN_M AS A	\n";
	  $strQry .= "WHERE 1=1	\n";
	  $strQry .= "	AND A.DEL_YN = 0	\n";
	  $strQry .= "	AND A.ADM_SEQ = ".$this->db->escape($admSeq)."	\n";

      $strQry .= ";";

      return $this->db->query($strQry)->getRowArray();
    }

	// 관리자 상세 (이메일로)
    public function getDetailByEmail($email) {
      $strQry  = "";

	  $strQry .= "SELECT A.ADM_SEQ, A.EMAIL, A.ADM_NM, A.REGR_ID, A.REG_DTTM, A.LVL, A.ORG_NM, A.PWD	\n";
	  $strQry .= "	, CASE WHEN A.LVL = 9 THEN '최고관리자'	\n";
	  $strQry .= "		WHEN A.LVL = 1 THEN '일반관리자'	\n";
	  $strQry .= "		WHEN A.LVL = 2 THEN '데이터관리자'	\n";
	  $strQry .= "	END AS LVL_NM	\n";
	  $strQry .= "FROM TB_ADMIN_M AS A	\n";
	  $strQry .= "WHERE 1=1	\n";
	  $strQry .= "	AND A.DEL_YN = 0	\n";
	  $strQry .= "	AND A.EMAIL = ".$this->db->escape($email)."	\n";

      $strQry .= ";";

      return $this->db->query($strQry)->getRowArray();
    }

	// 신규관리자(TB_ADMIN_M) insert
    public function insertAdmin ($data) {
		$this->db->table('TB_ADMIN_M')->insert($data);
        return $this->db->insertID();
	}

	// 관리자(TB_ADMIN_M) update (삭제, 패스워드)
	public function updateAdmin ($admSeq, $data) {
        $builder = $this->db->table('TB_ADMIN_M');
		$builder->where('ADM_SEQ', $admSeq)->update($data);

        return $this->db->affectedRows();
	}
}
