<?php
 /**
  * SettingModel.php
  *
  * Livesympo Admin 계정용 Model
  *
  * @package    App
  * @subpackage Models
  * @author     20201209. SUN.
  * @copyright  Livesympo
  * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
  * @link
  * @see
  * @since      2020.12.09
  * @deprecated
  */

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model {
	protected $table      = 'TB_SETTING_M';
	protected $primaryKey = 'SET_SEQ';

	// 하나의 키에대한 값 확인
    public function value($setKey) {
      $strQry  = "";

      $strQry .= "SELECT SET_VAL \n";
      $strQry .= "FROM TB_SETTING_M \n";
      $strQry .= "WHERE 1=1 \n";
	  $strQry .= "	AND SET_KEY = ".$this->db->escape($setKey)." \n";
	  // $strQry .= "	AND DEL_YN = 0	\n";

      $strQry .= ";";

      return $this->db->query($strQry)->getRowArray()['SET_VAL'];
    }

	// 설정마스터(TB_SETTING_M) insert
    public function insertValue ($data) {
		$this->db->table('TB_SETTING_M')->insert($data);
        return $this->db->insertID();
	}

	// 설정마스터(TB_SETTING_M) update
	public function updateValue ($setKey, $data) {
        $builder = $this->db->table('TB_SETTING_M');
		$builder->where('SET_KEY', $setKey)->update($data);

        return $this->db->affectedRows();
	}
}
