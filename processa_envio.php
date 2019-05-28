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
		public $status = array('codigo_status'=> null, 'descricai_status' => '');
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

	if(!$mensagem->mensagemValida()){
		header('Location : index.php');

	}

		$mail = new PHPMailer(true);
		try {
		    //Server settings
		    #$mail->SMTPDebug = 2;// Enable verbose debug output
		    $mail->SMTPDebug = false;
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

		    $mensagem->status['codigo_status'] = 1;
		    $mensagem->status['descricao_status'] = 'Mensagem enviada com sucesso';
		} catch (Exception $e) {
		    $mensagem->status['codigo_status'] = 2;
		    $mensagem->status['descricao_status'] = 'Não foi possivel enviar o email' . $mail->ErrorInfo;
		}

?>

<!DOCTYPE html>
<html>
<head>
	
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
	<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
		<div class="row">
			<div class="col-md-12">
				<? if ($mensagem->status['codigo_status'] == 1) { ?>
					<div class=" container">
						<h1 class="display-4 text-success">Sucesso </h1>
						<p><?= $mensagem->status['descricao_status'] ?></p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
					</div>
				<? } ?>

				<? if ($mensagem->status['codigo_status'] == 2) { ?>
					<div class=" container">
						<h1 class="display-4 text-danger">Erro</h1>
						<p><?= $mensagem->status['descricao_status'] ?></p>
						<a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
					</div>


				<? } ?>
			</div>
		</div>
	</div>
</body>
</html>