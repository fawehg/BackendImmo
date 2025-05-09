@extends('layouts.app')
@section('contents')

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Annonces | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #34495e;
            --bg-color: #f8f9fa;
            --card-color: #ffffff;
            --border-color: #e0e0e0;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            --medium-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 8px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .admin-container {
            max-width: 1800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
            border-radius: 2px;
        }

        /* Status Filters */
        .status-filters {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }

        .btn-primary {
            background-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: var(--light-shadow);
        }

        .btn-outline-primary {
            background-color: transparent;
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-success {
            background-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #219653;
        }

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        /* Table Container */
        .table-container {
            background-color: var(--card-color);
            border-radius: var(--border-radius);
            box-shadow: var(--medium-shadow);
            overflow: hidden;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.95rem;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1rem 1.25rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table thead th:first-child {
            border-top-left-radius: var(--border-radius);
        }

        .table thead th:last-child {
            border-top-right-radius: var(--border-radius);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:nth-child(even) {
            background-color: rgba(240, 240, 240, 0.5);
        }

        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .table tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Status Badges */
        .badge-status {
            padding: 0.5em 0.8em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 50px;
            display: inline-block;
            min-width: 90px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pending {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }

        .badge-approved {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(39, 174, 96, 0.3);
        }

        .badge-rejected {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .close {
            color: white;
            opacity: 0.8;
            text-shadow: none;
            transition: var(--transition);
            font-size: 1.5rem;
            line-height: 1;
        }

        .close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Form Controls */
        .form-control {
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: var(--transition);
            width: 100%;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .status-change {
            animation: pulse 0.5s ease-in-out;
        }

        /* Loading State */
        .loading-state {
            position: relative;
            overflow: hidden;
        }

        .loading-state::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.7), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .status-filters {
                width: 100%;
                justify-content: flex-start;
            }
            
            .table-container {
                padding: 0;
                overflow-x: auto;
            }
            
            .table {
                min-width: 992px;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border-radius: var(--border-radius);
                box-shadow: var(--light-shadow);
                padding: 1rem;
            }
            
            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0.5rem;
                border-bottom: 1px solid var(--border-color);
            }
            
            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary-color);
                margin-right: 1rem;
                flex: 1;
            }
            
            .table tbody td > * {
                flex: 2;
                text-align: right;
            }
            
            .action-buttons {
                justify-content: flex-end;
            }
            
            .table tbody tr:last-child td:last-child {
                border-bottom: none;
            }
        }

        /* Tooltips */
        [data-toggle="tooltip"] {
            position: relative;
            cursor: pointer;
        }
        
        [data-toggle="tooltip"]::after {
            content: attr(data-title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        [data-toggle="tooltip"]:hover::after {
            opacity: 1;
            visibility: visible;
            bottom: calc(100% + 5px);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="header-section">
            <h1 class="page-title">Gestion des Annonces</h1>
            <div class="status-filters">
                <a href="?status=all" class="btn btn-sm {{ request('status') == 'all' || !request('status') ? 'btn-primary' : 'btn-outline-primary' }}" data-toggle="tooltip" data-title="Voir toutes les annonces">
                    <i class="fas fa-list"></i> Toutes
                </a>
                <a href="?status=pending" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}" data-toggle="tooltip" data-title="Voir les annonces en attente">
                    <i class="fas fa-clock"></i> En attente
                </a>
                <a href="?status=approved" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}" data-toggle="tooltip" data-title="Voir les annonces approuvées">
                    <i class="fas fa-check-circle"></i> Approuvées
                </a>
                <a href="?status=rejected" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}" data-toggle="tooltip" data-title="Voir les annonces rejetées">
                    <i class="fas fa-times-circle"></i> Rejetées
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Vendeur</th>
                        <th>Prix</th>
                        <th>Superficie</th>
                        <th>Localisation</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['maisons', 'villas', 'appartements', 'bureaux', 'fermes', 'etage_villa', 'terrains'] as $type)
                        @foreach($propertyTypes[$type] as $property)
                            <tr data-property-id="{{ $property->id }}" data-property-type="{{ $type }}">
                                <td data-label="Type">
                                    <span class="badge-status badge-primary">
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </span>
                                </td>
                                <td data-label="Titre">
                                    <strong>{{ $property->titre ?? 'N/A' }}</strong>
                                </td>
                                <td data-label="Vendeur">
                                    {{ $property->vendeur ? ($property->vendeur->nom . ' ' . $property->vendeur->prenom) : 'N/A' }}
                                </td>
                                <td data-label="Prix">
                                    <span class="text-success font-weight-bold">
                                        {{ number_format($property->prix ?? 0, 0, ',', ' ') }} DT
                                    </span>
                                </td>
                                <td data-label="Superficie">
                                    {{ $property->superficie ?? 'N/A' }} m²
                                </td>
                                <td data-label="Localisation">
                                    @if($property->ville && $property->delegation)
                                        <i class="fas fa-map-marker-alt text-danger"></i> 
                                        {{ $property->ville->nom }}, {{ $property->delegation->nom }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td data-label="Statut">
                                    <span class="badge-status badge-{{ $property->status }}">
                                        @if($property->status === 'pending')
                                            <i class="fas fa-clock"></i> En attente
                                        @elseif($property->status === 'approved')
                                            <i class="fas fa-check"></i> Approuvée
                                        @else
                                            <i class="fas fa-times"></i> Rejetée
                                        @endif
                                    </span>
                                </td>
                                <td data-label="Date">
                                    <small class="text-muted">
                                        {{ $property->created_at->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.properties.show', [$type, $property->id]) }}" 
                                           class="btn btn-primary btn-sm" 
                                           data-toggle="tooltip" 
                                           data-title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($property->status === 'pending')
                                            <button class="btn btn-success btn-sm approve-btn" 
                                                    data-id="{{ $property->id }}" 
                                                    data-type="{{ $type }}"
                                                    data-toggle="tooltip"
                                                    data-title="Approuver cette annonce">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm reject-btn" 
                                                    data-id="{{ $property->id }}" 
                                                    data-type="{{ $type }}"
                                                    data-toggle="tooltip"
                                                    data-title="Rejeter cette annonce">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
           
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        Raison du rejet
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejection_reason" class="font-weight-bold">
                                Veuillez indiquer la raison du rejet
                            </label>
                            <textarea class="form-control" 
                                      name="rejection_reason" 
                                      id="rejection_reason" 
                                      rows="4" 
                                      required
                                      placeholder="Ex: Photos de mauvaise qualité, informations manquantes..."></textarea>
                            <small class="text-muted">
                                Cette explication sera visible par le vendeur.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-check mr-1"></i> Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTable with enhanced options
            const dataTable = $('#dataTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                pageLength: 25,
                order: [[7, 'desc']], // Sort by date descending
                columnDefs: [
                    { responsivePriority: 1, targets: 1 }, // Title
                    { responsivePriority: 2, targets: 8 }, // Actions
                    { responsivePriority: 3, targets: 2 }, // Seller
                    { responsivePriority: 4, targets: 0 }, // Type
                    { orderable: false, targets: [8] } // Disable sorting for actions column
                ],
               
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover',
                placement: 'top'
            });

            // Handle approval
            $(document).on('click', '.approve-btn', function() {
                const type = $(this).data('type');
                const id = $(this).data('id');
                const url = `/admin/properties/${type}/${id}`;

                Swal.fire({
                    title: 'Confirmer l\'approbation',
                    html: `<p>Êtes-vous sûr de vouloir approuver cette annonce ?</p>
                          <p class="text-muted small">Elle sera visible par tous les utilisateurs.</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check mr-1"></i> Oui, approuver',
                    cancelButtonText: '<i class="fas fa-times mr-1"></i> Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        const row = $(this).closest('tr');
                        row.addClass('loading-state');
                        
                        $.ajax({
                            url: url,
                            method: 'PATCH',
                            data: { status: 'approved' },
                            success: function(response) {
                                // Remove loading state
                                row.removeClass('loading-state');
                                
                                // Update the row
                                updateTableRow(type, id, response.property);
                                
                                // Show success message
                                Swal.fire({
                                    title: 'Succès',
                                    text: 'L\'annonce a été approuvée avec succès',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function(xhr) {
                                // Remove loading state
                                row.removeClass('loading-state');
                                
                                // Show error message
                                Swal.fire({
                                    title: 'Erreur',
                                    text: xhr.responseJSON.message || 'Une erreur est survenue lors de l\'approbation',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Handle rejection
            $(document).on('click', '.reject-btn', function() {
                const type = $(this).data('type');
                const id = $(this).data('id');
                $('#rejectForm').attr('action', `/admin/properties/${type}/${id}`);
                $('#rejectModal').modal('show');
            });

            // Handle reject form submission
            $('#rejectForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const data = form.serialize();
                const type = url.split('/')[3];
                const id = url.split('/')[4];
                const row = $(`tr[data-property-id="${id}"][data-property-type="${type}"]`);
                
                // Show loading state
                row.addClass('loading-state');
                
                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: data,
                    success: function(response) {
                        // Hide modal
                        $('#rejectModal').modal('hide');
                        
                        // Remove loading state
                        row.removeClass('loading-state');
                        
                        // Update the row
                        updateTableRow(type, id, response.property);
                        
                        // Show success message
                        Swal.fire({
                            title: 'Succès',
                            text: 'L\'annonce a été rejetée avec succès',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        
                        // Reset form
                        form.trigger('reset');
                    },
                    error: function(xhr) {
                        // Remove loading state
                        row.removeClass('loading-state');
                        
                        // Show error message
                        Swal.fire({
                            title: 'Erreur',
                            text: xhr.responseJSON.message || 'Une erreur est survenue lors du rejet',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Function to update table row after status change
            function updateTableRow(type, id, property) {
                const row = $(`tr[data-property-id="${id}"][data-property-type="${type}"]`);
                if (row.length) {
                    const statusCell = row.find('td[data-label="Statut"]');
                    const actionsCell = row.find('td[data-label="Actions"]');
                    
                    // Update status badge
                    let statusText = '';
                    let badgeClass = '';
                    let icon = '';
                    
                    if (property.status === 'pending') {
                        statusText = '<i class="fas fa-clock"></i> En attente';
                        badgeClass = 'badge-pending';
                    } else if (property.status === 'approved') {
                        statusText = '<i class="fas fa-check"></i> Approuvée';
                        badgeClass = 'badge-approved';
                    } else {
                        statusText = '<i class="fas fa-times"></i> Rejetée';
                        badgeClass = 'badge-rejected';
                    }
                    
                    statusCell.html(`<span class="badge-status ${badgeClass}">${statusText}</span>`);
                    
                    // Update action buttons
                    let buttons = `
                        <a href="/admin/properties/${type}/${property.id}" 
                           class="btn btn-primary btn-sm" 
                           data-toggle="tooltip" 
                           data-title="Voir les détails">
                            <i class="fas fa-eye"></i>
                        </a>
                    `;
                    
                    if (property.status === 'pending') {
                        buttons += `
                            <button class="btn btn-success btn-sm approve-btn" 
                                    data-id="${property.id}" 
                                    data-type="${type}"
                                    data-toggle="tooltip"
                                    data-title="Approuver cette annonce">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm reject-btn" 
                                    data-id="${property.id}" 
                                    data-type="${type}"
                                    data-toggle="tooltip"
                                    data-title="Rejeter cette annonce">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                    }
                    
                    actionsCell.html(`<div class="action-buttons">${buttons}</div>`);
                    
                    // Add animation
                    row.addClass('status-change');
                    setTimeout(() => row.removeClass('status-change'), 500);
                    
                    // Reinitialize tooltips
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }
        });
    </script>
</body>
</html>
@endsection