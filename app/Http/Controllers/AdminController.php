<?php
namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->autoMarkLate();

        $totalParcels    = Parcel::count();
        $totalBookings   = Booking::count();
        $totalUsers      = User::where('is_admin', false)->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        $todayTotal    = Booking::whereDate('created_at', today())->sum('total_amount');
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $todayParcels  = Parcel::whereDate('created_at', today())->count();
        $newUsers      = User::whereDate('created_at', today())->count();

        $todayQR  = Payment::where('method', 'qr')->whereDate('created_at', today())->count();
        $todayCOD = Payment::where('method', 'cod')->whereDate('created_at', today())->count();

        $todayQRTotal  = Booking::whereHas('payment', fn($q) => $q->where('method', 'qr'))
                            ->whereDate('created_at', today())->sum('total_amount');
        $todayCODTotal = Booking::whereHas('payment', fn($q) => $q->where('method', 'cod'))
                            ->whereDate('created_at', today())->sum('total_amount');

        $recentBookings = Booking::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalParcels','totalBookings','totalUsers','pendingBookings',
            'todayTotal','todayBookings','todayParcels','newUsers',
            'todayQR','todayCOD','todayQRTotal','todayCODTotal',
            'recentBookings'
        ));
    }

    public function parcels(Request $request)
    {
        $this->autoMarkLate();
        $this->autoCancelExpiredBookings();

        $query = Parcel::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('tracking_number','LIKE','%'.$request->search.'%')
                  ->orWhere('recipient_name','LIKE','%'.$request->search.'%');
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $parcels     = $query->whereNotIn('status',['late'])->latest()->get();
        $lateParcels = Parcel::where('status','late')->latest()->get();

        $uncollected = Booking::with(['user','items.parcel'])
            ->whereNotIn('status', ['done','cancelled'])
            ->get()
            ->filter(fn($b) => $b->isUncollected());

        return view('admin.parcels', compact('parcels','lateParcels','uncollected'));
    }

    public function createParcel()
    {
        return view('admin.parcel-form');
    }

    public function storeParcel(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|unique:parcels',
            'recipient_name'  => 'required',
            'recipient_phone' => 'required|regex:/^[0-9]+$/|min:9|max:11',
            'courier'         => 'required',
            'arrived_date'    => 'required|date',
            'arrived_time'    => 'required',
        ]);

        Parcel::create([
            'tracking_number' => $request->tracking_number,
            'recipient_name'  => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'courier'         => $request->courier,
            'arrived_date'    => $request->arrived_date,
            'arrived_time'    => $request->arrived_time,
            'status'          => 'registered',
            'price'           => 1.00,
        ]);

        return redirect()->route('admin.parcels')->with('success', 'Parcel added!');
    }

    public function editParcel(Parcel $parcel)
    {
        return view('admin.parcel-form', compact('parcel'));
    }

    public function updateParcel(Request $request, Parcel $parcel)
    {
        $request->validate([
            'tracking_number' => 'required|unique:parcels,tracking_number,'.$parcel->id,
            'recipient_name'  => 'required',
            'recipient_phone' => 'required|regex:/^[0-9]+$/|min:9|max:11',
            'courier'         => 'required',
            'arrived_date'    => 'required|date',
            'arrived_time'    => 'required',
            'status'          => 'required',
        ]);

        $data = $request->only([
            'tracking_number','recipient_name','recipient_phone',
            'courier','arrived_date','arrived_time','status',
        ]);

        if ($request->status === 'done') {
            $data['late_fee']   = 0;
            $data['late_since'] = null;
        }

        $parcel->update($data);

        return redirect()->route('admin.parcels')->with('success', 'Parcel updated!');
    }

    public function quickUpdateParcelStatus(Request $request, Parcel $parcel)
    {
        $request->validate([
            'status' => 'required|in:registered,booked,done,late',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'done') {
            $data['late_fee']   = 0;
            $data['late_since'] = null;
        }

        $parcel->update($data);

        return redirect()->route('admin.parcels')->with('success', 'Parcel status updated!');
    }

    public function deleteParcel(Parcel $parcel)
    {
        $parcel->delete();
        return redirect()->route('admin.parcels')->with('success', 'Parcel deleted!');
    }

    public function bookings(Request $request)
    {
        $this->autoCancelExpiredBookings();

        $query = Booking::with(['user','items.parcel','payment']);
        if ($request->status) $query->where('status', $request->status);
        $bookings = $query->latest()->get();

        return view('admin.bookings', compact('bookings'));
    }

public function updateBookingStatus(Request $request, Booking $booking)
{
    $request->validate([
        'status' => 'required|in:pending,ready,on_the_way,done,cancelled',
    ]);

    $oldStatus = $booking->status;
    $booking->update(['status' => $request->status]);

    if ($request->status === 'done') {
        foreach ($booking->items as $item) {
            $item->parcel->update([
                'status'   => 'done',
                'late_fee' => 0,
            ]);
        }
        try {
            $booking->load(['user','items.parcel','payment']);
            if ($booking->user && $booking->user->email) {
                Mail::to($booking->user->email)
                    ->send(new \App\Mail\BookingConfirmation($booking));
                Log::info('Email sent to: ' . $booking->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
        }
    }

    if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
        foreach ($booking->items as $item) {
            $parcel     = $item->parcel;
            $backStatus = $parcel->late_since ? 'late' : 'registered';
            $parcel->update(['status' => $backStatus]);
        }
    }

    return redirect()->route('admin.bookings')->with('success', 'Status updated!');
}

    public function shopStatus()
    {
        $isOpen = DB::table('settings')->where('key','shop_is_open')->value('value');
        $notice = DB::table('settings')->where('key','shop_notice')->value('value');
        $status = [
            'is_open' => $isOpen === null ? true : (bool)$isOpen,
            'notice'  => $notice ?? '',
        ];
        return view('admin.shop-status', compact('status'));
    }

    public function updateShopStatus(Request $request)
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'shop_is_open'],
            ['value' => $request->boolean('is_open') ? '1' : '0']
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'shop_notice'],
            ['value' => $request->input('notice','')]
        );
        return redirect()->route('admin.shop-status')->with('success', 'Shop status updated!');
    }

    public function qrCode()
    {
        $qrFilename = DB::table('settings')->where('key','qr_filename')->value('value');
        return view('admin.qr-code', compact('qrFilename'));
    }

    public function updateQrCode(Request $request)
    {
        $request->validate(['qr_image' => 'required|image|mimes:jpg,jpeg,png|max:5120']);

        $old = DB::table('settings')->where('key','qr_filename')->value('value');
        if ($old && file_exists(public_path('images/'.$old))) unlink(public_path('images/'.$old));

        $ext      = $request->file('qr_image')->getClientOriginalExtension();
        $filename = 'qr-payment.'.$ext;
        $request->file('qr_image')->move(public_path('images'), $filename);

        DB::table('settings')->updateOrInsert(['key'=>'qr_filename'],['value'=>$filename]);
        return redirect()->route('admin.qr-code')->with('success', 'QR Code updated!');
    }

    public function deleteQrCode()
    {
        $filename = DB::table('settings')->where('key','qr_filename')->value('value');
        if ($filename && file_exists(public_path('images/'.$filename))) unlink(public_path('images/'.$filename));
        DB::table('settings')->where('key','qr_filename')->delete();
        return redirect()->route('admin.qr-code')->with('success', 'QR Code deleted!');
    }

    public function updateLogo(Request $request)
    {
        $request->validate(['logo' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048']);

        $old = DB::table('settings')->where('key','logo_filename')->value('value');
        if ($old && file_exists(public_path('images/'.$old))) unlink(public_path('images/'.$old));

        $ext      = $request->file('logo')->getClientOriginalExtension();
        $filename = 'logo.'.$ext;
        $request->file('logo')->move(public_path('images'), $filename);

        DB::table('settings')->updateOrInsert(['key'=>'logo_filename'],['value'=>$filename]);
        return back()->with('success', 'Logo updated!');
    }

    public function bookingSlots()
    {
        $slots = DB::table('settings')->where('key','booking_slots')->value('value');
        $slots = $slots ? json_decode($slots, true) : [];
        return view('admin.booking-slots', compact('slots'));
    }

    public function updateBookingSlots(Request $request)
    {
        $slots = array_filter($request->input('slots', []), fn($s) => !empty(trim($s)));
        $slots = array_values($slots);
        DB::table('settings')->updateOrInsert(
            ['key' => 'booking_slots'],
            ['value' => json_encode($slots)]
        );
        return back()->with('success', 'Booking slots updated!');
    }

    public function uncollectedLink()
    {
        $token = DB::table('settings')->where('key','uncollected_token')->value('value');
        if (!$token) {
            $token = \Str::random(32);
            DB::table('settings')->updateOrInsert(
                ['key'=>'uncollected_token'],['value'=>$token]
            );
        }
        return back()->with('uncollected_link', url('/uncollected/'.$token));
    }

    public function uncollectedPublic(string $token)
    {
        $valid = DB::table('settings')
            ->where('key','uncollected_token')
            ->where('value',$token)
            ->exists();

        if (!$valid) abort(404);

        $this->autoMarkLate();

        $parcels = Parcel::whereIn('status',['registered','late'])
            ->orderByRaw("FIELD(status,'late','registered')")
            ->orderBy('arrived_date')
            ->get();

        return view('public.uncollected', compact('parcels'));
    }

    public function viewReceipt(Booking $booking)
    {
        $path = $booking->payment?->receipt_path;
        if (!$path) abort(404);
        $full = storage_path('app/public/'.$path);
        if (!file_exists($full)) abort(404);
        $ext  = pathinfo($full, PATHINFO_EXTENSION);
        $mime = match(strtolower($ext)) {
            'jpg','jpeg' => 'image/jpeg',
            'png'        => 'image/png',
            'pdf'        => 'application/pdf',
            default      => 'application/octet-stream',
        };
        return response()->file($full, ['Content-Type' => $mime]);
    }

    public function reviews()
    {
        $reviews = \App\Models\Review::with('user')->latest()->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function replyReview(Request $request, \App\Models\Review $review)
    {
        $request->validate(['reply' => 'required|string|max:500']);
        $review->update(['reply' => $request->reply]);
        return back()->with('success', 'Reply saved!');
    }

    private function autoMarkLate(): void
    {
        Parcel::whereNotIn('status',['done','booked','late'])
            ->get()
            ->each(fn($p) => $p->checkLate());
    }

    private function autoCancelExpiredBookings(): void
    {
        $expired = Booking::where('method', 'pickup')
            ->whereNotIn('status', ['done','cancelled'])
            ->whereDate('booking_date', '<', today())
            ->with('items.parcel')
            ->get();

        foreach ($expired as $booking) {
            $booking->update(['status' => 'cancelled']);

            foreach ($booking->items as $item) {
                $parcel      = $item->parcel;
                $arrivedDate = \Carbon\Carbon::parse($parcel->arrived_date);
                $daysOld     = $arrivedDate->diffInDays(now());

                if ($daysOld >= 14) {
                    $parcel->checkLate();
                } else {
                    $parcel->update([
                        'status'     => 'registered',
                        'late_fee'   => 0,
                        'late_since' => null,
                    ]);
                }
            }
        }
    }
}