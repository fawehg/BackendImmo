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
            <a href="{{ route('villas') }}" class="stat-card" aria-label="Voir les villas">
                <i class="fas fa-umbrella-beach"></i>
                <h3>Villas</h3>
                <div class="value">{{ $villasCount ?? 'N/A' }}</div>
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
            <div class="chart-card">
                <div class="chart-header">
                    <h2>Répartition par Type de Transaction</h2>
                </div>
                <div class="chart-wrapper">
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Fonction pour s'assurer que les valeurs sont numériques
        function ensureNumeric(value) {
            return isNaN(parseFloat(value)) ? 0 : parseFloat(value);
        }

        // Log pour déboguer les données
        console.log('Transactions Data:', {
            vente: [
                ensureNumeric({{ $transactionsData['Vente']['Appartement'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Vente']['Ferme'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Vente']['Maison'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Vente']['Bureau'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Vente']['EtageVilla'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Vente']['Terrain'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Vente']['Villa'] ?? 0 }})
            ],
            location_annuelle: [
                ensureNumeric({{ $transactionsData['Location Annuelle']['Appartement'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Annuelle']['Ferme'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Annuelle']['Maison'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Annuelle']['Bureau'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Annuelle']['EtageVilla'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Annuelle']['Terrain'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Annuelle']['Villa'] ?? 0 }})
            ],
            location_estivale: [
                ensureNumeric({{ $transactionsData['Location Estivale']['Appartement'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Estivale']['Ferme'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Estivale']['Maison'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Estivale']['Bureau'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Estivale']['EtageVilla'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Estivale']['Terrain'] ?? 0 }}),
                ensureNumeric({{ $transactionsData['Location Estivale']['Villa'] ?? 0 }})
            ]
        });

        // Types Chart (Doughnut)
        const ctxTypes = document.getElementById('typesChart').getContext('2d');
        new Chart(ctxTypes, {
            type: 'doughnut',
            data: {
                labels: ['Appartements', 'Fermes', 'Maisons', 'Bureaux', 'Étages de Villas', 'Terrains', 'Villas'],
                datasets: [{
                    data: [
                        ensureNumeric({{ $appartementsCount ?? 0 }}),
                        ensureNumeric({{ $fermesCount ?? 0 }}),
                        ensureNumeric({{ $maisonsCount ?? 0 }}),
                        ensureNumeric({{ $bureauxCount ?? 0 }}),
                        ensureNumeric({{ $etagesVillaCount ?? 0 }}),
                        ensureNumeric({{ $terrainsCount ?? 0 }}),
                        ensureNumeric({{ $villasCount ?? 0 }})
                    ],
                    backgroundColor: [
                        'rgba(0, 221, 235, 0.8)',
                        'rgba(255, 0, 225, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(252, 211, 77, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(74, 222, 128, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ],
                    borderColor: [
                        'rgba(0, 221, 235, 1)',
                        'rgba(255, 0, 225, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(252, 211, 77, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(74, 222, 128, 1)',
                        'rgba(139, 92, 246, 1)'
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

        // Status Chart (Bar) - Données statiques à remplacer
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['Disponible', 'Loué', 'En Maintenance', 'Vendu'],
                datasets: [{
                    label: 'Biens',
                    data: [120, 50, 30, 20], // TODO: Remplacer par données réelles
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

        // Transactions Chart (Stacked Bar)
        const ctxTransactions = document.getElementById('transactionsChart').getContext('2d');
        new Chart(ctxTransactions, {
            type: 'bar',
            data: {
                labels: ['Appartements', 'Fermes', 'Maisons', 'Bureaux', 'Étages de Villas', 'Terrains', 'Villas'],
                datasets: [
                    {
                        label: 'Vente',
                        data: [
                            ensureNumeric({{ $transactionsData['Vente']['Appartement'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Vente']['Ferme'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Vente']['Maison'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Vente']['Bureau'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Vente']['EtageVilla'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Vente']['Terrain'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Vente']['Villa'] ?? 0 }})
                        ],
                        backgroundColor: 'rgba(0, 221, 235, 0.8)',
                        borderColor: 'rgba(0, 221, 235, 1)',
                        borderWidth: 2,
                        minBarLength: 5
                    },
                    {
                        label: 'Location Annuelle',
                        data: [
                            ensureNumeric({{ $transactionsData['Location Annuelle']['Appartement'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Annuelle']['Ferme'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Annuelle']['Maison'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Annuelle']['Bureau'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Annuelle']['EtageVilla'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Annuelle']['Terrain'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Annuelle']['Villa'] ?? 0 }})
                        ],
                        backgroundColor: 'rgba(255, 0, 225, 0.8)',
                        borderColor: 'rgba(255, 0, 225, 1)',
                        borderWidth: 2,
                        minBarLength: 5
                    },
                    {
                        label: 'Location Estivale',
                        data: [
                            ensureNumeric({{ $transactionsData['Location Estivale']['Appartement'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Estivale']['Ferme'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Estivale']['Maison'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Estivale']['Bureau'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Estivale']['EtageVilla'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Estivale']['Terrain'] ?? 0 }}),
                            ensureNumeric({{ $transactionsData['Location Estivale']['Villa'] ?? 0 }})
                        ],
                        backgroundColor: 'rgba(252, 211, 77, 0.8)',
                        borderColor: 'rgba(252, 211, 77, 1)',
                        borderWidth: 2,
                        minBarLength: 5
                    }
                ]
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
                        boxPadding: 6
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: { color: 'var(--light)', font: { size: 12, family: 'Manrope' } }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)', drawBorder: false },
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

    <style>
        .dashboard-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .dashboard-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .dashboard-header h1 {
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .dashboard-header p {
            color: #718096;
            font-size: 1.1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card i {
            font-size: 2rem;
            color: #4a5568;
            margin-bottom: 1rem;
        }
        
        .stat-card h3 {
            font-size: 1.2rem;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .view-link {
            color: #4299e1;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }
        
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .chart-header {
            margin-bottom: 1rem;
        }
        
        .chart-header h2 {
            font-size: 1.3rem;
            color: #2d3748;
        }
        
        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
@endsection