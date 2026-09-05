<?php
if(!function_exists('rs_form_group')){
	function rs_form_group($data = array(),$input = ''){
		static $form_group_id = 0;
		$form_group_id++;
		?>
		<div id="<?php echo 'from_group_'.$form_group_id; ?>" class="<?php echo (isset($data['group_class'])? $data['group_class']: 'form-group'); ?> <?php echo ($data['media']? 'media_upload': ''); ?> app-form-field">
			<label class="<?php echo (isset($data['label_class'])? $data['label_class']: 'form-label'); ?>" for="<?php echo (isset($data['id'])? $data['id']: ''); ?>">
				<?php echo $data['label']; ?>
				<?php if(isset($data['required']) && $data['required']){
					echo '<span class="required">*</span>';
				} ?>
			</label>
			<div class="<?php echo (isset($data['input_class'])? $data['input_class']: 'form-input-wrap'); ?>">
				<?php
				echo $input;
				if($data['media']){
					?>
						<button class="btn btn-ghost dialog_open" data-title="Media Library" data-url="<?php echo base_url('ajax_query/uploader/'); ?>" type="button">Select media</button>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}


if(!function_exists('get_country')){
	function get_country($data = null){
		static $country_names = null;
		if ($country_names === null) {
			$country_path = FCPATH.'files/country/names.json';
			$country_names = json_decode(file_get_contents($country_path), true);
		}
		if(is_null($data)){
			return $country_names;
		}
		$key = strtoupper($data);
		return isset($country_names[$key]) ? $country_names[$key] : $data;
	}
}

if(!function_exists('get_days')){
	function get_days(){
		$days = array(
		    "Sunday"=>'Sunday',
		    "Monday"=>'Monday',
		    "Tuesday"=>'Tuesday',
		    "Wednesday"=>'Wednesday',
		    "Thursday"=>'Thursday',
		    "Friday"=>'Friday',
		    "Saturday"=>'Saturday'
		);
		return $days;
	}
}




if(!function_exists('get_department')){
	function get_department($data = array()){
		$CI =& get_instance(); 
		$CI->load->model("Department_model");
		return $CI->Department_model->Get($data);
		
	}
}

if(!function_exists('get_department_name')){
	function get_department_name($id, $fallback = 'Unknown Department'){
		$rows = get_department(array('id' => $id));
		return (!empty($rows) && isset($rows[0]->name) && $rows[0]->name !== '') ? $rows[0]->name : $fallback;
	}
}

if(!function_exists('current_login_user')){
	function current_login_user(){
		$CI =& get_instance();
		$CI->load->library(array('session'));
		$user = $CI->session->userdata('login_user');
		return is_array($user) ? $user : array();
	}
}

if(!function_exists('is_admin_user')){
	function is_admin_user(){
		$user = current_login_user();
		return isset($user['role']) && $user['role'] === 'admin';
	}
}

if(!function_exists('rs_placeholder_avatar')){
	function rs_placeholder_avatar($label = 'User'){
		$words = preg_split('/\s+/', trim((string)$label));
		$initials = '';
		foreach ($words as $word) {
			if ($word === '') {
				continue;
			}
			$initials .= strtoupper(substr($word, 0, 1));
			if (strlen($initials) >= 2) {
				break;
			}
		}
		if ($initials === '') {
			$initials = 'DR';
		}
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120">'
			.'<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
			.'<stop offset="0%" stop-color="#7c83ff"/><stop offset="100%" stop-color="#4f46e5"/>'
			.'</linearGradient></defs>'
			.'<rect width="120" height="120" rx="28" fill="url(#g)"/>'
			.'<text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle"'
			.' font-family="Arial, sans-serif" font-size="40" font-weight="700" fill="#ffffff">'.$initials.'</text>'
			.'</svg>';
		return 'data:image/svg+xml;charset=UTF-8,'.rawurlencode($svg);
	}
}

if(!function_exists('rs_profile_avatar')){
	function rs_profile_avatar($role = 'patient', $seed = ''){
		$role = strtolower(trim((string)$role));
		$pools = array(
			'doctor' => array(
				'doctor_3d_avatar_01.png','doctor_3d_avatar_02.png','doctor_3d_avatar_03.png','doctor_3d_avatar_04.png','doctor_3d_avatar_05.png',
				'doctor_3d_avatar_06.png','doctor_3d_avatar_07.png','doctor_3d_avatar_08.png','doctor_3d_avatar_09.png','doctor_3d_avatar_10.png'
			),
			'patient' => array(
				'patient_3d_avatar_01.png','patient_3d_avatar_02.png','patient_3d_avatar_03.png','patient_3d_avatar_04.png','patient_3d_avatar_05.png',
				'patient_3d_avatar_06.png','patient_3d_avatar_07.png','patient_3d_avatar_08.png','patient_3d_avatar_09.png','patient_3d_avatar_10.png'
			),
			'student' => array(
				'student_3d_avatar_01.png','student_3d_avatar_02.png','student_3d_avatar_03.png','student_3d_avatar_04.png','student_3d_avatar_05.png',
				'student_3d_avatar_06.png','student_3d_avatar_07.png','student_3d_avatar_08.png','student_3d_avatar_09.png','student_3d_avatar_10.png'
			),
		);
		if (!isset($pools[$role])) {
			$role = 'student';
		}
		$list = $pools[$role];
		$seed = trim((string)$seed);
		$index = $seed === '' ? 0 : abs(crc32($role.'-'.$seed)) % count($list);
		return 'assets/images/profiles/'.$list[$index];
	}
}

if(!function_exists('rs_media_url')){
	function rs_media_url($path, $label = 'User'){
		$path = trim((string)$path);
		if ($path === '') {
			return rs_placeholder_avatar($label);
		}
		if (strpos($path, 'http://localhost/hospital/') === 0) {
			return rs_placeholder_avatar($label);
		}
		if (strpos($path, 'assets/') === 0 || strpos($path, 'images/') === 0 || strpos($path, 'uploads/') === 0) {
			return base_url($path);
		}
		return $path;
	}
}


if(!function_exists('get_schedule')){
	function get_schedule($data = array()){
		$CI =& get_instance(); 
		$CI->load->model("Hospital_model");
		$CI->Hospital_model->set_table('doctors_schedule');
		return $CI->Hospital_model->Get_Data($data);
		
	}
}
if(!function_exists('get_doctors')){
	function get_doctors($data = array()){
		$CI =& get_instance(); 
		$CI->load->model("Hospital_model");
		$CI->Hospital_model->set_table('doctor');
		return $CI->Hospital_model->Get_Data($data);
		
	}
}
if(!function_exists('get_users')){
	function get_users($data = array()){
		$CI =& get_instance(); 
		$CI->load->model("Hospital_model");
		$CI->Hospital_model->set_table('user');
		return $CI->Hospital_model->Get_Data($data);
		
	}
}



if(!function_exists('is_login')){
	function is_login(){
		$CI =& get_instance(); 
		$CI->load->library(array('session'));
		if($CI->session->userdata('is_login')){
			return true;
		}
		if(isset($_COOKIE['hms_auth']) && !empty($_COOKIE['hms_auth'])){
			$decoded = json_decode(base64_decode($_COOKIE['hms_auth']), true);
			if(is_array($decoded) && !empty($decoded)){
				$CI->session->set_userdata('login_user', $decoded);
				$CI->session->set_userdata('is_login', true);
				return true;
			}
		}
		if($CI->session->userdata('is_login')){
			return true;
		}else{
			return false;
		}
	}
}
if(!function_exists('only_access')){
	function only_access($dataArray = array()){
		$CI =& get_instance();
		$loginUserRule = $CI->session->userdata("login_user")["role"];
		$CI->load->library(array('session'));
		if(!in_array($loginUserRule,$dataArray))
		redirect(base_url());
	}
}
