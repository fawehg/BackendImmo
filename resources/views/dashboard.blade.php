@extends('layouts.app')
  
@section('title', 'Dashboard -B2C-')
  
@section('contents')
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Laravel Admin Panel With Login and Registration</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
<style>

  body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    justify-content: space-between; 
            flex-grow: 1; 
        }

  .col-md-4 {
    position: relative;
    width: calc(33.3333333333% - 30px);
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
    float: left;
  }

  .icon-container {
    text-align: center;
    padding: 20px;
    border: 10px solid #ccc;
    border-radius: 10px;
    background-color: #fff;
    transition: background-color 0.3s ease;
  }

  .icon-container:hover {
    background-color: #f1f1f1;
  }

  .icon-container p {
    margin: 10px 0 0;
  }

  .icon-container i {
    font-size: 60px;
    color: #4169E1;
  }

  canvas {
    max-width: 400px;
    margin: 0 auto;
    display: block;
  }

  #charts-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }


#charts-container > div {
    margin-right:50px; 
}

        #charts-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    margin-top: 30px;
  }

  #charts-container > div {
    flex-basis: calc(100% - 100px); 
  }

  canvas {
    width: 300%;
    max-width: 300%; 
    height: auto; 
  }

</style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <a href="{{ route('ouvriers') }}">
        <div class="icon-container">
          <i class="fas fa-hard-hat"></i>
          <p>Ouvriers</p>
          <p>{{ $ouvriersCount }}</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('clients') }}">
        <div class="icon-container">
          <i class="fas fa-users"></i>
          <p>Clients</p>
          <p>{{ $clientsCount }}</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('demandes') }}">
        <div class="icon-container">
          <i class="fas fa-fw fa-clipboard-list fa-lg"></i>
          <p>Demandes</p>
          <p>{{ $demandesCount }}</p>
        </div>
      </a>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-8">
      <canvas id="professionChart" width="900" height="490"></canvas>
    </div>
    <div class="col-md-4">
      <canvas id="myChart" width="900" height="900"></canvas>
    </div>
  </div>



<script>
  var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Ouvriers', 'Clients'],
        datasets: [{
            label: 'Nombre total',
            data: [{{ $ouvriersCount }}, {{ $clientsCount }}],
            backgroundColor: [
                'rgba(0, 128, 0, 0.5)', 
                'rgba(0, 0, 255, 0.5)'
            ],
            borderColor: [
                'rgba(0, 128, 0, 1)',
                'rgba(0, 0, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        cutoutPercentage: 0, 
        animation: {
            animateRotate: false, 
            animateScale: true 
        },
        legend: {
            position: 'bottom'
        }
    }
});

    var professionCtx = document.getElementById('professionChart').getContext('2d');
    var professionChart = new Chart(professionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Profession Growth',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
</body>
</html>
@endsection
