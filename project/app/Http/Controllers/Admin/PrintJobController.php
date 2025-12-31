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
            'completed_today' => PrintJob::completed()->today()->count(),
            'failed_today' => PrintJob::failed()->today()->count(),
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
                return '<a href="'.route('admin-order-show', $data->order_id).'">#'.$data->order->order_number.'</a>';
            })
            ->editColumn('product_id', function(PrintJob $data) {
                return $data->product->name ?? 'N/A';
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
                
                if ($data->status === 'queued') {
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-start', $data->id) . '" class="start-print"><i class="fas fa-play"></i> '.__('Start Printing').'</a>';
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-hold', $data->id) . '" class="hold-print"><i class="fas fa-pause"></i> '.__('Put On Hold').'</a>';
                }
                
                if ($data->status === 'printing') {
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-complete', $data->id) . '" class="complete-print"><i class="fas fa-check"></i> '.__('Mark Complete').'</a>';
                    $actions .= '<a href="javascript:;" data-href="' . route('admin-printjob-fail', $data->id) . '" class="fail-print"><i class="fas fa-times"></i> '.__('Mark Failed').'</a>';
                }
                
                if ($data->status === 'on_hold') {
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
        return view('admin.printjob.show', compact('job'));
    }

    /**
     * Start printing a job
     */
    public function start(Request $request, $id)
    {
        $job = PrintJob::findOrFail($id);
        
        if ($job->status !== 'queued') {
            return response()->json(['error' => 'Job is not in queue'], 400);
        }

        $job->start(auth()->guard('admin')->id());

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
        
        if ($job->status !== 'printing') {
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

        $job = PrintJob::findOrFail($id);
        $job->fail($request->reason);

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

        $job = PrintJob::findOrFail($id);
        $job->hold($request->reason);

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
        $job = PrintJob::findOrFail($id);
        
        if ($job->status !== 'on_hold') {
            return response()->json(['error' => 'Job is not on hold'], 400);
        }

        $job->resume();

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
            'job_ids.*' => 'exists:print_jobs,id'
        ]);

        $jobs = PrintJob::whereIn('id', $request->job_ids)->get();
        $count = 0;

        foreach ($jobs as $job) {
            switch ($request->action) {
                case 'start':
                    if ($job->status === 'queued') {
                        $job->start(auth()->guard('admin')->id());
                        $count++;
                    }
                    break;
                case 'hold':
                    if ($job->status === 'queued') {
                        $job->hold($request->reason ?? 'Bulk action');
                        $count++;
                    }
                    break;
                case 'resume':
                    if ($job->status === 'on_hold') {
                        $job->resume();
                        $count++;
                    }
                    break;
                case 'assign':
                    $job->update(['printer_id' => $request->printer_id]);
                    $count++;
                    break;
            }
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
        $order = Order::find($orderId);
        if (!$order) return;

        $totalJobs = PrintJob::where('order_id', $orderId)->count();
        $completedJobs = PrintJob::where('order_id', $orderId)->completed()->count();

        if ($totalJobs > 0 && $totalJobs === $completedJobs) {
            // All print jobs completed, update order status to processing
            if ($order->status === 'pending') {
                $order->update(['status' => 'processing']);
            }
        }
    }
}
