<?php
 /**
  * RequestorModel.php
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

class RequestorModel extends Model {
	protected $table      = 'TB_PRJ_ENT_INFO_REQR_H';
	protected $primaryKey = 'PRJ_ENT_INFO_REQR_SEQ';

	// 사전등록자 목록
    public function list ($prjSeq) {
        $strQry  = "";

		$strQry .= "SELECT 	\n";
		$strQry .= "	EI.PRJ_ENT_INFO_REQR_SEQ, EI.REQR_SEQ, EI.REQR_NM, EI.MBILNO	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_1, '') AS ENT_INFO_EXTRA_VAL_1	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_2, '') AS ENT_INFO_EXTRA_VAL_2	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_3, '') AS ENT_INFO_EXTRA_VAL_3	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_4, '') AS ENT_INFO_EXTRA_VAL_4	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_5, '') AS ENT_INFO_EXTRA_VAL_5	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_6, '') AS ENT_INFO_EXTRA_VAL_6	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_7, '') AS ENT_INFO_EXTRA_VAL_7	\n";
		$strQry .= "        , IFNULL(EI.ENT_INFO_EXTRA_VAL_8, '') AS ENT_INFO_EXTRA_VAL_8	\n";
		$strQry .= "		, CASE WHEN EI.CONN_ROUTE_VAL = 1 THEN P.CONN_ROUTE_1	\n";
		$strQry .= "				WHEN EI.CONN_ROUTE_VAL = 2 THEN P.CONN_ROUTE_2	\n";
		$strQry .= "                WHEN EI.CONN_ROUTE_VAL = 3 THEN P.CONN_ROUTE_3	\n";
		$strQry .= "                ELSE ''	\n";
		$strQry .= "			END AS CONN_ROUTE_VAL_NM	\n";
		$strQry .= "		, EI.REG_DTTM	\n";
		$strQry .= "FROM TB_PRJ_ENT_INFO_REQR_H	AS EI	\n";
		$strQry .= "INNER JOIN TB_PRJ_M AS P	\n";
		$strQry .= "		ON (EI.PRJ_SEQ = P.PRJ_SEQ)	\n";
		$strQry .= "WHERE 1=1		\n";
		$strQry .= "	AND EI.PRJ_SEQ = ".$this->db->escape($prjSeq)."		\n";

        $strQry .= ";";

		// log_message('info', "RequestorModel - list. Qry - \n$strQry");
        return $this->db->query($strQry)->getResultArray();
    }
}
