<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//This Controller is a default Controller
class Ajax extends Admin_Controller{

	public function __construct(){

		parent::__construct();

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

	}

    public function index() {

        redirect(site_url('dashboard/'));

    }
    
    public function charge(){
        $this->load->model('bill_m');
        
        $this->db->where('bill_month', date('F-Y'));
        $bills = $this->bill_m->get();
        $i = 0;
        foreach( $bills as $bill ){
            $i++;
            $data = array(
                'bills' => ($bill->bills + 200),
                'dues' => ($bill->dues + 200),
                'remarks' => $bill->remarks . ', Service charge 200 Tk.',
                // 'service_charge' => ($bill->service_charge + 200)
            );
            
            $this->bill_m->save( $data, $bill->id );
        }
        
        echo $i . ' records updated.';
    }

    public function reviewSalary() {
        print_r( $_POST );
    }

    public function live_customer_search(){
        echo 'ok';
    }

    public function _report_exist($str){
        return true;
    }

    public function set_bills(){
        $this->load->model(array('dashboard/customer_m', 'dashboard/bill_m'));
        //set permission first then continue

        //check bill month set or not
        //if(isset($_POST['bill_month'])){
            //get customer bill info
        $last_month = date('F-Y', strtotime('-1 month'));
            $this->db->select('rt_customers.id, rt_customers.monthly_discount, rt_customers.cable_cost, rt_customers.setup_charge, rt_customers.dues as initial_due, rt_customers.packege, rt_packeges.bill_amount');
            $this->db->join('rt_packeges', 'rt_customers.packege=rt_packeges.id', 'left');
            $this->db->where(array('rt_customers.status'=>1));
            $info = $this->customer_m->get();
            $count = count($info);
            //set admin id 
            $admin = $this->session->userdata('id');
            //get services charge by month
            $service_charge = getOption(date('F'));

            foreach($info as $i) {

                //set data to set bills
                $current_bill = ($i->bill_amount - $i->monthly_discount);
                $previous_due = $this->bill_m->get_previous_dues_by_customer_id($i->id, $last_month);
                $total_bill = ($current_bill + $previous_due + $service_charge + $i->initial_due + $i->cable_cost + $i->setup_charge);

                $data = array(
                    'bills'=> $total_bill ,
                    'packege_id'=>$i->packege,
                    'customer_id'=>$i->id,
                    'dues'=>$total_bill,
                    'bill_month'=>date('F-Y'),
                    'packege_bill'=>$i->bill_amount,
                    'monthly_discount' => $i->monthly_discount,
                    'admin'=>$admin,
                    'remarks' => 'Monthly Bill : '.$i->bill_amount.'<br />Monthly Discount : '.$i->monthly_discount.'<br />Previous Due : '.$previous_due.'<br />Initial_dues : '.$i->initial_due.'<br />Setup Charge : '.$i->setup_charge.'<br /> Cable Cost : '.$i->cable_cost.'<br />Service Charge : '.$service_charge,
                    'service_charge'=> $service_charge,
                );

                //previous months bill
                // $previous_month = date("F", strtotime("last month")).date('-Y');
                // $this->db->select('dues, bills, discount, status');
                // $this->db->where(array('bill_month'=>$previous_month, 'memberid'=>$data['memberid'], 'status'=>0));
                // $pbill = $this->bill_m->get();

                // if(count($pbill)){
                //     if($pbill[0]->status==0){
                //         $data['bills'] = $data['bills'] + $pbill[0]->bills - $pbill[0]->discount;
                //     }else{
                //         $data['bills'] = $data['bills'] + $pbill[0]->dues;
                //     }
                // }


                if( !$this->bill_m->bill_exist( $data['customer_id'] ) ) {

                    //save bills to billings table
                    $ok = $this->bill_m->save( $data );

                    if( $ok ) {
                        //update customer table Set value 0 for (dues, cable_cost, setup_charge etc)
                        $default = array( 'dues' => 0, 'cable_cost' => 0, 'setup_charge' => 0 );
                        $this->customer_m->save( $default, $i->id );
                        $r = 1;
                        for($i = 0, $i < $count; $i++;){
                            $t = $r+$i;
                            // echo $t;
                            if( $t == $count ) {
                                echo 'ok';
                            }
                        }
                    }else{
                        echo 'no';
                    }
                } 
            }
        // }else{
        //     echo 'no';
        // }
    }

    public function set_salary(){

        $this->load->model(array('employee_m', 'salary_m'));

        //set permission first then continue

        //get Employee & salary info
        $last_month = date('F-Y', strtotime('-1 month'));
            $this->db->select('rt_employees.id, rt_employees.dues as initial_due, rt_employees.salary');
            $this->db->where(array('rt_employees.job_status'=>1));
            $info = $this->employee_m->get();
            $count = count($info);

            foreach($info as $i){

                //set data to set salary
                $current_salary = ($i->salary + $i->initial_due);
                $previous_due = $this->salary_m->get_previous_dues_by_employee_id($i->id, $last_month);
                $total_salary = ($current_salary + $previous_due);

                $data = array(
                    'employee_id'=>$i->id,
                    'salary_month'=>date('F-Y'),
                    'monthly_salary'=>$i->salary,
                    'total_amount'=>$total_salary,
                    'dues'=>$total_salary,
                    'remarks' => 'Monthly Salary : '.$i->salary.'<br />Previous Due : '.$previous_due
                );


                if(!$this->salary_m->salary_exist($data['employee_id'])){

                    //save salary to salaries table
                    $ok = $this->salary_m->save($data);

                    if($ok){
                        //update employees table Set value 0 for (dues)
                        $default = array('dues'=>0);
                        $this->employee_m->save($default, $i->id);
                        $r = 1;
                        for($i = 0, $i < $count; $i++;){
                            $t = $r+$i;
                            echo $t;
                            if($t ==$count){
                                echo 'ok';
                            }
                        }
                    }else{
                        echo 'no';
                    }
                } 
            }
    }

    public function update_permission(){
        if(!isset($_GET['auth']) AND !isset($_GET['component'])) return false;

        $auth = (int) $_GET['auth'];
        $component = $_GET['component'];

        $this->db->where('component', $component);
        $ok = $this->db->update('rt_permissions', array('auth'=> $auth));

        if($ok == True){
            echo 'ok';
        }
    }

    public function user_activate($id=false, $role = false){

        if($id != false || $role != false){

            if($this->user_m->have_permission(5)){

                $data['active'] = $role;

                $id = (int) $id;

                $ok = $this->user_m->save($data, $id);



                if($ok){

                    echo 'ok';

                }

            }

        }

    }

    public function reply_ticket(){
        $this->load->model( array( 'dashboard/treply_m', 'dashboard/support_m' ) );
        $rules = $this->treply_m->rules;

        $id = (int) $this->input->post('ticket_id');

        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()==TRUE){
            $data = $this->treply_m->array_from_post(array('summary'));
            $data['ticket_id'] = $id;
            $data['repliers_id'] = $this->session->userdata('id');
            $data['action_taken'] = 'Replied';

            $ok = $this->treply_m->save($data);

            //change the ticket type if posted value is different
            $this->support_m->save( array( 'type' => $this->input->post('type') ), $id );
            if($ok){
                $this->db->select('rt_support_reply.*, rt_users.name, rt_users.username, rt_users.email, rt_users.pic');
                $this->db->join('rt_users', 'rt_support_reply.repliers_id=rt_users.id');
                $this->db->where('rt_support_reply.id', $ok);
                $this->data['current'] = $this->treply_m->get();
                foreach($this->data['current'] as $c) :
                    echo '<li class="ticket-item">
                            <div class="row">
                                <div class="ticket-user col-md-6 col-sm-12">
                                    <img src="http://bootdey.com/img/Content/user_2.jpg" class="user-avatar">
                                    <span class="user-name">'.$c->name.'</span>
                                    <span class="user-at">Replied at</span>
                                    <span class="user-company">'.date("D d M Y g:i A", strtotime($c->created)).'</span>
                                </div>
                                <div class="ticket-time  col-md-4 col-sm-6 col-xs-12">
                                    <div class="divider hidden-md hidden-sm hidden-xs"></div>
                                    <i class="fa fa-dashboard"></i>
                                    <span class="time">3 Hours Ago</span>
                                </div>
                                <div class="ticket-type  col-md-2 col-sm-6 col-xs-12">
                                    <span class="divider hidden-xs"></span>
                                </div>
                                <div class="ticket-state bg-palegreen">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                  <p>'.$c->summary.'</p>
                                </div>
                            </div>
                        </li>';
                endforeach;
            }else{
                echo '
                    <li class="ticket-item">
                        <div class="row">
                            <div class="label label-warning">Sorry! could not proccess your request.</div>
                        </div>
                    </li>
                ';
            }
        }else{
            echo validation_errors();
        }
    }

    public function serve_ticket( $id = null ) {
        $this->load->model( array('dashboard/support_m', 'dashboard/treply_m'));
        if( $id && isset( $_POST['summary'] ) ) {

            $action = $this->input->post('action_taken');
            $ok = $this->treply_m->save( array( 'summary' => $this->input->post('summary'), 'ticket_id' => $id, 'action_taken' => $action ) );
            if( $action == 'Closed' ) {
                $this->support_m->save( array( 'status' => 2 ), $id );
            }
            if( $action == 'Suspended' ) {
                $this->support_m->save( array( 'status' => 3 ), $id );
            }

            echo ( $ok ) ? 'ok' : 'Sorry! cannot take action';
        } else {
            echo 'Sorry! no action taken';
        }
    }

    public function re_open_ticket($id = NULL){
        $this->load->model(array('dashboard/support_m', 'dashboard/treply_m'));
        $id = (int) $id;
        if($id){
            $data = array('status'=>1);
            $replies = array('ticket_id'=>$id, 'summary'=>'Re-opened this ticket again.', 'action_taken'=>'Re-open', 'repliers_id'=>$this->session->userdata('id'));
            $ok = $this->support_m->save($data, $id);
            $this->treply_m->save($replies);
            if($ok){
                echo 'ok';
            }else{
                echo 'no';
            }
        }else{
            echo 'no';
        }
    }

    public function activate_ticket($id = NULL){
        $this->load->model(array('dashboard/support_m', 'dashboard/treply_m'));
        $id = (int) $id;
        if($id){
            $data = array('status'=>1);
            $ok = $this->support_m->save($data, $id);

            $log = array(
                'ticket_id'=>$id,
                'summary'=>'Accept this Ticket.',
                'action_taken'=>'Accepted',
                'repliers_id'=>$this->session->userdata('id'),
            );

            $this->treply_m->save($log);

            if($ok){
                echo 'ok';
            }
        }
    }

    public function ticket_action($id = NULL){
        $id = (int) $id;
        if(!$id) return false;
        $this->load->model(array('dashboard/support_m', 'dashboard/treply_m'));

        if(!$this->user_m->have_permission(3)) return false;
        $rules = $this->support_m->rules;
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()==TRUE){
            $data = array();
            $data['modified'] = time();
            if($this->input->post('action_taken')=='Accepeted'){
                $data['status'] = 1;
            }elseif($this->input->post('action_taken')=='Closed'){
                $data['status'] = 2;
            }elseif($this->input->post('action_taken')=='Suspended'){
                $data['status'] = 3;
            }

            $ok = $this->support_m->save($data, $id);

            $replies = array('ticket_id'=>$id, 'summary'=>$this->input->post('summary'), 'action_taken'=>$this->input->post('action_taken'), 'repliers_id'=>$this->session->userdata('id'));
            $this->treply_m->save($replies);
            if($ok){
                echo 'ok';
            }
        }else{
            echo validation_errors();
        }
    }

    public function ticket_department_change( $id = NULL, $department = null ) {
        $this->load->model('dashboard/support_m');
        if( $id && $department ) {
            $id = (int) $id;
            $department = (string) $department;

            $ok = $this->support_m->save( array( 'department' => $department ), $id );
            echo ( $ok ) ? 'ok' : 'Sorry! Could not change the department.';
        } else {
            echo 'Sorry! no Department or Ticket ID supplied.';
        }
    }

    public function ticket_priority_change( $id = NULL, $priority = null ) {
        $this->load->model('dashboard/support_m');
        if( $id && $priority ) {
            $id = (int) $id;
            $priority = (string) $priority;

            $ok = $this->support_m->save( array( 'priority' => $priority ), $id );
            echo ( $ok ) ? 'ok' : 'Sorry! Could not change the Priority.';
        } else {
            echo 'Sorry! no Department or Ticket ID supplied.';
        }
    }

}



