<?php
   
    namespace Classes;

use Brevo\Client\Api\TransactionalEmailsApi as ApiTransactionalEmailsApi;
use Brevo\Client\Configuration as ClientConfiguration;
use Brevo\Client\Model\SendSmtpEmail as ModelSendSmtpEmail;
use Brevo\Client\TransactionalEmailsApiTest;
use SendinBlue\Client\Configuration;
    use SendinBlue\Client\Api\TransactionalEmailsApi;
    use \SendinBlue\Client\Model\SendSmtpEmail;
    use Exception;
    use GuzzleHttp;

    class Email {
        
        protected $email;
        protected $nombre;
        protected $token;
    
        public function __construct($email, $nombre, $token)
        {
            $this->email = $email;
            $this->nombre = $nombre;
            $this->token = $token;
    
        }

        public function enviarConfirmacion(){
            
            $config = ClientConfiguration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['API_KEY_BREVO']);

            $apiInstance = new ApiTransactionalEmailsApi(
                new GuzzleHttp\Client(),
                $config
            );

            /*$contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace</p>";
            $contenido .= "<p>Presiona aquí: <a href='".$_ENV['APP_URL']."/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= '</html>';    

            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => 'Confirma tu Cuenta',
                'sender' => ['name' => 'UpTask', 'email' => 'uptask@sendinblue.com'],
                //'replyTo' => ['name' => 'Sendinblue', 'email' => 'contact@sendinblue.com'],
                'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                'htmlContent' => '{{params.bodyMessage}}',
                'params' => ['bodyMessage' => $contenido]
            ]); */

            $sendSmtpEmail = new ModelSendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
            $sendSmtpEmail['to'] = array(array('email'=>$this->email, 'name'=>$this->nombre));
            $sendSmtpEmail['templateId'] = 1;
            $sendSmtpEmail['params'] = array('nombre'=>$this->nombre, 'token'=>$this->token, 'dominio'=>$_ENV['APP_URL']);
            $sendSmtpEmail['headers'] = array('api-key'=>'xkeysib-'||$_ENV['API_KEY_BREVO'], 
                                              'content-type'=>'application/json',
                                              'accept'=>'application/json');
            try {
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                print_r($result);
            } catch (Exception $e) {
                echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            }
        }

        public function enviarInstrucciones(){
            $config = ClientConfiguration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['API_KEY_BREVO']);

            $apiInstance = new ApiTransactionalEmailsApi(
                new GuzzleHttp\Client(),
                $config
            );

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Parece que has olvidado tu password, sigue el siguiente enlace para recuperarlo</p>";
            $contenido .= "<p>Presiona aquí: <a href='".$_ENV['APP_URL']."/reestablecer?token=" . $this->token . "'>Reestablecer Password</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= '</html>';

            $sendSmtpEmail = new ModelSendSmtpEmail([
                'subject' => 'Reestablece tu Password',
                'sender' => ['name' => 'UpTask', 'email' => 'uptask@sendinblue.com'],
                //'replyTo' => ['name' => 'Sendinblue', 'email' => 'contact@sendinblue.com'],
                'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                'htmlContent' => '{{params.bodyMessage}}',
                'params' => ['bodyMessage' => $contenido]
            ]); 

            try {
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                print_r($result);
            } catch (Exception $e) {
                echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            }
        }

    }

?>
