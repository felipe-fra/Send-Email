<?php 

    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";
    require "./bibliotecas/PHPMailer/POP3.php";
    require "./bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array ('codigo_status' => null, 'descricao_status' => '');

        public function __get($atributo){
            return $this->$atributo;           
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;            
        }

        public function messagemValida (){
            if(empty($this->mensagem) || empty($this->assunto) || empty($this->para)){
                
                return false;
            } else{
                
                return true;
            }
        }

    }

    $mensagem = new Mensagem();

    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);


    if (!$mensagem->messagemValida()){
        echo 'Mensagem não é válida';
        header('location: index.php');
        die();
    }
    $mail = new PHPMailer(true);
     

    try {
        //Configuração do servidor
        $mail->SMTPDebug = false;                                 
        $mail->isSMTP();                                     
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;                               
        $mail->Username = 'felipealmeidafra@gmail.com';                 
        $mail->Password = '31322907felipe'  ;                           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $mail->Port = 587;                                    

        //Remetente e destinatario 
        $mail->setFrom('felipealmeidafra@gmail.com', 'Felipe Remetente');
        $mail->addAddress($mensagem->__get('para'), 'Felipe Destinatario');


        //Conteudo do e-mail
        $mail->isHTML(true);                                 
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'Para ter acesso ao conteudo completo da Msg use um cliente com suporte ao HTML';

        $mail->send();
        $mensagem->status ['codigo_status'] = 1;
        $mensagem->status ['descricao_status'] = 'E-mail enviado com sucessso!';
        
    } catch (Exception $e) {
        $mensagem->status ['codigo_status'] = 2;
        $mensagem->status ['descricao_status'] = 'Não foi possivel enviar este e-mail! 
        Por favor tente novamente. Detalhes do erro: '. $mail->ErrorInfo;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>App Mail Send</title>
</head>

<body>
    <main class="container">  
        <header class="py-3 text-center">
            <img class="d-block mx-auto mb-2" src="imagens/logo.png" alt="" width="72" height="72">
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </header>

        <section class="row">
            <div class="col-md-12 text-center">
                <?php if($mensagem->status['codigo_status'] == 1){  ?>
                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso!</h1>
                        <p><?=$mensagem->status ['descricao_status']?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
                <?php } ?>
            
            
                <?php if($mensagem->status['codigo_status'] == 2){  ?>
                    <div class="container">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?=$mensagem->status ['descricao_status']?></p>
                        <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                    </div>

                <?php } ?>  
            </div>
        </section>
    </main>        
</body>