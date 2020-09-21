<?php
 /**
  * TestModel.php
  *
  * Livesympo test용 Model
  * cf) Query Builder : http://ci4doc.cikorea.net/database/query_builder.html
  * cf) Model/Entity : http://ci4doc.cikorea.net/models/model.html
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

class TestModel extends Model {
    protected $table = 'TB_TEST_M';
    protected $primaryKey = 'PRJ_SEQ';

    // insert - sql 직접작성
    public function dbInsertQuery($colInt, $colVc) {
      $strQry  = "";

      $strQry .= "INSERT INTO TB_TEST_M(COL_INT, COL_VC) \n";
      $strQry .= "VALUES(".$this->db->escape($colInt).", ".$this->db->escape($colVc).") \n";

      $strQry .= ";";

      $this->db->query($strQry);

      // return $this->db->affectedRows();
      return $this->db->insertID();
    }

    // insert - builder 사용
    public function dbInsertBuilder($colInt, $colVc) {
      $data = [
        'COL_INT' => $colInt,
        'COL_VC'  => $colVc,
      ];

      $this->db->table('TB_TEST_M')->insert($data);

      // return $this->db->affectedRows();
      return $this->db->insertID();
    }

    // select 1건 - sql 직접작성
    public function dbSelectItemQuery($seq = 0) {
      $strQry  = "";

      $strQry .= "SELECT * \n";
      $strQry .= "FROM TB_TEST_M \n";
      $strQry .= "WHERE 1=1 \n";
      $strQry .= "  AND SEQ = ".$this->db->escape($seq)." \n";

      $strQry .= ";";

      return $this->db->query($strQry)->getRowArray();
    }

    // select 1건 - builder 사용
    public function dbSelectItemBuilder($seq = 0) {

      // return $this->db->table('TB_TEST_M')->get();

      $limit = 1;
      $offset = 0;
      return $this->db->table('TB_TEST_M')->getWhere(['SEQ' => $seq], $limit, $offset)->getRowArray();
    }

    // select list - sql 직접작성
    public function dbSelectListQuery() {
      $strQry  = "";

      $strQry .= "SELECT * \n";
      $strQry .= "FROM TB_TEST_M \n";
      $strQry .= "WHERE 1=1 \n";

      $strQry .= ";";

      return $this->db->query($strQry)->getResultArray();
    }

    // select list - builder 사용
    public function dbSelectListBuilder() {
      $builder = $this->db->table('TB_TEST_M');
      return $builder->select('SEQ, COL_INT, COL_VC')->get()->getResultArray();
    }

    // update - sql 직접작성
    public function dbUpdateQuery($colInt, $colVc) {
      $strQry  = "";

      $strQry .= "UPDATE TB_TEST_M \n";
      $strQry .= "SET COL_VC = ".$this->db->escape($colVc)." \n";
      $strQry .= "WHERE 1=1 \n";
      $strQry .= "  AND COL_INT = ".$this->db->escape($colInt)." \n";

      $strQry .= ";";

      $this->db->query($strQry);

      return $this->db->affectedRows();
    }

    // update - builder 사용
    public function dbUpdateBuilder($colInt, $colVc) {
      $builder = $this->db->table('TB_TEST_M');

      $data = [
        'COL_VC' => $colVc
      ];

      $builder->where('COL_INT', $colInt)->update($data);
      return $this->db->affectedRows();
    }

    // delete - sql 직접작성
    public function dbDeleteQuery($seq) {
      $strQry  = "";

      $strQry .= "DELETE  \n";
      $strQry .= "FROM TB_TEST_M \n";
      $strQry .= "WHERE 1=1 \n";
      $strQry .= "  AND SEQ = ".$this->db->escape($seq)." \n";

      $strQry .= ";";

      $this->db->query($strQry);

      return $this->db->affectedRows();
    }

	// delete - builder 사용
    public function dbDeleteBuilder($seq) {
      $builder = $this->db->table('TB_TEST_M');

      $builder->where('SEQ', $seq)->delete();
      return $this->db->affectedRows();
    }
}
