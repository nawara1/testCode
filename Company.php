
<?php
require_once ("Top.php");

echo "I am in Company.php now"

class Company {
	private $companyid;
	private $companyname;
	private $companyinit;
	private $poc;
	private $deactivated;
	public static function getCompanies() {
		$dbh = getDbLink ();
		$statement = $dbh->prepare ( 'SELECT * FROM company' );
		$statement->setFetchMode ( PDO::FETCH_CLASS, 'Company' );
		$statement->execute ();
		$companies = array ();
		while ( $row = $statement->fetch () ) {
			$company = array (
					"companyid" => $row->getCompanyid (),
					"companyname" => $row->getCompanyname (),
					"companyinit" => $row->getCompanyinit (),
					"poc" => $row->getPoc () 
			);
			array_push ( $companies, $company );
		}
		// debug($row);
		return $companies;
	}
	public static function addCompany($company) {
		// var_dump($division);
		$dbh = getDbLink ();
		$dbh->beginTransaction ();
		$dbh->setAttribute ( PDO::ATTR_ERRMODE, SQL_DEBUG_LEVEL );
		$sql = "insert into company (companyname, companyinit, poc) values (?,?,?)";
		$sql = $dbh->prepare ( $sql );
		$success = $sql->execute ( array (
				$company->companyname,
				$company->companyinit,
				@$company->poc 
		) );
		if ($success) {
			$companyid = $dbh->lastInsertId ();
		}
		
		if ($success) {
			$dbh->commit ();
			return array (
					"message" => "Company Saved",
					"code" => 0 
			);
		} else {
			$dbh->rollBack ();
			return array (
					"message" => "Company Save Failed",
					"code" => 1 
			);
		}
	}
	
	public static function updateCompany($company) {
		$dbh = getDbLink ();
		$dbh->beginTransaction ();
		$sql = "update company set companyname = ?, companyinit = ?, poc = ?, deactived = ? where companyid = ?";
		$sql = $dbh->prepare ( $sql );
// 		if (! @$company->divisionid) {
// 			$company->divisionid = "";
// 		}
		$success = $sql->execute ( array (
				$company->companyname,
				$company->companyinit,
				@$company->poc,
				@$company->deactivated,
				$company->companyid
		));
// 		) );
		if ($success) {
			$dbh->commit ();
			return array (
					"message" => "Division Saved",
					"code" => 0 
			);
		} else {
			$dbh->rollBack ();
			// debug($sql->rowCount());
			return array (
					"message" => "Save Failed",
					"code" => 1 
			);
		}
	}
	public function getCompanyid() {
		return $this->companyid;
	}
	public function setCompanyid($companyid) {
		$this->companyid = $companyid;
	}
	public function getCompanyname() {
		return $this->companyname;
	}
	public function setCompanyname($companyname) {
		$this->companyname = $companyname;
	}
	public function getCompanyinit() {
		return $this->companyinit;
	}
	public function setCompanyinit($companyinit) {
		$this->companyinit = $companyinit;
	}
	public function getPoc() {
		return $this->poc;
	}
	public function setPoc($poc) {
		$this->poc = $poc;
	}
}
