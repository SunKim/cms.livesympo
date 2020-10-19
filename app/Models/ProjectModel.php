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
        $strQry  = "";

        $strQry .= "SELECT	\n";
		$strQry .= "	P.PRJ_SEQ, P.PRJ_TITLE, P.PRJ_TITLE_URI	\n";
		$strQry .= "	, P.STREAM_URL, P.MAIN_IMG_URI, P.MAIN_IMG_THUMB_URI, P.AGENDA_IMG_URI, P.AGENDA_IMG_THUMB_URI, P.FOOTER_IMG_URI, P.FOOTER_IMG_THUMB_URI	\n";
		$strQry .= "	, P.APPL_BTN_BG_COLR, P.ENT_THME_COLR, P.AGENDA_PAGE_YN	\n";
		$strQry .= "	, DATE_FORMAT(P.ST_DTTM, '%Y-%m-%d %H:%i') AS ST_DTTM	\n";
		$strQry .= "	, DATE_FORMAT(P.ED_DTTM, '%Y-%m-%d %H:%i') AS ED_DTTM	\n";
		$strQry .= "	, REGR_ID,  DATE_FORMAT(P.REG_DTTM, '%Y-%m-%d %H:%i') AS REG_DTTM	\n";
		$strQry .= "	, IFNULL(R.REQR_CNT, 0) AS REQR_CNT	\n";
		$strQry .= "	, IFNULL(SQ.CNT_CHOICE, 0) AS CNT_CHOICE, IFNULL(SQ.CNT_SBJ, 0) AS CNT_SBJ	\n";
		$strQry .= "	, IFNULL(SA.ASW_CNT, 0) AS ASW_CNT	\n";
        $strQry .= "FROM TB_PRJ_M AS P	\n";
		$strQry .= "LEFT OUTER JOIN (	\n";
		$strQry .= "	SELECT PRJ_SEQ, COUNT(DISTINCT REQR_SEQ) AS REQR_CNT	\n";
		$strQry .= "	FROM TB_PRJ_ENT_INFO_REQR_H	\n";
		$strQry .= "	GROUP BY PRJ_SEQ	\n";
		$strQry .= ") AS R	\n";
		$strQry .= "		ON (P.PRJ_SEQ = R.PRJ_SEQ)	\n";
		$strQry .= "LEFT OUTER JOIN (	\n";
		$strQry .= "	SELECT 	\n";
		$strQry .= "		PRJ_SEQ	\n";
		$strQry .= "		, IFNULL(SUM(IF (QST_TP = '객관식', 1, 0)), 0) AS CNT_CHOICE	\n";
		$strQry .= "		, IFNULL(SUM(IF (QST_TP = '주관식', 1, 0)), 0) AS CNT_SBJ	\n";
		$strQry .= "	FROM TB_SURVEY_QST_M	\n";
		$strQry .= "	GROUP BY PRJ_SEQ	\n";
		$strQry .= ") AS SQ	\n";
		$strQry .= "		ON (P.PRJ_SEQ = SQ.PRJ_SEQ)	\n";
		$strQry .= "LEFT OUTER JOIN (	\n";
		$strQry .= "	SELECT PRJ_SEQ, COUNT(REQR_SEQ) AS ASW_CNT	\n";
		$strQry .= "        FROM TB_SURVEY_ASW_REQR_H	\n";
		$strQry .= "        GROUP BY PRJ_SEQ	\n";
		$strQry .= ") AS SA	\n";
		$strQry .= "		ON (P.PRJ_SEQ = SA.PRJ_SEQ)	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND P.DEL_YN = 0	\n";

        // 프로젝트 타이틀
        if (isset($filter->prjTitle) && $filter->prjTitle != '') {
            $strQry .= "	AND P.PRJ_TITLE LIKE '%".$this->db->escapeLikeString($filter->prjTitle)."%'	\n";
        }
        // 프로젝트 타이틀 URI
        if (isset($filter->prjTitleUri) && $filter->prjTitleUri != '') {
            $strQry .= "	AND P.PRJ_TITLE_URI LIKE '%".$this->db->escapeLikeString($filter->prjTitleUri)."%'	\n";
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

		// log_message('info', "ProjectModel - list. Qry - \n$strQry");
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

        $strQry .= "SELECT 	\n";
        $strQry .= "	P.PRJ_SEQ, P.PRJ_TITLE, P.PRJ_TITLE_URI	\n";
		$strQry .= "	, P.STREAM_URL, P.MAIN_IMG_URI, P.AGENDA_IMG_URI, P.FOOTER_IMG_URI	\n";
        $strQry .= "	, CONCAT('".$_ENV['app.baseURL']."', P.MAIN_IMG_URI) AS MAIN_IMG_URL	\n";
        $strQry .= "	, CONCAT('".$_ENV['app.baseURL']."', P.AGENDA_IMG_URI) AS AGENDA_IMG_URL	\n";
        $strQry .= "	, CONCAT('".$_ENV['app.baseURL']."', P.FOOTER_IMG_URI) AS FOOTER_IMG_URL	\n";
		$strQry .= "	, DATE_FORMAT(P.ST_DTTM, '%Y-%m-%d') AS ST_DATE	\n";
        $strQry .= "	, DATE_FORMAT(P.ST_DTTM, '%H:%i') AS ST_TIME	\n";
		$strQry .= "	, DATE_FORMAT(P.ED_DTTM, '%Y-%m-%d') AS ED_DATE	\n";
        $strQry .= "	, DATE_FORMAT(P.ED_DTTM, '%H:%i') AS ED_TIME	\n";
        $strQry .= "	, CONN_ROUTE_1, CONN_ROUTE_2, CONN_ROUTE_3	\n";
		$strQry .= "	, P.AGENDA_BTN_TEXT, P.SURVEY_BTN_TEXT, P.QST_BTN_TEXT	\n";
		$strQry .= "	, P.APPL_BODY_COLR, P.APPL_BTN_BG_COLR, P.APPL_BTN_FONT_COLR, P.APPL_BTN_ALIGN, P.ENT_THME_COLR, P.ENT_THME_HEIGHT	\n";
		$strQry .= "	, P.STREAM_BODY_COLR, P.STREAM_BTN_BG_COLR, P.STREAM_BTN_FONT_COLR, P.STREAM_QA_BG_COLR, P.STREAM_QA_FONT_COLR	\n";
        $strQry .= "	, ENT_INFO_EXTRA_1, ENT_INFO_EXTRA_PHOLDER_1, ENT_INFO_EXTRA_REQUIRED_1	\n";
        $strQry .= "	, ENT_INFO_EXTRA_2, ENT_INFO_EXTRA_PHOLDER_2, ENT_INFO_EXTRA_REQUIRED_2	\n";
        $strQry .= "	, ENT_INFO_EXTRA_3, ENT_INFO_EXTRA_PHOLDER_3, ENT_INFO_EXTRA_REQUIRED_3	\n";
        $strQry .= "	, ENT_INFO_EXTRA_4, ENT_INFO_EXTRA_PHOLDER_4, ENT_INFO_EXTRA_REQUIRED_4	\n";
        $strQry .= "	, ENT_INFO_EXTRA_5, ENT_INFO_EXTRA_PHOLDER_5, ENT_INFO_EXTRA_REQUIRED_5	\n";
        $strQry .= "	, ENT_INFO_EXTRA_6, ENT_INFO_EXTRA_PHOLDER_6, ENT_INFO_EXTRA_REQUIRED_6	\n";
		$strQry .= "	, ENT_INFO_EXTRA_7, ENT_INFO_EXTRA_PHOLDER_7, ENT_INFO_EXTRA_REQUIRED_7	\n";
		$strQry .= "	, ENT_INFO_EXTRA_8, ENT_INFO_EXTRA_PHOLDER_8, ENT_INFO_EXTRA_REQUIRED_8	\n";
        $strQry .= "FROM TB_PRJ_M AS P	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND P.DEL_YN = 0	\n";
        $strQry .= "	AND P.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";

        $strQry .= ";";

        return $this->db->query($strQry)->getRowArray();
    }

	// 프로젝트 URI 체크 (프로젝트 저장시 기존에 입력된 동일한 URI 존재여부 체크)
    public function checkTitleUri ($titleUri, $prjSeq) {
        $strQry    = "";

        $strQry .= "SELECT 1	\n";
        $strQry .= "FROM TB_PRJ_M AS P	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND P.DEL_YN = 0	\n";
        $strQry .= "	AND P.PRJ_SEQ <> ".$this->db->escape($prjSeq)."	\n";
        $strQry .= "	AND P.PRJ_TITLE_URI = ".$this->db->escape($titleUri)."	\n";

        $strQry .= ";";

		// log_message('info', "ProjectModel - checkTitleUri. Qry - \n$strQry");
        return $this->db->query($strQry)->getResultArray();
    }

    // 프로젝트 insert
	public function insertProject ($data) {
		$this->db->table('TB_PRJ_M')->insert($data);
        return $this->db->insertID();
	}

    // 프로젝트 update
	public function updateProject ($prjSeq, $data) {
		$builder = $this->db->table('TB_PRJ_M');
		$builder->where('PRJ_SEQ', $prjSeq)->update($data);

        return $this->db->affectedRows();
	}

	// 프로젝트 입장가이드 목록
    public function enterGuideList ($prjSeq) {
        $strQry    = "";

        $strQry .= "SELECT *	\n";
        $strQry .= "FROM TB_PRJ_ENT_GUIDE_M AS EG	\n";
        $strQry .= "WHERE 1=1	\n";
        $strQry .= "	AND EG.DEL_YN = 0	\n";
        $strQry .= "	AND EG.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";
		$strQry .= "ORDER BY SERL_NO	\n";

        $strQry .= ";";

		// log_message('info', "ProjectModel - checkTitleUri. Qry - \n$strQry");
        return $this->db->query($strQry)->getResultArray();
    }

	// 프로젝트 입장가이드 insert
	public function insertEnterGuide ($data) {
		$this->db->table('TB_PRJ_ENT_GUIDE_M')->insert($data);
        return $this->db->insertID();
	}

	// 프로젝트 입장가이드 delete (update가 아니라 delete 후 insert)
	public function deleteEnterGuide ($prjSeq) {
		$builder = $this->db->table('TB_PRJ_ENT_GUIDE_M');
		$builder->where('PRJ_SEQ', $prjSeq)->delete();

		return $this->db->affectedRows();
	}
}
