<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Order #<?php echo $data['order']->order_number ?? ''; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        @page { size: auto; margin: 0mm; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; margin: 0; padding: 0; }
            .print-container { box-shadow: none; border: none; padding: 0; margin: 0; width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <?php $order = $data['order'] ?? null; ?>
    <?php if ($order): ?>

    <!-- Control Bar -->
    <div class="max-w-xl mx-auto mb-6 flex justify-between items-center no-print px-4 md:px-0">
        <a href="<?php echo URL_ROOT; ?>/procurements" class="flex items-center text-gray-600 hover:text-gray-900 transition">
            <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-2">
                <i class="fas fa-arrow-left text-sm"></i>
            </div>
            <span class="font-medium">Back to Procurements</span>
        </a>
        <button onclick="window.print()" class="bg-gray-900 text-white px-6 py-2.5 rounded-lg shadow-lg hover:bg-gray-800 transition flex items-center gap-2 font-medium">
            <i class="fas fa-print"></i> Print Slip
        </button>
    </div>

    <!-- Procurement Container (A4 Width) -->
    <div class="print-container max-w-xl mx-auto bg-white shadow-2xl rounded-xl overflow-hidden">
        
        <?php
        // School Logic
        $school_name_raw = $order->school_name ?? '';
        $school_name_lower = strtolower($school_name_raw);
        
        $logo = '';
        $school_display = 'MAKENI ISLAMIC INVENTORY';
        $brand_color = 'bg-gray-900'; // Default
        
        if (strpos($school_name_lower, 'milo') !== false) {
            $logo = 'milotech.jpg';
            $school_display = 'Milo Academy';
            $brand_color = 'bg-indigo-900';
            $text_color = 'text-indigo-900';
        } elseif (strpos($school_name_lower, 'max') !== false) {
            $logo = 'maxim.jpg';
            $school_display = 'Maxim International School';
            $brand_color = 'bg-yellow-500'; 
            $text_color = 'text-yellow-600';
        } elseif (strpos($school_name_lower, 'mukurich') !== false) {
            $logo = 'mukurich.jpg';
            $school_display = 'Mukurich Primary School';
            $brand_color = 'bg-teal-700';
            $text_color = 'text-teal-800';
        }
        ?>

        <!-- Header -->
        <div class="<?php echo $brand_color; ?> h-3 w-full"></div>
        <div class="p-8 md:p-12">
            <div class="flex justify-between items-start mb-12">
                <!-- Branding -->
                <div class="w-1/2">
                    <?php if ($logo): ?>
                        <img src="<?php echo URL_ROOT; ?>/img/logos/<?php echo $logo; ?>" alt="<?php echo $school_display; ?>" class="h-20 object-contain mb-4">
                        <h1 class="text-xl font-bold text-gray-900"><?php echo $school_display; ?></h1>
                    <?php else: ?>
                        <div class="mb-4">
                            <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Logo" class="h-20 object-contain">
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">MAKENI ISLAMIC INVENTORY</h1>
                    <?php endif; ?>

                    <!-- School Contact Info -->
                    <div class="mt-4 text-sm text-gray-500 leading-relaxed">
                        <?php if (isset($order->school_address) && $order->school_address): ?>
                            <p><i class="fas fa-map-marker-alt w-4 mr-1 opacity-60"></i> <?php echo $order->school_address; ?></p>
                        <?php endif; ?>
                        <?php if (isset($order->school_phone) && $order->school_phone): ?>
                            <p><i class="fas fa-phone w-4 mr-1 opacity-60"></i> <?php echo $order->school_phone; ?></p>
                        <?php endif; ?>
                        <?php if (isset($order->school_email) && $order->school_email): ?>
                            <p><i class="fas fa-envelope w-4 mr-1 opacity-60"></i> <?php echo $order->school_email; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Procurement Details -->
                <div class="text-right">
                    <h2 class="text-4xl font-bold text-gray-200 tracking-tight mb-1 uppercase">Procurement</h2>
                    <p class="text-gray-400 text-sm uppercase tracking-wide font-semibold mb-6">#<?php echo $order->order_number; ?></p>
                    
                    <div class="space-y-2 text-sm bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="flex justify-between gap-4 text-gray-600">
                            <span class="font-medium text-gray-500">Date:</span>
                            <span class="text-gray-900 font-semibold"><?php echo date('M d, Y', strtotime($order->created_at)); ?></span>
                        </div>
                        <div class="flex justify-between gap-4 text-gray-600">
                            <span class="font-medium text-gray-500">Time:</span>
                            <span class="text-gray-900"><?php echo date('H:i A', strtotime($order->created_at)); ?></span>
                        </div>
                        <div class="flex justify-between gap-4 text-gray-600">
                            <span class="font-medium text-gray-500">Campus:</span>
                            <span class="text-gray-900"><?php echo $order->campus_name; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Section -->
            <div class="mb-12 bg-gray-50 rounded-lg p-6 border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Vendor Details</h3>
                <div class="flex flex-wrap gap-8">
                    <div>
                        <p class="font-bold text-gray-900 text-lg"><?php echo $order->vendor_name ?? $order->supplier_name; ?></p>
                        <?php if ($order->vendor_email || $order->supplier_email): ?>
                            <p class="text-sm text-gray-600 mt-1"><?php echo $order->vendor_email ?? $order->supplier_email; ?></p>
                        <?php endif; ?>
                        <?php if ($order->vendor_phone || $order->supplier_phone): ?>
                            <p class="text-sm text-gray-600 mt-1"><?php echo $order->vendor_phone ?? $order->supplier_phone; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-10">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-900">
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-6/12 bg-gray-50 border-t border-b border-gray-200 first:rounded-l-lg last:rounded-r-lg">Material Description</th>
                            <th class="py-3 px-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-2/12 bg-gray-50 border-t border-b border-gray-200">Qty</th>
                            <th class="py-3 px-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider w-2/12 bg-gray-50 border-t border-b border-gray-200">Unit Value</th>
                            <th class="py-3 px-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider w-2/12 bg-gray-50 border-t border-b border-gray-200 first:rounded-l-lg last:rounded-r-lg">Total Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dashed divide-gray-200">
                        <?php foreach ($data['items'] ?? [] as $item): ?>
                        <tr>
                            <td class="py-4 px-4 align-top">
                                <p class="font-bold text-gray-900 text-sm"><?php echo $item->material_name; ?></p>
                                <p class="text-xs text-gray-500 mt-1 font-mono">SKU: <?php echo $item->sku ?? $item->material_sku ?? 'N/A'; ?></p>
                            </td>
                            <td class="py-4 px-4 text-center text-gray-700 text-sm align-top font-medium"><?php echo $item->quantity; ?></td>
                            <td class="py-4 px-4 text-right text-gray-700 text-sm align-top">K <?php echo number_format($item->unit_price, 2); ?></td>
                            <td class="py-4 px-4 text-right font-bold text-gray-900 text-sm align-top">K <?php echo number_format($item->subtotal, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals & Status -->
            <div class="flex flex-col md:flex-row justify-end border-t border-gray-200 pt-8">
                <div class="w-full md:w-5/12">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-gray-600">
                            <span class="text-sm font-medium">Subtotal Value</span>
                            <span class="text-sm font-semibold">K <?php echo number_format($order->total_amount, 2); ?></span>
                        </div>
                        <div class="h-px bg-gray-200 my-4"></div>
                        <div class="flex justify-between items-center text-gray-900">
                            <span class="text-base font-bold text-gray-800">Total Procurement Value</span>
                            <span class="text-2xl font-black <?php echo str_replace('bg-', 'text-', $brand_color); ?>">K <?php echo number_format($order->total_amount, 2); ?></span>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <?php
                    $status_colors = [
                        'received' => 'bg-green-100 text-green-700 border-green-200',
                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'cancelled' => 'bg-red-100 text-red-700 border-red-200'
                    ];
                    $status_style = $status_colors[$order->status] ?? $status_colors['pending'];
                    ?>
                    <div class="mt-8 flex justify-end">
                        <div class="px-4 py-2 rounded-full border <?php echo $status_style; ?> text-xs font-bold uppercase tracking-wide flex items-center gap-2">
                            <?php if ($order->status == 'received'): ?>
                                <i class="fas fa-check-circle"></i> RECEIVED
                            <?php elseif ($order->status == 'pending'): ?>
                                <i class="fas fa-clock"></i> PENDING RECEPTION
                            <?php else: ?>
                                <i class="fas fa-times-circle"></i> CANCELLED
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-16 pt-8 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-xs">For any inquiries regarding this procurement order, please contact the administration office.</p>
                <?php if (isset($order->school_email)): ?>
                    <p class="text-gray-400 text-xs mt-4"><?php echo $order->school_email; ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="<?php echo $brand_color; ?> h-2 w-full"></div>
    </div>

    <?php else: ?>
    <!-- Error State -->
    <div class="max-w-md mx-auto mt-20 p-8 bg-white rounded-xl shadow-lg text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Procurement Slip Not Found</h3>
        <p class="text-gray-500 mb-6 text-sm">The procurement order you requested could not be found.</p>
        <a href="<?php echo URL_ROOT; ?>/procurements" class="block-inline bg-gray-900 text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-800 transition">Back to Procurements</a>
    </div>
    <?php endif; ?>
</body>
</html>
