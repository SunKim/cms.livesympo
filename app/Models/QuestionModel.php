<?php
 /**
  * QuestionModel.php
  *
  * @package	App
  * @subpackage Models
  * @author	    20200914. SUN.
  * @copyright  Livesympo
  * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
  * @link
  * @see
  * @since		2020.09.14
  * @deprecated
  */

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model {
	// 하나의 프로젝트에 딸린 질문 list. $aprvYn = 1로 오면 승인된것만, 0이면 전체 다
	public function list($prjSeq, $aprvYn = 0) {
		$strQry  = "";

		$strQry .= "SELECT Q.QST_SEQ, Q.PRJ_SEQ, Q.REQR_SEQ, Q.QST_DESC, Q.REG_DTTM, Q.APRV_YN	\n";
		$strQry .= "	, MAX(IF(SERL_NO = 1, INPUT_VAL, NULL)) AS REQR_NM	\n";
		$strQry .= "    , MAX(IF(SERL_NO = 2, INPUT_VAL, NULL)) AS MBILNO	\n";
		$strQry .= "    , MAX(IF(SERL_NO = 3, INPUT_VAL, NULL)) AS HSPTL_NM	\n";
		$strQry .= "    , MAX(IF(SERL_NO = 4, INPUT_VAL, NULL)) AS SUBJ_NM	\n";
		$strQry .= "FROM TB_QST_M AS Q	\n";
		$strQry .= "INNER JOIN TB_PRJ_ENT_INFO_REQR_H AS I	\n";
		$strQry .= "		ON (Q.PRJ_SEQ = I.PRJ_SEQ AND Q.REQR_SEQ = I.REQR_SEQ)	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND Q.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";

		if ($aprvYn === 1) {
			$strQry .= "	AND Q.APRV_YN = 1	\n";
		}
		$strQry .= "GROUP BY Q.QST_SEQ, Q.PRJ_SEQ, Q.REQR_SEQ, Q.QST_DESC, Q.REG_DTTM, Q.APRV_YN	\n";
		$strQry .= "ORDER BY QST_SEQ	\n";

		$strQry .= ";";

		return $this->db->query($strQry)->getResultArray();
	}

}