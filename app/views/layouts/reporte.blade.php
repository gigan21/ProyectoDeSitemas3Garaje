<!DOCTYPE html>
<html>
<head>
    <title>Reporte</title>
    <style>
        /* Estilos base modernos */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color:rgb(48, 91, 232);
            margin: 0;
            padding: 20px;
        }
        
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .report-title {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 24px;
        }
        
        .report-subtitle {
            color: #7f8c8d;
            font-weight: normal;
        }
        
        .report-period {
            background: #f1c40f;
            color: white;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }
        
        /* Tablas mejoradas */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        .report-table thead tr {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            text-align: left;
        }
        
        .report-table th, 
        .report-table td {
            padding: 12px 15px;
        }
        
        .report-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }
        
        .report-table tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }
        
        .report-table tbody tr:last-of-type {
            border-bottom: 2px solid #3498db;
        }
        
        .report-table tbody tr:hover {
            background-color: #f1f8fe;
        }
        
        /* Totales */
        .report-total {
            background: #2ecc71 !important;
            color: white;
            font-weight: bold;
        }
        
        /* Footer */
        .report-footer {
            text-align: right;
            margin-top: 30px;
            color: #95a5a6;
            font-size: 12px;
        }
        
        /* Badges para estados */
        .badge {
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-primary {
            background: #3498db;
            color: white;
        }
        
        .badge-success {
            background: #2ecc71;
            color: white;
        }
        
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        
        /* Gráficos en PDF (usando CSS) */
        .chart-container {
            margin: 30px 0;
        }
        
        .chart-bar {
            height: 20px;
            background: #ecf0f1;
            border-radius: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        
        .chart-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #9b59b6);
            border-radius: 10px 0 0 10px;
        }
        
        /* Encabezado con logo */
        .company-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .company-info {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="report-container">
        @yield('report-content')
    </div>
</body>
</html>