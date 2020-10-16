<?php
define('SITE_KEY', '6Lc8NM4ZAAAAALLV7RdAt593JRkJ1Z0OTuECRtAp');
define('SECRET_KEY', '6Lc8NM4ZAAAAAByo_kWs-wvCFCuoYuW2q-oD7X4Y');
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
                        <h1 class="h4 text-gray-900 mb-4">Preencha os campos abaixo</h1>
                      </div>
                      <form class="user" method="POST" action="checked.php">
                        <div class="form-group">
                          <p class="mb-1 small">Data</p>
                          <input name="data" type="date" class="form-control form-control-user" placeholder="Data" value="<?php echo $data; ?>" required>
                        </div>
                        <div class="form-group">
                          <p class="mb-1 small">TIMEFRAME</p>
                          <select class="form-control" id="timeframe" required>
                            <option value="1">1M</option>
                            <option value="5" selected>5M</option>
                            <option value="10">10M</option>
                            <option value="15">15M</option>
                            <option value="30">30M</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <p class="mb-1 small">Quantidade de MartinGale</p>
                          <select class="form-control" name="gale" required>
                            <option value="1">1 MG</option>
                            <option value="2" selected>2 MG</option>
                            <option value="3">3 MG</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <p class="mb-1 small">Mercado</p>
                          <select class="form-control" id="mercado" required>
                            <option value="FALSE" selected>NORMAL</option>
                            <option value="TRUE">OTC</option>
                          </select>
                        </div>
                        <input type="text" name="g-recaptcha-response" id="g-recaptcha-response" hidden>
                        <div class="form-group">
                          <p class="mb-1 small">Sua lista de sinais</p>
                          <textarea maxlength="658" class="form-control" id="lista-sinais" name="lista" placeholder="MÁXIMO DE 30 SINAIS" required></textarea>
                        </div>
                        <button disabled id="btCheck" data-sitekey="<?php echo SITE_KEY; ?>" data-callback='onSubmit' data-action='submit' type="submit" name="check" class="btn btn-primary btn-user btn-block">
                          CHECAR LISTA
                        </button>
                      </form>
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
  <script src="../js/checker.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <script type='text/javascript' src='//auditioneasterhelm.com/8d/fd/28/8dfd28fc7b619e6aa3ed6253afd38f5f.js'></script>
</body>

</html>