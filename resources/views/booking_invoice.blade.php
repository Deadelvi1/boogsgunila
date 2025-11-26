@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Invoice Booking</h1>
    @if(session('success'))
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="space-y-2">
      <div><strong>Pemesan:</strong> {{ $booking->user->name ?? '-' }}</div>
      <div><strong>Gedung:</strong> {{ $booking->gedung->nama ?? '-' }}</div>
      <div><strong>Acara:</strong> {{ $booking->event_name }} ({{ $booking->event_type }})</div>
      <div><strong>Kapasitas:</strong> {{ $booking->capacity }} orang</div>
      <div><strong>Tanggal:</strong> {{ $booking->date }}</div>
      <div><strong>Waktu:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</div>
      <div><strong>Status Booking:</strong> 
        <span class="px-2 py-1 rounded text-white text-xs
          {{ $booking->status==='2' ? 'bg-green-600' : ($booking->status==='1' ? 'bg-yellow-600' : ($booking->status==='3' ? 'bg-red-600' : 'bg-blue-600')) }}">
          {{ ['1'=>'Menunggu','2'=>'Disetujui','3'=>'Ditolak','4'=>'Selesai'][$booking->status] ?? $booking->status }}
        </span>
      </div>
    </div>
    
    @if($booking->bookingFasilitas && $booking->bookingFasilitas->count() > 0)
    <hr class="my-4">
    <div>
      <strong>Fasilitas yang Disewa:</strong>
      <ul class="list-disc list-inside mt-2 space-y-1">
        @foreach($booking->bookingFasilitas as $bf)
          <li>{{ $bf->fasilitas->nama ?? '-' }} ({{ $bf->jumlah }}x) - Rp {{ number_format(($bf->fasilitas->harga ?? 0) * $bf->jumlah, 0, ',', '.') }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    
    <hr class="my-4">
    <div class="space-y-2">
      <div><strong>Total Pembayaran:</strong> <span class="text-xl font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></div>
      <div><strong>Status Pembayaran:</strong> 
        <span class="px-2 py-1 rounded text-white text-xs
          {{ $payment->status==='2' ? 'bg-green-600' : ($payment->status==='1' ? 'bg-yellow-600' : ($payment->status==='3' ? 'bg-red-600' : 'bg-gray-500')) }}">
          {{ ['0'=>'Belum dibayar','1'=>'Proses','2'=>'Selesai','3'=>'Dibatalkan'][$payment->status] ?? $payment->status }}
        </span>
      </div>
      <div><strong>Metode Pembayaran:</strong> 
        @if($payment->selected_method === 'bayar-ditempat')
          <span class="font-medium">Bayar di Tempat</span>
        @elseif($payment->selected_method === 'transfer-bank')
          <span class="font-medium">Transfer Bank</span>
          @if($payment->payment_account_number)
            <span class="text-sm text-gray-600">(Rek: {{ $payment->payment_account_number }})</span>
          @endif
        @elseif($payment->selected_method === 'e-wallet')
          <span class="font-medium">E-Wallet</span>
          @if($payment->payment_account_number)
            <span class="text-sm text-gray-600">({{ $payment->payment_account_number }})</span>
          @endif
        @else
          <span class="text-gray-500">Belum dipilih</span>
        @endif
      </div>
    </div>

    @if($payment->status === '0')
      <div class="mt-6 bg-blue-50 border rounded p-4">
        <h2 class="font-semibold mb-3">Pilih Metode Pembayaran</h2>
        
        <form method="POST" action="{{ route('payments.upload', $payment->id) }}" enctype="multipart/form-data" class="space-y-4" id="paymentForm">
          @csrf
          
          <div>
            <label class="block text-sm font-medium mb-2">Metode Pembayaran</label>
            <div class="space-y-2">
              @php
                $bayarDitempat = $paymentAccounts->where('type', 'bayar-ditempat')->first();
                $transferBanks = $paymentAccounts->where('type', 'transfer-bank');
                $ewallets = $paymentAccounts->where('type', 'e-wallet');
              @endphp
              
              @if($bayarDitempat)
              <label class="flex items-start p-3 border rounded cursor-pointer hover:bg-blue-100">
                <input type="radio" name="selected_method" value="bayar-ditempat" class="mt-1 mr-3" required>
                <div class="flex-1">
                  <div class="font-medium">Bayar di Tempat</div>
                  <div class="text-xs text-gray-600">{{ $bayarDitempat->description }}</div>
                </div>
              </label>
              @endif
              
              @if($transferBanks->count() > 0)
              <div class="border rounded p-2">
                <div class="font-medium text-sm mb-2">Transfer Bank</div>
                @foreach($transferBanks as $bank)
                <label class="flex items-start p-2 border rounded cursor-pointer hover:bg-blue-100 mb-2">
                  <input type="radio" name="selected_method" value="transfer-bank" class="mt-1 mr-3 payment-method-radio" data-account-id="{{ $bank->id }}" required>
                  <div class="flex-1">
                    <div class="font-medium">{{ $bank->name }}</div>
                    <div class="text-xs text-gray-600">Rek: {{ $bank->account_number }} a.n {{ $bank->account_name }}</div>
                    @if($bank->description)
                    <div class="text-xs text-gray-500">{{ $bank->description }}</div>
                    @endif
                  </div>
                </label>
                @endforeach
              </div>
              @endif
              
              @if($ewallets->count() > 0)
              <div class="border rounded p-2">
                <div class="font-medium text-sm mb-2">E-Wallet</div>
                @foreach($ewallets as $ewallet)
                <label class="flex items-start p-2 border rounded cursor-pointer hover:bg-blue-100 mb-2">
                  <input type="radio" name="selected_method" value="e-wallet" class="mt-1 mr-3 payment-method-radio" data-account-id="{{ $ewallet->id }}" required>
                  <div class="flex-1">
                    <div class="font-medium">{{ $ewallet->name }}</div>
                    <div class="text-xs text-gray-600">{{ $ewallet->account_number }} a.n {{ $ewallet->account_name }}</div>
                    @if($ewallet->description)
                    <div class="text-xs text-gray-500">{{ $ewallet->description }}</div>
                    @endif
                  </div>
                </label>
                @endforeach
              </div>
              @endif
            </div>
          </div>
          
          <input type="hidden" name="payment_account_id" id="payment_account_id" value="">
          
          <div id="proofUploadSection">
            <label class="block text-sm font-medium mb-2">Upload Bukti Pembayaran</label>
            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm border rounded px-3 py-2" id="proofFile">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau PDF (maks 4MB)</p>
          </div>
          
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">Upload Bukti Pembayaran</button>
        </form>
        
        <p class="text-xs text-gray-600 mt-3">Catatan: Setelah upload bukti pembayaran, status akan menjadi "Proses" dan menunggu verifikasi admin. Booking baru bisa digunakan setelah pembayaran terverifikasi.</p>
      </div>
      
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const form = document.getElementById('paymentForm');
          const accountIdInput = document.getElementById('payment_account_id');
          const methodRadios = document.querySelectorAll('input[name="selected_method"]');
          const proofSection = document.getElementById('proofUploadSection');
          const proofFile = document.getElementById('proofFile');
          
          methodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
              const selectedMethod = this.value;
              
              // Update account ID jika transfer/e-wallet
              if (selectedMethod === 'transfer-bank' || selectedMethod === 'e-wallet') {
                const accountRadio = document.querySelector(`input[data-account-id][value="${selectedMethod}"]:checked`);
                if (accountRadio) {
                  accountIdInput.value = accountRadio.dataset.accountId || '';
                }
                proofSection.style.display = 'block';
                proofFile.required = true;
              } else if (selectedMethod === 'bayar-ditempat') {
                accountIdInput.value = '';
                proofSection.style.display = 'none';
                proofFile.required = false;
              }
            });
          });
          
          // Handle account selection for transfer/e-wallet
          document.querySelectorAll('.payment-method-radio').forEach(radio => {
            radio.addEventListener('change', function() {
              if (this.checked) {
                accountIdInput.value = this.dataset.accountId || '';
              }
            });
          });
          
          form.addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="selected_method"]:checked');
            if (!selectedMethod) {
              e.preventDefault();
              alert('Pilih metode pembayaran terlebih dahulu');
              return false;
            }
            
            if (selectedMethod.value !== 'bayar-ditempat') {
              if (!accountIdInput.value) {
                e.preventDefault();
                alert('Pilih rekening/wallet terlebih dahulu');
                return false;
              }
              if (!proofFile.files.length) {
                e.preventDefault();
                alert('Upload bukti pembayaran terlebih dahulu');
                return false;
              }
            }
          });
        });
      </script>
    @elseif($payment->status === '1')
      <div class="mt-6 bg-yellow-50 border rounded p-4 text-yellow-700">
        Bukti pembayaran terkirim. Menunggu verifikasi admin.
      </div>
    @elseif($payment->status === '2')
      <div class="mt-6 bg-green-50 border rounded p-4 text-green-700">
        <div class="font-semibold mb-2">✓ Pembayaran Terverifikasi</div>
        <p>Pembayaran Anda telah diverifikasi oleh admin. Anda sekarang dapat menggunakan gedung sesuai jadwal yang telah disetujui.</p>
      </div>
    @elseif($payment->status === '3')
      <div class="mt-6 bg-red-50 border rounded p-4 text-red-700">
        Pembayaran dibatalkan. Silakan hubungi admin jika ini kesalahan.
      </div>
    @endif

    <div class="mt-6 flex gap-2">
      <a href="{{ route('booking.index') }}" class="px-4 py-2 border rounded">Kembali</a>
      <a href="{{ route('payments.index') }}" class="px-4 py-2 border rounded">Lihat Semua Pembayaran</a>
    </div>
  </div>
  <div class="text-center mt-4 text-sm text-gray-600">Silakan lakukan pembayaran sesuai instruksi sistem.</div>
  
</div>
@endsection


