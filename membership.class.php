<?php
require_once 'database.php';

class Product {
    public $id = '';
    public $first_name = '';
    public $last_name = '';
    public $membership_date = '';
    public $expiry_date = '';
    public $mode_payment = ''; // Use the correct property name here
    public $payment_status = '';
    public $promo = '';
    public $phoneNo = '';


    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        $sql = "INSERT INTO members(first_name, last_name, membership_date, expiry_date, payment_status, mode_payment, promo, phoneNo) VALUES(:first_name, :last_name, :membership_date, :expiry_date, :payment_status, :mode_payment, :promo, :phoneNo)";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':membership_date', $this->membership_date);
        $query->bindParam(':payment_status', $this->payment_status);
        $query->bindParam(':mode_payment', $this->mode_payment); // Use the correct property name here
        $query->bindParam(':promo', $this->promo);
        $query->bindParam(':expiry_date', $this->expiry_date);
        $query->bindParam(':phoneNo', $this->phoneNo);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function showAll($keyword = '', $expiry_filter = '') {
        $sql = "SELECT * FROM members WHERE 1";  // Always true, selects all members

        if ($keyword !== '') {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword)";
        }

        if ($expiry_filter === 'expired') {
            // Filter for expired memberships (expiry date less than or equal to today)
            $sql .= " AND expiry_date <= CURDATE()";
        } elseif ($expiry_filter === 'expiring') {
            // Filter for expiring memberships (expiry date within the next 7 days)
            $sql .= " AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        }

        $sql .= " ORDER BY first_name ASC";  // Sort by first name

        $query = $this->db->connect()->prepare($sql);

        // Bind parameters with wildcards if needed
        if ($keyword !== '') {
            $keyword = "%$keyword%";
            $query->bindParam(':keyword', $keyword);
        }

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }
    
    
    
    function fetchRecord($recordId){
        $sql = "SELECT * FROM members WHERE id=:recordId";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordId', $recordId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function edit() {
        $sql = "UPDATE members 
                SET first_name=:first_name, 
                    last_name=:last_name, 
                    membership_date=:membership_date, 
                    expiry_date=:expiry_date, 
                    mode_payment=:mode_payment, 
                    payment_status=:payment_status,
                    phoneNo=:phoneNo 
                WHERE id=:id";
        
        $query = $this->db->connect()->prepare($sql);
    
        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':membership_date', $this->membership_date);
        $query->bindParam(':expiry_date', $this->expiry_date);
        $query->bindParam(':mode_payment', $this->mode_payment); // Fixed placeholder name
        $query->bindParam(':payment_status', $this->payment_status);
        $query->bindParam(':phoneNo', $this->phoneNo);
        $query->bindParam(':id', $this->id);
    
        return $query->execute();
    }
    

    
    function delete($recordId){
        $sql = "DELETE FROM members WHERE id = :recordId";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordId', $recordId);
    
        return $query->execute();
    }

    
}