<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//The Customers Controller
/*
Note : Important Customer Rules
	Status : (0=>Pending, 3=>Suspend Request, 5=>Delete Request)
	Status : (1=>Activated, 7=>Suspended, 9=>Deleted)
*/
	
class Customers extends Admin_Controller{
	// protected $mikrotik;
	//construct function
	function __construct(){
		parent::__construct();
		$this->load->model(array('dashboard/customer_m', 'dashboard/user_m', 'dashboard/packege_m', 'dashboard/bill_m', 'dashboard/payment_m', 'customer_request_m', 'pkg_change_m', 'dashboard/area_m'));
		$this->load->library('mikrotik');

		//Active Customers
		$this->db->where('status', 1);
		$this->data['active_customer_count'] = $this->db->count_all_results('rt_customers');

		$this->data['total_customer_count'] = $this->db->count_all_results('rt_customers');

		//active request counter
		$this->db->where('status', 0);
		$this->data['pending_customer_count'] = $this->db->count_all_results('rt_customers');

		//Suspend Request Counter
		$this->db->where('status', 7);
		$this->data['inactive_customer_count'] = $this->db->count_all_results('rt_customers');

		//Delete Request Counter
		$this->db->where('status', 9);
		$this->data['disable_customer_count'] = $this->db->count_all_results('rt_customers');

		//Total request count
		$this->db->where('status', 0);
		$this->data['total_request_count'] = $this->db->count_all_results('rt_customers_request');
	}

	public function fetch() {
		if( $this->mikrotik->_enabled() ) {
			$clients = $this->mikrotik->getAll();
			if( is_array( $clients ) && count( $clients ) ) {
				foreach ( $clients as $client ) {
					$this->db->where( 'username', $client['name'] );
					$ok = $this->db->update( 'rt_customers', array( 'mktikId' => $client['.id'] ) );
					echo ( $ok ) ? 'Ok <br />' : 'No <br />';
				}
			}
		}
	}

	//Index function
	public function index( $start=NULL ) {
		// $days_remain=date('t') - date('j');
		// $bill_amount = 1500 / 30;
		// $bill_amount = $bill_amount * $days_remain;

		$this->db->where(array('rt_customers.status'=>1));
		$count = $this->db->count_all_results('rt_customers');

		//set up pagination
    	$perpage = 50;
    	if ($count > $perpage){
    		$this->load->library('pagination');

    		$config['base_url'] = site_url('dashboard/customers/index/');
    		$config['total_rows'] = $count;
    		$config['per_page'] = $perpage;
    		$config['uri_segment'] = 4;
    		$this->pagination->initialize($config);

    		$this->data['pagination'] = $this->pagination->create_links();
    		$offset =$this->uri->segment(4);
    	}else{
    		$this->data['pagination'] = '';
    		$offset = 0;
    	}

		//get data
		$this->db->select('rt_customers.*, rt_packeges.packege_name, rt_packeges.bill_amount');
		$this->db->join('rt_billings', 'rt_customers.id=rt_billings.customer_id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->where(array('rt_customers.status'=>1, 'rt_billings.bill_month'=>date('F-Y')));
		$this->db->order_by('rt_customers.id', 'desc');
    	$this->db->limit($perpage, $offset);
		$this->data['customers'] = $this->customer_m->get();

		//load template
		$this->data['title'] = 'Active Customers';
		$this->data['subview'] = 'admin/customer/index';
		$this->load->view('admin/_layout', $this->data);
	}

	//Index function
	public function search(){

		// $days_remain=date('t') - date('j');
		// $bill_amount = 1500 / 30;
		// $bill_amount = $bill_amount * $days_remain;
		$term = $this->security->xss_clean($_GET['s']);

		$this->db->like('username', $term);
		$this->db->or_like('name', $term);
		$this->db->or_like('email', $term);
		$this->db->or_like('cell_1', $term);
		$this->db->or_like('cell_2', $term);
		$count = $this->db->count_all_results('rt_customers');

		//set up pagination
    	$perpage = 50;
    	if ($count > $perpage){
    		$this->load->library('pagination');
    		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    		$config['base_url'] = site_url('dashboard/customers/search/');
    		$config['total_rows'] = $count;
    		$config['per_page'] = $perpage;
    		$config['uri_segment'] = 4;
    		$this->pagination->initialize($config);

    		$this->data['pagination'] = $this->pagination->create_links();
    		$offset =$this->uri->segment(4);
    	}else{
    		$this->data['pagination'] = '';
    		$offset = 0;
    	}

		//get data
		// $this->db->distinct();
		$this->db->select('rt_customers.*, rt_billings.customer_id, rt_packeges.packege_name, rt_packeges.bill_amount');
		$this->db->join('rt_billings', 'rt_customers.id=rt_billings.customer_id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->like('rt_customers.username', $term);
		$this->db->or_like('rt_customers.name', $term);
		$this->db->or_like('rt_customers.email', $term);
		$this->db->or_like('rt_customers.cell_1', $term);
		$this->db->or_like('rt_customers.cell_2', $term);
    	$this->db->order_by('rt_billings.id desc, rt_customers.id desc');
    	$this->db->group_by('rt_billings.customer_id');
    	$this->db->limit($perpage, $offset);
		$this->data['customers'] = $this->customer_m->get();

		//load template
		$this->data['title'] = 'Search Results';
		$this->data['subview'] = 'admin/customer/index';
		$this->load->view('admin/_layout', $this->data);
	}

	public function btrcReport() {
		$this->db->select('rt_customers.name, rt_customers.type, rt_customers.active_date, rt_customers.billing_address, rt_customers.connection_type, rt_customers.cell_1, rt_customers.cell_2, rt_customers.email, rt_customers.username, p.speed, p.packege_type');
		$this->db->join('rt_packeges as p', 'rt_customers.packege=p.id', 'left');
		$this->db->where('rt_customers.status', 1);
		$this->db->order_by('rt_customers.id', 'asc');
		$this->data['customers'] = $this->db->get('rt_customers')->result_array();
		//load template
		$this->load->view('admin/customer/btcl_report', $this->data);
	}

	public function btclReport(){
		//get data
		$this->db->select('rt_customers.name, rt_customers.active_date, rt_customers.billing_address, rt_customers.connection_type, rt_customers.cell_1, rt_customers.cell_2, rt_customers.email, rt_customers.username, p.speed, p.packege_type');
		$this->db->join('rt_packeges as p', 'rt_customers.packege=p.id', 'left');
		$this->db->where('rt_customers.status', 1);
		$this->db->order_by('rt_customers.id', 'asc');
		$query = $this->db->get('rt_customers');
		$this->data['customers'] = array();
		if( $query !== FALSE && $query->num_rows() > 0 ) {
			$this->data['customers'] = $query->result_array();
		}

		//load template
		$this->load->view('admin/customer/btcl_report', $this->data);
	}

	//Number collection function
	public function numberCollection($type = NULL){
		//filter by area
		$area = ( isset( $_GET['area'] ) && $_GET['area'] !== '') ? $_GET['area'] : '';
		if($type ==NULL){
			$type ='active';
		}
		if($type =='due'){
			$title = 'Due Customer\'s Number';
		}
		if($type =='disabled'){
			$where = array('rt_customers.status'=>9);
			$title = 'Disabled Customer\'s Number';
		}
		if($type =='pending'){
			$where = array('rt_customers.status'=>0);
			$title = 'Pending Customer\'s Number';
		}
		if($type =='active'){
			$where = array('rt_customers.status'=>1);
			$title = 'Active Customer\'s Number';
		}
		if($type =='all'){
			$where = array();
			$title = 'All Customer\'s Number';
		}
		if($type =='due'){
			//check and set bill months
			if($this->bill_m->bill_exist_this_month()){
				$bill_month = date('F-Y');
			}else{
				$bill_month = date('F-Y', strtotime('-1 month'));
			}
			//get due bill data
			$this->db->select('rt_customers.cell_1, rt_customers.cell_2, rt_billings.dues');
			$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
			$this->db->join('rt_billings', 'rt_customers.id=rt_billings.customer_id', 'left');
			$this->db->where(array('rt_customers.status'=>1, 'rt_billings.bill_month' => $bill_month, 'rt_billings.dues > '=>50));
			if( $area )
				$this->db->where( 'rt_customers.billing_area', $area );
			$this->data['customers'] = $this->customer_m->get();
		}else{
			$this->db->select('rt_customers.cell_1, rt_customers.cell_2');
			if($type !='all'){
				$this->db->where($where);
			}
			if( $area )
				$this->db->where( 'rt_customers.billing_area', $area );
			$this->data['customers'] = $this->customer_m->get();
		}

		//load template
		$this->data['areas'] = $this->area_m->get_areas();
		$this->data['area'] = $area;
		$this->data['title'] = ucwords($type).' customer\'s Number';
		$this->data['subview'] = 'admin/customer/numberCollection';
		$this->load->view('admin/_layout', $this->data);
	}

	//Index function
	public function lists($type = NULL, $start=NULL){
		if(isset($_POST['bill_month'])){
			$bill_month = $this->input->post('bill_month');
		}else{
			$bill_month = date('F-Y');
		}
		if($type ==NULL){
			$type ='active';
		}

		if($type =='inactive'){
			$where = array('rt_customers.status'=>7);
			$title = 'Inactive Customers';
		}
		if($type =='disabled'){
			$where = array('rt_customers.status'=>9);
			$title = 'Disabled Customers';
			$this->data['month'] = date('F');
		}
		if($type =='pending'){
			$where = array('rt_customers.status'=>0);
			$title = 'Pending Customers';
		}
		if($type =='active'){
			$where = array('rt_customers.status'=>1);
			$title = 'Active Customers';
		}
		if($type =='all'){
			$where = array();
			$title = 'All Customers';
		}
		
		//count
		if($type !='all'){
			$this->db->where($where);
		}
		$count = $this->db->count_all_results('rt_customers');

		//set up pagination
    	$perpage = 50;
    	if ($count > $perpage){
    		$this->load->library('pagination');

    		$config['base_url'] = site_url('dashboard/customers/lists/'.$type.'/');
    		$config['total_rows'] = $count;
    		$config['per_page'] = $perpage;
    		$config['uri_segment'] = 5;
    		$this->pagination->initialize($config);

    		$this->data['pagination'] = $this->pagination->create_links();
    		$offset =$this->uri->segment(5);
    	}else{
    		$this->data['pagination'] = '';
    		$offset = 0;
    	}

		//get data
		$this->db->select('rt_customers.*, rt_packeges.packege_name, rt_packeges.bill_amount');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		if($type !='all'){
			$this->db->where($where);
		}
		$this->db->order_by('rt_customers.id', 'desc');
    	$this->db->limit($perpage, $offset);
		$this->data['customers'] = $this->customer_m->get();

		//load template
		$this->data['title'] = ucwords($type).' customers';
		$this->data['subview'] = 'admin/customer/list';
		$this->load->view('admin/_layout', $this->data);
	}

	//Index function
	public function disabled( $month = null ) {
		$month = $month;
		if( $month == null )
			$month = date('F-Y');
		$from = date('Y-m', strtotime( $month ) ) . '-01';
		$to = date('Y-m-t', strtotime( $from ) );
		$this->db->select('rt_customers.id');
		$this->db->where('rt_customers.suspend_date between {$from} and {$to}');
		$this->db->where( array( 'rt_customers.status' => 9 ) );
		$query = $this->customer_m->get('rt_customers');
		$count = ( empty( $query ) ) ? 0 : count( $query );

		//set up pagination
    	$perpage = 50;
    	if ($count > $perpage){
    		$this->load->library('pagination');

    		$config['base_url'] = site_url('dashboard/customers/disabled/'.$month.'/');
    		$config['total_rows'] = $count;
    		$config['per_page'] = $perpage;
    		$config['uri_segment'] = 5;
    		$this->pagination->initialize($config);

    		$this->data['pagination'] = $this->pagination->create_links();
    		$offset =$this->uri->segment(5);
    	}else{
    		$this->data['pagination'] = '';
    		$offset = 0;
    	}

		//get data
		$this->db->select('rt_customers.*, rt_packeges.packege_name, rt_packeges.bill_amount');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->join('rt_billings', 'rt_customers.id=rt_billings.customer_id', 'left');
		$this->db->where('rt_customers.suspend_date between {$from} and {$to}');
		$this->db->where( array( 'rt_customers.status' => 9 ) );
		$this->db->order_by('rt_customers.id', 'desc');
    	$this->db->limit($perpage, $offset);
		$this->data['customers'] = $this->customer_m->get();

		//load template
		$this->data['month'] = ( $month ) ? date('F', strtotime( $month ) ) : '';
		$this->data['title'] = 'Disabled Customers (' . $month . ')';
		$this->data['subview'] = 'admin/customer/list';
		$this->load->view('admin/_layout', $this->data);
	}

	public function info($id){

		//if ID not passed then redirect to customers list
		if(!$id) redirect(site_url('dashboard/customers'));
		//get billing history for this user
		$id = (int) $id;
		$this->data['info'] = $this->customer_m->get($id);
		$this->data['billing_area'] = $this->area_m->get($this->data['info']->billing_area);
		$this->data['packege'] = $this->packege_m->get($this->data['info']->packege);

		//get payment history
		$this->db->select('rt_billings.*, rt_packeges.packege_name, rt_packeges.bill_amount, rt_payments.paid_amount, rt_payments.due_amount, rt_payments.pay_date, rt_payments.instant_discount');
		$this->db->join('rt_packeges', 'rt_billings.packege_id=rt_packeges.id', 'left');
		$this->db->join('rt_payments', 'rt_billings.id=rt_payments.billing_id', 'left');
		$this->db->where(array('rt_billings.customer_id'=> $id));
		$this->db->order_by('rt_billings.id desc, rt_payments.id desc');
		$this->data['billings'] = $this->bill_m->get();

		//get packeges for dropdown
		$this->data['packeges'] = $this->packege_m->get_packeges();

		//get packege change request
		$this->db->where(array('status'=>0, 'customer_id'=>$id, 'request_type'=>'packege_change'));
		$this->data['packege_change'] = $this->customer_request_m->get();

		//change history
		$this->db->where('customer_id', $id);
		$this->data['history'] = $this->customer_request_m->get();

		//load templates and data
		$this->data['title'] = 'Information of "'.ucwords($this->data['info']->name).'"';
		$this->data['subview'] = 'admin/customer/info';
		$this->load->view('admin/_layout', $this->data);
	}

	//Index function
	public function print_list(){
		//get data
		$this->db->select('rt_customers.*, rt_billings.bills, rt_billings.dues as due, rt_packeges.packege_name, rt_packeges.speed, rt_packeges.bill_amount');
		$this->db->join('rt_billings', 'rt_customers.id=rt_billings.customer_id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->where(array('rt_customers.status'=>1, 'rt_billings.bill_month'=>date('F-Y')));
		$this->data['customers'] = $this->customer_m->get();

		//load template
		if(!$this->user_m->have_permission(9)){
			$this->data['title'] = 'Access Denied!';
			$this->data['subview'] = 'admin/no_permission';
			$this->load->view('admin/_layout', $this->data);
		}else{
			$this->data['title'] = 'Active Customers';
			$this->load->view('admin/customer/print_list', $this->data);
		}
	}

	public function customer_details($id){
		$id = (int) $id;
		$this->db->select('rt_customers.*, rt_packeges.packege_name, rt_packeges.bill_amount');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->where('rt_customers.username', $id);
		$info = $this->customer_m->get();

		if(count($info)){
			echo '
				<div class="input-group" style="width:100%;">
					<table class="table table-stripped">
						<tbody>
							<tr>
								<td style="width:30%;">Customer Name</td>
								<td>: <strong>'.$info[0]->name.'</strong></td>
							</tr>
							<tr>
								<td style="width:30%;">Customer Username</td>
								<td>: <strong>'.$info[0]->username.'</strong></td>
							</tr>
							<tr>
								<td>Customer Address</td>
								<td>: <strong>'.$info[0]->address.'</strong></td>
							</tr>
							<tr>
								<td>Contact </td>
								<td>: <strong>'.$info[0]->cell_1.', '.$info[0]->cell_2.'</strong></td>
							</tr>
							<tr>
								<td>Packege details</td>
								<td>: <strong>'.$info[0]->packege_name.' (<strong>'.$info[0]->bill_amount.'TK.</strong>)</strong></td>
							</tr>
							<tr>
								<td>Bill Dues</td>
								<td>: <strong>'.$info[0]->dues.'TK.</strong></td>
							</tr>
							<tr>
								<td>Status</td>
								<td>
									: <strong>';
									switch ($info[0]->status) {
										case '1': echo 'Active';
											break;
										case '7': echo 'Suspended';
											break;
										case '9': echo 'Deleted';
											break;
										default: echo 'Pending';
											break;
									}
									'</strong>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			';
		}else{
			echo '<p>Sorry! customer details not found.</p>';
		}
	}

	public function change_request(){
		//get data
		$this->db->select('rt_customers_request.*, rt_customers.userId, rt_customers.username, rt_customers.mktikId, rt_customers.packege as cur_packege_id, rt_packeges.packege_name, rt_packeges.bill_amount, pkg.packege_name as rq_packege_name, pkg.bill_amount as rq_bill_amount, rt_users.name as requestor_name');
		$this->db->join('rt_customers', 'rt_customers_request.customer_id=rt_customers.id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->join('rt_packeges as pkg', 'rt_customers_request.requested_packege=pkg.id', 'left');
		$this->db->join('rt_users', 'rt_customers_request.request_by=rt_users.id', 'left');
		$this->db->where('rt_customers_request.status', 0);
		$this->data['customers'] = $this->customer_request_m->get();

		//load template
		$this->data['title'] = 'Customer\'s request';
		$this->data['subview'] = 'admin/customer/change_request';
		$this->load->view('admin/_layout', $this->data);
	}

	public function request_history(){

		$this->db->where_not_in(array('status'=>0));
		$count = $this->db->count_all_results('rt_customers_request');

		//set up pagination
    	$perpage = 25;
    	if ($count > $perpage){
    		$this->load->library('pagination');

    		$config['base_url'] = site_url('dashboard/customers/request_history/');
    		$config['total_rows'] = $count;
    		$config['per_page'] = $perpage;
    		$config['uri_segment'] = 4;
    		$this->pagination->initialize($config);

    		$this->data['pagination'] = $this->pagination->create_links();
    		$offset =$this->uri->segment(4);
    	}else{
    		$this->data['pagination'] = '';
    		$offset = 0;
    	}

		//get data
		$this->db->select('rt_customers_request.*, rt_customers.username, rt_customers.packege as cur_packege_id, rt_packeges.packege_name, rt_packeges.bill_amount, pkg.packege_name as rq_packege_name, pkg.bill_amount as rq_bill_amount, rt_users.name as requestor_name');
		$this->db->join('rt_customers', 'rt_customers_request.customer_id=rt_customers.id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->join('rt_packeges as pkg', 'rt_customers_request.requested_packege=pkg.id', 'left');
		$this->db->join('rt_users', 'rt_customers_request.request_by=rt_users.id', 'left');
		$this->db->where_not_in(array('rt_customers_request.status'=>0));
		$this->db->limit($perpage, $offset);
		$this->db->order_by('id', 'desc');
		$this->data['customers'] = $this->customer_request_m->get();

		//load template
		$this->data['title'] = 'Customer\'s request';
		$this->data['subview'] = 'admin/customer/request_history';
		$this->load->view('admin/_layout', $this->data);
	}

	public function request_details($id){
		//check ID supplied or not
		if(!$id) redirect(site_url('dashboard/customers/change_request'));
		$id = (int) $id;

		//get data
		$this->db->select('rt_customers_request.*, rt_customers.name, rt_customers.username as current_username, rt_customers.address, rt_customers.email as current_email, rt_customers.fathers_name, rt_customers.mothers_name, rt_customers.billing_area, rt_customers.cell_1, rt_customers.cell_2, rt_customers.monthly_discount, rt_customers.packege as cur_packege_id, rt_packeges.packege_name, rt_packeges.bill_amount, pkg.packege_name as rq_packege_name, pkg.bill_amount as rq_bill_amount, ppkg.id as previous_package_id, ppkg.packege_name as pervious_package_name, ppkg.bill_amount as previous_pkg_bill, rt_users.name as requestor_name');
		$this->db->join('rt_customers', 'rt_customers_request.customer_id=rt_customers.id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->join('rt_packeges as pkg', 'rt_customers_request.requested_packege=pkg.id', 'left');
		$this->db->join('rt_packeges as ppkg', 'rt_customers_request.previous_package=ppkg.id', 'left');
		$this->db->join('rt_users', 'rt_customers_request.request_by=rt_users.id', 'left');
		$this->db->where('rt_customers_request.id', $id);
		$this->data['customers'] = $this->customer_request_m->get();

		//load template
		$this->data['title'] = ucwords(str_replace('_', ' ', $this->data['customers'][0]->request_type)).' Request';
		$this->data['subview'] = 'admin/customer/request_details';
		$this->load->view('admin/_layout', $this->data);
	}

	public function request( $id ) {
		if( !$id ) redirect( site_url( 'dashboard/customers' ) );
		//get data
		$this->data['info'] = $this->customer_m->get( $id );
		$this->data['services'] = array(
         ''=>'Select Request type', 
         'package_change'=>'Package Change', 
         'address_change'=>'Address Change', 
         'username_change'=>'Username Change', 
         'password_change'=>'Password Change', 
         'email_change'=>'Email Change', 
         'mobile_change'=>'Mobile Change', 
         'active_customer_suspend'=>'Suspend', 
         'bill_add'=>'Bill Add', 
         'bill_minus'=>'Bill Minus',
         'active_customer_suspend' => 'Suspend',
         'old_customer_active' => 'Activate',
      );

		if( isset( $_POST['request_type'] ) ) :
			$rules = $this->customer_request_m->rules;
			$this->form_validation->set_rules($rules);
			if( $this->form_validation->run() == True ) :
				$data = $this->customer_request_m->array_from_post(array(
					'request_type', 'process_time', 'remarks'
				));
				if( $data['request_type']=='package_change' ) :
					$data['requested_packege'] = $this->input->post('requested_packege');
					$data['previous_package'] = $this->data['info']->packege;
				endif;
				if( $data['request_type']=='address_change' )
					$data['new_address'] = $this->input->post('what_to_change');
				if( $data['request_type']=='username_change' )
					$data['new_username'] = $this->input->post('what_to_change');
				if( $data['request_type']=='email_change' )
					$data['email'] = $this->input->post('what_to_change');
				if( $data['request_type'] == 'mobile_change' )
					$data['mobile'] = $this->input->post('what_to_change');
				if( $data['request_type'] == 'bill_add' )
					$data['amount'] = floatval( $this->input->post( 'what_to_change' ) );
				if( $data['request_type'] == 'bill_minus' )
					$data['amount'] = floatval( $this->input->post('what_to_change') );
				$data['process_time'] = strtotime( $data['process_time'] );
				$data['request_by'] = $this->session->userdata('id');
				$data['request_time'] = time();
				$data['customer_id'] = $id;
				$ok = $this->customer_request_m->save( $data );
				if( $ok ) :
					redirect( site_url( 'dashboard/customers/?action=true' ) );
				else :
					redirect( site_url( 'dashboard/customers/?action=false&code=' ) );
				endif;
			else :
				return validation_errors();
			endif;
		endif;

		//get pending requests...........
		$this->db->where(array('rt_customers_request.customer_id'=>$this->data['info']->id, 'rt_customers_request.status'=>0));
		$this->data['pending_request'] = $this->customer_request_m->get();

		//get packege details
		$this->data['packege'] = $this->packege_m->get($this->data['info']->packege);
		$this->data['packeges'] = $this->packege_m->get_packeges();

		//get bills
		$this->db->where(array('customer_id'=>$this->data['info']->id, 'bill_month'=>date('F-Y')));
		$this->data['bill'] = $this->bill_m->get();

		$this->data['title'] = 'Customer Request';
		$this->data['subview'] = 'admin/customer/request';
		$this->load->view('admin/_layout', $this->data);
	}

	public function process_request( $action, $id ) {
		if( !$id ) 
			redirect( site_url( 'dashboard/customers/change_request/?action=false&code=' ) );
		if( ! $this->user_m->have_permission(7) )
			redirect( site_url( 'dashboard/customers/change_request/?permission=no' ) );
		$id = (int) $id;
		$action = $action;
		$this->db->select('rt_customers_request.*, rt_customers.mktikId, rt_customers.username, rt_customers.password, rt_customers.remote_ip, rt_customers.remote_mac, rt_customers.packege, rt_customers.dues as initial_due, rt_customers.monthly_discount, rt_customers.cable_cost, rt_customers.setup_charge, rt_customers.active_date, rt_packeges.bill_amount, rt_packeges.packege_name, rt_packeges.mikrotik_profile as profile_mikrotik, pkg.mikrotik_profile, pkg.bill_amount as rq_bill_amount');
		$this->db->join('rt_customers', 'rt_customers_request.customer_id=rt_customers.id', 'left');
		$this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
		$this->db->join('rt_packeges as pkg', 'rt_customers_request.requested_packege=pkg.id', 'left');
		$this->db->where( array( 'rt_customers_request.id' => $id, 'rt_customers_request.status'=>0 ) );
		$info = $this->customer_request_m->get();
		if( ! count( $info ) ) redirect(site_url('dashboard/customers/change_request/?action=false&code='));
			if( $action == 'accept' ) :
				//call the request function
				$request = $info[0]->request_type;
				$this->$request( $info[0] );
			elseif( $action == 'cancel' ) :
				//cancel the request
				$calcel = array( 'status'=>2, 'action_date'=>time() );
				$ok = $this->customer_request_m->save($calcel, $id);
				if($ok) :
					redirect(site_url('dashboard/customers/change_request/?action=true'));
				endif;
			else :
				redirect( site_url( 'dashboard/customers/change_request/?action=false&code=' ) );
			endif;
	}

	private function address_change( $info ) {
		$data = array( 'address' => $info->new_address );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				redirect( 'dashboard/customers/change_request/?action=true' );
			endif;
		else :
			redirect( 'dashboard/customers/change_request/?action=false&code=' );
		endif;
	}

	private function package_change( $info ) {
		//check mikrotik enabled
		if( $this->mikrotik->_enabled() ) :
			$mktikId = ( $info->mktikId ) ? 
				$info->mktikId : 
				$this->mikrotik->getIdByName( $info->username );
			if( $mktikId == null ) :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				exit;
			endif;
			$array = array(
		   	".id" => $mktikId, //id of mikrotik user
		      "profile"  => $info->mikrotik_profile,
		   );
		   if( $this->mikrotik->changeProfile( $array ) ) :
				if( $this->_packege_change( $info ) ) :
					redirect( 'dashboard/customers/change_request/?action=true&code=', 'refresh' );
				else :
					redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				endif;
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		else :
			if( $this->_packege_change( $info ) ) :
				redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		endif;
	}

	private function _packege_change( $info ) {
		$data = array();
		$data['packege'] = $info->requested_packege;
		//fetch current dues and total bills from rt_billings table to update them with additional bills
		$this->db->select('id, dues, bills, remarks');
		$this->db->where( array('bill_month' => date('F-Y'), 'customer_id' => $info->customer_id ) );
		$this->db->limit(1);
		$billings = $this->bill_m->get();
		//If package changes request found then modify bill amount for this month
		//If upgrade packeges then calculate extra bill for remain days and add them to billing and payments for this month's
		//If downgrade packege Don't Substract Bills because downgrade is not permitted in the middle of the months
		if( $info->rq_bill_amount > $info->bill_amount ) :
			//Check bill exist or not
			if( $this->bill_m->bill_exist( $info->customer_id ) ) :
				//activation date to calculate bills
				$active_date = date( 'Y-m-d', $info->process_time ); 
				//calculate monthly bills by remain days
				$d = new DateTime(date('Y-m-d'));
				$lastDay = $d->format('Y-m-t'); //last day of this month
				if( $active_date < $lastDay ) :
					//remain days of this month calculated from active date
					$remain_days = date( 'd', strtotime( $lastDay ) ) - date( 'd', $info->process_time );
					$remain_days = $remain_days + 1;
					// devide packege amount by total days of this month
					//total days in this month  date("t");
					$total_days =  date("t");
					//current month bill of this user
					$current_bill = ( $info->bill_amount - $info->monthly_discount );
				   //requested_bill 
				   $requested_bill = ( $info->rq_bill_amount - $info->monthly_discount );
					//calculate additional bills by subsctructed requested bills - current packages bills
					$additional_bill = $requested_bill - $current_bill;
					//One day bill
					$oneDaysbill = $additional_bill / $total_days;
					//packege bill
					$addition_total =  $oneDaysbill * $remain_days;
					//Sumission additional bills of this bill months
					$dues = $billings[0]->dues + $addition_total;
					$bills = $billings[0]->bills + $addition_total;
					$remarks = $billings[0]->remarks;
					$remarks .= '<br />Pakcege Upgrade Additional Bill : '.$addition_total;
					$billing_dues = array( 'dues'=> $dues, 'bills'=>$bills, 'packege_bill'=>$info->rq_bill_amount, 'remarks'=>$remarks );
					//update billings total dues with additional bills
					$this->db->where( array( 'bill_month'=>date( 'F-Y' ), 'customer_id'=>$info->customer_id ) );
					$ok = $this->db->update( 'rt_billings', $billing_dues );
					if( $ok ) :
						//update dues in the payments dues if payments exist
						$this->db->select('rt_payments.*, rt_billings.dues');
						$this->db->join('rt_billings', 'rt_payments.billing_id=rt_billings.id', 'left');
						$this->db->where( array( 'rt_billings.bill_month' => date('F-Y'), 'rt_payments.customer_id'=>$info->customer_id));
						$payments = $this->payment_m->get();
						if( count( $payments ) ) :
							foreach( $payments as $pmts ) :
								$due_amount = $pmts->due_amount + $addition_total;
								$payment_dues = array( 'due_amount'=> $due_amount );
								$this->payment_m->save( $payment_dues, $pmts->id );
							endforeach;
						endif;
					endif;
				endif;
			endif; //end checking bill exit or not
		endif; //end checking packege upgrade or not
		//update user packege with requested packege
		$this->customer_m->save( array( 'packege' => $data['packege'] ), $info->customer_id );
		//accept request
		if( $this->accept_request( $info->id ) ) :
			return true;
		else :
			return false;
		endif;
	}

	private function accept_request( $id ) {
		$monthWize = date('Y-m-d');
		$process_time = time();
		$ok = $this->customer_request_m->save( array( 'status' => 1, 'process_time' => $process_time, 'monthWize' => $monthWize ), $id );
		return ( $ok ) ? true : false;
	}

	private function username_change( $info ) {
		//check mikrotik enabled
		if( $this->mikrotik->_enabled() ) :
			$mktikId = ( $info->mktikId ) ? 
				$info->mktikId : 
				$this->mikrotik->getIdByName( $info->username );
			if( $mktikId == null ) :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				exit;
			endif;
			$array = array(
		   	".id" => $mktikId, //id of mikrotik user
		      "name"  => $info->new_username,
		   );
		   if( $this->mikrotik->changeName( $array ) ) :
				if( $this->_username_change( $info ) )
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			else
				if( $this->_username_change( $info ) )
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			endif;
		endif;
	}

	private function _username_change( $info ) {
		$data = array( 'username' => $info->new_username );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				return true;
			endif;
		else :
			return false;
		endif;
	}

	private function password_change( $info ) {
		//check mikrotik enabled
		if( $this->mikrotik->_enabled() ) :
			$mktikId = ( $info->mktikId ) ? 
				$info->mktikId : 
				$this->mikrotik->getIdByName( $info->username );
			if( $mktikId == null ) :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				exit;
			endif;
			$array = array(
		   	".id" => $mktikId, //id of mikrotik user
		      "password"  => $info->new_password,
		   );
		   if( $this->mikrotik->changePassword( $array ) ) :
				if( $this->_password_change( $info ) )
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			else
				if( $this->_password_change( $info ) )
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			endif;
		endif;
	}

	private function _password_change( $info ) {
		$data = array( 'password' => $info->new_password );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		$password = $this->user_m->hash( $data['password'] );
		$this->user_m->save( array( 'password' => $password ), $info->userId );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				return true;
			endif;
		else :
			return false;
		endif;
	}

	private function email_change( $info ) {
		$data = array( 'email' => $info->email );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				redirect( 'dashboard/customers/change_request/?action=true' );
			endif;
		else :
			redirect( 'dashboard/customers/change_request/?action=false&code=' );
		endif;
	}

	private function mobile_change( $info ) {
		$data = array( 'cell_1' => $info->mobile );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				redirect( 'dashboard/customers/change_request/?action=true' );
			endif;
		else :
			redirect( 'dashboard/customers/change_request/?action=false&code=' );
		endif;
	}

	private function active_customer_suspend( $info ) {
		//check mikrotik enabled
		if( $this->mikrotik->_enabled() ) :
			$mktikId = ( $info->mktikId ) ? 
				$info->mktikId : 
				$this->mikrotik->getIdByName( $info->username );
			if( $mktikId == null ) :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				exit;
			endif;
		   if( $this->mikrotik->disable( $mktikId ) ) :
				if( $this->_suspend( $info ) ) :
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
				else :
					redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				endif;
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		else :
			if( $this->_suspend( $info ) ) :
				redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		endif;
	}

	private function _suspend( $info ) {
		$data = array( 'status' => 9, 'suspend_date' => date('Y-m-d', $info->action_date ) );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				return true;
			endif;
		else :
			return false;
		endif;
	}

	private function new_line_activation( $info ) {
		
		//check mikrotik enabled
		if( $this->mikrotik->_enabled() ) :
			$array = array(
				"disabled" => "no",
		      	"name"     => $info->username,
		      	"password" => $info->password,
		      	"comment"  => "{ new $info->username user created from Web API }",
		      	"service"  => "pppoe",
		      	"profile" => $info->profile_mikrotik //User packege
			);
			if( getOption('remote_ip') == 1 && !empty( $info->remote_ip ) )
				$array['remote-address'] = $info->remote_ip; //customer IP
			if( getOption('remote_mac') == 1 && !empty( $info->remote_mac ) )
				$array['caller-id'] = $info->remote_mac; //customer mac
		   if( $this->mikrotik->make( $array ) ) :
				if( $this->_activate( $info ) ) :
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
				else :
					redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				endif;
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		else :
			if( $this->_activate( $info ) ) :
				redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		endif;
	}

	private function _activate( $info ) {

		//if Old customer Activation request then set bills and calculated previous dues
		//if new activation request found, then set bill with cable cost + setup charge and others charge as well service is (if necessary)

		//service charge if month is equal
		$service_month = strtolower( date('F') );
		$service_charge = floatval( getOption( $service_month ) );

		//if bill is not exist then set bills for this customer
		if( !$this->bill_m->bill_exist( $info->customer_id ) ) :

			//get previous dues by customer id
			$previous_dues = $this->bill_m->get_previous_dues_for_old_customer( $info->customer_id );

			//set data to set bills
			$admin = $this->session->userdata('id');

			//calculate monthly bills by remain days
			$d = new DateTime(date('Y-m-d'));
			$lastDay = $d->format('Y-m-t'); //last day of this month

			//remain days of this month calculated from active date
			$remain_days = date('d', strtotime($lastDay)) - date('d', $info->process_time);
			$remain_days = $remain_days + 1;

			// devide packege amount by default 30 monthly days
			//total days in this month  date("t");
			$total_days =  date("t");

			//current month bill of this user
		   	$current_bill = ($info->bill_amount - $info->monthly_discount);
			$oneDaysbill = $current_bill / $total_days;

			//packege bill
			$packege_bill =  $oneDaysbill * $remain_days;
			//check activation / Proccecing date is next month or later
			if( date( 'Y-m-d', $info->process_time ) > $lastDay ) :

				//total bill if activation date is next month
				$total_bill = ($service_charge + $info->initial_due + $info->cable_cost + $info->setup_charge + $previous_dues);

				//monthly discount
				$monthly_discount = 0;
				$packege_bill = 0;

				//set remarks
				$remarks = 'Previous Dues : '.$previous_dues.'<br />Initial_dues : '.$info->initial_due.'<br />Setup Charge : '.$info->setup_charge.'<br /> Cable Cost : '.$info->cable_cost.'<br />Service Charge : '.$service_charge;
			else :

				//total bill if activation date is current month
		      $total_bill = ($packege_bill + $service_charge + $info->initial_due + $info->cable_cost + $info->setup_charge + $previous_dues);

		      //monthly discount
		      $monthly_discount = $info->monthly_discount;

		      //set remarks
		      $remarks = 'Previous Dues : '.$previous_dues.'<br />Monthly Bill ('.$remain_days.' Days) : '.$packege_bill.'<br />Monthly Discount : '.$info->monthly_discount.'<br />Initial_dues : '.$info->initial_due.'<br />Setup Charge : '.$info->setup_charge.'<br /> Cable Cost : '.$info->cable_cost.'<br />Service Charge : '.$service_charge;
			endif;

		   //set data for billings
		   $bill = array(
		      'bills'=> $total_bill ,
		      'packege_id'=>$info->packege,
		      'customer_id'=>$info->customer_id,
		      'dues'=>$total_bill,
		      'bill_month'=>date('F-Y'),
		      'packege_bill'=>$packege_bill,
		      'monthly_discount' => $monthly_discount,
		      'admin'=>$admin,
		      'remarks' => $remarks,
		   );

			//set bills
			$ok = $this->bill_m->save( $bill );

		endif;

		//activate customer and reset the additional bills
		$data = array( 'status' => 1, 'setup_charge' => 0, 'cable_cost' => 0, 'dues' => 0 );
		$ok = $this->customer_m->save( $data, $info->customer_id );
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				return true;
			endif;
		else :
			return false;
		endif;
	}

	private function old_customer_active( $info ) {

		//check mikrotik enabled
		if( $this->mikrotik->_enabled() ) :
			$mktikId = ( $info->mktikId ) ? 
				$info->mktikId : 
				$this->mikrotik->getIdByName( $info->username );
			if( $mktikId == null ) :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				exit;
			endif;
		   if( $this->mikrotik->enable( $mktikId ) ) :
				if( $this->_activate( $info ) ) :
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
				endif;
			else :
				if( $this->_activate( $info ) ) :
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
				else :
					redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
				endif;
			endif;
		else :
			if( $this->_activate( $info ) ) :
					redirect( 'dashboard/customers/change_request/?action=true', 'refresh' );
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=', 'refresh' );
			endif;
		endif;
	}

	private function bill_add( $info ) {
		//fetch current dues and total bills from rt_billings table to update them with additional bills
		$this->db->select('id, dues, bills, remarks');
		$this->db->where(array('bill_month'=>date('F-Y'), 'customer_id'=>$info->customer_id));
		$this->db->limit(1);
		$billings = $this->bill_m->get();
		//check bill exist or not
		if( $this->bill_m->bill_exist( $info->customer_id ) ) :
			$add_amount = floatval($info->amount);
			$remarks = $billings[0]->remarks;
			$remarks .= '<br />Bill Add : '.$add_amount;
			$bill = array('dues'=>$billings[0]->dues + $add_amount, 'bills'=>$billings[0]->bills + $add_amount, 'remarks'=>$remarks);
			$this->bill_m->save($bill, $billings[0]->id);
			//udate payments
			//update dues in the payments dues if payments exist
			$this->db->select('rt_payments.*, rt_billings.dues');
			$this->db->join('rt_billings', 'rt_payments.billing_id=rt_billings.id', 'left');
			$this->db->where(array('rt_billings.bill_month'=>date('F-Y'), 'rt_payments.customer_id'=>$info->customer_id));
			$payments = $this->payment_m->get();
			if(count($payments)) :
				foreach($payments as $pmts) :
					$due_amount = $pmts->due_amount + $add_amount;
					$payment_dues = array('due_amount'=> $due_amount);
					$this->payment_m->save($payment_dues, $pmts->id);
				endforeach;
			endif;
			//accept the request
			if( $this->accept_request( $info->id ) ) :
				redirect( 'dashboard/customers/change_request/?action=true' );
			else :
				redirect( 'dashboard/customers/change_request/?action=false&code=' );
			endif;
		else :
			redirect( 'dashboard/customers/change_request/?action=false&code=' );
		endif;
	}

	private function bill_minus( $info ) {
		//fetch current dues and total bills from rt_billings table to update them with additional bills
		$this->db->select('id, dues, bills, remarks');
		$this->db->where(array('bill_month'=>date('F-Y'), 'customer_id'=>$info->customer_id));
		$this->db->limit(1);
		$billings = $this->bill_m->get();
		//check bill exist or not
		if( $this->bill_m->bill_exist( $info->customer_id ) ) :
			$minus_amount = floatval($info->amount);
			$remarks = $billings[0]->remarks;
			$remarks .= '<br />Bill Minus : '.$minus_amount;
			$bill = array('dues'=>$billings[0]->dues - $minus_amount, 'bills'=>$billings[0]->bills - $minus_amount, 'remarks'=>$remarks);
			$ok = $this->bill_m->save($bill, $billings[0]->id);
			//udate payments
			//update dues in the payments dues if payments exist
			$this->db->select('rt_payments.*, rt_billings.dues');
			$this->db->join('rt_billings', 'rt_payments.billing_id=rt_billings.id', 'left');
			$this->db->where(array('rt_billings.bill_month'=>date('F-Y'), 'rt_payments.customer_id'=>$info->customer_id));
			$payments = $this->payment_m->get();
			if( count( $payments ) ) :
				foreach( $payments as $pmts ) :
					$due_amount = $pmts->due_amount - $minus_amount;
					$payment_dues = array('due_amount'=> $due_amount);
					$this->payment_m->save($payment_dues, $pmts->id);
				endforeach;
			endif;
		endif;
		//accept request
		if( $ok ) :
			if( $this->accept_request( $info->id ) ) :
				redirect(site_url('dashboard/customers/change_request/?action=true'));
			endif;
		endif;
	}

	public function edit( $id = NULL ) {
		$this->load->model( array( 'dashboard/area_m', 'dashboard/support_m' ) );
		$this->load->helper( 'email' );

        //Process Membership Form
        if( isset( $_POST ) ) :
            $rules = $this->customer_m->rules;
            $this->form_validation->set_rules( $rules );
            if( $this->form_validation->run() == True ) :
                $data = $this->customer_m->array_from_post(
                    array(
                        'name',
                        'fathers_name',
                        'mothers_name',
                        'username',
                        'password',
                        'email',
                        'address',
                        'billing_address',
                        'connection_type',
                        'billing_area',
                        'cell_1',
                        'cell_2',
                        'packege',
                        'monthly_discount',
                        'active_date',
                        'setup_charge',
                        'cable_cost',
                        'dues'
                    )
                );
                $data['billing_area'] = ( int ) $data['billing_area'];
                $data['packege'] = ( int ) $data['packege'];
                $data['monthly_discount'] = ( int ) $data['monthly_discount'];
                $data['setup_charge'] = ( int ) $data['setup_charge'];
                $data['cable_cost'] = ( int ) $data['cable_cost'];
                $data['dues'] = ( int ) $data['dues'];
               $data['password'] = ( $data['password'] ) ? $data['password'] : getOption('default_password');
               // $data['email'] = ( empty( $data['email'] ) ) ? $data['username'] . getOption('site_url') : $data['email'];
               $data['creator'] = ( int )$this->session->userdata('id');
               if( !$id ) :
                	$data['modified'] = time();
                	if( valid_email( $data['email'] ) ) :
                		$user['email'] = $data['email'];
                		$user['name'] = $data['name'];
                		$user['username'] = $data['username'];
                		$user['modified'] = time();
		                $user['group'] = 1;
		                $user['verification_code'] = $this->user_m->hash($user['modified'].$user['email']);
		                $user['password'] = $this->user_m->hash( $data['password'] );
		                $regi = $this->user_m->save( $user );
		                if( $regi ) :
		                	$data['userId'] = $regi; //Linked to users table and Customers Table with the userID
		                	$site_email = 'No Reply : <' . getOption('site_email') . '>';
		                    $link = site_url('activate/'.$id.'/?vkey='.$user['verification_code']);
		                    $info['name'] = $user['name'];
		                    $info['message'] = '<h4 class="secondary">
		                        <singleline label="Title">
		                           You have registered an account with jbcpbd.com earlier. You have to confirm your email address. Your confirmation link is below.
		                        </singleline></h4>
		                        <multiline label="Description">
		                           <p><a href="'.$link.'" class="button-blue" target="_blank"style="margin:40px 0; padding:6px 30px; background-color:rgb(75, 219, 81); font-size: 20px; font-weight: bold; color:#F2F5F7; text-decoration: none !important;"> Click Here To Verify Your Email </a></p>
		                           <p>If your are having problem, please copy the link below &amp; paste to your browser.</p>
		                           <p class="permalink_section">'.$link.'</p>
		                           <p>Note: If you have any query or comments please <a href="'.site_url('contact').'" target="_blank">Contact</a> with us.<br />Team<br />jbcpbd.com</p>
		                        </multiline>';
		                  $params = array(
		                     'from'=>$site_email,
		                     'to'=>$data['email'],
		                     'subject'=> getOption('site_title') .' Registration',
		                     'message'=> $this->load->view( 'email/common', $info, TRUE ),
		                  );

		                  send_mail( $params );
		               endif;
                	endif;
               endif;
               // dump( $data ); exit;
                $ok = $this->customer_m->save( $data, $id );
               //  echo '<pre>';
               // dump( $this->db->last_query() ); exit;
                if( $ok ) :
                	//Open New tickets for the connection or activation
                	$openTicket = array(
                		'customer_id'=>$ok,
                		'username' => $data['username'],
                		'ownerid'=>$this->session->userdata('id'),
                		'department'=>'support',
                		'type' => 'New line',
                		'summary'=>'New line Connection and Activation',
                		'modified'=>time()
                	);
                	$ticket = $this->support_m->save( $openTicket );
                	//If new line then send an activation request
                	if( !$id ) :
	                	//open activation notification for activate account
	                	$activation = array(
	                		'customer_id'=>$ok,
	                		'request_by'=>$this->session->userdata('id'),
	                		'request_time'=>time(),
	                		'process_time'=>strtotime($this->input->post('active_date')),
	                		'remarks'=>'New line connection and Activation.',
	                		'request_type'=>'new_line_activation'
	                	);
	                	$this->customer_request_m->save($activation);
	               endif;
                	//redirect to customer list with success message
                	$ref = ( $id ) ? 'active' : 'pending';
                	redirect( site_url( 'dashboard/customers/lists/' . $ref . '/?action=true' ) );
               endif;
            endif;
        	endif;
        //get data
        $this->data['packeges'] = $this->packege_m->get_packeges();
        $this->data['areas'] = $this->area_m->get_areas();
		//load template
		if( $id ) :
			$this->data['title'] = 'Edit Customer';
			$this->data['subview'] = 'admin/customer/edit';
			if( !$this->user_m->have_permission( $this->data['auths'][4]->auth ) ) :
				redirect( site_url( 'dashboard/customers/edit' ) );
			endif;
			//get customer data by id
			$this->data['customer'] = $this->customer_m->get( $id );
		else :
			$this->data['title'] = 'Add New Customer';
			$this->data['subview'] = 'admin/customer/edit';
			if( !$this->user_m->have_permission( $this->data['auths'][3]->auth ) ) :
				$this->data['title'] = 'Permission required';
				$this->data['subview'] = 'admin/no_permission';
			endif;
			//get default data from std class
			$this->data['customer'] = $this->customer_m->get_new();
		endif;
		$this->data['id'] = $id;
		$this->load->view('admin/_layout', $this->data);
	}

    public function _exist_username( $string ) {

    	$id = $this->uri->segment(4);
    	$this->db->where('username', $string);

    	! $id || $this->db->where('id !=', $id);
		$user = $this->customer_m->get();

		if(count($user) !=0) :
			$this->form_validation->set_message('_exist_username', '%s is already exists.');
			return FALSE;
		else :
			return TRUE;
		endif;
    }

    public function _exist_email( $string ){
    	if( !empty( $string ) ) :
	    	$id = $this->uri->segment(4);
	    	$this->db->where('email', $string);
	    	! $id || $this->db->where('id !=', $id);
			$user = $this->customer_m->get();

			if(count($user) !=0) :
				$this->form_validation->set_message('_exist_email', '%s is already exists.');
				return FALSE;
			else :
				return TRUE;
			endif;
		else :
			return TRUE;
		endif;
    }

    public function _packege_exist($string){
    	$this->db->where('id', $string);
		$user = $this->packege_m->get();

		if(count($user) ==0) :
			$this->form_validation->set_message('_packege_exist', '%s does not exists.');
			return FALSE;
		else :
			return TRUE;
		endif;
    }

	public function autosuggest(){
		$term = (string) $_GET['term'];
		$return_arr = array();
		$this->db->like('username', $term);
		$query = $this->customer_m->get();

		foreach ($query as $q) {
			$row['value'] = $q->username;
            array_push($return_arr,$row);
		}

		$json=json_encode($return_arr);

		echo $json;
	}

	public function active_suggetions(){
		$term = (string) $_GET['term'];
		$return_arr = array();
		$this->db->like('username', $term);
		$this->db->where('status', 1);
		$query = $this->customer_m->get();

		foreach ($query as $q) {
			$row['value'] = $q->username;
            array_push($return_arr,$row);
		}

		$json=json_encode($return_arr);

		echo $json;
	}

	public function request_suggetions(){
		$term = (string) $_GET['term'];
		$return_arr = array();
		$this->db->like('username', $term);
		$this->db->where('status', 1);
		$query = $this->customer_m->get();

		foreach ($query as $q) {
			$row['value'] = $q->username;
            array_push($return_arr,$row);
		}

		$json=json_encode($return_arr);

		echo $json;
	}

}