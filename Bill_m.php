<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Bill_m extends MY_Model{
	protected $_table_name = 'rt_billings';
	protected $_order_by = 'rt_billings.id desc';
	public function __construct(){
		parent::__construct();
	}
	public $rules = array(
		'username' => array(
			'field'=>'username',
			'label'=>'Custommer Username',
			'rules'=>'trim|required|callback__exist_username'
		),
		'reciept_no' => array(
			'field'=>'reciept_no',
			'label'=>'Reciept Number',
			'rules'=>'trim|max_lenght[50]|'
		),
		'paid_amount' => array(
			'field'=>'paid_amount',
			'label'=>'Bill Amount',
			'rules'=>'trim|required|is_natural_no_zero|callback__bill_exist'
		),
		'instant_discount' => array(
			'field'=>'instant_discount',
			'label'=>'Bill Discount',
			'rules'=>'trim|is_natural'
		),
		'remarks' => array(
			'field'=>'remarks',
			'label'=>'Remarks Note',
			'rules'=>'trim'
		)
	);

	public $rules_edit = array(
		'username' => array(
			'field'=>'username',
			'label'=>'Custommer Username',
			'rules'=>'trim|required'
		),
		'paid' => array(
			'field'=>'paid',
			'label'=>'Bill Amount',
			'rules'=>'trim|required|callback__bill_exist'
		),
		'instant_discount' => array(
			'field'=>'instant_discount',
			'label'=>'Instant Discount',
			'rules'=>'trim|is_natural'
		),
		'remarks' => array(
			'field'=>'remarks',
			'label'=>'Remarks Note',
			'rules'=>'trim'
		)
	);

	//get STDclass for empty field array
	public function get_new(){
		$netbill = new stdClass();
		$netbill->username = '';
        $netbill->bill_month = date("F-Y");
        $netbill->pay_date = date('d-m-Y');
        $netbill->paid_amount = '';
        $netbill->instant_discount = 0;
        $netbill->reciept_no = 0;
        $netbill->remarks = '';
		return $netbill;
	}

	public function get_total_bill($date){
		$this->db->select('amount');
		if($date){
			$this->db->where('pay_date', $date);
		}
		$query = parent::get();


			return array_sum($query);
	}

	public function bill_exist($customer_id, $month = NULL){

			//set default month
			if($month == NULL){
				$month = date('F-Y');
			}

			//check bill exist or not
			$this->db->select('id');
			$this->db->where(array('customer_id'=>$customer_id, 'bill_month'=>$month));
			$query = parent::get();

			return ( count( $query ) ) ? true : false;
	}

	public function bill_exist_this_month(){
		$month = date('F-Y');

		//check bill exist or not
		$this->db->select('id');
		$this->db->where('bill_month', $month);
		$query = parent::get();
		return ( count( $query ) ) ? true : false;
	}
}