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
    public function list ($prjSeq, $search) {
        $strQry  = "";

		$strQry .= "SELECT *	\n";
		$strQry .= "FROM (	\n";
		$strQry .= "	SELECT @rownum:=@rownum+1 AS ROWNUM, T.*	\n";
		$strQry .= "	FROM (	\n";
		$strQry .= "		SELECT 	\n";
		$strQry .= "			EI.PRJ_ENT_INFO_REQR_SEQ, EI.REQR_SEQ	\n";
		$strQry .= "			, TRIM(EI.REQR_NM) AS REQR_NM	\n";
		$strQry .= "			, TRIM(EI.MBILNO) AS MBILNO	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_1, '') AS ENT_INFO_EXTRA_VAL_1	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_2, '') AS ENT_INFO_EXTRA_VAL_2	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_3, '') AS ENT_INFO_EXTRA_VAL_3	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_4, '') AS ENT_INFO_EXTRA_VAL_4	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_5, '') AS ENT_INFO_EXTRA_VAL_5	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_6, '') AS ENT_INFO_EXTRA_VAL_6	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_7, '') AS ENT_INFO_EXTRA_VAL_7	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_8, '') AS ENT_INFO_EXTRA_VAL_8	\n";
		$strQry .= "				, CASE WHEN EI.CONN_ROUTE_VAL = 1 THEN P.CONN_ROUTE_1	\n";
		$strQry .= "						WHEN EI.CONN_ROUTE_VAL = 2 THEN P.CONN_ROUTE_2	\n";
		$strQry .= "						WHEN EI.CONN_ROUTE_VAL = 3 THEN P.CONN_ROUTE_3	\n";
		$strQry .= "						ELSE ''	\n";
		$strQry .= "					END AS CONN_ROUTE_VAL_NM	\n";
		$strQry .= "				, EI.REG_DTTM	\n";
		$strQry .= "		FROM TB_PRJ_ENT_INFO_REQR_H	AS EI	\n";
		$strQry .= "		INNER JOIN TB_PRJ_M AS P	\n";
		$strQry .= "				ON (EI.PRJ_SEQ = P.PRJ_SEQ)	\n";
		$strQry .= "		INNER JOIN (SELECT @rownum:=0) T2	\n";
		$strQry .= "		WHERE 1=1	\n";
		$strQry .= "			AND EI.PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";
		$strQry .= "		ORDER BY EI.REG_DTTM, EI.REQR_NM	\n";
		$strQry .= "	) AS T	\n";
		$strQry .= ") AS TT	\n";
		$strQry .= "WHERE 1=1	\n";

		if (isset($search['searchReqrNm']) && $search['searchReqrNm'] != '') {
			$strQry .= "	AND REQR_NM LIKE '%".$this->db->escapeLikeString($search['searchReqrNm'])."%'	\n";
		}
		if (isset($search['searchReqrMbilno']) && $search['searchReqrMbilno'] != '') {
			$strQry .= "	AND MBILNO LIKE '%".$this->db->escapeLikeString($search['searchReqrMbilno'])."%'	\n";
		}

		$strQry .= "ORDER BY ROWNUM	\n";
        $strQry .= ";";

		// log_message('info', "RequestorModel - list. Qry - \n$strQry");
        return $this->db->query($strQry)->getResultArray();
    }

	// 참석자 목록
    public function attendanceList ($prjSeq, $search) {
        $strQry  = "";

		$strQry .= "SELECT *	\n";
		$strQry .= "FROM (	\n";
		$strQry .= "	SELECT @rownum:=@rownum+1 AS ROWNUM, T.*	\n";
		$strQry .= "	FROM (	\n";
		$strQry .= "		SELECT EI.PRJ_ENT_INFO_REQR_SEQ, A.PRJ_SEQ, A.REQR_SEQ, A.DVC_GB	\n";
		$strQry .= "			, A.FIRST_ENTER_DTTM	\n";
		$strQry .= "			, LEAST(P.ED_DTTM, IFNULL(A.LAST_LEAVE_DTTM, P.ED_DTTM)) AS LAST_LEAVE_DTTM	\n";
		$strQry .= "			, EI.REQR_NM, EI.MBILNO	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_1, '') AS ENT_INFO_EXTRA_VAL_1	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_2, '') AS ENT_INFO_EXTRA_VAL_2	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_3, '') AS ENT_INFO_EXTRA_VAL_3	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_4, '') AS ENT_INFO_EXTRA_VAL_4	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_5, '') AS ENT_INFO_EXTRA_VAL_5	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_6, '') AS ENT_INFO_EXTRA_VAL_6	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_7, '') AS ENT_INFO_EXTRA_VAL_7	\n";
		$strQry .= "			, IFNULL(EI.ENT_INFO_EXTRA_VAL_8, '') AS ENT_INFO_EXTRA_VAL_8	\n";
		$strQry .= "			, CASE WHEN EI.CONN_ROUTE_VAL = 1 THEN P.CONN_ROUTE_1	\n";
		$strQry .= "				WHEN EI.CONN_ROUTE_VAL = 2 THEN P.CONN_ROUTE_2	\n";
		$strQry .= "				WHEN EI.CONN_ROUTE_VAL = 3 THEN P.CONN_ROUTE_3	\n";
		$strQry .= "				ELSE ''	\n";
		$strQry .= "				END AS CONN_ROUTE_VAL_NM	\n";
		$strQry .= "		FROM (	\n";
		$strQry .= "			SELECT 	\n";
		$strQry .= "				PRJ_SEQ, REQR_SEQ	\n";
		$strQry .= "				, MIN(IF(LOG_GB = 'ENTER', REG_DTTM, NULL)) AS FIRST_ENTER_DTTM	\n";
		$strQry .= "				, MAX(IF(LOG_GB = 'LEAVE', REG_DTTM, NULL)) AS LAST_LEAVE_DTTM	\n";
		$strQry .= "				, MAX(DVC_GB) AS DVC_GB	\n";
		$strQry .= "			FROM TB_REQR_LOG_H	\n";
		$strQry .= "			WHERE 1=1	\n";
		$strQry .= "				AND PRJ_SEQ = ".$this->db->escape($prjSeq)."	\n";
		$strQry .= "			GROUP BY PRJ_SEQ, REQR_SEQ	\n";
		$strQry .= "		) AS A	\n";
		$strQry .= "		INNER JOIN TB_PRJ_ENT_INFO_REQR_H AS EI	\n";
		$strQry .= "				ON (A.PRJ_SEQ = EI.PRJ_SEQ AND A.REQR_SEQ = EI.REQR_SEQ)	\n";
		$strQry .= "		INNER JOIN TB_PRJ_M AS P	\n";
		$strQry .= "				ON (A.PRJ_SEQ = P.PRJ_SEQ)	\n";
		$strQry .= "		INNER JOIN (SELECT @rownum:=0) T2	\n";
		$strQry .= "		ORDER BY FIRST_ENTER_DTTM, REQR_NM	\n";
		$strQry .= "	) AS T	\n";
		$strQry .= ") AS TT	\n";
		$strQry .= "WHERE 1=1	\n";

		if (isset($search['searchAttNm']) && $search['searchAttNm'] != '') {
			$strQry .= "	AND REQR_NM LIKE '%".$this->db->escapeLikeString($search['searchAttNm'])."%'	\n";
		}
		if (isset($search['searchAttMbilno']) && $search['searchAttMbilno'] != '') {
			$strQry .= "	AND MBILNO LIKE '%".$this->db->escapeLikeString($search['searchAttMbilno'])."%'	\n";
		}

        $strQry .= ";";

		// log_message('info', "RequestorModel - attendanceList. Qry - \n$strQry");
        return $this->db->query($strQry)->getResultArray();
    }

	// 프로젝트 사전등록자 목록 전체 삭제
	public function deleteAllRequestor ($prjSeq) {
      $strQry  = "";

      $strQry .= "DELETE  \n";
      $strQry .= "FROM TB_PRJ_ENT_INFO_REQR_H \n";
      $strQry .= "WHERE 1=1 \n";
      $strQry .= "  AND PRJ_SEQ = ".$this->db->escape($prjSeq)." \n";

      $strQry .= ";";

      $this->db->query($strQry);

      return $this->db->affectedRows();
    }

	//ajax - 프로젝트 사전등록자 선택 삭제 (TB_PRJ_ENT_INFO_REQR_H는 DEL_YN 없이 row 삭제)
    public function deleteRequestor ($prjSeq, $reqrSeq) {
		$builder = $this->db->table('TB_PRJ_ENT_INFO_REQR_H');
		$builder->where(['PRJ_SEQ' => $prjSeq, 'REQR_SEQ' => $reqrSeq])->delete();
        return $this->db->affectedRows();
	}

	//ajax - 프로젝트 사전등록자 선택 수정
    public function updateRequestor ($prjSeq, $reqrSeq, $data) {
		$builder = $this->db->table('TB_PRJ_ENT_INFO_REQR_H');
		$builder->where(['PRJ_SEQ' => $prjSeq, 'REQR_SEQ' => $reqrSeq])->update($data);

        return $this->db->affectedRows();
	}

	// 기존에 등록한 신청자인지 확인. 존재하면 REQR_SEQ. 없으면 0
	public function checkReqr ($reqrNm, $mbilno) {
		$strQry  = "";

		$strQry .= "SELECT IFNULL(MAX(REQR_SEQ), 0) AS REQR_SEQ	\n";
		$strQry .= "FROM TB_REQR_M	\n";
		$strQry .= "WHERE 1=1	\n";
		$strQry .= "	AND REQR_NM = ".$this->db->escape($reqrNm)."	\n";
		$strQry .= "    AND MBILNO = ".$this->db->escape($mbilno)."	\n";

		$strQry .= ";";
		// log_message('info', "projectModel - list. Qry - \n$strQry");

		return $this->db->query($strQry)->getRowArray()['REQR_SEQ'];
	}

	// 신청자마스터 (TB_REQR_M) insert
    public function insertReqr ($data) {
		$this->db->table('TB_REQR_M')->insert($data);
        return $this->db->insertID();
	}

	// 입장정보신청자등록이력 (TB_PRJ_ENT_INFO_REQR_H) insert
    public function insertEntInfoReqr ($data) {
		$this->db->table('TB_PRJ_ENT_INFO_REQR_H')->insert($data);
        return $this->db->insertID();
	}

	// 기존 등록된 데이터 존재여부 체크 - $prjSeq, $reqrSeq로 입장정보신청자등록이력 (TB_PRJ_ENT_INFO_REQR_H) select
    public function checkEntInfoReqr ($prjSeq, $reqrSeq) {
      $limit = 1;
      $offset = 0;
      return $this->db->table('TB_PRJ_ENT_INFO_REQR_H')->getWhere(['PRJ_SEQ' => $prjSeq, 'REQR_SEQ' => $reqrSeq], $limit, $offset)->getRowArray();
    }

	// 입장정보신청자등록이력 (TB_PRJ_ENT_INFO_REQR_H) update
    public function updateEntInfoReqr ($data) {
		$builder = $this->db->table('TB_PRJ_ENT_INFO_REQR_H');
		$builder->where(['PRJ_SEQ' => $data['PRJ_SEQ'], 'REQR_SEQ' => $data['REQR_SEQ']])->update($data);

        return $this->db->affectedRows();
	}
}
