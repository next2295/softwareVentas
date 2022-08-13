<?php 
//loginUser
	class Login extends Controllers{
		public function __construct()
		{
			session_start();
			parent::__construct();
			
			if (isset($_SESSION['login'])) {
				header('Location:'.base_url().'/home');
			}
			
		}

		public function login()
		{
			$data['page_tag'] = "Login-sistema Admin";
			$data['page_title'] = "Login";
			$data['page_name'] = "login";
			$data['page_functions_js'] = "functions_login.js";
			$this->views->getView($this,"login",$data);
		}
		public function loginUser(){
			if ($_POST) {
				if (empty($_POST['txtUser'])||empty($_POST['txtPassword'])) {
					$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
				}else{

					$user=strClean($_POST['txtUser']);
				    $password=strClean($_POST['txtPassword']);
				    $cript=hash('SHA256', $password);
				    $request_user=$this->model->loginUser($user,$cript);
				    if (empty($request_user)) {
						$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.' ); 
					}else{
						/*=============================================
						REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
						=============================================*/

						date_default_timezone_set('America/La_Paz');

						$fecha = date('Y-m-d');
						$hora = date('H:i:s');

						$fechaActual = $fecha.' '.$hora;
						$arrData = $request_user;
						if($arrData['status'] == 1){
							$_SESSION['idUser'] = $arrData['id_usuario'];
							$_SESSION['login'] = true;

							$arrData = $this->model->sessionLogin($_SESSION['idUser']);
							//$_SESSION['userData']=$arrData;
							sessionUser($_SESSION['idUser']);					
							$arrResponse = array('status' => true, 'msg' => 'ok');
							$ultimoLogin =$this->model->ultimoLoguin($arrData['id_usuario'],$fechaActual);
						}else{
							$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
						}
						
					}
				    
				}
				sleep(1);
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				
			}
			die();
		}

	}
 ?>