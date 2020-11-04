<?php
 /**
  * SurveyModel.php
  *
  * @package	App
  * @subpackage Models
  * @author	    20201007. SUN.
  * @copyright  Livesympo
  * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
  * @link
  * @see
  * @since		2020.09.14
  * @deprecated
  */

namespace App\Models;

use CodeIgniter\Model;

class SurveyModel extends Model {
	// 하나의 프로젝트에 딸린 설문질문 list.
	public function surveyQstList($prjSeq) {
		$strQry  = "";

		$strQry .= "SELECT SURVEY_QST_SEQ, PRJ_SEQ, QST_NO, QST_TITLE, QST_TP, QST_MULTI_YN	\n";
		$strQry .= "FROM TB_SURVEY_QST_M	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";
		$strQry .= "ORDER BY QST_NO	\n";

		$strQry .= ";";
		// log_message('info', "SurveyModel - surveyQstList. Qry - \n$strQry");

		return $this->db->query($strQry)->getResultArray();
	}

	// 하나의 프로젝트에 딸린 설문질문에 대한 보기 list
	public function surveyQstChoiceList($prjSeq) {
		$strQry  = "";

		$strQry .= "SELECT SURVEY_QST_CHOICE_SEQ, SURVEY_QST_SEQ, PRJ_SEQ, QST_NO, CHOICE_NO, CHOICE	\n";
		$strQry .= "FROM TB_SURVEY_QST_CHOICE_D	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";
		$strQry .= "ORDER BY QST_NO, CHOICE_NO	\n";

		$strQry .= ";";
		// log_message('info', "SurveyModel - surveyQstChoiceList. Qry - \n$strQry");

		return $this->db->query($strQry)->getResultArray();
	}

	// 하나의 프로젝트에 딸린 설문질문에 대한 참여자들의 답변 list
	public function surveyAswList($prjSeq) {
		$strQry  = "";

		$strQry .= "SELECT SA.SURVEY_ASW_SEQ, SA.PRJ_SEQ, SA.REQR_SEQ, DATE_FORMAT(SA.REG_DTTM, '%Y-%m-%d %H:%i') AS ASW_DTTM	\n";
		$strQry .= "	, IFNULL(SA.ASW_1, '') AS ASW_1	\n";
		$strQry .= "	, IFNULL(SA.ASW_2, '') AS ASW_2	\n";
		$strQry .= "	, IFNULL(SA.ASW_3, '') AS ASW_3	\n";
		$strQry .= "	, IFNULL(SA.ASW_4, '') AS ASW_4	\n";
		$strQry .= "	, IFNULL(SA.ASW_5, '') AS ASW_5	\n";
		$strQry .= "	, IFNULL(SA.ASW_6, '') AS ASW_6	\n";
		$strQry .= "	, IFNULL(SA.ASW_7, '') AS ASW_7	\n";
		$strQry .= "	, IFNULL(SA.ASW_8, '') AS ASW_8	\n";
		$strQry .= "	, IFNULL(SA.ASW_9, '') AS ASW_9	\n";
		$strQry .= "	, IFNULL(SA.ASW_10, '') AS ASW_10	\n";
		$strQry .= "    , EI.MBILNO, EI.REQR_NM	\n";
		$strQry .= "    , IFNULL(EI.ENT_INFO_EXTRA_VAL_1, '') AS ENT_INFO_EXTRA_VAL_1	\n";
		$strQry .= "    , IFNULL(EI.ENT_INFO_EXTRA_VAL_2, '') AS ENT_INFO_EXTRA_VAL_2	\n";
		$strQry .= "    , IFNULL(EI.ENT_INFO_EXTRA_VAL_3, '') AS ENT_INFO_EXTRA_VAL_3	\n";
		$strQry .= "FROM TB_SURVEY_ASW_REQR_H AS SA	\n";
		$strQry .= "INNER JOIN TB_PRJ_ENT_INFO_REQR_H AS EI	\n";
		$strQry .= "		ON (SA.PRJ_SEQ = EI.PRJ_SEQ AND SA.REQR_SEQ = EI.REQR_SEQ)	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND SA.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";

		$strQry .= ";";
		// log_message('info', "SurveyModel - surveyAswList. Qry - \n$strQry");

		return $this->db->query($strQry)->getResultArray();
	}

	// 하나의 프로젝트에 딸린 설문에 대한 참여자들 통계 => 필요하면 하자
	public function surveyAswStat($prjSeq) {
		$strQry  = "";

		$strQry .= ";";
		// log_message('info', "SurveyModel - surveyAswList. Qry - \n$strQry");

		return $this->db->query($strQry)->getResultArray();
	}

	// 설문질문마스터(TB_SURVEY_QST_M) insert
    public function insertSurveyQst ($data) {
		$this->db->table('TB_SURVEY_QST_M')->insert($data);
        return $this->db->insertID();
	}

	// 설문질문마스터(TB_SURVEY_QST_M) delete (수정을 할 경우 지우고 다시 insert)
	public function deleteSurveyQst ($prjSeq) {
		$builder = $this->db->table('TB_SURVEY_QST_M');
		$builder->where('PRJ_SEQ', $prjSeq)->delete();

		return $this->db->affectedRows();
	}

	// 설문질문보기상세(TB_SURVEY_QST_CHOICE_D) insert
    public function insertSurveyChoice ($data) {
		$this->db->table('TB_SURVEY_QST_CHOICE_D')->insert($data);
        return $this->db->insertID();
	}

	// 설문질문보기상세(TB_SURVEY_QST_CHOICE_D) delete (수정을 할 경우 지우고 다시 insert)
	public function deleteSurveyChoice ($prjSeq) {
		$builder = $this->db->table('TB_SURVEY_QST_CHOICE_D');
		$builder->where('PRJ_SEQ', $prjSeq)->delete();

		return $this->db->affectedRows();
	}
}
