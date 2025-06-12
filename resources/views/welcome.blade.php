<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

    .inter-base {
        font-family: "Inter", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    .inter-bold {
        font-family: "Inter", sans-serif;
        font-weight: 700;
        font-style: normal;
    }
</style>

@extends('layouts.app')

@section('title', 'Ramtek - PT. Inovasi Benua Maritim - Unofficial sekedar tugas kuliah')

@section('meta')
    <meta name="description" content="Ramtek adalah sistem pemesanan kamar asrama teknik UNHAS yang cepat, transparan, dan berbasis online.">
    <meta name="keywords" content="ramtek, unhas, booking kamar, asrama teknik unhas, pemesanan asrama, booking, asrama, inovasi benua maritim, inomaru,">

    <!-- OG Tags -->
    <meta property="og:title" content="Ramtek - PT. Inovasi Benua Maritim">
    <meta property="og:description" content="Booking kamar asrama teknik UNHAS dengan mudah dan cepat secara online.">
    <meta property="og:image" content="{{ asset('img/pt-inovasi-og.jpg') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Ramtek - PT. Inovasi Benua Maritim">
    <meta name="twitter:description" content="Booking kamar asrama teknik UNHAS secara online. Transparan dan efisien.">
    <meta name="twitter:image" content="{{ asset('img/pt-inovasi-og.jpg') }}">

    <!-- Canonical -->
    <link rel="canonical" href="{{ url('/') }}" />

    <!-- Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Ramtek - PT. Inovasi Benua Maritim",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('img/pt-inovasi-2xx.png') }}",
      "sameAs": [
        "https://www.instagram.com/ramtek_unhas"
      ],
      "description": "Platform digital untuk pemesanan kamar asrama teknik Universitas Hasanuddin secara online."
    }
    </script>
@endsection


@section('content')
    <main class="min-h-screen w-full flex gap-4 flex-col">
        <div class="w-full content-center md:px-8 px-4 py-4">
            <section
                class="max-w-screen-xl mx-auto relative top-0 px-4 bg-cover bg-center bg-no-repeat max-md:pt-56 py-7 rounded-xl"
                style="background-image: url('{{ asset('img/hero1.jpg') }}');">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent z-10 rounded-b-xl">
                </div>

                <div class="relative z-20 mx-auto text-white md:grid md:grid-cols-2 justify-between gap-20">
                    <div class="flex flex-col justify-end">
                        <div class="flex flex-col gap-1 mb-8 max-md:text-center">
                            <h3 class=" text-3xl inter-bold">Tinggal Nyaman, Belajar Tenang ( Unofficial sekedar tugas kuliah ges)</h3>
                            <p class="inter-base text-lg opacity-80">Dapatkan Asrama impian kalian!!</p>
                        </div>
                        <div class="w-full grid grid-flow-col gap-2 bg-blue-500 p-2 rounded-lg">
                            <p
                                class="text-sm bg-white h-14 text-black font-medium flex items-center justify-center rounded">
                                Gratis Air</p>
                            <p
                                class="text-sm bg-white h-14 text-black font-medium flex items-center justify-center rounded">
                                Gratis Listrik</p>
                            <p
                                class="text-sm bg-white h-14 text-black font-medium flex items-center justify-center rounded">
                                Keamanan</p>
                        </div>
                    </div>
                    <section class="max-md:hidden rounded-lg overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1093.54833821619!2d119.49901959404765!3d-5.230314316694764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee147fb76d7fd%3A0x88b0fffe0f90afee!2sRamsis%20FT%20Unhas%20Gowa!5e0!3m2!1sid!2sid!4v1748534726136!5m2!1sid!2sid"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            class="mx-auto" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </section>
                </div>
            </section>
        </div>

        <div class="w-full content-center md:px-8 px-4 py-4">
            <section class="mx-auto max-w-screen-xl">
                <div class="max-w-screen-xl inter-base text-3xl mb-5">
                    <h3 class="inter-bold text-3xl">Pilih Asrama</h3>
                    <p class="text-lg">Cari kamar impian kalian!</p>
                </div>

                <div class="max-w-80 grid grid-cols-3 rounded-sm overflow-hidden border border-gray-300 mb-3">
                    <button
                        class="tab-button text-gray-700 hover:bg-blue-500 hover:text-white font-light transition-all duration-300 ease-in-out text-sm py-1 border-r border-gray-300">Semua</button>
                    <button
                        class="tab-button text-gray-700 hover:bg-blue-500 hover:text-white font-light transition-all duration-300 ease-in-out text-sm py-1 border-r border-gray-300">Putra</button>
                    <button
                        class="tab-button text-gray-700 hover:bg-blue-500 hover:text-white font-light transition-all duration-300 ease-in-out text-sm py-1">Putri</button>
                </div>

                <div>
                    <div class="grid grid:cols-1 sm:grid-cols-2 md:grid-cols-3 md:mb-6 lg:grid-cols-4 gap-4 mt-3">
                        @foreach ($buildings as $building)
                            <div class="block dorm-card rounded-xl bg-white border p-4"
                                data-type="{{ strtolower($building->type) }}">
                                <div class="relative flex flex-col gap-4 bg-white rounded-lg">
                                    <img src="{{ Storage::disk('s3')->url($building->image_url) }}"
                                        alt="{{ $building->name }}" class="w-full h-44 object-cover rounded-xl">
                                    <div class="flex flex-col gap-3">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3 class="text-lg inter-bold semibold text-gray-900">
                                                    {{ $building->name }}
                                                </h3>
                                                <p
                                                    class="flex items-start gap-1 inter-base font-light text-sm flex-col md:flex-row">
                                                    <span>Mulai dari</span>
                                                    <span class="text-red-600">
                                                        Rp.
                                                        {{ number_format($building->floors->min('price') ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <p class="content-end text-sm">{{ ucfirst(strtolower($building->type)) }}</p>
                                        </div>

                                        <hr class="border-gray-300">

                                        <a href="{{ route('buildings.show', $building->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-lg font-semibold py-2 rounded-lg text-center transition-all duration-300 ease-in-out">
                                            Pilih Asrama
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-slate-900">Asrama Teknik & Cara Kerja</h2>
            </div>
            <div class="mx-auto border px-6 divide-y rounded-lg my-6">
                <div class="accordion" role="accordion">
                    <button type="button"
                        class="toggle-button w-full text-base outline-none text-left font-semibold py-4 text-slate-900 hover:text-blue-600 flex items-center">
                        <span class="mr-4">Apakah ini website resmi Asrama Unhas atau PT. Inovasi Benua Maritim?</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                            class="w-3 fill-current ml-auto shrink-0">
                            <path class="plus"
                                d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            <path
                                d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                        </svg>
                    </button>
                    <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            BUKAN-BUKAN-BUKAN WOY, segala hal yang tercantum disini merupakan hasil karangan kelompok kami untuk kebutuhna tugas mata kuliah RPL, berikut adalah website resmi dari PT. INovasi Benua Maritim https://inomarunhas.com/ 
                        </p>
                    </div>
                </div>
                <div class="accordion" role="accordion">
                    <button type="button"
                        class="toggle-button w-full text-base outline-none text-left font-semibold py-4 text-slate-900 hover:text-blue-600 flex items-center">
                        <span class="mr-4">Profile PT. Benua Maritim & Asrama Teknik</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                            class="w-3 fill-current ml-auto shrink-0">
                            <path class="plus"
                                d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            <path
                                d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                        </svg>
                    </button>
                    <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            PT. Inovasi Benua Maritim (Inomar) merupakan perusahaan yang didirikan oleh Universitas
                            Hasanuddin dan bergerak di berbagai bidang strategis, seperti otomotif, distribusi farmasi dan
                            alat kesehatan, konstruksi, pemasaran dan perdagangan, serta pengelolaan apotek dan klinik
                            pratama.
                            <br>
                            Salah satu wujud komitmen Universitas Hasanuddin dalam mendukung pengembangan mahasiswa adalah
                            melalui penyediaan fasilitas Asrama Mahasiswa Fakultas Teknik (Ramtek). Ramtek membuka
                            kesempatan bagi seluruh mahasiswa baru untuk bergabung sebagai warga asrama. Asrama ini tidak
                            hanya menjadi tempat tinggal yang nyaman dan terjangkau, tetapi juga menawarkan lingkungan yang
                            kondusif bagi pengembangan potensi akademik, sosial, dan organisasi mahasiswa.
                        </p>
                    </div>
                </div>

                <div class="accordion" role="accordion">
                    <button type="button"
                        class="toggle-button w-full text-base outline-none text-left font-semibold py-4 text-slate-900 hover:text-blue-600 flex items-center">
                        <span class="mr-4">Cara Booking</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                            class="w-3 fill-current ml-auto shrink-0">
                            <path class="plus"
                                d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            <path
                                d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                        </svg>
                    </button>
                    <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                        </p>
                        <h2 class="text-sm text-slate-600 leading-relaxed mb-2">Langkah-Langkah Booking Asrama Ramtek</h2>
                        <ol class="list-decimal list-inside text-sm text-slate-600 leading-relaxed space-y-1">
                            <li>Pilih asrama yang diinginkan.</li>
                            <li>Tentukan tanggal <i>check-in</i> dan <i>check-out</i> sesuai kebutuhan.</li>
                            <li>Pilih kamar beserta lantainya.</li>
                            <li>Lakukan proses <i>checkout</i> pada sistem.</li>
                            <li>Setelah <i>checkout</i>, kirimkan bukti pembayaran.</li>
                            <li>Tunggu hingga admin/staff menyetujui permintaan booking Anda.</li>
                            <li>Setelah booking disetujui, mahasiswa dapat langsung menghubungi staff untuk informasi lebih
                                lanjut.</li>
                        </ol>
                    </div>
                </div>

                <div class="accordion" role="accordion">
                    <button type="button"
                        class="toggle-button w-full text-base outline-none text-left font-semibold py-4 text-slate-900 hover:text-blue-600 flex items-center">
                        <span class="mr-4">Pengambilan Kunci Asrama</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                            class="w-3 fill-current ml-auto shrink-0">
                            <path class="plus"
                                d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            <path
                                d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                        </svg>
                    </button>
                    <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Setelah booking Anda disetujui, silakan menghubungi admin atau staff untuk pengambilan kunci
                            asrama saat sudah mendekati tanggal check-in. Alternatifnya, Anda juga dapat langsung datang ke
                            Asrama Teknik Unhas untuk mengambil kunci secara langsung pada jam kantor.
                        </p>
                    </div>
                </div>

                <div class="accordion" role="accordion">
                    <button type="button"
                        class="toggle-button w-full text-base outline-none text-left font-semibold py-4 text-slate-900 hover:text-blue-600 flex items-center">
                        <span class="mr-4">Komunikasi Dengan Staff Asrama</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                            class="w-3 fill-current ml-auto shrink-0">
                            <path class="plus"
                                d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            <path
                                d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                        </svg>
                    </button>
                    <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Anda dapat menghubungi staff asrama melalui nomor kontak yang tertera di bagian bawah website.
                            Selain itu, Anda juga dapat langsung mendatangi kantor Asrama Teknik yang lokasinya dapat
                            dilihat pada peta di atas.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <section class="mx-auto w-full md:px-8 mb-6 py-8 px-4 md:hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1093.54833821619!2d119.49901959404765!3d-5.230314316694764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee147fb76d7fd%3A0x88b0fffe0f90afee!2sRamsis%20FT%20Unhas%20Gowa!5e0!3m2!1sid!2sid!4v1748534726136!5m2!1sid!2sid"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" class="mx-auto"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.accordion').forEach(elm => {
                const button = elm.querySelector('.toggle-button');
                const content = elm.querySelector('.content');
                const plusIcon = button.querySelector('.plus');

                button.addEventListener('click', () => {
                    const isHidden = content.classList.toggle('invisible');
                    content.style.maxHeight = isHidden ? '0px' : `${content.scrollHeight + 100}px`;
                    button.classList.toggle('text-blue-600', !isHidden);
                    button.classList.toggle('text-gray-800', isHidden);
                    content.classList.toggle('pb-6', !isHidden);
                    plusIcon.classList.toggle('hidden', !isHidden);
                    plusIcon.classList.toggle('block', isHidden);
                });
            })
        });

        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const selected = this.textContent.trim().toLowerCase();
                const cards = document.querySelectorAll('.dorm-card');

                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('bg-blue-500', 'text-white');
                    btn.classList.add('text-gray-700');
                });

                this.classList.remove('text-gray-700');
                this.classList.add('bg-blue-500', 'text-white');

                cards.forEach(card => {
                    const type = card.getAttribute('data-type').toLowerCase();
                    if (selected === 'semua' || selected === type) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.tab-button').click();
        });
    </script>
@endsection
