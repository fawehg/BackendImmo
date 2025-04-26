@extends('layouts.app')

@section('title', 'Tableau de Bord - B2C')

@section('contents')
    @if ($errors->any())
        <div style="background: #ff4d4d; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-header">
            <h1>Tableau de Bord Immobilier</h1>
            <p>Explorez les performances de vos biens en temps réel</p>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <a href="{{ route('appartements') }}" class="stat-card" aria-label="Voir les appartements">
                <i class="fas fa-building"></i>
                <h3>Appartements</h3>
                <div class="value">{{ $appartementsCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="{{ route('fermes') }}" class="stat-card" aria-label="Voir les fermes">
                <i class="fas fa-tractor"></i>
                <h3>Fermes</h3>
                <div class="value">{{ $fermesCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="{{ route('maisons') }}" class="stat-card" aria-label="Voir les maisons">
                <i class="fas fa-home"></i>
                <h3>Maisons</h3>
                <div class="value">{{ $maisonsCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="{{ route('bureaux') }}" class="stat-card" aria-label="Voir les bureaux">
                <i class="fas fa-briefcase"></i>
                <h3>Bureaux</h3>
                <div class="value">{{ $bureauxCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="{{ route('etagesVillas') }}" class="stat-card" aria-label="Voir les étages de villas">
                <i class="fas fa-layer-group"></i>
                <h3>Étages de Villas</h3>
                <div class="value">{{ $etagesVillaCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="{{ route('terrains') }}" class="stat-card" aria-label="Voir les terrains">
                <i class="fas fa-tree"></i>
                <h3>Terrains</h3>
                <div class="value">{{ $terrainsCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
            <a href="{{ route('villas') }}" class="stat-card" aria-label="Voir les terrains">
            <i class="fas fa-umbrella-beach"></i>	                <h3>Villas</h3>
                <div class="value">{{  $villasCount ?? 'N/A' }}</div>
                <span class="view-link">Détails <i class="fas fa-arrow-right"></i></span>
            </a>
        </div>

        <!-- Charts Container -->
        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <h2>Répartition des Types de Biens</h2>
                </div>
                <div class="chart-wrapper">
                    <canvas id="typesChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <h2>Statut des Biens</h2>
                </div>
                <div class="chart-wrapper">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        // Types Chart (Doughnut)
        const ctxTypes = document.getElementById('typesChart').getContext('2d');
        new Chart(ctxTypes, {
            type: 'doughnut',
            data: {
                labels: ['Appartements', 'Fermes', 'Maisons', 'Bureaux', 'Étages de Villas', 'Terrains'],
                datasets: [{
                    data: [
                        {{ $appartementsCount ?? 0 }},
                        {{ $fermesCount ?? 0 }},
                        {{ $maisonsCount ?? 0 }},
                        {{ $bureauxCount ?? 0 }},
                        {{ $etagesVillaCount ?? 0 }},
                        {{ $terrainsCount ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(0, 221, 235, 0.8)',
                        'rgba(255, 0, 225, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(252, 211, 77, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(74, 222, 128, 0.8)'
                    ],
                    borderColor: [
                        'rgba(0, 221, 235, 1)',
                        'rgba(255, 0, 225, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(252, 211, 77, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(74, 222, 128, 1)'
                    ],
                    borderWidth: 2,
                    hoverBorderColor: '#fff',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12, family: 'Manrope', weight: '500' },
                            color: 'var(--light)'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(10, 14, 26, 0.9)',
                        borderRadius: 10,
                        padding: 12,
                        bodyFont: { size: 12, family: 'Manrope' },
                        titleFont: { size: 14, family: 'Manrope', weight: '600' },
                        boxPadding: 6,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = total ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '75%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1200,
                    easing: 'easeOutElastic'
                }
            }
        });

        // Status Chart (Bar)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['Disponible', 'Loué', 'En Maintenance', 'Vendu'],
                datasets: [{
                    label: 'Biens',
                    data: [120, 50, 30, 20], // Replace with real data
                    backgroundColor: [
                        'rgba(0, 221, 235, 0.8)',
                        'rgba(255, 0, 225, 0.8)',
                        'rgba(252, 211, 77, 0.8)',
                        'rgba(236, 72, 153, 0.8)'
                    ],
                    borderColor: [
                        'rgba(0, 221, 235, 1)',
                        'rgba(255, 0, 225, 1)',
                        'rgba(252, 211, 77, 1)',
                        'rgba(236, 72, 153, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 12,
                    hoverBorderColor: '#fff',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(10, 14, 26, 0.9)',
                        borderRadius: 10,
                        padding: 12,
                        bodyFont: { size: 12, family: 'Manrope' },
                        titleFont: { size: 14, family: 'Manrope', weight: '600' },
                        boxPadding: 6,
                        callbacks: {
                            label: function(context) {
                                return `${context.raw} biens`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)', drawBorder: false },
                        ticks: { color: 'var(--light)', font: { size: 12, family: 'Manrope' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: 'var(--light)', font: { size: 12, family: 'Manrope' } }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutElastic'
                }
            }
        });
    </script>
@endsection