<?php    
    header('Content-Type: text/html; charset=utf-8');

    if(!isset($_GET['token']) || ($token != $_GET['token'])){
		echo 'Acesso não autorizado';
		exit;
	}

?>
    <?php include_once("templates/header.php"); ?>

    <style>
        .wrapper {
            height: 600px !important;
            position: relative; 
        }
        
        @media (min-width: 992px) {
            .wrapper {
                height: 75% !important;
            }
        }
    </style>

    <div class="row m-0 w-100 h-100">
        <div class="col-12 col-lg-3">

            <div class="card mt-5">
                <div class="card-body">
                    <div class="input-group-append">
                        <select id="select-date" onchange="onChangeSelected()" class="input-group-text w-100 bg-white" aria-label="Default select example">
                        </select>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 mb-3">
                            <div class="card text-white" style="background-color: #7b25be" >
                                <div class="card-header">CRLV-PR</div>
                                <div class="card-body">
                                    <h5 id="crlv-pr-value" class="card-text fw-bold fs-2"></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card bg-warning" >
                                <div class="card-header">ATPV-PR</div>
                                <div class="card-body">
                                    <h5 id="atpv-pr-value" class="card-text fw-bold fs-2"></h5>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            
        </div>
        <div class="col-12 col-lg-9 h-100 w-100">
            <div class="h-100 w-100">
                <div class="wrapper w-100">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="assets/js/chart-js-3.9.1.js"></script>
    
    <script>
          async function main() {

            const data = await fetch('json.php').then(response => response.json());

            const labels = Object.keys(data).map(key => key.split(" - ")[0]).reverse();

            const dataGraph = {
                labels: labels,
                datasets: [
                    {
                        label: 'CRLV-PR',
                        backgroundColor: '#7b25be',
                        borderColor: '#7b25be',
                        data: Object.keys(data).map(key => data[key]['CRLV']).reverse(),
                    },
                    {
                        label: 'ATPV-PR',
                        backgroundColor: '#ffc107',
                        borderColor: '#ffc107',
                        data: Object.keys(data).map(key => data[key]['ATPV']).reverse(),
                    },
                ]
            };

            const config = {
                type: 'line',
                data: dataGraph,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        title: {
                            display: true,
                            text: ''
                        }
                    }
                },
            };

            const myChart = new Chart( document.getElementById('myChart'), config);

            const selectEl = document.getElementById('select-date');

            selectEl.innerHTML = "";

            Object.keys(data).forEach(key => {
              const optionEl = document.createElement('option');
              optionEl.innerHTML = key.split(" - ")[0];
              optionEl.setAttribute('value', key);

              const current = new Date();
              const dateString = current.getUTCFullYear() +"-"+ (current.getUTCMonth()+1);
              if (key.contains(dateString)) {
                optionEl.setAttribute('selected', true);
              }

              selectEl.appendChild(optionEl);
            })

            function onChangeSelected () {
                const value = selectEl.value;
                const label = selectEl.options[selectEl.selectedIndex].text;

                const key = label + " - " + value;

                console.log(data[key]);

                document.getElementById('atpv-pr-value').innerHTML = data[key]['ATPV'];
                document.getElementById('crlv-pr-value').innerHTML = data[key]['CRLV'];
            }

            onChangeSelected();

          }

          main();

      </script>

      
      <script type="text/javascript" src="assets/js/chart-js-3.9.1.js"></script>
      <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    </body>
    </html>