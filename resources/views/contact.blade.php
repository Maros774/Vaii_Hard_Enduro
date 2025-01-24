@extends('layouts.app')

@section('content')
    <h2>Kontaktujte nás</h2>
    <ul>
        <li>Email: <a href="mailto:info@hardenduro.sk">info@hardenduro.sk</a></li>
        <li>Instagram: <a href="https://www.instagram.com/hardenduroland/">@hardenduroland</a></li>
        <li>Telefón: +421 918 390 195</li>
    </ul>

    <!-- obrázok -->
    <img
        src="{{ asset('media/about/images/billy-bolt_HEWC-2023-Rnd1-Xross_04411-1200.jpg') }}"
        alt="Billy Bolt"
        class="img-rounded img-fluid"
        style="max-width: 100%; height: auto;"
    >
@endsection
