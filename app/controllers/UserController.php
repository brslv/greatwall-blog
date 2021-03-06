<?php

/**
 * UserController
 */
class User extends Main {

	/**
	 * The default method
	 *
	 */
	public function index() {
		Redirect::to('user/me');
	}

	/**
	 * Loggs in the user
	 *
	 */
	public function login() {
		$message = null;

		if (isset($_POST['loginSubmit'])) {
			$userModel = $this->getModel('UserModel');
			$user = $userModel->login();

			if ($user) {
				$_SESSION['id'] = $user->id;
				$_SESSION['username'] = $user->username;
				$_SESSION['firstname'] = $user->firstname;
				$_SESSION['lastname'] = $user->lastname;
				$_SESSION['email'] = $user->email;
				$_SESSION['role'] = $user->role;

				Redirect::to('user/me');
			} else {
				$message = ['Login failed. Please, try again.'];
			}
		}

		$this->getView('loginView', $message);
	}

	/**
	 * Registers a new user
	 *
	 */
	public function register() {
		$message = null;

		if (isset($_POST['registerSubmit'])) {
			$userModel = $this->getModel('UserModel');
			$user = $userModel->register();
			if ($user) {
				$message = ['Nice! You\'re awesome. Now log in the system.'];
			} else {
				$message = ['Something went wrong. Please, try again.'];
			}
		}

		$this->getView('registerView', $message);
	}

	/**
	 * Gets the profileView view, which displays information about the user
	 *
	 */
	public function me() {
		if (!isset($_SESSION['username'])) {
			Redirect::to('homepage');
		}

		$commentModel = $this->getModel('CommentModel');
		$myComments = $commentModel->getMine();

		$data = [
			'myComments' => $myComments,
		];

		$this->getView('profileView', $data);
	}

	/**
	 * Performs a logout action and destroys the current session
	 *
	 */
	public function logout() {
		session_destroy();
		session_unset('username');
		session_unset('id');
		session_unset('firstname');
		session_unset('lastname');
		session_unset('email');
		session_unset('role');
		Redirect::to('homepage');
	}

	/**
	 * Change password and username
	 * 
	 * @param  stirng $thing Provide 'password' / 'username', based on what the client whants to change
	 */
    public  function change($thing){
        $userModel = $this->getModel('UserModel');
        $msg = null;

        if(empty($thing)){
            Redirect::to("user/me");
        }
        if($thing[0]=='password'){
            if(isset($_POST['submit'])){
            	if(!empty($_POST['password'])) {
	                $newPassword = $_POST['password'];
	                if($userModel->changePassword($newPassword)) {
	                	$msg = 'You have changed your password.';
	                } else {
	                	$msg = 'Something went wrong. Please, try again.';
	                }
	            }
            }

            $data = [
            	'message' => $msg
            ];

            $this->getView('changePasswordView', $data);

        }else if($thing[0]=='username'){


            if(isset($_POST['submit'])){
            	if(!empty($_POST['newUserName'])) {
	                $newUsername = $_POST['newUserName'];
	                if($userModel->changeUsername($newUsername)) {
	                	$msg = 'You have changed your username successfull.';
	                } else {
	                	$msg = 'Something went wrong. Please, try again.';
	                }
	            }
            }

            $data = [
            	'message' => $msg
            ];

            $this->getView('changeUsernameView', $data);
        }

    }
}
