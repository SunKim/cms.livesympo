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
	public function list($prjSeq, $aprvYn = 1, $orderBy = 'REG_DTTM DESC') {
		$strQry  = "";

		$strQry .= "SELECT Q.QST_SEQ, Q.PRJ_SEQ, Q.FAKE_YN, Q.REQR_SEQ, Q.FAKE_NM, Q.QST_DESC, Q.APRV_YN, Q.SEL_YN	\n";
		$strQry .= "	, DATE_FORMAT(Q.REG_DTTM, '%Y-%m-%d %H:%i') AS REG_DTTM	\n";
		$strQry .= "	, IF(P.ANONYM_USE_YN = 0, IFNULL(I.REQR_NM, '관리자'), '비사전등록') AS REQR_NM, I.MBILNO, I.ENT_INFO_EXTRA_VAL_1, I.ENT_INFO_EXTRA_VAL_2	\n";
		$strQry .= "FROM TB_QST_M AS Q	\n";
		$strQry .= "INNER JOIN TB_PRJ_M AS P	\n";
		$strQry .= "		ON (Q.PRJ_SEQ = P.PRJ_SEQ)	\n";
		$strQry .= "LEFT OUTER JOIN TB_PRJ_ENT_INFO_REQR_H AS I	\n";
		$strQry .= "		ON (Q.PRJ_SEQ = I.PRJ_SEQ AND Q.REQR_SEQ = I.REQR_SEQ)	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND Q.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";

		if ($aprvYn == 1) {
			$strQry .= "	AND Q.APRV_YN = 1	\n";
		}
		$strQry .= "ORDER BY $orderBy	\n";

		$strQry .= ";";
		// log_message('info', "QuestionModel - list. Qry - \n$strQry");

		return $this->db->query($strQry)->getResultArray();
	}

	// 선택된 질문 1건 가져오기
	public function getSelectedQuestion ($prjSeq) {
		$strQry  = "";

		$strQry .= "SELECT Q.QST_SEQ, Q.PRJ_SEQ, Q.FAKE_YN, Q.REQR_SEQ, Q.FAKE_NM, Q.QST_DESC, Q.APRV_YN, Q.SEL_YN	\n";
		$strQry .= "	, DATE_FORMAT(Q.REG_DTTM, '%Y-%m-%d %H:%i') AS REG_DTTM	\n";
		$strQry .= "	, IF(P.ANONYM_USE_YN = 0, IFNULL(I.REQR_NM, '관리자'), '비사전등록') AS REQR_NM, I.MBILNO, I.ENT_INFO_EXTRA_VAL_1, I.ENT_INFO_EXTRA_VAL_2	\n";
		$strQry .= "FROM TB_QST_M AS Q	\n";
		$strQry .= "INNER JOIN TB_PRJ_M AS P	\n";
		$strQry .= "		ON (Q.PRJ_SEQ = P.PRJ_SEQ)	\n";
		$strQry .= "LEFT OUTER JOIN TB_PRJ_ENT_INFO_REQR_H AS I	\n";
		$strQry .= "		ON (Q.PRJ_SEQ = I.PRJ_SEQ AND Q.REQR_SEQ = I.REQR_SEQ)	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND Q.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";
		$strQry .= "	AND Q.SEL_YN = 1	\n";
		$strQry .= "	AND Q.APRV_YN = 1	\n";
		$strQry .= "ORDER BY Q.REG_DTTM DESC	\n";
		$strQry .= "LIMIT 1	\n";
		$strQry .= ";";

		log_message('info', "QuestionModel - getSelectedQuestion. Qry - \n$strQry");

		$result = $this->db->query($strQry)->getRowArray();
		return $result ? $result : null;
	}

	// 질문(TB_QST_M) insert
	public function insertQuestion ($data) {
		$this->db->table('TB_QST_M')->insert($data);
		return $this->db->insertID();
	}

	// 질문(TB_QST_M) update (승인)
	public function updateQuestion ($qstSeq, $data) {
		$builder = $this->db->table('TB_QST_M');
		$builder->where('QST_SEQ', $qstSeq)->update($data);

		return $this->db->affectedRows();
	}

	// 질문(TB_QST_M) 선택. 선택된 질문은 SEL_YN을 1로. 나머지는 0으로
	public function selectQuestion ($prjSeq, $qstSeq) {
		$this->db->transStart();
		
		try {
			// 1. 해당 프로젝트의 모든 질문을 SEL_YN = 0으로 초기화
			$builder = $this->db->table('TB_QST_M');
			$builder->where('PRJ_SEQ', $prjSeq);
			$builder->update(['SEL_YN' => 0]);
			
			// 2. 선택된 질문만 SEL_YN = 1로 업데이트
			$builder = $this->db->table('TB_QST_M');
			$builder->where('QST_SEQ', $qstSeq);
			$builder->where('PRJ_SEQ', $prjSeq); // 보안을 위해 프로젝트 시퀀스도 확인
			$builder->update(['SEL_YN' => 1]);
			
			$this->db->transComplete();
			
			if ($this->db->transStatus() === FALSE) {
				return false;
			}
			
			return true;
		} catch (Exception $e) {
			$this->db->transRollback();
			log_message('error', 'QuestionModel - selectQuestion error: ' . $e->getMessage());
			return false;
		}
	}

	// 질문(TB_QST_M) delete
	public function deleteQuestion ($qstSeq) {
		$builder = $this->db->table('TB_QST_M');
		$builder->where('QST_SEQ', $qstSeq)->delete();

		return $this->db->affectedRows();
	}
}