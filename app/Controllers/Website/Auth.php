<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('website/auth/login', ['title' => 'Login']);
    }

    public function attempt()
    {
        if (! $this->validate(['email' => 'required|valid_email', 'password' => 'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $user = (new UserModel())->where('email', $this->request->getPost('email'))->first();
        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email or password is incorrect.');
        }
        if (! $user['status']) {
            return redirect()->back()->with('error', 'Your account is disabled. Please contact support.');
        }
        session()->regenerate();
        session()->set(['user_id' => $user['id'], 'user_name' => $user['name']]);
        $to = session()->get('redirect_after_login') ?: base_url('account');
        session()->remove('redirect_after_login');
        return redirect()->to($to)->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    public function register()
    {
        return view('website/auth/register', ['title' => 'Create account']);
    }

    public function store()
    {
        $rules = [
            'name'             => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'required|numeric|min_length[10]|max_length[15]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $users = new UserModel();
        $id    = $users->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'   => 1,
        ]);
        session()->regenerate();
        session()->set(['user_id' => $id, 'user_name' => $this->request->getPost('name')]);
        return redirect()->to(base_url('account'))->with('success', 'Account created. Welcome to ' . site_name() . '!');
    }

    public function forgot()
    {
        return view('website/auth/forgot', ['title' => 'Forgot password']);
    }

    /**
     * Generates a reset token. No mail server is configured by default, so in
     * development the reset link is shown on screen. In production, send $link by email.
     */
    public function sendReset()
    {
        if (! $this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $users = new UserModel();
        $user  = $users->where('email', $this->request->getPost('email'))->first();
        if ($user) {
            $token = bin2hex(random_bytes(24));
            $users->update($user['id'], ['reset_token' => hash('sha256', $token), 'reset_expires' => date('Y-m-d H:i:s', time() + 3600)]);
            $link = base_url('reset-password/' . $token);
            if (ENVIRONMENT !== 'production') {
                return redirect()->back()->with('success', 'Demo mode: open this link to reset your password: ' . $link);
            }
            // TODO production: send $link with CodeIgniter Email library.
        }
        return redirect()->back()->with('success', 'If that email is registered, a reset link has been sent.');
    }

    public function reset(string $token)
    {
        if (! $this->userByToken($token)) {
            return redirect()->to(base_url('forgot-password'))->with('error', 'This reset link is invalid or expired.');
        }
        return view('website/auth/reset', ['title' => 'Reset password', 'token' => $token]);
    }

    public function updatePassword(string $token)
    {
        $user = $this->userByToken($token);
        if (! $user) {
            return redirect()->to(base_url('forgot-password'))->with('error', 'This reset link is invalid or expired.');
        }
        if (! $this->validate(['password' => 'required|min_length[6]', 'confirm_password' => 'required|matches[password]'])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }
        (new UserModel())->update($user['id'], [
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);
        return redirect()->to(base_url('login'))->with('success', 'Password updated. Please login.');
    }

    private function userByToken(string $token): ?array
    {
        return (new UserModel())->where('reset_token', hash('sha256', $token))
            ->where('reset_expires >=', date('Y-m-d H:i:s'))->first();
    }

    public function logout()
    {
        session()->remove(['user_id', 'user_name']);
        return redirect()->to(base_url('login'))->with('info', 'You have been logged out.');
    }
}
