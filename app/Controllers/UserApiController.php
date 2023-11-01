<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use \Firebase\JWT\KEY;

class UserApiController extends BaseController
{
	use ResponseTrait;
	public function create()
	{
		$users = new User();

		$data = [
			'name' => $this->request->getVar('name'),
			'email' => $this->request->getVar('email'),
			'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
		];

		$is_email = $users->where('email', $this->request->getVar('email'))->first();
		if ($is_email) {
			return $this->respondCreated([
				'status' => 0,
				'message' => 'Email already exists'
			]);
		} else {
			$result = $users->save($data);
			if ($result) {
				return $this->respondCreated([
					'status' => 1,
					'message' => 'User created successfully'
				]);
			} else {
				return $this->respondCreated([
					'status' => 0,
					'message' => 'Failed to create user'
				]);
			}
		}
	}

	public function login()
	{
		$users = new User();
		$email = $this->request->getVar('email');
		$password = $this->request->getVar('password');
		$is_email = $users->where('email', $email)->first();
		if ($is_email) {
			$verify_password = password_verify($password, $is_email['password']);
			if ($verify_password) {
				$key = 'claraafhalidhfagldaf';
				$payload = [
					'iss' => 'localhost',
					'aud' => 'localhost',
					'data' => [
						'user_id' => $is_email['id'],
						'name' => $is_email['name'],
						'email' => $is_email['email']
					]
				];
				$jwt = JWT::encode($payload, $key, 'HS256');
				return $this->respondCreated([
					'status' => 1,
					'jwt' => $jwt,
					'message' => 'user Login successfully'
				]);
			} else {
				return $this->respondCreated([
					'status' => 0,
					'message' => 'Invalid Email and Password'
				]);
			}
		} else {
			return $this->respondCreated([
				'status' => 0,
				'message' => 'Email is not exists'
			]);
		}
	}

	public function readUser()
	{
		$request = service('request');
		$key = 'claraafhalidhfagldaf';
		$headers = $request->getHeader('authorization');
		$jwt = $headers->getValue();
		$userData = JWT::decode($jwt, new KEY($key, 'HS256'));
		$users = $userData->data;
		return $this->respond([
			'status' => 1,
			'users' => $users
		]);
	}
}
