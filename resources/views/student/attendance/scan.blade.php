@extends('layouts.main')
@section('title', 'Scan Attendance QR - MGM-System')

@section('content')
    <div class="max-w-xl mx-auto space-y-6">
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white mx-auto mb-3">
                <i class='bx bx-camera text-3xl'></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Scan Attendance QR</h1>
            <p class="text-slate-500 mt-1">Allow camera access, then point at the QR code.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-bold text-slate-800">Camera</h2>
                <p class="text-sm text-slate-500 mt-1">If scanning fails, you can enter the code manually.</p>
            </div>
            <div class="p-5 space-y-4">
                <div id="reader" class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"></div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('student.subjects.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors">
                        Back
                    </a>
                    <a href="{{ route('student.attendance.checkin.show', ['code' => 'XXXXXX']) }}"
                        onclick="event.preventDefault(); document.getElementById('manualForm').classList.toggle('hidden');"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-emerald-700 hover:bg-emerald-50 border border-emerald-200 transition-colors">
                        Enter code manually
                    </a>
                </div>

                <form id="manualForm" class="hidden" method="POST" action="{{ route('student.attendance.checkin.submit') }}">
                    @csrf
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Code</label>
                        <input name="code" required
                            class="w-full text-center tracking-[0.35em] font-extrabold text-lg border border-slate-200 rounded-2xl px-4 py-4 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all"
                            placeholder="XXXXXX" />
                    </div>
                    <button type="submit"
                        class="mt-3 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/20">
                        <i class='bx bx-check'></i>
                        Check in
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.10/minified/html5-qrcode.min.js"></script>
    <script>
        const targetPrefix = @json(route('student.attendance.checkin.show', ['code' => 'CODE']));

        function normalizeUrl(url) {
            try {
                return new URL(url);
            } catch (_) {
                return null;
            }
        }

        const onScanSuccess = (decodedText) => {
            // If QR is a full URL, go there. If it's just a code, redirect to checkin page.
            const u = normalizeUrl(decodedText);
            if (u) {
                window.location.href = decodedText;
                return;
            }

            const code = decodedText.trim();
            if (!code)
                return;
            window.location.href = targetPrefix.replace('CODE', encodeURIComponent(code));
        };

        const html5QrCode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(cameras => {
            const backCam = cameras.find(c => /back|rear|environment/i.test(c.label)) ?? cameras[0];
            return html5QrCode.start(
                { deviceId: { exact: backCam.id } },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            );
        }).catch(err => {
            const el = document.getElementById('reader');
            el.innerHTML = '<div class="p-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-2xl">Camera error: ' + (err?.toString?.() ?? err) + '</div>';
        });
    </script>
@endpush

