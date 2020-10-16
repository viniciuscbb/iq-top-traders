<?php
define('SITE_KEY', '6Lc8NM4ZAAAAALLV7RdAt593JRkJ1Z0OTuECRtAp');
define('SECRET_KEY', '6Lc8NM4ZAAAAAByo_kWs-wvCFCuoYuW2q-oD7X4Y');
$wins   = '';
$loss   = '';
$assert = '';
$sinais[] = '';

function getCaptcha($SecretKey)
{
  $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$SecretKey}");
  $Return = json_decode($Response);
  return $Return;
}

function httpPost($url, $data)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
  ));
  return curl_exec($ch);
}

function checarLista($array)
{
  global $sinais, $wins, $loss, $assert;
  $resposta = httpPost('http://127.0.0.1:5000/lista', $array);
  $resposta = json_decode($resposta, true);

  $wins   = $resposta['wins'];
  $loss   = $resposta['loss'];
  $sinais = $resposta['sinais'];

  if ($wins == 0) {
    $assert = 0;
  } else {
    $assert = round((($loss / $wins - 1) * 100 * -1), 2);
  }
  //echo 'nome: ' . $nome . '<br> data: ' . $data . '<br> gale: ', $gale, '<br> sinais: <br>';
}

function imprime()
{
  global $sinais;
  if (!$sinais[0] == '') {

    echo 'LISTA DE SINAIS CHECADAS PELO IQ TOP TRADERS CHECKER&#10;&#10;';
    foreach ($sinais as $value) {
      echo $value . '&#10;';
    }
    echo '&#10;CHEQUE SUA LISTA EM IQTOPTRADERS.GA/CHECKER';
  }
}

if (isset($_POST['check'])) {
  $nome = $_SERVER['REMOTE_ADDR'];
  $data = $_POST['data'];
  $gale = (int) $_POST['gale'];
  $lista = $_POST['lista'];

  date_default_timezone_set('America/Sao_Paulo');
  $hoje = date('Y-m-d');

  if ($data <= $hoje) {
    $Return = getCaptcha($_POST['g-recaptcha-response']);
    //var_dump($Return);
    if ($Return->success == true && $Return->score > 0.5) {
      $array = array("nome" => $nome, "data" => $data, "gale" => $gale, "sinais" => $lista);
      $array = json_encode($array);

      try {
        checarLista($array);
      } catch (Exception $e) {
        $sinais[] = 'LISTA DE SINAIS INVÁLIDA';
      }
    } else {
      echo "<script>alert('Erro de reCaptcha')</script>";
      header("location: index.php");
    }
  } else {
    echo "<script>alert('NÃO CONSIGO PREVER O FUTURO AMIGÃO, COLOQUE UMA DATA DO PASSADO!!!')</script>";
    header("location: index.php");
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="../img/favicon.ico" />

  <title>IQ TOP TRADERS</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
  <link href="../css/checker.css" rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <script src="../js/checker.js"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY; ?>"></script>
  <script src="../js/prebid-ads.js"></script>
  <script type='text/javascript' src='//auditioneasterhelm.com/9b/54/ee/9b54eeb082d61b6b37fd7ddb6be4670c.js'></script>
</head>

<body id="page-top">
  <script>
    if (window.canRunAds === undefined) {
      window.location = '../adblock.php'
    }
  </script>
  <!--Big blue-->

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon rotate-n-15">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="sidebar-brand-text mx-3">IQ TT <sup>CHECKER</sup></div>
          </a>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <a target="_blank" href="https://t.me/joinchat/Rs4DnFaQhq_EnM__UuxcCA" class="btn btn-primary btn-circle" title="Entre no nosso grupo no Telegram">
              <i class="fab fa-telegram-plane"></i>
            </a>
            <div class="topbar-divider d-none d-sm-block"></div>
            <a href="../donate.php" class="btn btn-success btn-icon-split">
              <span class="icon text-white-50">
                <i class="fas fa-hand-holding-usd"></i>
              </span>
              <span class="text">FAÇA SUA DOAÇÃO</span>
            </a>
          </ul>
        </nav>
        <!-- End of Topbar -->
        <!-- Earnings (Monthly) Card Example -->
        <div class="container">
          <div class="row">
            <div class="col-lg-9">
              <!-- Illustrations -->
              <div class="ad">
                <script type="text/javascript">
                  atOptions = {
                    'key': '1a872ff72043ddb4f40a95daa176696b',
                    'format': 'iframe',
                    'height': 90,
                    'width': 728,
                    'params': {}
                  };
                  document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://auditioneasterhelm.com/1a872ff72043ddb4f40a95daa176696b/invoke.js"></scr' + 'ipt>');
                </script>
              </div>
              <div class="card shadow cartao mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">VERIFICADOR DE SINAIS</h6>
                </div>
                <div class="card-body">
                  <div class="col-lg-6">
                    <div class="p-5">
                      <div class="text-center">
                        <h1 class="h1 mb-0 text-gray-800">Volte sempre!</h1>
                        <p class="mb-4">Confira sua lista checada abaixo.</p>
                      </div>
                      <ul class="navbar-nav ml-auto mensagem">
                        <a href="#" class="btn btn-success btn-icon-split">
                          <span class="icon text-white-50">
                            <i class="fas fa-check"></i>
                          </span>
                          <span class="text">WINS: <?php echo $wins; ?></span>
                        </a>
                        <a href="#" class="btn btn-danger btn-icon-split">
                          <span class="icon text-white-50">
                            <i class="fas fa-times"></i>
                          </span>
                          <span class="text">LOSS: <?php echo $loss; ?></span>
                        </a>
                        <a href="#" class="btn btn-primary btn-icon-split">
                          <span class="icon text-white-50">
                            <i class="fas fa-percent"></i>
                          </span>
                          <span class="text">ASSERTIVIDADE: <?php echo $assert; ?>%</span>
                        </a>
                      </ul>
                      <p class="mb-4 small"></p>
                      <div class="form-group">
                        <p class="mb-1 small">Resultado da sua lista de sinais</p>
                        <textarea class="form-control" id="lista-result" name="lista-result" required><?php try {
                                                                                                        echo imprime();
                                                                                                      } catch (Exception $e) {
                                                                                                        echo 'LISTA DE SINAIS INVÁLIDA';
                                                                                                      } ?></textarea>
                      </div>
                      <div class="text-center">
                        <h1 class="h3 mb-0 text-gray-800">Telegram e doações.</h1>
                        <p class="mb-4">Entre no nosso grupo no Telegram para mais novidades e também contribua com uma doação para manter nosso serviço.</p>
                      </div>
                      <ul class="navbar-nav ml-auto mensagem">
                        <a target="_blank" href="https://t.me/joinchat/Rs4DnFaQhq_EnM__UuxcCA" class="btn btn-primary btn-circle" title="Entre no nosso grupo no Telegram">
                          <i class="fab fa-telegram-plane"></i>
                        </a>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <a href="../donate.php" class="btn btn-success btn-icon-split">
                          <span class="icon text-white-50">
                            <i class="fas fa-hand-holding-usd"></i>
                          </span>
                          <span class="text">FAÇA SUA DOAÇÃO</span>
                        </a>
                      </ul>
                      <script>
                        grecaptcha.ready(function() {
                          grecaptcha.execute('<?php echo SITE_KEY; ?>', {
                              action: 'submit'
                            })
                            .then(function(token) {
                              //console.log(token);
                              document.getElementById('g-recaptcha-response').value = token;
                            });
                        });
                      </script>
                    </div>
                  </div>
                </div>
              </div>
              <div class="ad">
                <script type="text/javascript">
                  atOptions = {
                    'key': '1a872ff72043ddb4f40a95daa176696b',
                    'format': 'iframe',
                    'height': 90,
                    'width': 728,
                    'params': {}
                  };
                  document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://auditioneasterhelm.com/1a872ff72043ddb4f40a95daa176696b/invoke.js"></scr' + 'ipt>');
                </script>
              </div>
              <div class="adbot">

              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; IQ Top Traders 2020</span>
          </div>
        </div>
      </footer>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- End of Main Content -->

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <script type='text/javascript' src='//auditioneasterhelm.com/8d/fd/28/8dfd28fc7b619e6aa3ed6253afd38f5f.js'></script>
</body>

</html>