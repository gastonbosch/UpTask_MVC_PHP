<?php
   
    namespace Classes;

    use Brevo\Client\Configuration;
    use Brevo\Client\Api\TransactionalEmailsApi;
    use \Brevo\Client\Model\SendSmtpEmail;
    use Exception;
    use GuzzleHttp;

    class EmailBrevo {
        
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
            
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['API_KEY_BREVO']);

            $apiInstance = new TransactionalEmailsApi(
                new GuzzleHttp\Client(),
                $config
            );

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace</p>";
            $contenido .= "<p>Presiona aquí: <a href='".$_ENV['APP_URL']."/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= '</html>';    

            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => 'Confirma tu Cuenta',
                'sender' => ['name' => 'UpTask', 'email' => 'uptask@sendinblue.com'],
                //'replyTo' => ['name' => 'Sendinblue', 'email' => 'contact@sendinblue.com'],
                'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                'htmlContent' => '<html><body><h1>This is a transactional email {{params.bodyMessage}}</h1></body></html>',
                'params' => ['bodyMessage' => $contenido]
            ]); 

            try {
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                print_r($result);
            } catch (Exception $e) {
                echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            }
        }

        public function enviarInstrucciones(){
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['API_KEY_BREVO']);

            $apiInstance = new TransactionalEmailsApi(
                new GuzzleHttp\Client(),
                $config
            );

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Parece que has olvidado tu password, sigue el siguiente enlace para recuperarlo</p>";
            $contenido .= "<p>Presiona aquí: <a href='".$_ENV['APP_URL']."/reestablecer?token=" . $this->token . "'>Reestablecer Password</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= '</html>';

            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => 'Reestablece tu Password',
                'sender' => ['name' => 'UpTask', 'email' => 'uptask@sendinblue.com'],
                //'replyTo' => ['name' => 'Sendinblue', 'email' => 'contact@sendinblue.com'],
                'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                'htmlContent' => '<html><body><h1>This is a transactional email {{params.bodyMessage}}</h1></body></html>',
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
