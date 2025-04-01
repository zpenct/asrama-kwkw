@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto mt-6 p-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Upload Payment Proof</h2>

        {{-- Info Summary --}}
        <div class="space-y-3 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-600">Booking ID</span>
                <span class="font-medium">{{ $transaction->booking_id }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Status Payment</span>
                <span class="capitalize font-medium">{{ str_replace('_', ' ', $transaction->status) }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Amount to Pay</span>
                <span class="font-medium text-blue-600">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Payment Deadline</span>
                <span class="font-medium text-red-600">{{ $transaction->booking->expired_at }}</span>
            </div>
        </div>

        {{-- Notification Based on Status --}}
        @if ($transaction->booking->status === 'expired' || $transaction->booking->expired_at < now())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                Booking Anda telah kadaluarsa, Silahkan booking lagi.
            </div>
        @elseif($transaction->status === 'rejected')
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                Bukti pembayaran Anda telah ditolak oleh admin. Silahkan coba lagi.
            </div>
        @elseif ($transaction->status === 'waiting_verification')
            <div class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded mb-6">
                Bukti pembayaran telah dikirim. Mohon tunggu hingga maksimal <strong>1x24 jam</strong> untuk proses
                verifikasi oleh admin.
            </div>
        @elseif ($transaction->status === 'paid')
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
                Selamat! Pembayaran Anda telah berhasil dan booking Anda telah dikonfirmasi.
            </div>
        @endif


        {{-- Upload Form --}}
        @if ($transaction->status === 'waiting_payment')
            <form action="{{ route('transactions.submit_upload', $transaction->id) }}" method="POST"
                enctype="multipart/form-data" class="border-t pt-6">
                @csrf

                <div class="mb-4" id="upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Proof</label>

                    <div id="upload-area"
                        class="mt-3 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="payment_proof"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="payment_proof" name="payment_proof" type="file" class="sr-only"
                                        accept="image/*" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG max 2MB</p>
                        </div>
                    </div>

                    {{-- Preview Area --}}
                    <div id="preview-container" class="mt-4 hidden relative">
                        <img id="preview-image" class="w-full max-h-64 object-contain border rounded-md shadow-sm"
                            alt="previww" />
                        <button type="button" id="remove-preview"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center shadow hover:bg-red-600"
                            title="Hapus gambar">
                            &times;
                        </button>
                    </div>

                    @error('payment_proof')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>


                <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Submit Payment Proof
                </button>
            </form>
        @endif

    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('payment_proof');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const removeButton = document.getElementById('remove-preview');
            const uploadArea = document.getElementById('upload-area');

            input?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadArea.classList.add('hidden'); // hide upload form
                    };
                    reader.readAsDataURL(file);
                } else {
                    resetUpload();
                }
            });

            removeButton?.addEventListener('click', function() {
                resetUpload();
            });

            function resetUpload() {
                input.value = '';
                previewImage.src = '';
                previewContainer.classList.add('hidden');
                uploadArea.classList.remove('hidden');
            }
        });
    </script>
@endsection
