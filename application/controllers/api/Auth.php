<?php

use Restserver\Libraries\REST_Controller;

use function PHPSTORM_META\type;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Auth extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->__resTraitConstruct();

        $this->load->model('Auth_model');
        $this->load->model('Laporan_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');

        $userData = $this->db->get_where('tb_user', ['email' => $email])->row_array();

        // apabila email ada
        if ($userData) {
            // cek password
            if (password_verify($password, $userData['password'])) {
                $data_login = [
                    'id_user' => $userData['id_user'],
                    'created_at' => time()
                ];

                $this->Auth_model->saveLoginLog($data_login);

                $this->response([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'data' => $userData
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Password Tidak Sesuai'
                ], 401);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ], 401);
        }
    }

    public function index_get($tipe, $id = NULL)
    {
        if ($tipe == 'all') {
            $data_user = $this->Auth_model->getUser('all');

            if ($data_user) {
                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Mendapatkan Semua Data User',
                    'data' => $data_user
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Mendapatkan Semua Data User',
                ], 401);
            }
        }

        if ($tipe == 'id_user') {
            $id_user = $id;
            $data_user = $this->Auth_model->getUser('id_user', $id_user);

            if ($data_user) {
                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Mendapatkan Data User',
                    'data' => $data_user
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Mendapatkan Data User',
                ], 401);
            }
        }

        if ($tipe == 'profil') {
            $id_user = $id;
            $data_user = $this->Auth_model->getUser('id_user', $id_user);
            $all_user = $this->Auth_model->getUser('level', 2);
            $all_laporan_user = $this->Laporan_model->getLaporan('id_user', $id_user);

            if ($data_user) {
                $response = [
                    'user' => $data_user,
                    'count_users' => count($all_user),
                    'count_laporan' => count($all_laporan_user),
                ];

                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Mendapatkan Data Profil',
                    'data' => $response
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Mendapatkan Data Profil',
                ], 404);
            }
        }

        if ($tipe == 'level') {
            $id_level = $id;
            $data_user = $this->Auth_model->getUser('level', $id_level);

            if ($data_user) {
                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Mendapatkan Data User',
                    'data' => $data_user
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Mendapatkan Data User',
                ], 401);
            }
        }
    }

    // test
    public function register_post()
    {
        $this->load->model('Auth_model');
        $email = $this->post('email');

        // cek apakah email sudah terdaftar
        $user = $this->Auth_model->getUserByEmail($email);

        if ($user) {
            $this->response([
                'status' => false,
                'message' => 'Email telah terdaftar',
                'data' => $user
            ], 401);
        }

        // data ditangkap
        $data_user = [
            'level' => 2,
            'username' => $this->post('username'),
            'email' => $email,
            'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
            'telepon' => $this->post('telepon'),
            'nik' => $this->post('nik'),
            'alamat' => $this->post('alamat'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'foto' => 'no-image.jpg',
            'dibuat_pada' => time()
        ];

        // data diinput
        if ($this->Auth_model->insertUser($data_user)) {
            $this->response([
                'status' => true,
                'message' => 'Register Berhasil',
                'data' => $data_user
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Register Gagal',
                'data' => $data_user
            ], 401);
        }
        // respon
    }

    //fungsi hapus data
    public function delete_delete($id_user)
    {
        // $id_user = $this->post('id_user');

        $user = $this->Auth_model->getUser('id_user', $id_user);

        if (!$user) {
            $this->response([
                'status' => false,
                'message' => 'User Tidak Ditemukan',
            ], 404);
        }

        // hapus data
        if ($this->Auth_model->deleteUser($id_user)) {
            $this->response([
                'status' => true,
                'message' => 'User Berhasil Dihapus',
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'User Gagal Dihapus',
            ], 401);
        }
    }

    public function update_put($tipe)
    {
        if ($tipe == 'user') {
            // jika update detail user
            $id_user = $this->put('id_user');

            $user = $this->Auth_model->getUser('id_user', $id_user);

            if ($user) {
                $data_update = [
                    'id_user' => $id_user,
                    'username' => $this->put('username'),
                    'email' => $this->put('email'),
                    'nik' => $this->put('nik'),
                    'telepon' => $this->put('telepon'),
                    'alamat' => $this->put('alamat'),
                    'diubah_pada' => time()
                ];

                if ($this->Auth_model->updateUser('id_user', $data_update, $id_user)) {
                    $this->response([
                        'status' => true,
                        'message' => 'Berhasil mengupdate user',
                        'data' => $data_update
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal mengupdate user',
                    ], 401);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'User Tidak Ditemukan',
                ], 404);
            }
        }

        if ($tipe == 'password') {
            $id_user = $this->put('id_user');
            $password_lama = $this->put('password_lama');
            $password_baru = $this->put('password_baru');

            $user = $this->Auth_model->getUser('id_user', $id_user);

            if ($user) {
                if (password_verify($password_lama, $user['password'])) {
                    $data = [
                        'password' => password_hash($password_baru, PASSWORD_DEFAULT),
                    ];

                    if ($this->Auth_model->updateUser('id_user', $data, $id_user)) {
                        $this->response([
                            'status' => true,
                            'message' => 'Password Berhasil Dirubah',
                        ], 200);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Password Gagal Dirubah',
                        ], 401);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Password Lama Tidak Sesuai',
                    ], 200);
                }
            } else {
                // jika user tidak ada
                $this->response([
                    'status' => false,
                    'message' => 'User Tidak Ditemukan',
                ], 404);
            }
        }
    }

    // fungsi proses send email
    private function _sendEmail($token, $email, $type)
    {
        $config = [
            'protocol'      => 'smtp',
            'smtp_host'     => 'smtp.gmail.com',
            'smtp_user'     => 'indiekostteknotirta@gmail.com',
            'smtp_pass'     => 'indieforever',
            'smtp_port'     => 465,
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'smtp_crypto'   => 'ssl',
            'crlf'          => "\r\n",
            'newline'       => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('indiekostteknotirta@gmail.com', 'INDIEKOST');
        $this->email->to($email);

        if ($type == 'verify') {
            $message = 'Klik link berikut untuk melakukan aktivasi akun anda <a href="' . base_url("auth/verify") . '?email=' . $email . '&token=' . $token . '">AKTIVASI AKUN</a>';

            $this->email->subject('Verifikasi Akun INDIEKOST');
            $this->email->message($message);
        } else if ($type == 'forgot') {
            $message = 'Klik link berikut untuk mengatur ulang password akun anda <a href="' . base_url("auth/resetpassword") . '?email=' . $email . '&token=' . $token . '">RESET PASSWORD</a>';

            $this->email->subject('Reset Password Akun INDIEKOST');
            $this->email->message($message);
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function forgotPassword_post()
    {
        if ($this->post('email')) {
            // jika ada email
            $email = $this->post('email');

            $user = $this->db->get_where('pengguna', ['email_pengguna' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                $token = uniqid(true);

                $user_token = [
                    'email'         => $email,
                    'token'         => $token,
                    'date_created'  => time()
                ];

                $this->db->insert('user_token', $user_token);

                $this->_sendEmail($token, $email, 'forgot');

                $this->response([
                    'status' => true,
                    'message' => 'Berhasil mengirim email reset password'
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Email tidak terdaftar atau belum aktif'
                ], 401);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ], 401);
        }
    }
}
