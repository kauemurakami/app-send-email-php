<?php  

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php"; //especificações do protocolo de envio de email
	#print_r($_POST);

	use \PHPMailer\PHPMailer\PHPMailer;
	use \PHPMailer\PHPMailer\Exception;

	class Mensagem{
		private $para = null;
		private $assunto = null;
		private $mensagem = null;

		public function __get($atributo){
			return $this->$atributo;
		}

		public function __set($atributo, $valor){
			$this->$atributo = $valor;
		}

		public function mensagemValida(){
			if ( empty($this->para) || empty($this->assunto) || empty($this->mensagem) ){
				return false;	
			}
			return true;
		}
	}

	$mensagem = new Mensagem();
	$mensagem->__set('para',$_POST['para']);
	$mensagem->__set('assunto',$_POST['assunto']);
	$mensagem->__set('mensagem',$_POST['mensagem']);

	print_r($mensagem);

	if(!$mensagem->mensagemValida()){
		die();
	}

		$mail = new PHPMailer(true);
		try {
		    //Server settings
		    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'kauesarcastico@gmail.com';                 // SMTP username
		    $mail->Password = 'kauesarcastico';                           // SMTP password
		    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = 587;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('kauesarcastico@gmail.com', 'Kauê remetente');
		    $mail->addAddress($mensagem->__get('para'));     // Add a recipient
		    //$mail->addAddress('ellen@example.com');               // Name is optional
		    //$mail->addReplyTo('kauesarcastico@gmail.com', 'resposta para ');
		    //$mail->addCC('cc@example.com');
		    //$mail->addBCC('bcc@example.com');

		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $mensagem->__get('assunto');//assunto
		    $mail->Body    = $mensagem->__get('mensagem'); // conteudo do email
		    $mail->AltBody = 'é necessário utilizar um client que suport html para ter acesso total ao conteudo da mensagem';

		    $mail->send();
		    echo 'Email enviado com sucesso';
		} catch (Exception $e) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}

?>