<?php

namespace App\Http\Controllers\Admin;

use App\Models\PrintJob;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Datatables;

class PrintJobController extends AdminBaseController
{
    /**
     * Display print queue dashboard
     */
    public function index()
    {
        $stats = [
            'queued' => PrintJob::queued()->count(),
            'printing' => PrintJob::printing()->count(),
            'completed_today' => PrintJob::completed()->whereDate('completed_at', today())->count(),
            'failed_today' => PrintJob::failed()->whereDate('updated_at', today())->count(),
        ];

        return view('admin.printjob.index', compact('stats'));
    }

    /**
     * Display queued jobs
     */
    public function queue()
    {
        $jobs = PrintJob::with(['order', 'product', 'printer'])
            ->queued()
            ->whereHas('order', function ($query) {
                $query->where('status', 'processing')
                    ->whereIn('print_status', [Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING]);
            })
            ->priority()
            ->paginate(50);

        return view('admin.printjob.queue', compact('jobs'));
    }

    /**
     * Display currently printing jobs
     */
    public function printing()
    {
        $jobs = PrintJob::with(['order', 'product', 'printer'])
            ->printing()
            ->orderBy('started_at', 'desc')
            ->paginate(50);

        return view('admin.printjob.printing', compact('jobs'));
    }

    /**
     * Display completed jobs
     */
    public function completed()
    {
        $jobs = PrintJob::with(['order', 'product', 'printer'])
            ->completed()
            ->orderBy('completed_at', 'desc')
            ->paginate(50);

        return view('admin.printjob.completed', compact('jobs'));
    }

    /**
     * Display failed jobs
     */
    public function failed()
    {
        $jobs = PrintJob::with(['order', 'product', 'printer'])
            ->failed()
            ->orderBy('updated_at', 'desc')
            ->paginate(50);

        return view('admin.printjob.failed', compact('jobs'));
    }

    /**
     * DataTables endpoint for print jobs
     */
    public function datatables($status = 'all')
    {
        $query = PrintJob::with(['order', 'product', 'printer']);

        switch ($status) {
            case 'queued':
                $query->queued();
                break;
            case 'printing':
                $query->printing();
                break;
            case 'completed':
                $query->completed();
                break;
            case 'failed':
                $query->failed();
                break;
        }

        $datas = $query->latest('id')->get();

        return Datatables::of($datas)
            ->editColumn('order_id', function(PrintJob $data) {
                if (!$data->order) {
                    return 'N/A';
                }
                return '<a href="'.route('admin-order-show', $data->order_id).'">#'.$data->order->order_number.'</a>';
            })
            ->editColumn('product_id', function(PrintJob $data) {
                return optional($data->product)->name ?? 'N/A';
            })
            ->editColumn('status', function(PrintJob $data) {
                return $data->status_badge;
            })
            ->editColumn('priority', function(PrintJob $data) {
                $badges = [
                    1 => '<span class="badge badge-danger">High</span>',
                    2 => '<span class="badge badge-warning">Medium</span>',
                    3 => '<span class="badge badge-secondary">Low</span>',
                ];
                return $badges[$data->priority] ?? 'Normal';
            })
            ->addColumn('action', function(PrintJob $data) {
                $actions = '<div class="godropdown"><button class="go-dropdown-toggle">'.__('Actions').'<i class="fas fa-chevron-down"></i></button><div class="action-list">';
                
                $actions .= '<a href="' . route('admin-printjob-show', $data->id) . '"> <i class="fas fa-eye"></i> '.__('View Details').'</a>';
                
                if ($data->status === PrintJob::STATUS_QUEUED) {
                    $canStart = $data->order
                        && $data->order->status === 'processing'
                        && in_array($data->order->print_status, [Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING], true);

                    if ($canStart) {
                        $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-start', $data->id) . '" class="start-print"><i class="fas fa-play"></i> '.__('Start Printing').'</a>';
                        $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-hold', $data->id) . '" class="hold-print"><i class="fas fa-pause"></i> '.__('Put On Hold').'</a>';
                    } else {
                        $actions .= '<span class="dropdown-item text-muted"><i class="fas fa-cogs"></i> '.__('Awaiting Manufacturing').'</span>';
                    }
                }
                
                if ($data->status === PrintJob::STATUS_PRINTING) {
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-complete', $data->id) . '" class="complete-print"><i class="fas fa-check"></i> '.__('Mark Complete').'</a>';
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-fail', $data->id) . '" class="fail-print"><i class="fas fa-times"></i> '.__('Mark Failed').'</a>';
                }
                
                if ($data->status === PrintJob::STATUS_ON_HOLD) {
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-resume', $data->id) . '" class="resume-print"><i class="fas fa-play"></i> '.__('Resume').'</a>';
                }
                
                $actions .= '</div></div>';
                return $actions;
            })
            ->rawColumns(['order_id', 'status', 'priority', 'action'])
            ->toJson();
    }

    /**
     * Show print job details
     */
    public function show($id)
    {
        $job = PrintJob::with(['order', 'product', 'printer'])->findOrFail($id);
        $designFile = $job->design_file ?: optional($job->product)->print_file;
        $designFileUrl = $this->resolvePrintFileUrl($designFile);

        return view('admin.printjob.show', compact('job', 'designFileUrl'));
    }

    /**
     * Start printing a job
     */
    public function start(Request $request, $id)
    {
        $job = PrintJob::with('order')->findOrFail($id);
        
        if ($job->status !== PrintJob::STATUS_QUEUED) {
            return response()->json(['error' => 'Job is not in queue'], 400);
        }

        if (!$job->order || $job->order->status !== 'processing') {
            return response()->json(['error' => 'Order is not in processing state'], 400);
        }

        if (!in_array($job->order->print_status, [Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING], true)) {
            return response()->json(['error' => 'Order must be Print Ready before starting jobs'], 400);
        }

        $job->start(auth()->guard('admin')->id());
        $this->syncOrderPrintStateFromJobs($job->order_id);

        return response()->json([
            'success' => true,
            'message' => 'Print job started successfully'
        ]);
    }

    /**
     * Complete a print job
     */
    public function complete(Request $request, $id)
    {
        $job = PrintJob::findOrFail($id);
        
        if ($job->status !== PrintJob::STATUS_PRINTING) {
            return response()->json(['error' => 'Job is not currently printing'], 400);
        }

        $job->complete($request->notes);

        // Update order status if all print jobs are completed
        $this->checkOrderPrintCompletion($job->order_id);

        return response()->json([
            'success' => true,
            'message' => 'Print job completed successfully'
        ]);
    }

    /**
     * Mark print job as failed
     */
    public function fail(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $job = PrintJob::with('order')->findOrFail($id);

        if ($job->order && $job->order->print_status === Order::PRINT_STATUS_MANUFACTURING) {
            return response()->json(['error' => 'Order is still in manufacturing'], 400);
        }

        if (!in_array($job->status, [PrintJob::STATUS_QUEUED, PrintJob::STATUS_PRINTING, PrintJob::STATUS_ON_HOLD], true)) {
            return response()->json(['error' => 'Job cannot be marked as failed from its current status'], 400);
        }

        $job->fail($request->reason);
        $this->syncOrderPrintStateFromJobs($job->order_id);

        return response()->json([
            'success' => true,
            'message' => 'Print job marked as failed'
        ]);
    }

    /**
     * Put print job on hold
     */
    public function hold(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $job = PrintJob::with('order')->findOrFail($id);

        if ($job->order && $job->order->print_status === Order::PRINT_STATUS_MANUFACTURING) {
            return response()->json(['error' => 'Order is still in manufacturing'], 400);
        }

        if (!in_array($job->status, [PrintJob::STATUS_QUEUED, PrintJob::STATUS_PRINTING], true)) {
            return response()->json(['error' => 'Only queued or printing jobs can be put on hold'], 400);
        }

        $job->hold($request->reason);
        $this->syncOrderPrintStateFromJobs($job->order_id);

        return response()->json([
            'success' => true,
            'message' => 'Print job put on hold'
        ]);
    }

    /**
     * Resume a held print job
     */
    public function resume($id)
    {
        $job = PrintJob::with('order')->findOrFail($id);

        if ($job->order && $job->order->print_status === Order::PRINT_STATUS_MANUFACTURING) {
            return response()->json(['error' => 'Order is still in manufacturing'], 400);
        }
        
        if (!in_array($job->status, [PrintJob::STATUS_ON_HOLD, PrintJob::STATUS_FAILED], true)) {
            return response()->json(['error' => 'Only failed or on-hold jobs can be resumed'], 400);
        }

        $job->resume();
        $this->syncOrderPrintStateFromJobs($job->order_id);

        return response()->json([
            'success' => true,
            'message' => 'Print job resumed'
        ]);
    }

    /**
     * Assign print job to a printer
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'printer_id' => 'required|exists:admins,id'
        ]);

        $job = PrintJob::findOrFail($id);

        if ($job->status === PrintJob::STATUS_COMPLETED) {
            return response()->json(['error' => 'Completed jobs cannot be reassigned'], 400);
        }

        $job->update(['printer_id' => $request->printer_id]);

        return response()->json([
            'success' => true,
            'message' => 'Print job assigned successfully'
        ]);
    }

    /**
     * Bulk actions on print jobs
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:start,hold,resume,assign',
            'job_ids' => 'required|array',
            'job_ids.*' => 'exists:print_jobs,id',
            'printer_id' => 'nullable|required_if:action,assign|exists:admins,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $jobs = PrintJob::with('order')->whereIn('id', $request->job_ids)->get();
        $count = 0;
        $affectedOrderIds = [];

        foreach ($jobs as $job) {
            switch ($request->action) {
                case 'start':
                    $canStart = $job->status === PrintJob::STATUS_QUEUED
                        && $job->order
                        && $job->order->status === 'processing'
                        && in_array($job->order->print_status, [Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING], true);

                    if ($canStart) {
                        $job->start(auth()->guard('admin')->id());
                        $count++;
                        $affectedOrderIds[] = $job->order_id;
                    }
                    break;
                case 'hold':
                    $canHold = in_array($job->status, [PrintJob::STATUS_QUEUED, PrintJob::STATUS_PRINTING], true)
                        && $job->order
                        && $job->order->status === 'processing'
                        && $job->order->print_status !== Order::PRINT_STATUS_MANUFACTURING;

                    if ($canHold) {
                        $job->hold($request->reason ?? 'Bulk action');
                        $count++;
                        $affectedOrderIds[] = $job->order_id;
                    }
                    break;
                case 'resume':
                    $canResume = in_array($job->status, [PrintJob::STATUS_ON_HOLD, PrintJob::STATUS_FAILED], true)
                        && $job->order
                        && $job->order->status === 'processing'
                        && $job->order->print_status !== Order::PRINT_STATUS_MANUFACTURING;

                    if ($canResume) {
                        $job->resume();
                        $count++;
                        $affectedOrderIds[] = $job->order_id;
                    }
                    break;
                case 'assign':
                    if ($job->status !== PrintJob::STATUS_COMPLETED) {
                        $job->update(['printer_id' => $request->printer_id]);
                        $count++;
                    }
                    break;
            }
        }

        foreach (array_unique(array_filter($affectedOrderIds)) as $orderId) {
            $this->syncOrderPrintStateFromJobs($orderId);
        }

        return response()->json([
            'success' => true,
            'message' => "$count jobs updated successfully"
        ]);
    }

    /**
     * Get production capacity stats
     */
    public function capacityStats()
    {
        $products = Product::pod()
            ->select('id', 'name', 'production_cap')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'capacity' => $product->production_cap,
                    'used' => $product->getTodayProductionCount(),
                    'remaining' => $product->remaining_capacity,
                    'utilization' => $product->capacity_utilization
                ];
            });

        return response()->json($products);
    }

    /**
     * Check if all print jobs for an order are completed
     */
    protected function checkOrderPrintCompletion($orderId)
    {
        $this->syncOrderPrintStateFromJobs($orderId);
    }

    /**
     * Resolve a print file to a public URL, supporting legacy locations.
     */
    protected function resolvePrintFileUrl($printFile)
    {
        if (empty($printFile)) {
            return null;
        }

        if (filter_var($printFile, FILTER_VALIDATE_URL)) {
            return $printFile;
        }

        $normalized = ltrim($printFile, '/');
        $candidates = [
            ['disk' => public_path($normalized), 'url' => asset($normalized)],
            ['disk' => public_path('assets/files/designs/' . $normalized), 'url' => asset('assets/files/designs/' . $normalized)],
            ['disk' => public_path('assets/images/products/' . $normalized), 'url' => asset('assets/images/products/' . $normalized)],
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate['disk'])) {
                return $candidate['url'];
            }
        }

        return null;
    }

    /**
     * Keep order-level print status in sync with line-item print jobs.
     */
    protected function syncOrderPrintStateFromJobs($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return;
        }

        // Do not mutate finalized/shipped orders from print-job actions.
        if ($order->print_status === Order::PRINT_STATUS_SHIPPED || $order->status === 'completed') {
            return;
        }

        $statusCounts = PrintJob::where('order_id', $orderId)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $totalJobs = (int) $statusCounts->sum();
        if ($totalJobs === 0) {
            return;
        }

        $completedJobs = (int) ($statusCounts[PrintJob::STATUS_COMPLETED] ?? 0);
        $printingJobs = (int) ($statusCounts[PrintJob::STATUS_PRINTING] ?? 0);

        $updates = [];

        if ($completedJobs === $totalJobs) {
            $updates['print_status'] = Order::PRINT_STATUS_PRINTED;
            $updates['printed_at'] = now();
            if ($order->status === 'pending') {
                $updates['status'] = 'processing';
            }
        } elseif ($printingJobs > 0) {
            $updates['print_status'] = Order::PRINT_STATUS_PRINTING;
            $updates['printed_at'] = null;
            if ($order->status === 'pending') {
                $updates['status'] = 'processing';
            }
        } else {
            // Queue/on-hold/failed states all mean the order is not fully printed yet.
            $updates['print_status'] = Order::PRINT_STATUS_PRINT_READY;
            $updates['printed_at'] = null;
        }

        if (!empty($updates)) {
            $order->update($updates);
        }
    }
}
