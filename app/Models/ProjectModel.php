<?php
 /**
    * ProjectModel.php
    *
    * Livesympo ProjectModel Model
    *
    * @package        App
    * @subpackage Models
    * @author         20200914. SUN.
    * @copyright    Livesympo
    * @license        http://www.php.net/license/3_01.txt    PHP License 3.01
    * @link
    * @see
    * @since            2020.09.14
    * @deprecated
    */

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model {
    protected $table      = 'TB_PRJ_M';
	protected $primaryKey = 'PRJ_SEQ';

	// 프로젝트 목록
    public function list ($filter, $beginIndex, $endIndex) {
        $strQry    = "";

        $strQry .= "SELECT *	\n";
        $strQry .= "FROM TB_PRJ_M AS P	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND P.DEL_YN = 0	\n";

        // 프로젝트 타이틀
        if (isset($filter->prjTitle) && $filter->prjTitle != '') {
            $strQry .= "	AND P.PRJ_TITLE LIKE '%".$this->db->escapeLikeString($filter->prjTitle)."%' \n";
        }
        // 프로젝트 타이틀 URI
        if (isset($filter->prjTitleUri) && $filter->prjTitleUri != '') {
            $strQry .= "	AND P.PRJ_TITLE_URI LIKE '%".$this->db->escapeLikeString($filter->prjTitleUri)."%' \n";
        }
        // 시작일시
    	if (isset($filter->stDttm) && $filter->stDttm != '') {
    		$strQry .= "	AND P.ST_DTTM >= ".$this->db->escape($filter->stDttm)."	\n";
    	}
        // 종료일시
    	if (isset($filter->edDttm) && $filter->edDttm != '') {
    		$strQry .= "	AND P.ED_DTTM <= ".$this->db->escape($filter->edDttm)."	\n";
    	}

        $strQry .= "ORDER BY P.PRJ_SEQ DESC	\n";
        $strQry .= "LIMIT ".$this->db->escape($beginIndex).", ".$this->db->escape($endIndex)."	\n";

        $strQry .= ";";

        return $this->db->query($strQry)->getResultArray();
    }

    // 프로젝트 목록 건수
    public function count ($filter) {
        $strQry    = "";

        $strQry .= "SELECT 	\n";
        $strQry .= "    IFNULL(SUM(1), 0) AS CNT_ALL	\n";
        $strQry .= "    , IFNULL(SUM(IF(ST_DTTM > NOW(), 1, 0)), 0) AS CNT_COMING	\n";
        $strQry .= "    , IFNULL(SUM(IF(ED_DTTM < NOW(), 1, 0)), 0) AS CNT_COMP	\n";
        $strQry .= "    , IFNULL(SUM(IF(ST_DTTM <= NOW() AND ED_DTTM >= NOW(), 1, 0)), 0) AS CNT_ING	\n";
        $strQry .= "FROM TB_PRJ_M AS P	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND P.DEL_YN = 0	\n";

        // 프로젝트 타이틀
        if (isset($filter->prjTitle) && $filter->prjTitle != '') {
            $strQry .= "	AND P.PRJ_TITLE LIKE '%".$this->db->escapeLikeString($filter->prjTitle)."%' \n";
        }
        // 프로젝트 타이틀 URI
        if (isset($filter->prjTitleUri) && $filter->prjTitleUri != '') {
            $strQry .= "	AND P.PRJ_TITLE_URI LIKE '%".$this->db->escapeLikeString($filter->prjTitleUri)."%' \n";
        }
        // 시작일시
    	if (isset($filter->stDttm) && $filter->stDttm != '') {
    		$strQry .= "	AND P.ST_DTTM >= ".$this->db->escape($filter->stDttm)."	\n";
    	}
        // 종료일시
    	if (isset($filter->edDttm) && $filter->edDttm != '') {
    		$strQry .= "	AND P.ED_DTTM <= ".$this->db->escape($filter->edDttm)."	\n";
    	}

        $strQry .= ";";

        return $this->db->query($strQry)->getRowArray();
    }

    // 프로젝트 상세
    public function detail ($prjSeq) {
        $strQry    = "";

        $strQry .= "SELECT *	\n";
        $strQry .= "FROM TB_PRJ_M AS P	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND P.DEL_YN = 0	\n";
        $strQry .= "	AND P.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";

        $strQry .= ";";

        return $this->db->query($strQry)->getRowArray();
    }


}
