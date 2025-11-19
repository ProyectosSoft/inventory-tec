<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Entrega</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; }
        .section { margin-top: 20px; }
    </style>
</head>
<body>

    <h1>Acta de Entrega de Equipos</h1>

    <p><strong>Consecutivo:</strong> {{ $assignment->consecutive }}</p>

    <div class="section">
        <h2>Datos del Empleado</h2>

        <p><strong>Nombre:</strong> {{ $assignment->employee->full_name }}</p>
        <p><strong>Documento:</strong> {{ $assignment->employee->document_id }}</p>
        <p><strong>Empresa:</strong> {{ $assignment->employee->company?->name }}</p>
        <p><strong>Fecha de asignación:</strong> {{ $assignment->assigned_at->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>Equipos Entregados</h2>

        <table>
            <thead>
                <tr>
                    <th>Asset</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignment->items as $item)
                    <tr>
                        <td>{{ $item->device->asset_tag }}</td>
                        <td>{{ $item->device->type->name }}</td>
                        <td>{{ $item->device->specs_map['brandcarac'] ?? '—' }}</td>
                        <td>{{ $item->device->specs_map['modelcarac'] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section" style="margin-top: 40px;">
        <p>
            Yo, <strong>{{ $assignment->employee->full_name }}</strong>,
            recibo a satisfacción los equipos aquí relacionados y me comprometo a su buen uso,
            custodia y devolución en buen estado.
        </p>

        <br><br><br>

        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: center;">
                    ___________________________<br>
                    Firma del Empleado
                </td>
                <td style="border: none; text-align: center;">
                    ___________________________<br>
                    Firma de Entrega
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
