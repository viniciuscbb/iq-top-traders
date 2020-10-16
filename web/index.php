<?php
require_once './config.php';

function getOpetations()
{
  $conection = conection();
  $sql = "SELECT count(*) AS opera FROM operacoes";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $opera = $row['opera'];
  return $opera;
}

function getTraders()
{
  $conection = conection();
  $sql = "SELECT COUNT(DISTINCT id) as traders FROM operacoes";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $traders = $row['traders'];
  return $traders;
}

function getOpetations24h()
{
  date_default_timezone_set('America/Sao_Paulo');
  $hojeC = date('Y-m-d');
  $hojeC = $hojeC . ' 00:00:00';
  $hojeF = date('Y-m-d H:i:s');
  $conection = conection();
  $sql = "SELECT count(*) AS opera FROM operacoes WHERE hora BETWEEN '$hojeC' AND '$hojeF'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $opera = $row['opera'];
  return $opera;
}

function getLastTrade($id)
{
  $conection = conection();
  $sql = "SELECT hora FROM operacoes WHERE id = '$id' ORDER BY hora DESC";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $hora = $row['hora'];
  return $hora;
}

function getWins($id)
{
  $conection = conection();
  $sql = "SELECT count(resultado) AS win FROM operacoes WHERE resultado = 'WIN' and id = '$id'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $win = $row['win'];
  return $win;
}

function getWinsSem($id)
{
  date_default_timezone_set('America/Sao_Paulo');
  $dataFinal = date('Y-m-d') . ' 23:59:59';
  $dataInicial = date("Y-m-d", strtotime(date("Y-m-d") . "-7 days")) . ' 00:00:00';
  $conection = conection();
  $sql = "SELECT 
            count(resultado) AS win 
          FROM 
            operacoes 
          WHERE 
            resultado = 'WIN' and id = '$id' and hora BETWEEN '$dataInicial' AND '$dataFinal'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $win = $row['win'];
  return $win;
}

function getWinsDia($id)
{
  date_default_timezone_set('America/Sao_Paulo');
  $dataFinal = date('Y-m-d') . ' 23:59:59';
  $dataInicial = date('Y-m-d') . ' 00:00:00';
  $conection = conection();
  $sql = "SELECT 
            count(resultado) AS win 
          FROM 
            operacoes 
          WHERE 
            resultado = 'WIN' and id = '$id' and hora BETWEEN '$dataInicial' AND '$dataFinal'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $win = $row['win'];
  return $win;
}

function getLoss($id)
{
  $conection = conection();
  $sql = "SELECT count(resultado) AS win FROM operacoes WHERE resultado = 'LOSS' and id = '$id'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $win = $row['win'];
  return $win;
}

function getLossSem($id)
{
  date_default_timezone_set('America/Sao_Paulo');
  $dataFinal = date('Y-m-d') . ' 23:59:59';
  $dataInicial = date("Y-m-d", strtotime(date("Y-m-d") . "-7 days")) . ' 00:00:00';
  $conection = conection();
  $sql = "SELECT count(resultado) AS win FROM operacoes WHERE resultado = 'LOSS' and id = '$id' and hora BETWEEN '$dataInicial' AND '$dataFinal'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $win = $row['win'];
  return $win;
}

function getLossDia($id)
{
  date_default_timezone_set('America/Sao_Paulo');
  $dataFinal = date('Y-m-d') . ' 23:59:59';
  $dataInicial = date('Y-m-d') . ' 00:00:00';
  $conection = conection();
  $sql = "SELECT count(resultado) AS win FROM operacoes WHERE resultado = 'LOSS' and id = '$id' and hora BETWEEN '$dataInicial' AND '$dataFinal'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $win = $row['win'];
  return $win;
}

function getTop1Wins()
{
  $conection = conection();
  $sql = "SELECT count(resultado) AS wins, id, nome FROM operacoes WHERE resultado = 'WIN' GROUP BY id ORDER BY wins DESC";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $id = $row['id'];
  $nome = $row['nome'];
  $wins = getWins($id);

  echo '<div class="text-white-50 small">Nome: ' . $nome . '</div>
        <div class="text-white-50 small">ID: ' . $id . '</div>
        <div class="text-white-50 small">Wins: ' . $wins . '</div>';
}

function getTop1Loss()
{
  $conection = conection();
  $sql = "SELECT count(resultado) AS loss, id, nome FROM operacoes WHERE resultado = 'LOSS' GROUP BY id ORDER BY loss DESC";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $id = $row['id'];
  $nome = $row['nome'];
  $loss = getLoss($id);

  echo '<div class="text-white-50 small">Nome: ' . $nome . '</div>
        <div class="text-white-50 small">ID: ' . $id . '</div>
        <div class="text-white-50 small">Loss: ' . $loss . '</div>';
}

function getTop100()
{
  date_default_timezone_set('America/Sao_Paulo');
  $dataFinal = date('Y-m-d') . ' 23:59:59';
  $dataInicial = date("Y-m-d", strtotime(date("Y-m-d") . "-7 days")) . ' 00:00:00';
  $conection = conection();
  $sql = "SELECT id, nome, hora, count(case resultado when 'WIN' then 1 else null end) as result
          FROM 
            operacoes 
          WHERE
            hora BETWEEN '$dataInicial' AND '$dataFinal'
          GROUP BY
            id
          ORDER BY
            result desc
          LIMIT 50";

  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id        = $row['id'];
    $nome      = $row['nome'];
    $hora      = getLastTrade($id);
    $hora      =  date("d/m/Y H:m:s", strtotime($hora));
    $wins      = getWinsSem($id);
    $loss      = getLossSem($id);

    if ($wins == 0) {
      $porcent = 0;
    } else {
      $porcent = round((($loss / $wins - 1) * 100 * -1), 2);
    }

    echo '<tr role="row" class="odd">
          <td>
            <div class="botao">
              <a href="profile.php?id=' . $id . '" type="button" class="btn btn-dark">
                <i class="fas fa-history text-gray-300"></i>
                Histórico
              </a>
            </div>
          </td>                              
          <td>' . $id . '</td>
          <td>' . $nome . '</td>
          <td>' . $hora . '</td>
          <td>' . $wins . '</td>
          <td>' . $loss . '</td>
          <td>' . $porcent . '%</td>
        </tr>';
  }
}

function getTop10()
{
  date_default_timezone_set('America/Sao_Paulo');
  $dataFinal = date('Y-m-d') . ' 23:59:59';
  $dataInicial = date('Y-m-d') . ' 00:00:00';
  $conection = conection();
  $sql = "SELECT id, nome, hora, count(case resultado when 'WIN' then 1 else null end) as result
          FROM 
            operacoes 
          WHERE
            hora BETWEEN '$dataInicial' AND '$dataFinal'
          GROUP BY
            id
          ORDER BY
            result desc
          LIMIT 10";


  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id        = $row['id'];
    $nome      = $row['nome'];
    $hora      = getLastTrade($id);
    $hora      =  date("d/m/Y H:m:s", strtotime($hora));
    $wins      = getWinsDia($id);
    $loss      = getLossDia($id);

    if ($wins == 0) {
      $porcent = 0;
    } else {
      $porcent = round((($loss / $wins - 1) * 100 * -1), 2);
    }

    echo '<tr role="row" class="odd">
          <td>
            <div class="botao">
              <a href="profile.php?id=' . $id . '" type="button" class="btn btn-dark">
                <i class="fas fa-history text-gray-300"></i>
                Histórico
              </a>
            </div>
          </td>                              
          <td>' . $id . '</td>
          <td>' . $nome . '</td>
          <td>' . $hora . '</td>
          <td>' . $wins . '</td>
          <td>' . $loss . '</td>
          <td>' . $porcent . '%</td>
        </tr>';
  }
}

if (isset($_POST['search'])) {
  $id = $_POST['id'];
  header("location: details.php?id=$id");
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

  <title>IQ TOP TRADERS</title>

  <link rel="shortcut icon" href="img/favicon.ico" />
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <script data-ad-client="ca-pub-9503739403669970" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <script src="js/prebid-ads.js"></script>
</head>

<body id="page-top">
  <script>
    if (window.canRunAds === undefined) {
      window.location = 'adblock.php'      
    }
  </script>

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
            <div class="sidebar-brand-text mx-3">IQ TT <sup>FREE</sup></div>
          </a>

          <!-- Topbar Search -->
          <form method="POST" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" name="id" class="form-control bg-light border-0 small" placeholder="Pesquisar por NOME ou ID..." aria-label="Pesquisar" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="search">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <a target="_blank" href="https://t.me/joinchat/Rs4DnFaQhq_EnM__UuxcCA" class="btn btn-primary btn-circle" title="Entre no nosso grupo no Telegram">
              <i class="fab fa-telegram-plane"></i>
            </a>
            <div class="topbar-divider d-none d-sm-block"></div>
            <a href="donate.php" class="btn btn-success btn-icon-split">
              <span class="icon text-white-50">
                <i class="fas fa-hand-holding-usd"></i>
              </span>
              <span class="text">FAÇA SUA DOAÇÃO</span>
            </a>
            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form method="POST" class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" name="id" class="form-control bg-light border-0 small" placeholder="Pesquisar por NOME ou ID..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit" name="search">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Content Row -->
          <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">OPERAÇÕES CATALOGADAS</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <a id="operacoes">
                          <?php echo getOpetations(); ?>
                        </a>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">OPERAÇÕES CATALOGADAS EM
                        24HRS</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <a id="em24h">
                          <?php echo getOpetations24h(); ?>
                        </a>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">TRADERS CATALOGADOS</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <a id="traders">
                          <?php echo getTraders(); ?>
                        </a>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4 tops">
              <div class="card bg-success text-white shadow">
                <div class="card-body">
                  TOP 1 WINS
                  <?php echo getTop1Wins(); ?>
                </div>
              </div>
              <div class="card bg-danger text-white shadow">
                <div class="card-body">
                  TOP 1 LOSS
                  <?php echo getTop1Loss(); ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div class="row">

            <div class="col-lg-12 mb-3">
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
                  document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://www.displaynetworkprofit.com/1a872ff72043ddb4f40a95daa176696b/invoke.js"></scr' + 'ipt>');
                </script>
              </div>
              <div class="card shadow cartao mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">TOP 10 TRADERS DE HOJE</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="dataTable_wrapper2" class="dataTables_wrapper dt-bootstrap4">
                      <div class="row">
                        <div class="col-lg-12">

                          <table class="table table-bordered dataTable" id="dataTable2" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                              <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 50px;">#</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 70px;">ID</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 239px;">
                                  NOME</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 150px;">
                                  ÚLTIMO TRADE
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 50px;">
                                  WINS</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 50px;">
                                  LOSS</th>
                                <th id="teste2" class="sorting_desc" tabindex="0" aria-controls="dataTable2" rowspan="1" colspan="1" style="width: 50px;" aria-sort="descending">
                                  ASSERTIVIDADE
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php echo getTop10(); ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
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
                  document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://www.displaynetworkprofit.com/1a872ff72043ddb4f40a95daa176696b/invoke.js"></scr' + 'ipt>');
                </script>
              </div>
              <div class="card shadow cartao mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">TOP 50 TRADERS SEMANAL</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                      <div class="row">
                        <div class="col-lg-12">

                          <table class="table table-bordered dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                              <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 50px;">#</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 70px;">ID</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 239px;">
                                  NOME</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 150px;">
                                  ÚLTIMO TRADE
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 50px;">
                                  WINS</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 50px;">
                                  LOSS</th>
                                <th id="teste" class="sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 50px;" aria-sort="descending">
                                  ASSERTIVIDADE
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php echo getTop100(); ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="adbot">
                <script async="async" data-cfasync="false" src="//pl15799672.toprevenuenetwork.com/2b1921c6a7c5d9158f4a9e6644600cc2/invoke.js"></script>
                <div id="container-2b1921c6a7c5d9158f4a9e6644600cc2"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; IQ Top Traders 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

  <script>
    var table = $('#dataTable').DataTable();
    table
      .columns('#teste')
      .order('desc')
      .draw();

    var table2 = $('#dataTable2').DataTable();
    table2
      .columns('#teste2')
      .order('desc')
      .draw();

    operacoes = document.getElementById('operacoes');
    tempo = parseInt(operacoes.innerText)
    em24h = document.getElementById('em24h');
    tempo2 = parseInt(em24h.innerText)
    em24h = document.getElementById('em24h');
    tempo2 = parseInt(em24h.innerText)

    var timer = setInterval(function() {
      tempo += 1;
      tempo2 += 1;
      operacoes.innerText = tempo;
      em24h.innerText = tempo2;
    }, 300)

    traders = document.getElementById('traders');
    tempo3 = parseInt(traders.innerText)
    var dtimer = setInterval(function() {
      tempo3 += 1;
      traders.innerText = tempo3;

    }, 700)

    $(document).ready(function() {
      $('#dataTable2').DataTable();
    });
  </script>
  <script type='text/javascript' src='//pl15799865.toprevenuenetwork.com/8d/fd/28/8dfd28fc7b619e6aa3ed6253afd38f5f.js'></script>
</body>

</html>