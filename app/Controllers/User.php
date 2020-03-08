<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class User extends Controller
{
  public function index()
  {
    return view('welcome_message');
  }

  public function create()
  {
    try
    {
      helper(['form', 'url']);
      $db = \Config\Database::connect();
      $builder = $db->table('user');
      $firstName = $this->request->getVar('prenom');
      $name = $this->request->getVar('nom');
      $email = $this->request->getVar('email');
      $token = md5($email);
      $password = $this->request->getVar('password');

      if(empty($firstName) || empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password))
      {
        $data = [
         'success' => false,
         'msg' => "Veuillez compléter le formulaire en respectant les règles de chaque champ"
        ];
        return $this->response->setJSON($data);
      }

      $data = [
          'first_name' => $firstName,
          'name' => $name,
          'email' => $email,
          'token' => $token,
          'hashed_password' => $this->hashPassword($this->request->getVar('password')),
          'creation_date' => date("Y-m-d"),
          'is_admin' => 0
          ];

         if($builder->insert($data))
         {
           $this->sendConfirmationMail($token, $email, $firstName, $name);
         }

         $data = [
          'success' => true,
          'msg' => "Un email de confirmation vous a été envoyé"
     ];
    }
    catch (\Exception $e)
    {
      $data = [
       'success' => false,
       'msg' => "Une erreur est survenue lors de votre inscription"
      ];
    }

     return $this->response->setJSON($data);
  }

  private function sendConfirmationMail($token, $email, $firstName, $name)
  {
    $to      = $email;
    $subject = 'Confirmation de votre inscription';
    $message = 'Bonjour '.$firstName.' '.$name."\r\n".'Veuillez cliquer sur ce lien pour activer votre compte : '.base_url('index.php/user/activateaccount/'.$token);
    $headers = 'From: interway.notification@gmail.com' . "\r\n" .
    'Reply-To: noReply' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
     mail($to, $subject, $message, $headers);
  }

  private function hashPassword($password = null)
  {
    $options = [
    'memory_cost' => 1<<17, // 128 Mb
    'time_cost'   => 4,
    'threads'     => 3,
    ];
    $hash = password_hash($password, PASSWORD_ARGON2I, $options);
    return $hash;
  }

  public function verifyPassword($password = null, $saltedPassword = null)
  {
    if(password_verify('jslkfjsdghiiKMLK54564', '$argon2i$v=19$m=131072,t=4,p=3$VVhwajhXemxoOW9WNmxLSg$Ad4fjQwTomY1GUUnHyKs2CY0M9LZWI3gxyGKJgIShzY'))
    {
      var_dump('good password');
    }
    else
    {
      var_dump('wrong password');
    }
  }

  public function activateaccount($token)
  {
    if(strlen($token) == 32)
    {
      $db = \Config\Database::connect();
      $builder = $db->table('user');
      $data = [
        'token' => '',
        'is_active' => 1
      ];
      $builder->where('token', $token);
      $builder->update($data);
      if($db->affectedRows() == 1)
      {
        return view('activationsuccess');
      }
      else
      {
        return view('activationfailed');
      }
    }
    else
    {
      return view('activationfailed');
    }
  }
}
