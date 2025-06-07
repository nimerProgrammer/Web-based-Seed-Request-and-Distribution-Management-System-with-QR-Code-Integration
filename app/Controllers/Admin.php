<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
        return session()->get("user_id") ? redirect()->to(base_url('/admin/dashboard')) : redirect()->to(base_url('/admin/login'));
    }

    public function login()
    {
        session()->set("title", "Login");
        session()->set("current_tab", "login");

        return view('admin/login');
    }

    public function get_user_data()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Email not found'
            ]);
        }
    
        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Wrong password'
            ]);
        }
    
        session()->set([
            'user_id' => $user['users_tbl_id'],
            'user_email' => $user['email'],
            'logged_in' => true,
        ]);
    
        return $this->response->setJSON([
            'success' => true,
            'redirect_url' => base_url('admin/dashboard')
        ]);
        
        
    }

    public function get_user_data_by_id()
    {
        $user_id = $this->request->getPost("user_id");

        $User_Model = new UserModel();

        $user_data = $User_Model->where("users_tbl_id", $user_id)->findAll(1)[0];

        return json_encode($user_data);
    }

    public function dashboard()
    {
        if (!session()->get("user_id")) {
            session()->set("redirect_after_login", base_url(uri_string()));

            $response = [
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata("response", $response);

            return redirect()->to(base_url('/admin/login'));
        }

        session()->set("title", "Dashboard");
        session()->set("current_tab", "dashboard");

        $User_Model = new UserModel();

        $data["user"] = $User_Model->where("users_tbl_id", session()->get("user_id"))->findAll(1)[0];

        $header = view('admin/templates/header');
        $body = view('admin/dashboard');
        // $modals = view('_admin/modals/profile_modal');
        $footer = view('admin/templates/footer');

        return $header . $body . $footer;
    }

    public function inventory()
    {
        if (!session()->get("user_id")) {
            session()->set("redirect_after_login", base_url(uri_string()));

            $response = [
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata("response", $response);

            return redirect()->to(base_url('/admin/login'));
        }

        session()->set("title", "Inventory");
        session()->set("current_tab", "inventory");

        $header = view('admin/templates/header');
        $body = view('admin/inventory');
        // $modals = view('_admin/modals/profile_modal');
        $footer = view('admin/templates/footer');

        return $header . $body . $footer;
    }

    public function seedsRequests()
    {
        if (!session()->get("user_id")) {
            session()->set("redirect_after_login", base_url(uri_string()));

            $response = [
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata("response", $response);

            return redirect()->to(base_url('/admin/login'));
        }

        session()->set("title", "Seeds Requests");
        session()->set("current_tab", "seeds_requests");

        $header = view('admin/templates/header');
        $body = view('admin/seedsRequests');
        // $modals = view('_admin/modals/profile_modal');
        $footer = view('admin/templates/footer');

        return $header . $body . $footer;
    }

    public function beneficiaries()
    {
        if (!session()->get("user_id")) {
            session()->set("redirect_after_login", base_url(uri_string()));

            $response = [
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata("response", $response);

            return redirect()->to(base_url('/admin/login'));
        }

        session()->set("title", "Beneficiaries");
        session()->set("current_tab", "beneficiaries");

        $header = view('admin/templates/header');
        $body = view('admin/beneficiaries');
        // $modals = view('_admin/modals/profile_modal');
        $footer = view('admin/templates/footer');

        return $header . $body . $footer;
    }

    public function reports()
    {
        if (!session()->get("user_id")) {
            session()->set("redirect_after_login", base_url(uri_string()));

            $response = [
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata("response", $response);

            return redirect()->to(base_url('/admin/login'));
        }

        session()->set("title", "Reports");
        session()->set("current_tab", "reports");

        $header = view('admin/templates/header');
        $body = view('admin/reports');
        // $modals = view('_admin/modals/profile_modal');
        $footer = view('admin/templates/footer');

        return $header . $body . $footer;
    }

    public function logs()
    {
        if (!session()->get("user_id")) {
            session()->set("redirect_after_login", base_url(uri_string()));

            $response = [
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata("response", $response);

            return redirect()->to(base_url('/admin/login'));
        }

        session()->set("title", "Logs");
        session()->set("current_tab", "logs");

        $header = view('admin/templates/header');
        $body = view('admin/logs');
        // $modals = view('_admin/modals/profile_modal');
        $footer = view('admin/templates/footer');

        return $header . $body . $footer;
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('/admin/login'));
    }
    
    // public function searching()
    // {
    //     $search = $this->request->getPost("search");

    //     $User_Model = new UserModel();

    //     $user_data = $User_Model->like("email", $search)->findAll(5);

    //     return json_encode($user_data);

    // }
}
