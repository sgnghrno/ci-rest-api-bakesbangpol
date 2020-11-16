<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    // login method/page
    public function index()
    {
        // untuk memverifikasi sesi login
        verifyAccess(true);

        $data['title'] = 'Login';

        // validation forms        
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // load view login dengan template auth
            $this->load->view('template/auth/header_auth_view', $data);
            $this->load->view('auth/login_view');
            $this->load->view('template/auth/footer_auth_view');
        } else {
            // mengambil input form login
            $email = htmlspecialchars($this->input->post('email', true));
            $password = htmlspecialchars($this->input->post('password', true));

            // get user by email
            $data_user = $this->Auth_model->getUser('email', $email);

            // jika ada user
            if ($data_user) {
                // cek apakah user aktif

                // cek password
                if (password_verify($password, $data_user['password'])) {
                    // cek role user
                    // lalu redirect ke aplikasi
                    if ($data_user['level'] == 1) {
                        // data untuk session
                        $session_data = [
                            'id_user' => $data_user['id_user'],
                            'email' => $data_user['email'],
                            'level' => $data_user['level'],
                        ];

                        // data untuk login history
                        $login_history = [
                            'id_user' => $data_user['id_user'],
                            // 'os' => getOS(),
                            // 'browser' => getBrowser(),
                            // 'ip' => getUserIP(),
                            'created_at' => time(),
                        ];

                        // menyimpan session login
                        $this->session->set_userdata($session_data);

                        // menyimpan riwayat login
                        $this->Auth_model->saveLoginLog($login_history);

                        redirect('admin');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Hanya admin yang dapat mengakses sistem ini.</div>');
                        redirect('login');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Password salah.</div>');

                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Email tidak terdaftar</div>');

                redirect('login');
            }
        }
    }

    public function forgotPassword()
    {
        // untuk memverifikasi sesi login
        verifyAccess(true);

        $data['title'] = 'Forgot Password';

        $this->load->view('template/auth/header_auth_view', $data);
        $this->load->view('auth/forgotpassword_view');
        $this->load->view('template/auth/footer_auth_view');
    }

    // fungsi untuk logout
    public function logout()
    {
        // unset session login
        $this->session->unset_userdata('id_user');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('level');

        $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Anda telah berhasil Log Out</div>');

        redirect('login');
    }
}
