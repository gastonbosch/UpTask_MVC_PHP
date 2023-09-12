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
            try {
                $config = ClientConfiguration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['API_KEY_BREVO']);

                $apiInstance = new ApiTransactionalEmailsApi(
                    new GuzzleHttp\Client(),
                    $config
                );

                $sendSmtpEmail = new ModelSendSmtpEmail([
                    'subject' => 'Confirma tu Cuenta',
                    'sender' => ['name' => 'Gaston', 'email' => 'gastonbosch@hotmail.com'],
                    //'replyTo' => ['name' => 'Sendinblue', 'email' => 'contact@sendinblue.com'],
                    //'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                    'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                    'htmlContent' => '<html><body><p><strong>Hola {{params.nombre}}</strong> 
                    Has Creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace</p>
                    <p>Presiona aquí: <a href="{{params.dominio}}/confirmar?token={{params.token}}">Confirmar Cuenta</a></p>
                    <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p></body></html>',
                    'params' => ['nombre' => $this->nombre, 'token'=>$this->token, 'dominio'=>$_ENV['APP_URL']]
                ]); 
                
                /*$sendSmtpEmail = new ModelSendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
                $sendSmtpEmail['to'] = array(array('email'=>$this->email, 'name'=>$this->nombre));
                $sendSmtpEmail['templateId'] = 1;
                $sendSmtpEmail['params'] = array('nombre'=>$this->nombre, 'token'=>$this->token, 'dominio'=>$_ENV['APP_URL']);
                $sendSmtpEmail['headers'] = array('api-key'=>'xkeysib-'||$_ENV['API_KEY_BREVO'], 
                                                'content-type'=>'application/json',
                                                'accept'=>'application/json');*/
                
                
                
                //debuguear($sendSmtpEmail);
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                //print_r($result);
            } catch (Exception $e) {
                //debuguear($e->getMessage());
                echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            }
        }

        public function enviarInstrucciones(){

            try {
                $config = ClientConfiguration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['API_KEY_BREVO']);

                $apiInstance = new ApiTransactionalEmailsApi(
                    new GuzzleHttp\Client(),
                    $config
                );

                $sendSmtpEmail = new ModelSendSmtpEmail([
                    'subject' => 'Reestablecer password',
                    'sender' => ['name' => 'Gaston', 'email' => 'gastonbosch@hotmail.com'],
                    //'replyTo' => ['name' => 'Sendinblue', 'email' => 'contact@sendinblue.com'],
                    //'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                    'to' => [[ 'name' => $this->nombre, 'email' => $this->email]],
                    'htmlContent' => '<html><p><strong>Hola {{params.nombre}}</strong> Parece que has olvidado tu password, sigue el siguiente enlace para recuperarlo</p>
                    <p>Presiona aquí: <a href="{{params.dominio}}/reestablecer?token={{params.token}}">Reestablecer Password</a></p>
                    <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p></html>',
                    'params' => ['nombre' => $this->nombre, 'token'=>$this->token, 'dominio'=>$_ENV['APP_URL']]
                ]); 
                
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                
            } catch (Exception $e) {
                echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            }
        }
    }

?>
